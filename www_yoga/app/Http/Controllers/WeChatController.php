<?php

namespace App\Http\Controllers;

use EasyWeChat\Factory;

use Illuminate\Http\Request;
use App\Common\HttpResponse;

use App\Model\UserModel;
use App\Util\FileUtil;


class WeChatController extends Controller
{

    public function __construct()
    {

    }

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve(Request $request)
    {
        //Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');

        $app->server->push(function ($message) {

            FileUtil::logRecord(array(
                'message' => print_r($message, true)
            ));
            // $message->FromUserName // 用户的 openid
            // $message->MsgType // 消息类型：event, text....
            switch ($message['MsgType']) {
                case 'event':
                    $event = strtolower($message['event']);
                    if ($event == 'subscribe')
                        $msg = '终于等到你，还好我没放弃。';
                    else if ($event == 'click') {
                        switch ($message->EventKey) {
                            case 'V1001_TODAY_MUSIC':
                                $msg = '暂未开放';
                                break;

                            case 'V1001_GOOD':
                                $msg = '发个表情点赞。';
                                break;
                        }
                    }
                    break;
                case 'text':
                    $msg = $this->dealTextMessage($message['Content']);
                    break;
                case 'image':
                    $msg = '照骗已收到。';
                    break;
                case 'voice':
                    $msg = '中国好声音。';
                    break;
                case 'video':
                    $msg = '大视频已收到。';
                    break;
                case 'shortvideo':
                    $msg = '小丫小呀小视频。';
                    break;
                case 'location':
                    $msg = '我已经知道你在哪，马上去找你哦。';
                    break;
                case 'link':
                    $msg = '链接已收到。';
                    break;
                default:
                    $msg = '消息已收到，谢谢反馈。';
                    break;
            }
            return $msg;
        });
        /*$app->server->push(function($message){
            return "欢迎关注 overtrue！";
        });*/

        $app->server->serve()->send();

    }


    /*
     * 处理文字消息
     */
    public function dealTextMessage($message)
    {

        $appConfig = app()->container->getParameter('app');

        if (strpos($message, '老师') !== false) {
            $registerUrl = $appConfig['protocol'] . $appConfig['domain']['main'] . '#/user/register/1';
            return "请点击链接进行注册\n<a href='{$registerUrl}'>点击注册</a>";
        } else {
            return '';
        }

    }

}
