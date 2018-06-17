<?php

namespace App\Http\Controllers;

use App\Model\CampaignShareReceiverModel;
use Illuminate\Support\Facades\DB;
use mikehaertl\wkhtmlto\Image;

use App\Model\OrderModel;
use App\Model\UserModel;
use Illuminate\Http\Request;

use App\Common\HttpResponse;

use App\Model\CampaignModel;
use App\Model\CampaignMemberModel;
use App\Model\DiscountCodeModel;
use App\Model\TicketModel;

use App\Model\CampaignShareModel;

use App\Model\CampaignFeedbackStudentModel;
use App\Model\CampaignFeedbackTeacherModel;

use App\Util\DateUtil;
use App\Util\FileUtil;

use Illuminate\Support\Facades\Session;

class CampaignController extends Controller
{
    const USER_IS_TEACHER = 1;
	const USER_IS_STUDENT = 2;

	const CAMPAIGN_STATUS_IS_NOT_PUBLISH = 0;
	const CAMPAIGN_STATUS_IS_PUBLISH = 1;
    const CAMPAIGN_STATUS_IS_START = 2;
    const CAMPAIGN_STATUS_IS_FINISH = 3;

    const DISCOUNT_CODE_TYPE_REDUCE_MONEY = 1;
	const DISCOUNT_CODE_TYPE_PERCENT_MONEY = 2;
	const DISCOUNT_CODE_TYPE_FREE = 3;

	public $campaignStatusArr = array(
		0 => '未发布',
		1 => '已发布',
		2 => '进行中',
		3 => '已结束'
	);

	public function __construct()
	{

	}

	/**
	 * 新增/修改活动
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function doCampaign(Request $request){
		$userIn = Session::get('userData');
		if($userIn['UserType'] == self::USER_IS_TEACHER) {
			$data = $request->all();
			$data['Status'] = $data['Status'] !== self::CAMPAIGN_STATUS_IS_START
			&& $data['Status'] !== self::CAMPAIGN_STATUS_IS_FINISH ? $data['Status'] : 0;

			if(isset($data['UniqueId']) && $data['UniqueId']) {
				if(CampaignModel::updateCampaign($data, $userIn['Id'])) {
					return HttpResponse::success();
				} else {
					return HttpResponse::error('修改活动失败！');
				}
			} else {
				$campaignId = CampaignModel::saveCampaign($data);
				if($campaignId) {
					return HttpResponse::success(['campaignId' => $campaignId]);
				} else {
					return HttpResponse::error('创建活动失败！');
				}
			}

		} else {
			return HttpResponse::error();
		}
	}

	/**
	 * 活动列表
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function find(Request $request){
		$userIn = Session::get('userData');

		$data = $request->all();
		$data['page'] = isset($data['page']) ? $data['page'] : 1;
		$condition = array(
			'Status' => self::CAMPAIGN_STATUS_IS_PUBLISH,
			'IsDeleted' => 0
		);

		if($data['status'] == 1) {	//最热
			$condition['IsHot'] = 1;
		} else if($data['status'] == 2) {	//附近

		} else if($data['status'] == 3) {	//我的
			if($userIn['UserType'] == self::USER_IS_STUDENT) {
				$condition['campaignId'] = CampaignMemberModel::getMycCampaignId($userIn['Id']);
				if(empty($condition['campaignId'] )) {
					return HttpResponse::success([]);
				}
			} else if($userIn['UserType'] == self::USER_IS_TEACHER){
				$condition['CreatedById'] = $userIn['Id'];
			}
		}

		$campaignList = CampaignModel::findCampaign(
			$condition
		);

		return HttpResponse::success($campaignList);

	}

	/**
	 * 活动列表
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index(Request $request){
        $userIn = Session::get('userData');
        if($userIn['UserType'] == self::USER_IS_TEACHER) {
            $data = $request->all();
            $data['page'] = isset($data['page']) ? $data['page'] : 1;
            $returnData = [];

            $campaignList = CampaignModel::getCampaign(
                array(
                    'Status' => $data['status'],
                    'CreatedById' => $userIn['Id'],
					'IsDeleted' =>0
                )
            );

            foreach ($campaignList as $campaignIn) {
                $dateStr = DateUtil::getDateChinaShow($campaignIn->CreatedDate);
                if(!isset($returnData[$dateStr])) {
                    $returnData[$dateStr] = [];
                }
                array_push($returnData[$dateStr], $campaignIn);
            }
            return HttpResponse::success($returnData);
        } else {
            return HttpResponse::error();
        }

	}

	/**
	 * 获取老师的活动列表
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getMyCampaignList(Request $request){
		$userIn = Session::get('userData');
		if($userIn['UserType'] == self::USER_IS_TEACHER) {
			$data = $request->all();

			$campaignList = CampaignModel::getCampaign(
				array(
					'Status' => $data['campaignStatus'],
					'CreatedById' => $userIn['Id']
				),
				array('Name', 'UniqueId'), false
			);

			return HttpResponse::success($campaignList);
		} else {
			return HttpResponse::error();
		}

	}

	/**
	 * 活动详情
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function detail(Request $request){
		$userIn = Session::get('userData');
		$data = $request->all();

		$campaignIn = CampaignModel::getCampaign(
			array(
				'UniqueId' => $data['CampaignId'],
				'IsDeleted' => 0
			)
		);
		if(!empty($campaignIn)) {
			$campaignIn[0]->StatusName = $this->campaignStatusArr[$campaignIn[0]->Status];
			//已开始
			if($campaignIn[0]->Status != 0) {
				$campaignIn[0]->memberList = CampaignMemberModel::getMemberIn($campaignIn[0]->UniqueId);
			}

			if($campaignIn[0]->IsFreeOfCharge == 0) {	//不是免费活动
				$campaignIn[0]->priceList = TicketModel::getTicketPrice(
					array(
						'CampaignId' => $campaignIn[0]->UniqueId,
						//'date' => date('Y-m-d H:i:s')
					)
				);
			}


			return HttpResponse::success($campaignIn[0]);
		} else {
			return HttpResponse::error('活动不存在或已删除！');
		}
	}


	/**
	 * 获取参与活动信息
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function participateCampaignIn(Request $request)
	{
		$userIn = Session::get('userData');
		$data = $request->all();
		if($userIn['UserType'] == self::USER_IS_STUDENT || $userIn['UserType'] == self::USER_IS_TEACHER) {
			$campaignIn = CampaignModel::getCampaign(
				array(
					'UniqueId' => $data['CampaignId'],
					'IsDeleted' => 0
				),
				array(
					'UniqueId','Name','Type','HostName','Address',
					'StartTime','EndTime','Status','IsFreeOfCharge'
				), false
			);
			if(!empty($campaignIn)) {
				//已开始
				if($campaignIn[0]->Status == self::CAMPAIGN_STATUS_IS_PUBLISH) {
					$priceList = [];
					if($campaignIn[0]->IsFreeOfCharge == 0) {
						$priceList = TicketModel::getTicketPrice(array(
							'CampaignId' => $data['CampaignId'],
							'date'=>date('Y-m-d H:i:s')
						));
						if(empty($priceList)) {
							return HttpResponse::error('活动价格异常！');
						}
					}

					return HttpResponse::success(array(
						'campaignIn' => $campaignIn[0],
						'priceList' =>$priceList
					));

				} else {
					return HttpResponse::error('', $campaignIn[0]);
				}

			} else {
				return HttpResponse::error('活动不存在或已删除！');
			}
		} else {
			return HttpResponse::error();
		}
	}

	/**
	 * 获取活动基本信息
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function campaignBaseIn(Request $request)
	{
		$userIn = Session::get('userData');
		$data = $request->all();
		if($userIn['UserType'] == self::USER_IS_STUDENT || $userIn['UserType'] == self::USER_IS_TEACHER) {
			$campaignIn = CampaignModel::getCampaign(
				array(
					'UniqueId' => $data['CampaignId'],
					'IsDeleted' => 0
				),
				array(
					'UniqueId', 'Name', 'Type', 'HostName', 'Address',
					'StartTime', 'EndTime', 'Status', 'IsFreeOfCharge'
				), false
			);
			return HttpResponse::success( $campaignIn[0] );

		}

	}

	/**
	 * 参与活动
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function participate(Request $request)
	{
		$userIn = Session::get('userData');
		$data = $request->all();
		if($userIn['UserType'] == self::USER_IS_STUDENT || $userIn['UserType'] == self::USER_IS_TEACHER) {
			$campaignIn = CampaignModel::getCampaign(
				array(
					'UniqueId' => $data['CampaignId'],
					'IsDeleted' => 0
				),
				array(
					'Id','UniqueId','Name','Type','HostName','Address',
					'StartTime','EndTime','Status','IsFreeOfCharge','SignedUpPerson'
				), false
			);
			if(!empty($campaignIn)) {
				//已开始
				if($campaignIn[0]->Status == self::CAMPAIGN_STATUS_IS_PUBLISH) {
					$orderIn = array(
						'ContactId' => isset($userIn['ContactId']) ? $userIn['ContactId'] :0,
						'UserId' => $userIn['Id'],
						'CampaignId' => $data['CampaignId'],
						'Email' => $data['Email'],
						'Username' => $data['Username'],
						'Currency_unit' => $data['Currency_unit'],
						'taocan' => $data['taocan'],
						'ShareFromUserId' => isset($data['shareFromUserId'])?$data['shareFromUserId']:0
					);
					if($campaignIn[0]->IsFreeOfCharge != 1) {	//不是免费活动
						$priceIn = TicketModel::getTicketPrice(array(
							'CampaignId' => $data['CampaignId'],
							'date'=>date('Y-m-d H:i:s')
						));

						if(!empty($priceIn)) {
							$length = count($priceIn);
							$orderIn['TicketId'] = $priceIn[$length-1]->Id;
							$orderIn['TicketPrice'] = $priceIn[$length-1]->Price;
							$orderIn['TotalAmount'] =  $priceIn[$length-1]->Price;
							if( $data['DiscountCode'] != '') {
								$codeIn = DiscountCodeModel::getDiscountCode(array(
									'DiscountCode' => $data['DiscountCode'],
									'CampaignId' => $data['CampaignId'],
									'Status' => 1,
									'date' => date('Y-m-d H:i:s')
								));
								if(!empty($codeIn)) {
									if($codeIn[0]->MaxUsage > $codeIn[0]->UsedCount) {
										$orderIn['DiscountCodeId'] = $codeIn[0]->Id;
										if($codeIn[0]->DiscountType == self::DISCOUNT_CODE_TYPE_FREE ) {
											$orderIn['TotalAmount'] = 0;
											$orderIn['PayableAmount'] = 0;
										}else if($codeIn[0]->DiscountType == self::DISCOUNT_CODE_TYPE_REDUCE_MONEY ) {

											$orderIn['DiscountAmount'] = $codeIn[0]->DiscountAmount;
											$orderIn['PayableAmount'] = floatval($orderIn['TotalAmount'])
												- floatval($orderIn['DiscountAmount']);

										}else if($codeIn[0]->DiscountType == self::DISCOUNT_CODE_TYPE_PERCENT_MONEY ) {
											$orderIn['PayableAmount'] = ($codeIn[0]->DiscountPercent/10)
												*floatval($orderIn['TotalAmount']);

											$orderIn['DiscountAmount'] = floatval($orderIn['TotalAmount'])
												- $orderIn['PayableAmount'];
										}
									} else {
										return HttpResponse::error('优惠码超出使用次数~');
									}
								} else {
									return HttpResponse::error('优惠码不可用!');
								}
							} else {
								$orderIn['PayableAmount'] = $orderIn['TotalAmount'];
							}
						} else {
							return HttpResponse::error('活动价格异常！');
						}

					} else {
						$orderIn['TotalAmount'] = 0;
						$orderIn['PayableAmount'] = 0;
					}


					DB::beginTransaction();
					try {

						if($orderIn['PayableAmount'] == 0) {
							$orderIn['OrderStatus'] = 1;
						}

						$orderNo = OrderModel::save($orderIn);

						if(isset($orderIn['OrderStatus']) && $orderIn['OrderStatus'] == 1) {	//免费，则直接参与话题
							$memberId = CampaignMemberModel::save(array(
								'CampaignUniqueId' => $campaignIn[0]->UniqueId,
								'CampaignId' => $campaignIn[0]->Id,
								'OrderNo' => $orderNo,
								'UserId' => $userIn['Id'],
								'ContactId' => isset($userIn['ContactId']) ?$userIn['ContactId']:0
							));

							if($memberId) {
								CampaignModel::addSignedUpPerson($campaignIn[0]->UniqueId, $campaignIn[0]->SignedUpPerson);
							}

							if(isset($orderIn['DiscountCodeId'])) {		//使用了优惠券
								DiscountCodeModel::useCode($orderIn['DiscountCodeId']);
							}
						}

						DB::commit();

						return HttpResponse::success(array(
							'PayableAmount' => $orderIn['PayableAmount'],
							'OrderNo' => $orderNo
						));

					} catch (\Exception $e) {
						DB::rollBack();
						return HttpResponse::error('参与活动失败！');
					}
				} else {
					return HttpResponse::error('', $campaignIn[0]);
				}

			} else {
				return HttpResponse::error('活动不存在或已删除！');
			}
		} else {
			return HttpResponse::error();
		}

	}

	/**
	 * 发布活动
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function publish(Request $request)
	{
		$userIn = Session::get('userData');
		$data = $request->all();
		if($userIn['UserType'] == self::USER_IS_TEACHER) {
			if($data['Status'] == self::CAMPAIGN_STATUS_IS_PUBLISH) {

				if(CampaignModel::changeStatus(
					self::CAMPAIGN_STATUS_IS_PUBLISH,
					array(
						'CreatedById' => $userIn['Id'],
						'UniqueId' => $data['CampaignId'],
						'Status' =>  self::CAMPAIGN_STATUS_IS_NOT_PUBLISH,
					)
				)) {
					return HttpResponse::success();
				} else {
					return HttpResponse::error('修改活动状态出错！');
				}

			} else {
				return HttpResponse::error('传入活动状态出错！');
			}
		} else {
			return HttpResponse::error();
		}
	}


    /**
     * 获取分享微信配置
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getShareConf(Request $request)
    {
        $data = $request->all();
        require $_SERVER["DOCUMENT_ROOT"] . '/../app/lib/wxJssdk/jssdk.php';

        $weChatConfig = require $_SERVER["DOCUMENT_ROOT"] . '/../app/config/wechat.php';
        $weChatConfig = $weChatConfig['official_account']['default'];
        $appId = $weChatConfig['app_id'];
        $appSecret = $weChatConfig['secret'];

        $jssdk = new \JSSDK($appId, $appSecret, $data['url']);
        $signPackage = $jssdk->getSignPackage();

        $wxConfig = array(
            'debug' => false,
            'appId' => $signPackage['appId'],
            'timestamp' => $signPackage['timestamp'],
            'nonceStr' => $signPackage['nonceStr'],
            'signature' => $signPackage['signature'],
            'jsApiList' => array('onMenuShareTimeline', 'onMenuShareAppMessage')
        );

        return HttpResponse::success($wxConfig);
    }

	/**
	 * 分享活动默认的内容
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function shareDefaultIn(Request $request)
	{
		$data = $request->all();
		$shareCampaignIn =  CampaignModel::getShareCampaignIn($data['campaignId']);

        $appConfig = app()->container->getParameter('app');
        $documentRoot = $request->server->get('DOCUMENT_ROOT');

        $app = app('wechat.official_account');

        //echo $appConfig['protocol'].$appConfig['domain']['main'].'#/campaign/detail/'.$data['campaignId'];
		//$result = $app->qrcode->forever( $appConfig['protocol'].$appConfig['domain']['main'].'#/campaign/detail/'.$data['campaignId']);
		//echo $result['ticket'];
		//$shareCampaignIn['qrCode'] = $app->qrcode->url($result['ticket']);
        //$qrCode = FileUtil::downloadFile($url, $documentRoot);
		$shareCampaignIn['qrCode'] = $appConfig['protocol'].$appConfig['domain']['main'].'api/qrcode/show?url='
			.urlencode($appConfig['protocol'].$appConfig['domain']['main'].'#/campaign/detail/'.$data['campaignId']);

        if(!empty($shareCampaignIn['picList'])) {
            return HttpResponse::success($shareCampaignIn);
        } else {
            return HttpResponse::error('获取分享内容失败!');
        }
	}

	/**
	 * 分享活动默认的内容
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function shareIn(Request $request)
	{
		$data = $request->all();
		$shareIn = CampaignShareModel::getShareIn($data['Id']);
		if(!empty($shareIn)) {
			return HttpResponse::success($shareIn);
		} else {
			return HttpResponse::error('分享内容不存在或已删除！');
		}
	}

    /**
     * 输出分享活动静态页面
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pageShare(Request $request){
        $data = $request->all();
        $shareCampaignContent = FileUtil::getPageTemplate($request, 'shareCampaign');
        $shareIn = CampaignShareModel::getShareIn($data['Id']);
        if(!empty($shareIn)) {
            $shareCampaignContent = str_replace('【content】', $shareIn->ShareContent, $shareCampaignContent);
        }

        return $shareCampaignContent;
    }

	/**
	 * 分享活动
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function doShare(Request $request)
	{
		$userIn = Session::get('userData');
		$data = $request->all();

        $campaignIn = CampaignModel::getCampaign(
            array(
                'UniqueId' => $data['CampaignId'],
                'IsDeleted' => 0
            ),
            array(
                'Id'
            ),
            false
        );

        $data['ShareFromUserId'] = $userIn['Id'];
        $data['CampaignId'] = $campaignIn[0]->Id;
        $shareId = CampaignShareModel::saveShare($data);
        if ($shareId) {
            $documentRoot = $request->server->get('DOCUMENT_ROOT');
            $appConfig = app()->container->getParameter('app');
            // You can pass a filename, a HTML string, an URL or an options array to the constructor
            $image = new Image($appConfig['protocol'] . $appConfig['domain']['main'] . 'api/campaign/pageShare?Id=' . $shareId);
            $image->setOptions(array(
                'width' => 750
            ));
            $saveUrl = 'app/campaign-share/' . $shareId . '.png';
            $image->saveAs($documentRoot . '/' . $saveUrl);


            return HttpResponse::success(array(
                'shareId' => $shareId
            ));
        } else {
            return HttpResponse::error('新增分享失败！');
        }
    }

    /**
     * 打开别人分享的活动
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function receiveShare(Request $request)
    {
        $userIn = Session::get('userData');
        $data = $request->all();

        $campaignIn = CampaignModel::getCampaign(
            array(
                'UniqueId' => $data['campaignId'],
                'IsDeleted' => 0
            ),
            array(
                'Id'
            ),
            false
        );

		if(isset($data['campaignShareUniqueId'])){
			$campaignShareIn = CampaignShareModel::getShare(
				array(
					'UniqueId' => $data['campaignShareUniqueId'],
					'IsDeleted' => 0
				),
				array(
					'Id'
				)
			);
			unset($data['campaignShareUniqueId']);
			$data['campaignShareId'] = $campaignShareIn[0]->Id;
		}

        $data['ShareToUserId'] = $userIn['Id'];
        $data['campaignId'] = $campaignIn[0]->Id;

//        if($userIn['Id'] == $data['shareFromUserId']){
//            return HttpResponse::error('点击自己的分享无效!');
//        }

        $receiveId = CampaignShareReceiverModel::saveShareReceiver($data);
        if ($receiveId) {
            return HttpResponse::success(array(
                'receiveId' => $receiveId
            ));
        } else {
            return HttpResponse::error('接收分享失败!');
        }
    }

    /**
     * 活动反馈
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function feedback(Request $request)
    {
        $userIn = Session::get('userData');
        $data = $request->all();
        $campaignStatus = CampaignModel::getCampaignStatus($data['CampaignId']);

		if($campaignStatus == self::CAMPAIGN_STATUS_IS_FINISH) {
			$rating = $data['Rating'];
			unset($data['Rating']);
			$result = null;
			$data['ContactId'] = isset($userIn['ContactId']) ? $userIn['ContactId'] : 0;
			$data['UserId'] = $userIn['Id'];
			if($userIn['UserType'] == self::USER_IS_TEACHER) {
				$data['CampaignCompleted'] = $data['CampaignStatus'] == 'true' ? 1 : 0;
				unset($data['CampaignStatus']);
				$data['PlatformRating'] = $rating;
				$result = CampaignFeedbackTeacherModel::save($data);
			} else if($userIn['UserType'] == self::USER_IS_STUDENT) {
				$data['AttendedCampaign'] = $data['CampaignStatus'] == 'true' ? 1 : 0;
				unset($data['CampaignStatus']);
				$data['OverallRating'] = $rating;
				$result = CampaignFeedbackStudentModel::save($data);
			}
			if($result) {
				return HttpResponse::success();
			} else {
				return HttpResponse::error('添加反馈失败！');
			}
		} else {
			$message = '';
			if($campaignStatus == -1){
				$message = '活动不存在或已删除！';
			} else {
				$message = '活动不在完成状态！';
			}
			return HttpResponse::error($message, $campaignStatus);
		}

	}


}
