<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use App\Model\BaseModel;

class DiscountCodeModel extends BaseModel
{
	//
    static protected $table = 'DiscountCode';

	/**
	 * 创建优惠券
	 */
	public static function save($data) {
		$data['DiscountCode'] = self::makePromoCode();
		return parent::saveData(self::$table, $data);
	}


	/**
	 * 获取优惠码信息
	 */
	public static function getDiscountCode($condition) {

		$dbObj = DB::table(self::$table)
			->select('Id','DiscountName','DiscountCode','CampaignId',
				'DiscountType','DiscountAmount','DiscountPercent','MaxUsage',
				'UsedCount','StartTime','EndTime','Status','CreatedDate');


		if(isset($condition['date']) && $condition['date']){
			$dbObj->where('StartTime', '<=', $condition['date'])
				->where('EndTime', '>=', $condition['date']);
			unset($condition['date']);
		}

		if(isset($condition['useStatus'])) {
			if($condition['useStatus'] == 1) {
				$dbObj->where('UsedCount', '>', 0);
			} else {
				$dbObj->where('UsedCount', '=', 0);
			}
			unset($condition['useStatus']);
		}
		return $dbObj->where($condition)->orderByRaw('Id desc')->get()->toArray();
	}

	/**
	 * 执行使用优惠券
	 */
	public static function useCode($codeId) {
		$codeIn = parent::getData(self::$table, array('Id','MaxUsage','UsedCount'), array('Id' => $codeId));

		if(!empty($codeIn)) {
			return parent::updateData(self::$table,
				array('UsedCount' => intval($codeIn[0]->UsedCount)+1),
				array('Id' => $codeId)
			);
		} else {
			return false;
		}
	}

	/**
	 * 生成优惠码
	 */
	public static function makePromoCode() {
		$code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$rand = $code[rand(0,25)]
			.strtoupper(dechex(date('m')))
			.date('d').substr(time(),-5)
			.substr(microtime(),2,5)
			.sprintf('%02d',rand(0,99));
		for(
			$a = md5( $rand, true ),
			$s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
			$d = '',
			$f = 0;
			$f < 8;
			$g = ord( $a[ $f ] ),
			$d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
			$f++
		);
		return $d;
	}
}
