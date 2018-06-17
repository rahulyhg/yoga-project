<?php

namespace App\Common;

class HttpResponse
{

    public function __construct()
    {
    }

    public static function success($data=[], $message="")
	{
		$responseData = array(
			"code" => 200,
			"result" => $data,
			"message" => $message
		);
		return response()->json($responseData);

	}

	public static function error($message='', $data=[], $code = 400)
	{
		$responseData = array(
			"code" => $code,
			"result" => $data,
			"message" => $message
		);
		return response()->json($responseData);

	}



    public static function notLogin($message='')
    {
        $responseData = array(
            "code" => -100,
            "message" => $message? $message : '请执行登录！'
        );
        return response()->json($responseData);

    }


	//
}
