<?php
namespace App\Http\Middleware;

use App\Common\HttpResponse;
use Closure;

use EasyWeChat\Factory;
use Illuminate\Support\Facades\Session;
use App\Model\UserModel;
use Symfony\Component\HttpFoundation\Cookie;

class WxMiddleware
{

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
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userIn = Session::get('userData');
        $requestUri = $request->getRequestUri();

        if (empty($userIn)) {
            if ((strpos($requestUri, '/api') === false
                    || strpos($requestUri, '/api/login') !== false)
                && strpos($requestUri, '/wechat') === false
            ) {
                $data = $request->all();
                $targetUrl = '/#' . (isset($data['targetUrl']) ? $data['targetUrl'] : '/campaign/find');
                if (isset($data['targetUrl'])) {
                    Session::set('targetUrl', $data['targetUrl']);
                }

                $this->wxLogin($targetUrl);
            } else {
                return $next($request);
            }
        } else {
            if (!$userIn['Nickname'] && isset($_COOKIE['snsapi_userinfo']) && $_COOKIE['snsapi_userinfo']) {
                $this->wxLogin(null);
            }

            //其实这里可以用缓存记录当前用户状态 因为有审核 和屏蔽状态，需要实时同步
            $userData = UserModel::getUserBaseIn(
                array(
                    'WeChatOpenID' => $userIn['WeChatOpenID']
                )
            );

            $userData = json_decode(json_encode($userData), true);

            if (empty($userData['Username'])) {
                //未注册去除标识数据
//                unset($userData['Id']);
//                unset($userData['UserType']);
            }

            Session::set('userData', $userData);

            unset($userData['WeChatOpenID']);

            setcookie('userData', json_encode($userData), 0, '/');
            //}

            return $next($request);
        }

    }

    public function wxLogin($targetUrl)
    {
//        $path = $_SERVER['DOCUMENT_ROOT'] . '/../app/config/';
//        $config = require($path . 'wechat.php');
//
//        //静默授权
//        $snsapi_base = false;
//        //下面地址为静默授权
//        if (isset($_COOKIE['targetUrl']) && (strpos($_COOKIE['targetUrl'], '/campaign/detail') !== false || strpos($_COOKIE['targetUrl'], '/campaign/find') !== false)) {
//            $snsapi_base = true;
//            $config['official_account']['default']['oauth']['scopes'] = array('snsapi_base');
//        } else {
//            $config['official_account']['default']['oauth']['scopes'] = array('snsapi_userinfo','snsapi_base');
//        }
//
//        $configString = var_export($config, true);
//        $configToFile = <<<phpteach
//<?php
//
///*
// * This file is part of the overtrue/laravel-wechat.
// *
// * (c) overtrue <i@overtrue.me>
// *
// * This source file is subject to the MIT license that is bundled
// * with this source code in the file LICENSE.
// */
//
// return $configString;
//phpteach;
//        file_put_contents($path . 'wechat.php', $configToFile);

        //静默授权
        $snsapi_base = false;
        //下面地址为静默授权
        if (isset($_COOKIE['targetUrl']) && (strpos($_COOKIE['targetUrl'], '/campaign/detail') !== false || strpos($_COOKIE['targetUrl'], '/campaign/find') !== false)) {
            $snsapi_base = true;
        } else {
            $snsapi_base = false;
        }
        //如果cookie设置了snsapi类型，以cookie为准
        if (isset($_COOKIE['snsapi_userinfo']) && $_COOKIE['snsapi_userinfo']) {
            $snsapi_base = false;
        }

        $app = $snsapi_base ? app('wechat.official_account.base') : app('wechat.official_account.default');

        $oauth = $app->oauth;
        try {
            //获取 OAuth 授权结果用户信息
            $user = $oauth->user();

            $targetUrl = $_COOKIE['targetUrl'];


            $userData = UserModel::getUserBaseIn(
                array(
                    'WeChatOpenID' => $user['original']['openid']
                )
            );
            $userData = json_decode(json_encode($userData), true);

            if (empty($userData)) {
                if ($snsapi_base) {
                    $userData = array(
                        'WeChatOpenID' => $user['original']['openid'],
                        'IsActive' => 1
                    );
                } else {
                    $userData = array(
                        'Nickname' => $user['nickname'],
                        'Avatar' => $user['avatar'],
                        'WeChatOpenID' => $user['original']['openid'],
                        'IsActive' => 1
                    );
                }

                //创建用户
                UserModel::addUser($userData);
            } else if (!$snsapi_base) {
                $userInfo = array(
                    'Nickname' => $user['nickname'],
                    'Avatar' => $user['avatar'],
                );

                $userData['Nickname'] = $user['nickname'];
                $userData['Avatar'] = $user['Avatar'];
                $condition = array(
                    'WeChatOpenID' => $user['original']['openid']
                );
                UserModel::updateUser($userInfo, $condition);
            }

            Session::set('userData', $userData);
            unset($userData['WeChatOpenID']);
            setcookie('userData', json_encode($userData), 0, '/');
            header('location:' . $targetUrl);

        } catch (\Exception $e) {
            Session::set('targetUrl', $targetUrl);
            $oauth->redirect()->send();
        }
    }
}
