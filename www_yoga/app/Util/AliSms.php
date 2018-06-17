<?php
/**
 * Created by PhpStorm.
 * User: Rainy
 */
namespace App\Util;

use YYHSms\SendSms;

class AliSms
{

	public static function sendRegisterCode($data) {

		$config = array(
			'accessKeyId'    => 'LTAICNq4TIjHNVhd',
			'accessKeySecret' => '9P8vHLpRBdxuP6JFIPNPLsBUzfrJ7g'
		);

		$parems = [
			'PhoneNumbers' => $data['phoneNumber'],
			'SignName' => '来瑜伽',
			'TemplateCode' => 'SMS_123737123',
			'TemplateParam' => [
				'code' => $data['code']
			]
		];
		$sms = new SendSms($config, $parems);
		return (array) $sms -> send();
	}
}