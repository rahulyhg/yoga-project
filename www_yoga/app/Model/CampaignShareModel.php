<?php

namespace App\Model;

use App\Model\BaseModel;
use Illuminate\Support\Facades\DB;

class CampaignShareModel extends BaseModel
{
	//
    static protected $table = 'CampaignShare';

	/**
	 * 获取活动分享 废弃
	 * @param $data
	 * @return int
	 */
	public static function getShareIn($id){
		$shareIn = parent::getData(
			self::$table,
			array('CampaignId', 'ShareFromUserId',
				'OpenTimesCount', 'ShareMethod', 'ShareContent',
				'CreatedDate'
			),
			array('IsDeleted' =>0, 'Id' => $id)
		);
		if(!empty($shareIn)) {
		    self::addOpenCount($id, $shareIn[0]->OpenTimesCount);
			return $shareIn[0];
		} else {
			return null;
		}
	}


	/**
	 * 获取活动分享
	 * @param $data
	 * @return int
	 */
	public static function getShare($condition, $fields=null) {
		if(empty($condition)) {
			return false;
		}
		if(!$fields) {
			$fields = array('CampaignId', 'ShareFromUserId',
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

		$campaignShareData = $query->orderByRaw($orderBy)->get()->toArray();

		return $campaignShareData;
	}

    /**
     * 查看数加一
     * @param $data
     * @return int
     */
    public static function addOpenCount($id, $openCount){
        return parent::updateData(
            self::$table,
            array(
                'OpenTimesCount' => intval($openCount)+1
            ),
            array('IsDeleted' =>0, 'Id' => $id)
        );

    }



	/**
	 * 新增活动分享
	 * @param $data
	 * @return int
	 */
	public static function saveShare($data){
		return parent::saveData(self::$table, $data);
	}
}
