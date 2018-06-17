<?php
/**
 * Created by PhpStorm.
 * User: Rainy
 */

namespace App\Util;

use Eventviva\ImageResize;

class ImageUtil
{
    /**
     * @param integer $imgWidth
     * @param integer $imgHeight
     * @param string $fileFull
     * @throws \Exception
     */
    public static function scaleImage($imgWidth,$imgHeight, $fileFull){
        $imgArray = getimagesize($fileFull);

        if(empty($imgArray))
            throw new \Exception('Image Save Failed');

        if($imgArray[0] < $imgWidth && $imgArray[1] < $imgHeight) {
            $imgWidth = $imgArray[0];
            $imgHeight = $imgArray[1];
        }else{
            $proportion = min($imgWidth/$imgArray[0],$imgHeight/$imgArray[1]);
            $imgWidth = $imgArray[0] * $proportion;
            $imgHeight = $imgArray[1] * $proportion;
        }

        $image = new ImageResize($fileFull);
        $image->resizeToBestFit($imgWidth, $imgHeight);
        $image->save($fileFull);
    }

	/**
	 *
	 * @throws \Exception
	 */
	public static function uploadFile($file, $documentRoot)
	{
		$uploadResult = array();

		$fileSize = $file->getClientSize();

		if ($fileSize > 2097152) {
			$uploadResult['code'] = -1;
		}

		//$fileType = strtolower($file->getClientMimeType());

		//$imageMimeType = array('image/png', 'image/jpeg', 'image/bmp', 'image/gif');
		//$fileType = in_array($fileType, $imageMimeType) ? self::MESSAGE_IMAGE : self::MESSAGE_FILE;

		$tmp_name = uniqid('file_', true) . '.' . $file->getClientOriginalExtension();
		$filePath = $documentRoot . '/' . 'upload' . '/';

		if (!file_exists($filePath)) {
			mkdir($filePath, 0777, true);
		}

		if($file->move($filePath, $tmp_name)){

			self::scaleImageByImagick(750, 500, $filePath.$tmp_name);

			$uploadResult['code'] = 1;
			$uploadResult['path'] = 'upload'.'/'.$tmp_name;
		} else {
			$uploadResult['code'] = 0;
		}
		return $uploadResult;
	}


	public  static function scaleImageByImagick($width, $height, $source_img, $target_img=''){

		if(class_exists('\Imagick')) {
			$im = new \Imagick(realpath($source_img));
			$profiles = $im->getImageProfiles("icc", true);
			$im->stripImage();
			if(!empty($profiles)) {
				$im->profileImage("icc", $profiles['icc']);
			}

			$srcWH = $im->getImageGeometry(); //获取源图片宽和高

			//图片等比例缩放宽和高设置 ，根据宽度设置等比缩放
			if($srcWH['width'] > $width){
				$srcW['width'] = $width;
				$srcH['height'] = $srcW['width']/$srcWH['width']*$srcWH['height'];
			}else{
				$srcW['width'] = $srcWH['width'];
				$srcH['height'] = $srcWH['height'];
			}

			//按照比例进行缩放
			$im->adaptiveResizeImage ( $srcW['width'], $srcH['height'], true );

			//生成图片
			$im->setImageFileName($source_img);

			$im->writeImage();

			$im->destroy();

		} else {
			return false;
		}
	}

}


