<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Common\HttpResponse;
use Illuminate\Support\Facades\Session;

use App\Model\UserModel;

class TeacherController extends Controller
{
	const USER_IS_TEACHER = 1;

	public function __construct()
	{

	}

    /**
     * 老师首页
     * @return \Illuminate\Http\JsonResponse
     */
    public function home()
    {
		$userIn = Session::get('userData');
		if($userIn['UserType'] == self::USER_IS_TEACHER) {
			$userStatus = 1;
			$userInObj = UserModel::getUserBaseIn(array('Id' => $userIn['Id']));
			if ($userInObj != null) {
				if ($userInObj->IsActive == 1) {
					if ($userInObj->IsAxamine == 0) {
						$userStatus = -3;
					}
				} else {
					$userStatus = -2;     //已封号
				}
			} else {
				$userStatus = -1;
				//未注册 调到注册页面
			}
			return HttpResponse::success(array('status' => $userStatus));
		} else {
			return HttpResponse::error();
		}
    }


}
