import BaseModelService from './base/BaseModel.service'

export default class DiscountCodeModelService extends BaseModelService {

	constructor() {
		super();
	}

	/**
	 * 获取优惠码列表
	 * @param data
	 * @param callback
	 */
	static getDiscountCodeList(data, callback) {
		this.getData('discountCodeIndex', data, callback);
	}

	/**
	 * 创建优惠码
	 * @param data
	 * @param callback
	 */
	static createDiscountCode(data, callback) {
		this.getData('createDiscountCode', data, callback);
	}

	/**
	 * 使用优惠码
	 * @param data
	 * @param callback
	 */
	static useDiscountCode(data, callback) {
		this.getData('useDiscountCode', data, callback);
	}

}
