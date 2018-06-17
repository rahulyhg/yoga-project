import BaseModelService from './base/BaseModel.service'

export default class UserModelService extends BaseModelService {

	constructor() {
		super();
	}

	/**
	 * 发送验证码
	 * @param data
	 * @param callback
	 */
	static sendCode(data, callback) {
		this.getData('sendCode', data, callback);
	}

	/**
	 * 注册
	 * @param data
	 * @param callback
	 */
	static register(data, callback) {
		this.getData('register', data, callback);
	}


	/**
	 * 注册
	 * @param data
	 * @param callback
	 */
	static register(data, callback) {
		this.getData('register', data, callback);
	}

	/**
	 * 老师当前的状态
	 */
	static getTeacherStatus(callback) {
		this.getData('teacherHome', null, callback);
	}


	/**
	 * 退出登录
	 */
	static logout(callback) {
		this.getData('logout', null, callback);
	}


}
