<?php
namespace App\Http\Middleware;

use App\Common\HttpResponse;
use App\Common\Api;
use Closure;
use Illuminate\Support\Facades\Session;

class ApiMiddleware
{
    /**
     * api配置信息
     */
    protected $apiConfigIn;

    /**
     * Create a new middleware instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
	{

//		$userData = Session::get('userData');
//		if(empty($userData)) {
//			$userIn = array('Id' => '34', 'Avatar' => 'http://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTJq7d6MqlepFyY56HicJMvDvSPODC5VyibNzJyaHUiaIFZmxwXv1AWzALulyj1vE39x53Oo5yNxzKuEQ/132',
//				'Username' => '17701810917', 'Nickname' => '张朋鹏',
//				'WeChatOpenID' => 'oJ0s61RR8QbMGfqNaSc7mWYLGrvU', 'UserType' => 1, 'IsActive'=>1);
//			Session::set('userData', $userIn);
//			setcookie('userData', json_encode($userIn), 0, '/');
//		}


//		$userIn = null;
//		Session::set('userData', $userIn);
//		setcookie('userData', json_encode($userIn), 0, '/');

		$url = $request->getRequestUri();

		if( strpos($url, 'login') !== false
            || strpos($url, 'wechat') !== false) {
			return $next($request);
		} else {
			$checkIn = Api::checkApiParams($request);
			if ($checkIn['errorCode'] == 0) {
				return $next($request);
			} else {
				return HttpResponse::error($checkIn['message'], [], $checkIn['errorCode']);
			}
		}

    }
}
