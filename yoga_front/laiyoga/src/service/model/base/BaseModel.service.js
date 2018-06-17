import ApiService from '../../request/Api.service'

export default class BaseModelService {

	constructor() {
	}

	/**
	 * post 请求
	 * @param apiName API名称
	 * @param data  参数数据
	 * @param callback 回调方法
	 */
	static postRequest(apiName, data, callback) {
		return ApiService.postRequest(apiName, data, callback);
	}

	/**
	 * get 请求
	 * @param apiName API名称
	 * @param data    参数数据
	 * @param callback  回调方法
	 */
	static getRequest(apiName, data, callback) {

		return ApiService.getRequest(apiName, data, callback);
	}

	/**
	 * 获取远程数据
	 * @param apiName
	 * @param data
	 * @param callback
	 * @param method 提交方式
	 */
	static getData(apiName, data, callback, method) {
		method = (method ? method : 'post').toLocaleLowerCase();
		if (method === 'post') {
			return this.postRequest(apiName, data, callback);
		} else if (method === 'get') {
			return this.getRequest(apiName, data, callback);
		}
	}

	/**
	 * 获取远程数据
	 * @param apiName
	 * @param data
	 * @param callback
	 * @param method 提交方式
	 */
	static getUrl(apiName, data) {

		return ApiService.getRequestParamUrl(apiName, data);

	}


	/**
	 * 获取本地缓存数据
	 * @param apiName API名称
	 */
	static getCacheData(apiName) {
		return;
	}


}
