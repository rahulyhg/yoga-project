<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Common\HttpResponse;

use App\Model\FileModel;
use App\Util\ImageUtil;

use Illuminate\Support\Facades\Session;

class FileController extends Controller
{
    const USER_IS_TEACHER = 1;

	public function __construct()
	{

	}

	/**
	 * 上传文件
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function upload(Request $request)
	{
		$file = $request->files->get('file');
		$data = $request->all();
		if (!empty($file)) {
			$documentRoot = $request->server->get('DOCUMENT_ROOT');
			$uploadResult = ImageUtil::uploadFile($file, $documentRoot);

			if($uploadResult['code'] == 1) {
				unset($data['file']);
				$data['FilePath'] = $uploadResult['path'];
				$data['FileId'] = FileModel::saveFile($data);

				return HttpResponse::success($data);
			} else {
				return HttpResponse::error('上传失败', $uploadResult);
			}

		}
		return HttpResponse::error('请上传文件！');
	}



}
