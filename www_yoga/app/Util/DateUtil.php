<?php
/**
 * Created by PhpStorm.
 * User: Rainy
 */

namespace App\Util;

class DateUtil
{
	public static function  getDateChinaShow($date) {
		$weekArray=array("日","一","二","三","四","五","六");
		$timeStamp = strtotime($date);

		return date('m',$timeStamp).'-'.date("d",$timeStamp)
			.' 周'.$weekArray[date("w",$timeStamp)];
	}

	/**
	 * 客户端传来的时间 拼成字符串
	 * @param $dateArr
	 */
	public static function getDateStr($date) {
		return $date.':00';
	}

}