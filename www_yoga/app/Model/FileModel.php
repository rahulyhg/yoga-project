<?php

namespace App\Model;

use App\Model\BaseModel;

use Illuminate\Support\Facades\DB;

class FileModel extends BaseModel
{
	//
    static protected $table = 'File';


    public static function getFileList($pictureId) {

		return DB::table(self::$table)
			->select('Id', 'FilePath','FileType')
			->whereIn('Id', explode(',',$pictureId))
			->get()->toArray();

	}

	/**
	 * 新增文件
	 * @param $data
	 * @return int
	 */
	public static function saveFile($data){
		return parent::saveData(self::$table, $data);
	}
}
