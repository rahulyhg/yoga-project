<?php

namespace App\Model;

use App\Model\BaseModel;

class CampaignFeedbackStudentModel extends BaseModel
{
	//
    static protected $table = 'CampaignFeedbackStudent';

	/**
	 * 创建回馈
	 */
	public static function save($data) {
		return parent::saveData(self::$table, $data);
	}

	/**
	 * 获取活动反馈
	 * @param $data
	 * @return int
	 */
	public static function getFeedIn($campaignId)
	{
		$feedIn = parent::getData(
			self::$table,
			array('UserId', 'ContactId','AttendedCampaign','CampaignId',
				'OverallRating', 'CampaignSummary', 'CampaignPhotos',
				'CreatedDate'
			),
			array('IsDeleted' => 0, 'CampaignId' => $campaignId),
			10
		);
		return $feedIn;

	}
}
