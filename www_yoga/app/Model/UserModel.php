<?php

namespace App\Model;

use App\Model\BaseModel;
use Illuminate\Support\Facades\DB;

class UserModel extends BaseModel
{
    const USER_TYPE_TEACHER = 2;
    const USER_TYPE_STUDENT = 1;

	static protected $table = 'User';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array('Username', 'Mobile');
    protected $primaryKey = 'Id';

    public static function checkUserExist($condition)
	{
		$userIn = parent::getData(self::$table, array('id'), $condition);
		if(!empty($userIn)) {
			return true;
		} else {
			return false;
		}
	}

    /**
     * 添加用户
     * @param $data
     *
     * @return int
     */
	public static function addUser($data) {
		if($data['UserType'] = self::USER_TYPE_TEACHER
			|| $data['UserType'] = self::USER_TYPE_STUDENT) {
			return  parent::saveData(self::$table, $data);
		} else {
			return false;
		}
    }

	/**
	 * 更新用户
	 * @param $data
	 *
	 * @return int
	 */
	public static function updateUser($data, $condition) {
		return  parent::updateData(self::$table, $data, $condition);
	}

	/**
	 * 注册用户
	 * @param $data
	 *
	 * @return int
	 */
	public static function regUser($data, $weChatOpenID) {
		return parent::updateData(self::$table, $data, array(
			'WeChatOpenID' => $weChatOpenID
		));
	}


    /**
     * 添加用户
     * @param $data
     *
     * @return int
     */
    public static function updateTeacher($data, $condition) {
        return  parent::updateData(self::$table, $data, $condition);
    }


    /**
     * 获取用户基本信息
     */
    public static function getUserBaseIn($condition) {
        $userIn = parent::getData(
            self::$table,
            array('Id','Nickname','Username','Mobile','Avatar',
                'ContactID','UserType','IsAxamine','IsActive', 'WeChatOpenID'
            ),
            $condition, 1
        );
        if(isset($userIn[0])){
            return $userIn[0];
        } else {
            return null;
        }
    }

	/**
	 * 获取用户列表
	 * @param $userId
	 * @return mixed
	 */
	public static function getUserList($userId) {
		return DB::table(self::$table)
			->select('Id', 'Name','Nickname','Avatar')
			->whereIn('Id', $userId)
			->get()->toArray();

	}

}
