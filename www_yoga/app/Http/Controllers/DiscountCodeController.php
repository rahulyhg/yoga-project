<?php

namespace App\Http\Controllers;
use App\Model\CampaignModel;
use App\Model\UserModel;
use App\Util\DateUtil;
use Illuminate\Http\Request;
use App\Common\HttpResponse;

use App\Model\DiscountCodeModel;

use Illuminate\Support\Facades\Session;

class DiscountCodeController extends Controller
{
    const USER_IS_TEACHER = 1;
	const USER_IS_STUDENT = 2;

    const CAMPAIGN_STATUS_IS_START = 2;

	public function __construct()
	{

	}

	/**
	 * 优惠码列表
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index(Request $request){
        $userIn = Session::get('userData');
        if($userIn['UserType'] == self::USER_IS_TEACHER) {
            $data = $request->all();
            $data['page'] = isset($data['page']) ? $data['page'] : 1;
			$data['Status'] = isset($data['Status']) ? $data['Status'] : 1;
            $returnData = ['codeList' => array(), 'campaignList'=>array()];

            $condition = array(
				'Status' => $data['Status'],
				'IsDeleted' => 0,
				'CreatedById' => $userIn['Id']
			);
            if(isset($data['useStatus'])){
				$condition['useStatus'] = $data['useStatus'];
			}
			if(isset($data['CampaignId']) && $data['CampaignId']){
				$condition['CampaignId'] = $data['CampaignId'];
			}
            $codeList = DiscountCodeModel::getDiscountCode($condition);

			$returnData['campaignList'] = CampaignModel::getCampaign(
            	array('CreatedById' => $userIn['Id'], 'Status' => array(1,2,3,4)),
				array('Id', 'UniqueId', 'Name', 'Status'), false
			);

            foreach ($codeList as $codeIn) {
                $dateStr = DateUtil::getDateChinaShow($codeIn->CreatedDate);
                if(!isset($returnData['codeList'][$dateStr])) {
                    $returnData['codeList'][$dateStr] = [];
                }
                array_push($returnData['codeList'][$dateStr], $codeIn);
            }
            return HttpResponse::success($returnData);
        } else {
            return HttpResponse::error();
        }

	}

	/**
	 * 创建优惠码
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function create(Request $request){
		$userIn = Session::get('userData');
		if($userIn['UserType'] == self::USER_IS_TEACHER) {
			$data = $request->all();

			$data['StartTime'] = DateUtil::getDateStr($data['StartTime']);
			$data['EndTime'] = DateUtil::getDateStr($data['EndTime']);

			$addIn = DiscountCodeModel::save($data);
			return HttpResponse::success($addIn);
		} else {
			return HttpResponse::error();
		}
	}


	/**
	 * 使用优惠码
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function useCode(Request $request){
		$userIn = Session::get('userData');
		if($userIn['UserType'] == self::USER_IS_STUDENT) {
			$data = $request->all();

			$codeIn = DiscountCodeModel::getDiscountCode(array(
				'DiscountCode' => $data['DiscountCode'],
				'CampaignId' => $data['CampaignId'],
				'Status' => 1,
				'date' => date('Y-m-d H:i:s')
			));
			if(!empty($codeIn)) {
				if($codeIn[0]->MaxUsage > $codeIn[0]->UsedCount) {
					unset($codeIn[0]->MaxUsage);
					unset($codeIn[0]->UsedCount);
					return HttpResponse::success($codeIn[0]);
				} else {
					return HttpResponse::error('优惠码超出使用次数~');
				}
			} else {
				return HttpResponse::error('优惠码不可用!');
			}

		} else {
			return HttpResponse::error();
		}
	}



}
