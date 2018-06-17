<?php

namespace App\Model;

use App\Model\BaseModel;

class CampaignFeedbackTeacherModel extends BaseModel
{
	//
    static protected $table = 'CampaignFeedbackTeacher';

	/**
	 * 创建回馈
	 */
	public static function save($data) {
		return parent::saveData(self::$table, $data);
	}

	/**
	 * 获取活动小结
	 * @param $data
	 * @return int
	 */
	public static function getFeedIn($campaignId)
	{
		$feedIn = parent::getData(
			self::$table,
			array('UserId', 'ContactId','CampaignCompleted','CampaignId',
				'PlatformRating', 'CampaignSummary', 'CampaignPhotos',
				'CreatedDate'
			),
			array('IsDeleted' => 0, 'CampaignId' => $campaignId),
			10
		);
		return $feedIn;
	}
}
