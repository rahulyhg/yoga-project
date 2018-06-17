<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Common\HttpResponse;

use App\Util\FileUtil;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Model\OrderModel;
use App\Model\CampaignModel;
use App\Model\CampaignMemberModel;
use App\Model\DiscountCodeModel;

class PayController extends Controller
{

	const CAMPAIGN_STATUS_IS_NOT_PUBLISH = 0;
	const CAMPAIGN_STATUS_IS_PUBLISH = 1;
	const CAMPAIGN_STATUS_IS_START = 2;
	const CAMPAIGN_STATUS_IS_FINISH = 3;

	const ORDER_STATUS_IS_IS_NOT_PAY = 1;
	const ORDER_STATUS_IS_IS_PAY = 2;
	const ORDER_STATUS_IS_IS_CANCEL = 0;

    const USER_IS_TEACHER = 1;

	public function __construct()
	{

	}

	/**
	 * 执行支付
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function doPay(Request $request)
	{

		//$datra = $this -> convertCurrency('AUD','CNY',1);


		$data = $request->all();

		$userIn = Session::get('userData');
		$payment = app('wechat.payment');

		$orderIn = OrderModel::getOrderIn($data['OrderNo']);

		$errorMessage = '';

		if($orderIn !== false) {
			if($orderIn->OrderStatus != self::ORDER_STATUS_IS_IS_NOT_PAY){
				$errorMessage = '订单不在未支付状态';
			} else {
				$campaignIn = CampaignModel::getCampaign(
					array('UniqueId' => $orderIn->CampaignId, 'IsDeleted' => 0),
					array('Type', 'Name', 'HostName', 'Description', 'Status'),
					false
				);

				if(empty($campaignIn)) {
					$errorMessage = '活动不存在或已删除';
				} else {
					if($campaignIn[0]->Status != self::CAMPAIGN_STATUS_IS_PUBLISH) {
						$errorMessage = '活动不在发布状态';
					}
				}



				if($errorMessage == '') {
					$product = [
						'trade_type'       => 'JSAPI',
						'body'             => '参与'.$campaignIn[0]->Name,
						'detail'           => '参与'.$campaignIn[0]->Name.', 活动支付',
						'out_trade_no'     => $data['OrderNo'],
						//'total_fee'        => floatval($orderIn->PayableAmount)*100,
						//'fee_type'         => 'HKD',
						'openid' => $userIn['WeChatOpenID']
					];
					if ( isset($orderIn->Currency_unit) && $orderIn->Currency_unit  == '￥') {
						$product['total_fee'] = floatval($orderIn->PayableAmount)*100;
					} else {
						$product['total_fee'] = floatval($orderIn->PayableAmount)*0.8*100;
					}
					$result = $payment->order->unify($product);

					if(isset($result['prepay_id'])) {
						$jssdk = $payment->jssdk;

						$json = $jssdk->bridgeConfig($result['prepay_id']);

						$shareCampaignContent = FileUtil::getPageTemplate($request, 'wxPay');
						$shareCampaignContent = str_replace('【campaignId】', $orderIn->CampaignId, $shareCampaignContent);
						return str_replace('【pay_json】', $json, $shareCampaignContent);
					} else {
						$errorMessage = $result['return_msg'];
					}
				}
			}
		} else {
			$errorMessage = '订单不存在或已删除';
		}

		if($errorMessage != '') {
			return $errorMessage .= '，活动支付失败!';
		}
	}







	public function convertCurrency($from, $to, $amount)
	{
		$data = file_get_contents("http://www.baidu.com/s?wd={$from}%20{$to}&rsv_spt={$amount}");
		preg_match("/<div>1\D*=(\d*\.\d*)\D*<\/div>/",$data, $converted);
		$converted = preg_replace("/[^0-9.]/", "", $converted[1]);
		return $converted;
	}


	/**
	 * 支付确认 微信回调
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function payConfirm(Request $request)
	{

		$payment = app('wechat.payment');
		$response = $payment->handlePaidNotify(function($message, $fail){

			FileUtil::logRecord(array(
				'message' => print_r($message, true)
			));

			// 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
			$order = OrderModel::getOrderIn($message['out_trade_no']);

			if (!$order || $order->OrderStatus == self::ORDER_STATUS_IS_IS_PAY) { // 如果订单不存在 或者 订单已经支付过了
				return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
			}

			FileUtil::logRecord(array(
				'message' => print_r($order, true)
			));

			///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////

			if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
				// 用户是否支付成功
				if (array_get($message, 'result_code') === 'SUCCESS') {
					DB::beginTransaction();
					try {
						//支付状态成功
						$payStatus = OrderModel::paySuccess($message['out_trade_no']);
						if($payStatus) {
							$campaignIn = CampaignModel::getCampaign(
								array('UniqueId' => $order->CampaignId, 'IsDeleted' => 0),
								array('Id', 'UniqueId','Type', 'Name', 'HostName', 'Description', 'Status', 'SignedUpPerson'),
								false
							);
							if(!empty($campaignIn)){
								//用户参与活动
								$memberId = CampaignMemberModel::save(array(
									'CampaignUniqueId' => $campaignIn[0]->UniqueId,
									'CampaignId' => $campaignIn[0]->Id,
									'OrderNo' => $message['out_trade_no'],
									'UserId' => $order->UserId,
									'ContactId' =>$order->ContactId
								));

								if($memberId) {
									CampaignModel::addSignedUpPerson($campaignIn[0]->UniqueId, $campaignIn[0]->SignedUpPerson);
								}

								if(isset($orderIn['DiscountCodeId'])) {		//使用了优惠券
									DiscountCodeModel::useCode($order->DiscountCodeId);
								}
							}

						}
						DB::commit();

					} catch (\Exception $e) {
						DB::rollBack();
						return $fail('订单更新支付失败，请稍后再通知我');
					}
					// 用户支付失败
				} elseif (array_get($message, 'result_code') === 'FAIL') {
					$order->status = 'paid_fail';
				}
			} else {
				return $fail('通信失败，请稍后再通知我');
			}

			$order->save(); // 保存订单

			return true; // 返回处理完成
		});

		$response->send(); // return $response;

	}


}
