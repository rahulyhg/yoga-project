<?php

namespace App\Model;

use App\Model\BaseModel;

class CampaignShareReceiverModel extends BaseModel
{
	//
    static protected $table = 'CampaignShareReceiver';

	/**
	 * 新增接收分享
	 * @param $data
	 * @return int
	 */
	public static function saveShareReceiver($data){
		return parent::saveData(self::$table, $data);
	}
}
