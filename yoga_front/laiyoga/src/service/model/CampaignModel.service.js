import BaseModelService from './base/BaseModel.service'

export default class CampaignModelService extends BaseModelService {

	constructor() {
		super();
	}

	/**
	 * 获取活动列表
	 * @param data
	 * @param callback
	 */
	static getCampaign(data, callback) {
		this.getData('campaignIndex', data, callback);
	}

	/**
	 * 创建活动
	 * @param data
	 * @param callback
	 */
	static doCampaign(data, callback) {
		this.getData('doCampaign', data, callback);
	}

	/**
	 * 获取活动详情
	 * @param data
	 * @param callback
	 */
	static getCampaignIn(data, callback) {
		this.getData('campaignDetail', data, callback);
	}

	/**
	 * 接收活动分享
	 * @param data
	 * @param callback
	 */
	static receiveShareCampaignIn(data, callback) {
		this.getData('campaignReceiveShare', data, callback);
	}

	/**
	 * 参与活动详情
	 * @param data
	 * @param callback
	 */
	static getParticipateCampaignIn(data, callback) {
		this.getData('participateCampaignIn', data, callback);
	}

	/**
	 * 获取分享配置
	 * @param data
	 * @param callback
	 */
	static getShareConf(data, callback) {
		this.getData('getShareConf', data, callback);
	}


	/**
	 * 活动基本信息
	 * @param data
	 * @param callback
	 */
	static getCampaignBaseIn(data, callback) {
		this.getData('campaignBaseIn', data, callback);
	}


	/**
	 * 获取活动列表
	 * @param data
	 * @param callback
	 */
	static getMyCampaignList(data, callback) {
		this.getData('myCampaignList', data, callback);
	}


	/**
	 * 发现活动列表
	 * @param data
	 * @param callback
	 */
	static findCampaign(data, callback) {
		this.getData('findCampaign', data, callback);
	}

	/**
	 * 参与活动
	 * @param data
	 * @param callback
	 */
	static participateCampaign(data, callback) {
		this.getData('participateCampaign', data, callback);
	}

	/**
	 * 发布活动
	 */
	static publishCampaign(data, callback) {
		this.getData('publishCampaign', data, callback);
	}


  /**
   * 获取活动发布主要信息
   */
  static getShareCampaignDefaultIn(data, callback) {
    this.getData('shareCampaignDefaultIn', data, callback);
  }

  /**
   * 保存活动
   */
  static saveShareCampaignIn(data, callback) {
    this.getData('doCampaignShare', data, callback);
  }

  /**
   * 活动详情
   */
  static getCampaignShareIn(data, callback) {
    this.getData('campaignShareIn', data, callback);
  }

	/**
	 * 反馈活动
	 */

	static campaignFeedback(data, callback) {
		this.getData('campaignFeedback', data, callback);
	}

}
