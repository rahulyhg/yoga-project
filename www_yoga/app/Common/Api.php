<?php
namespace App\Common;

use Illuminate\Support\Facades\Session;

/**
 * Class Api
 * @package App\Common
 */
class Api
{

	protected static $routerConfig = null;

    const ERROR_USER_NOT_LOGIN = -1;
	const ERROR_FIELD_IS_EMPTY = -2;
	const ERROR_USER_DO_REGISTER = -3;
	const ERROR_USER_NOT_ACTIVE = -4;


    public function __construct()
    {
    }
    public static function setRouterConfig($routerConfig) {
        self::$routerConfig = $routerConfig;
    }

    public static function checkApiParams($request) {
		$errorCode = 0;
    	$routerIn = self::getControllerAction($request);

		$controller = $routerIn['controller'] == '' ? '': '/'.$routerIn['controller'];
		$action = $routerIn['action'] == '' ? '': '/'.$routerIn['action'];

		if(isset(self::$routerConfig[$routerIn['module'].$controller.$action])) {
			$currentRouterIn = self::$routerConfig[$routerIn['module'].$controller.$action];

			if(!empty($currentRouterIn)) {
                if(isset($currentRouterIn['isAuth']) && $currentRouterIn['isAuth'] == 1) {
                    $userData = Session::get('userData');
                    if(!$userData) {
                        $errorCode = self::ERROR_USER_NOT_LOGIN;
                    } else if(!isset($userData['Username']) || !$userData['Username']){
						$errorCode = self::ERROR_USER_DO_REGISTER;
					} else if(isset($userData['IsActive']) && $userData['IsActive'] == 0){
						$errorCode = self::ERROR_USER_NOT_ACTIVE;
					}
                }
                if( $errorCode == 0 && isset($currentRouterIn['params'])) {
                    $errorCode = self::doCheckApiParams($request, $currentRouterIn['params']);
                }
            }

			$errorMessage = '';

			switch ($errorCode) {
				case self::ERROR_USER_NOT_LOGIN:
					$errorMessage = '请先登录！';
					break;
				case self::ERROR_USER_DO_REGISTER;
					$errorMessage = '请先注册！';
					break;
				case self::ERROR_USER_NOT_ACTIVE:
					$errorMessage = '账号已封，请联系管理员！';
					break;
                case self::ERROR_FIELD_IS_EMPTY:
                    $errorMessage = '必填字段为空！';
                    break;
			}
			return array('errorCode' => $errorCode, 'message' => $errorMessage);
		} else {
			return array('errorCode' => '-10', 'message' => '请求API的地址出错！');
		}

	}

	/**
	 * 获取controller action
	 * @param $request
	 * @return array
	 */
	public static function getControllerAction($request) {
		$url = $request->getRequestUri();
		$returnIn = array('module' =>'', 'controller' => '', 'action' => '');
    	$urlIn = explode('/', $url);
    	$returnIn['module'] = $urlIn[1];
		if(isset($urlIn[2])) {
			$returnIn['controller'] = $urlIn[2];
		}
    	if(isset($urlIn[3])) {
    		$urlActionIn = explode('?', $urlIn[3]);
    		$returnIn['action'] = $urlActionIn[0];
		}
		return $returnIn;

	}

	/**
	 * 检测输入数据
	 * @param $request
	 * @param $routerParams 配置的检测参数
	 * @return int
	 */
    public static function doCheckApiParams($request, $routerParams)
	{
		$paramsData = $request->all();
		$errorCode = 0;	//默认为0 则检测通过

		foreach ($routerParams as $field => $fieldIn){
			if(isset($fieldIn['require']) && $fieldIn['require']) {

				if(!isset($paramsData[$field]) || (!$paramsData[$field] && $paramsData[$field] !== 0)) {
					$errorCode = self::ERROR_FIELD_IS_EMPTY;
				}
			}
		}

		return $errorCode;

	}

	//
}
