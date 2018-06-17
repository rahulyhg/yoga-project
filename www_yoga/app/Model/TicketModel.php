<?php

namespace App\Model;

use App\Model\BaseModel;
use Illuminate\Support\Facades\DB;

class TicketModel extends BaseModel
{
	//
    static protected $table = 'Ticket';

	/**
	 * 创建门票
	 */
	public static function save($data) {
		return parent::saveData(self::$table, $data);
	}

	/**
	 * 获取价格
	 * 按level排序，越大越优先
	 */
	public static function getTicketPrice($condition) {
		$dbObj = DB::table(self::$table)
			->select('Id', 'Price','Name','AndDiscountCode','StartTime', 'EndTime');


		if(isset($condition['date'])){
			$dbObj->where('StartTime', '<=', $condition['date'])
				->where('EndTime', '>=', $condition['date']);
			unset($condition['date']);
		}
		$dbObj->where($condition)->where(array(
			'Status' => 1,
			'IsDeleted' => 0
		));

		return $dbObj->orderByRaw('level desc')->get()->toArray();

	}


	/**
	 * 删除门票
	 */
	public static function delete($condition) {
		return parent::updateData(self::$table, array('IsDeleted' => 1), $condition);
	}


}
