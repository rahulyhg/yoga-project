import Vue from 'vue'

import appRequest from '../../config/request.config'
import Bus from '../../store/bus.js'

export default class ApiService {
	constructor() {
	}

	static getRequestUrl(apiName, urlParams) {
		let requestUrl = '';
		if (appRequest.IS_REQUEST_BY_DOMAIN === 1) {
			requestUrl = appRequest.TARGET_DOMAIN;
		} else {
			requestUrl = '/';
		}
		if (appRequest.PROXY_DATA[apiName]) {
			requestUrl += appRequest.PROXY_DATA_PREFIX
				+ '/' + appRequest.PROXY_DATA[apiName];

			if (typeof urlParams !== 'undefined') {
				for (let key in urlParams) {
					requestUrl += '/' + key + '/' + urlParams[key];
				}
			}
		} else {
			console.error('API NAME IS ERROR!');
		}
		return requestUrl;
	}

	/**
	 * 设置请求参数
	 * @param data
	 */
	static getRequestData(data) {
		let requestData = {};
		if (data !== null && data) {
			for (let key in data) {
				if (key === 'urlParams') continue;
				let value = '';
				if(typeof data[key] !== 'undefined') {
					if (typeof data[key] === 'object') {
						value = JSON.stringify(data[key]);
					} else {
						value = data[key];
					}
					requestData[key] = value;
				}
			}
		}
		return requestData;
	}

	/**
	 * 发送GET请求
	 * @param apiName
	 * @param data
	 * @param responseDataType
	 * @returns {any}
	 */
	static getRequest(apiName, data, callback, responseDataType) {
		let urlParams = data ? data.urlParams : {};
		let requestUrl = this.getRequestUrl(apiName, urlParams);
		responseDataType = responseDataType ? responseDataType : 'json';

		//请求发送的数据
		let requestData = this.getRequestData(data);
		Vue.http.get(requestUrl, {params: requestData}, {
			responseType: responseDataType,
			emulateJSON: true
		}).then(data => {
				this.dealDataStatus(data.body, callback);
			},
			errorData => {
				this.dealErrorStatus(errorData);
			}
		);
	}

	/**
	 * 发送POST请求
	 * @param apiName
	 * @param data
	 * @param callback
	 * @param responseDataType
	 * @returns {*}
	 */
	static postRequest(apiName, data, callback, responseDataType) {
		let urlParams = data ? data.urlParams : {};
		let requestUrl = this.getRequestUrl(apiName, urlParams);

		responseDataType = responseDataType ? responseDataType : 'json';
		//请求发送的数据
		let requestData = this.getRequestData(data);

		Vue.http.post(requestUrl, requestData, {
			responseType: responseDataType,
			emulateJSON: true
		}).then(data => {
				this.dealDataStatus(data.body, callback);
			},
			errorData => {
				this.dealErrorStatus(errorData);
			}
		);
	}

	/**
	 * 获取请求url
	 */
	static getRequestParamUrl(apiName, data) {
		let urlParams = data ? data.urlParams : {};
		let requestUrl = this.getRequestUrl(apiName, urlParams);

		let n = 0;
		for (let key in data) {
			requestUrl += n === 0 ? '?' : '&';
			requestUrl += key + '=' + data[key];
			n++;
		}
		return requestUrl;
	}

	/**
	 * 处理公用状态
	 */
	static dealDataStatus(data, callback) {
		if (data.code == -1) {
			window.location.href='/api/login';
		} else if(data.code == -3) {
			let locationUrl = window.location.href.replace(appRequest.APP_DOMAIN+'#', '');
			window.location.href='/#/user/register?locationUrl='+encodeURIComponent(locationUrl);
		}
		else {
			if (typeof callback === 'function') {
				callback(data)
			}
		}
	}

	/**
	 * 处理请求错误
	 */
	static dealErrorStatus() {

	}

}
