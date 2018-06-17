<?php
/**
 * Created by PhpStorm.
 * User: Rainy
 */

namespace App\Util;


class FileUtil
{
    /**
     * 下载文件
     * @param $url
     * @param dir
     */
    public static function downloadFile($url, $dir){

        $content = file_get_contents($url); // 得到二进制图片内容

        $filePath = 'qrcode/'.uniqid('file_', true).self::getFileExtend($url);

        file_put_contents($dir .'/'. $filePath, $content); // 写入文件

    }


    public static function getFileExtend($url)
    {
        $urlArr = $url.explode('.');
        $count = count($urlArr);
        if($count > 1) {
            return $urlArr[($count - 1)];
        } else {
            return 'jpg';
        }
    }

    /**
     * 记录日志
     * @param array $data
     * @param string $Separator
     * @return bool|int
     */
    public static function  logRecord($data=array(), $Separator="," ){
        if(!is_array($data) ) return false;

        $filename = 'log-'.date('Y-m-d').'.txt';
        $content = implode( $Separator,  $data );
        $result = file_put_contents( '/opt/www/laiyujia/log/'.$filename,(date('Y-m-d h:i:s',time())).' '.$content."\r\n",FILE_APPEND | LOCK_EX );
        return $result;
    }

    /**
     * 获取静态模板
     */
    public static function getPageTemplate($request, $pageName){
        if(empty($pageName)) return false;
        $documentRoot = $request->server->get('DOCUMENT_ROOT');

        $result = file_get_contents( $documentRoot.'/page/'.$pageName.'.html');
        return $result;
    }

}