<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Common\HttpResponse;
use Illuminate\Support\Facades\Session;
use App\Model\UserModel;
use App\Util\AliSms;

class UserController extends Controller
{
	public function __construct()
	{
	}

    /**
     * 登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $data = $request->all();
        $userData = Session::get('userData');

        if(isset($_COOKIE['targetUrl']) && !isset($data['targetUrl'])){
            $targetUrl = $_COOKIE['targetUrl'];
        }else{
            $targetUrl = '/#/'.(isset($data['targetUrl']) ? $data['targetUrl'] : 'campaign/find');
        }

        header('location:' . $targetUrl);
    }

	/**
	 * 发送验证码
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function sendCode(Request $request)
	{
		$data = $request->all();
		if(!UserModel::checkUserExist(array('Username' => $data['phoneNumber']))) {

			$data['code'] = rand(1000, 9999);
			$sendStatus = AliSms::sendRegisterCode($data);
			if($sendStatus['Message'] == 'OK') {
				Session::set('codeVer',  $data);
				return HttpResponse::success($data);
			} else {
				return HttpResponse::error('发送验证码失败，请稍后重试！');
			}

		} else {
			return HttpResponse::error('用户已存在！');
		}

	}

	/**
	 * 执行注册
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function register(Request $request)
	{
        $userData = Session::get('userData');
        if(!empty($userData)) {
            $data = $request->all();
            $codeVer = Session::get('codeVer');
            if($data['code'] == $codeVer['code'] && $data['phoneNumber'] == $codeVer['phoneNumber']) {
                $updateUserData = array_merge($userData, array(
                    'Username' => $data['phoneNumber'],
                    'Mobile' => $data['phoneNumber'],
                    'UserType' => $data['userType'],
                ));
                $weChatOpenID = $updateUserData['WeChatOpenID'];
				unset($updateUserData['WeChatOpenID']);
                //更新用户
				$regStatus = UserModel::regUser($updateUserData, $weChatOpenID);
                if($regStatus) {

					$userData = UserModel::getUserBaseIn(
						array(
							'WeChatOpenID' =>  $weChatOpenID,
							'IsActive' => 1
						)
					);

                    Session::set('userData', json_decode( json_encode( $userData),true));

                    return HttpResponse::success($userData);
                } else {
                    return HttpResponse::error('注册失败！');
                }
            } else {
                return HttpResponse::error('注册验证错误！');
            }
        } else {
            return HttpResponse::error('访问失败！');
        }
	}

	/**
	 * 退出登录
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout(Request $request)
	{
		Session::set('userData', null);
		setcookie('userData', '', time()-3600, '/');
		return HttpResponse::success();
	}

    public function setMenu()
    {
        $app = app('wechat.official_account');
        $appConfig = app()->container->getParameter('app');
        $buttons = [
            [
                "name" => "瑜伽",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "首页",
                        "url" => $appConfig['protocol'] . $appConfig['domain']['main']
                    ]
                ],
            ],
            [
                "type" => "view",
                "name" => "发现",
                "url" =>  $appConfig['protocol'] . $appConfig['domain']['main']."#/campaign/find"
            ],
            [
                "type" => "view",
                "name" => "我的",
                "url" =>  $appConfig['protocol'] . $appConfig['domain']['main']."#/user/home"
            ]
        ];

        $app->menu->create($buttons);
    }

}
