<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Session;

class BaseModel
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    /**
     * 获取数据
     * @param $data
     *
     * @return int
     */
    public static function getData($table, $fields=null, $condition=null, $limit=null, $orderBy='Id desc')
    {
        $obj = DB::table($table);
        if($fields != null) {
            $obj->select($fields);
        }

        if($condition != null){
            $obj->where($condition);
        }

        if(is_numeric($limit)) {
            $obj->limit($limit);
        }
        $obj->orderByRaw($orderBy);

        return $obj->get()->toArray();

    }


    /**
     * 保存数据
     * @param $data
     *
     * @return int
     */
    public static function saveData($table, $data)
    {
        $userIn = Session::get('userData');

        if ($userIn && isset($userIn['Id'])) {
            $data['CreatedById'] = $userIn['Id'];
            $data['LastModifiedById'] = $userIn['Id'];
        }

        return DB::table($table)->insertGetId($data);

    }

	/**
     * 更新数据
     * @param $data 更新的数据
     * @param $condition 更新的条件
     *
     * @return int
     */
    public static function updateData($table, $data, $condition) {
        if(!empty($condition)) {
            $userIn = Session::get('userData');

            if($userIn && isset($userIn['Id'])) {
                $data['LastModifiedById'] = $userIn['Id'];
            }

            $data['LastModifiedDate'] = date('Y-m-d H:i:s');

            return DB::table($table)->where($condition)->update($data);
        } else {
            return 0;
        }
    }


}
