<?php

namespace App\Model;

use App\Model\BaseModel;

class OrderModel extends BaseModel
{

	const ORDER_STATUS_IS_IS_NOT_PAY = 1;
	const ORDER_STATUS_IS_IS_PAY = 2;
	const ORDER_STATUS_IS_IS_CANCEL = 0;

	//
    static protected $table = 'Order';

	/**
	 * 生成订单
	 */
	public static function save($data) {
		$data['OrderNo'] = self::getOrderNo();
		if(parent::saveData(self::$table, $data)){
			return $data['OrderNo'];
		}
	}

	/**
	 * 生成订单号
	 */
	public static function getOrderNo() {
		return $order_number = date('Ymd').substr(implode(NULL,
				array_map('ord', str_split(substr(uniqid(), 7, 13), 1))),
				0, 8);
	}

	/**
	 * 获取订单信息
	 * @param $uniqueId
	 * @return object
	 */
	public static function getOrderIn($orderNo) {
		$orderData = parent::getData(
			self::$table,
			array('OrderNo', 'ContactId', 'UserId', 'CampaignId', 'OrderStatus', 'PayableAmount', 'DiscountCodeId'),
			array('OrderNo'=>$orderNo, 'IsDeleted' => 0)
		);
		if(!empty($orderData)) {
			return $orderData[0];
		} else {
			return false;
		}

	}

	/**
	 * 获取订单信息
	 * @param $uniqueId
	 * @return object
	 */
	public static function paySuccess($orderNo) {
		return self::updateData(self::$table, array(
			'OrderStatus' =>self::ORDER_STATUS_IS_IS_PAY,
		), array('OrderNo'=>$orderNo));
	}
}
