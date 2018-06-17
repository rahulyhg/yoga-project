<?php

namespace App\Model;

use App\Model\BaseModel;
use App\Model\UserModel;
use Illuminate\Support\Facades\DB;

class CampaignMemberModel extends BaseModel
{
	//
	static protected $table = 'CampaignMember';

	/**
	 * 添加参与话题
	 */
	public static function save($data) {
		return parent::saveData(self::$table, $data);
	}

	/**
	 * 获取用户基本信息
	 */
	public static function getMemberIn($campaignId) {
		$campaignUserIn = [];
		$campaignMemberIn = DB::table(self::$table)
			->select('CampaignId','UserId','ContactId')
			->where(array('CampaignUniqueId' => $campaignId, 'IsDeleted'=>0))
			->where('Status', '!=', 0)
			->get()->toArray();

		if(!empty($campaignMemberIn)) {
			$userIdArr = [];
			foreach ($campaignMemberIn as $member) {
				array_push($userIdArr, $member->UserId);
			}
			$campaignUserIn = UserModel::getUserList($userIdArr);
		}
		return $campaignUserIn;
	}

	/**
	 * 我参加的活动
	 */
	public static function getMycCampaignId($userId) {
		$campaignId = [];
		$campaignMemberIn = DB::table(self::$table)
			->select('CampaignId','CampaignUniqueId','UserId','ContactId')
			->where(array('UserId' => $userId, 'IsDeleted'=>0))
			->where('Status', '!=', 0)
			->get()->toArray();

		if(!empty($campaignMemberIn)) {
			foreach ($campaignMemberIn as $member) {
				array_push($campaignId, $member->CampaignId);
			}
		}
		return $campaignId;
	}
}
