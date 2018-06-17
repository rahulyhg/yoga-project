<?php

namespace App\Model;

use App\Model\BaseModel;

use App\Model\FileModel;

use App\Model\CampaignFeedbackTeacherModel;
use App\Model\CampaignFeedbackStudentModel;

use App\Util\DateUtil;
use Illuminate\Support\Facades\DB;

class CampaignModel extends BaseModel
{

	const CAMPAIGN_STATUS_IS_NOT_PUBLISH = 0;
	const CAMPAIGN_STATUS_IS_PUBLISH = 1;
	const CAMPAIGN_STATUS_IS_START = 2;
	const CAMPAIGN_STATUS_IS_FINISH = 3;

	//
	static protected $table = 'Campaign';
    /**
     * 获取活动信息
     */
    public static function getCampaign($condition, $fields=null, $getPic=true) {
    	if(empty($condition)) {
    		return false;
		}
    	if(!$fields) {
			$fields = array('UniqueId','Name','Type','HostName',
				'IsFreeOfCharge','MaxPerson',
				'SignedUpPerson','Description','Poster','StartTime',
				'EndTime','Location','Address','Status','CreatedDate','CreatedById'
			);
		}
		$orderBy = 'Id desc';
		$query = DB::table(self::$table)->select($fields);

    	if(isset($condition['Status']) && is_array($condition['Status'])) {
			$query->whereIn('Status', $condition['Status']);
			unset($condition['Status']);
		}
		if(!empty($condition)) {
			$query->where($condition);
		}

		$campaignData = $query->orderByRaw($orderBy)->get()->toArray();

        if($getPic && !empty($campaignData)) {
			foreach ($campaignData as &$perCampaign){
				if($perCampaign->Poster) {
					$perCampaign->pictureList = FileModel::getFileList($perCampaign->Poster);
				}
			}
		}

        return $campaignData;
    }

	/**
	 * 获取活动的所有图片
	 */
	public static function getShareCampaignIn($campaignId) {
		$picList = [];
		$campaignData = self::getData(self::$table, array(
			'Name', 'Poster', 'Status'
		), array(
			'UniqueId' => $campaignId
		));
		if($campaignData) {
			$picId = $campaignData[0]->Poster;
			if($campaignData[0]->Status == self::CAMPAIGN_STATUS_IS_FINISH) {
				$teacherFeedIn = CampaignFeedbackTeacherModel::getFeedIn($campaignId);
				if($teacherFeedIn) {
					foreach($teacherFeedIn as $perFeedIn) {
						$picId .= $perFeedIn->CampaignPhotos;
					}
				}
				$stuFeedIn = CampaignFeedbackStudentModel::getFeedIn($campaignId);
				if($stuFeedIn) {
					foreach($stuFeedIn as $perFeedIn) {
						$picId .= $perFeedIn->CampaignPhotos;
					}
				}
			}
			$picIdList = implode(',', array_filter(explode(',', $picId)));

			if(!empty($picIdList)) {
				$picList = FileModel::getFileList($picIdList);
			}
		}

		return array(
			'picList' => $picList,
			'campaignIn' => !empty($campaignData)?$campaignData[0]:[]
		);
	}

	/**
	 * 创建活动
	 */
	public static function saveCampaign($data) {
		$priceList = json_decode($data['priceList'], true);
		unset($data['priceList']);
		DB::beginTransaction();
		$data['UniqueId'] = self::getCampaignId();
		try {
			$data['StartTime'] = DateUtil::getDateStr($data['StartTime']);
			$data['EndTime'] = DateUtil::getDateStr($data['EndTime']);
			$data['IsFreeOfCharge'] = $data['IsFreeOfCharge'] == 'true' ? 1: 0;
			parent::saveData(self::$table, $data);
			if($data['IsFreeOfCharge'] != 1) {
				foreach ($priceList as &$perPrice) {
					if($perPrice['Price'] > 0) {
						$perPrice['AndDiscountCode'] =
							isset($perPrice['AndDiscountCode']) && $perPrice['AndDiscountCode'] == 'true' ? 1 : 0;
						$perPrice['StartTime'] = DateUtil::getDateStr($perPrice['StartTime']);
						$perPrice['EndTime'] =  DateUtil::getDateStr($perPrice['EndTime']);
						$perPrice['CampaignId'] = $data['UniqueId'];
						TicketModel::save($perPrice);
					}
				}
			}

			DB::commit();
			return $data['UniqueId'];

		} catch (\Exception $e) {
			DB::rollBack();
			return '';
		}
	}

	/**
	 * 创建活动
	 */
	public static function addSignedUpPerson($campaignId, $signedUpPerson) {

		return parent::updateData(self::$table,
			array('SignedUpPerson' => intval($signedUpPerson)+1),
			array('UniqueId' => $campaignId)
		);
	}

	/**
	 * 修改活动
	 */
	public static function updateCampaign($data, $userId) {
		if(isset($data['priceList'])) {
			$priceList = json_decode($data['priceList'], true);
			unset($data['priceList']);
		}
		DB::beginTransaction();
		try {
			$data['StartTime'] = DateUtil::getDateStr($data['StartTime']);
			$data['EndTime'] = DateUtil::getDateStr($data['EndTime']);
			$data['IsFreeOfCharge'] = $data['IsFreeOfCharge'] == 'true' ? 1: 0;
			$updateStatus = parent::updateData(self::$table, $data,
				array(
					'CreatedById' => $userId,
					'UniqueId' => $data['UniqueId']
				)
			);
			if($updateStatus) {
				//删除价格
				$updateTicketStatus = TicketModel::delete(array(
					'CreatedById' => $userId,
					'CampaignId' => $data['UniqueId']
				));
				if($updateTicketStatus && $data['IsFreeOfCharge'] != 1) {
					foreach ($priceList as &$perPrice) {
						if($perPrice['Price'] > 0) {
							unset($perPrice['Id']);
							$perPrice['AndDiscountCode'] =
								isset($perPrice['AndDiscountCode']) && $perPrice['AndDiscountCode'] == 'true' ? 1 : 0;
							$perPrice['StartTime'] = DateUtil::getDateStr($perPrice['StartTime']);
							$perPrice['EndTime'] =  DateUtil::getDateStr($perPrice['EndTime']);
							$perPrice['CampaignId'] = $data['UniqueId'];
							TicketModel::save($perPrice);
						}
					}
				}
			}
			DB::commit();
			return true;
		} catch (\Exception $e) {
			DB::rollBack();
			return false;
		}
	}

	/**
	 * 获取活动状态
	 * @param $uniqueId
	 * @return Number
	 */
	public static function getCampaignStatus($uniqueId) {
		$status = -1;	//活动不存在或删除
		$campaignData = parent::getData(
			self::$table,
			array('Status'),
			array('UniqueId'=>$uniqueId, 'IsDeleted' => 0)
		);
		if(!empty($campaignData)) {
			$status = $campaignData[0]->Status;
		}

		return $status;

	}

	/**
	 * 发现活动
	 */
	public static function findCampaign($condition) {
		$dbObj = DB::table(self::$table)
			->select('Id','UniqueId','Name','Description','Address',
				'CreatedDate','Poster', 'IsFreeOfCharge');

		$orderBy = 'Id desc';
		if(isset($condition['date']) && $condition['date'] != ''){
			$dbObj->where('StartTime', '<=', $condition['date'])
				->where('EndTime', '>=', $condition['date']);
			unset($condition['date']);
		}
		if(isset($condition['IsHot'])) {
			$orderBy = 'IsHot desc,'.$orderBy;
			unset($condition['IsHot']);
		}

		if(isset($condition['campaignId'])) {
			$dbObj->whereIn('Id', $condition['campaignId']);
			unset($condition['campaignId']);
		}

		$dbObj->where($condition);

		$campaignData = $dbObj->orderByRaw($orderBy)->get()->toArray();

		if(!empty($campaignData)) {
			foreach ($campaignData as &$perCampaign){
				if($perCampaign->Poster) {
					$perCampaign->pictureList = FileModel::getFileList($perCampaign->Poster);
				}
				if($perCampaign->IsFreeOfCharge != 1) {
					$perCampaign->priceIn = TicketModel::getTicketPrice(array(
						'CampaignId' => $perCampaign->UniqueId,
						'date' => date('Y-m-d H:i:s')
					));
				}
			}
		}

		return $campaignData;
	}

	/**
	 * 修改活动
	 */
	public static function changeStatus($status, $condition) {
		return $updateStatus = parent::updateData(self::$table,
			array('Status'=> $status),
			$condition
		);
	}

	/**
	 * 获取需要开始的活动
	 */

	public static function getNeedStartCampaign() {
		$dbObj = DB::table(self::$table)
			->select('Id', 'Status');

		$date = date('Y-m-d H:i:s');

		$dbObj->where('StartTime', '<=', $date)
		->where(array('Status' => self::CAMPAIGN_STATUS_IS_PUBLISH, 'IsDeleted' => 0));


		return $dbObj->orderByRaw('id asc')->get()->toArray();
	}

	/**
	 * 获取需要结束的活动
	 */
	public static function getNeedEndCampaign() {
		$dbObj = DB::table(self::$table)
			->select('Id', 'Status');

		$date = date('Y-m-d H:i:s');

		$dbObj->where('EndTime', '<=', $date)
			->where(array('Status' => self::CAMPAIGN_STATUS_IS_START, 'IsDeleted' => 0));


		return $dbObj->orderByRaw('id asc')->get()->toArray();
	}

	/**
	 * 批量操作活动状态
	 */
	public static function setCampaignStatus($campaignId, $status) {
		if(!empty($campaignId)) {
			$data = array(
				'Status' => $status,
				'LastModifiedDate' => date('Y-m-d H:i:s')
			);

			return DB::table(self::$table)->whereIn('Id', $campaignId)->update($data);
		}
	}

	/**
	 * 生成唯一活动唯一标识
	 */
	public static function getCampaignId(){
		$s = uniqid('', true);

		$hex = substr($s, 0, 13);
		$dec = $s[13] . substr($s, 15); // skip the dot
		return base_convert($hex, 16, 36) . base_convert($dec, 10, 36);
	}
}
