<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Endroid\QrCode\QrCode;


class QrCodeController extends Controller
{

	public function __construct()
	{

	}

	/**
	 * 生成二维码
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show(Request $request){
		$data = $request->all();

		$qrCode = new QrCode(urldecode($data['url']));

		header('Content-Type: '.$qrCode->getContentType());
		return $qrCode->writeString();
	}

}
