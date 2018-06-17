import appConfig from './app.config'

export default {
	IS_REQUEST_BY_DOMAIN: appConfig.requestByDomain,		//是否带域名请求API
	APP_DOMAIN:appConfig.domain,
	TARGET_DOMAIN: appConfig.apiDomain,
	PROXY_DATA_PREFIX: 'api',
	SUB_DIR: appConfig.subDir,

	PROXY_DATA: {
		sendCode: 'user/sendCode',
		register: 'user/register',	  //用户注册
		logout: 'user/logout',
		teacherHome:'teacher/home',
		campaignIndex: 'campaign/index',
		doCampaign: 'campaign/doCampaign',
		publishCampaign: 'campaign/publish',
		campaignDetail: 'campaign/detail',
		participateCampaignIn : 'campaign/participateCampaignIn',
		getShareConf: 'campaign/getShareConf',
		campaignReceiveShare: 'campaign/receiveShare',
		campaignBaseIn:'campaign/baseIn',
		campaignFeedback:'campaign/feedback',
		myCampaignList: 'campaign/getMyCampaignList',
		findCampaign:'campaign/find',
		participateCampaign:'campaign/participate',
    shareCampaignDefaultIn:'campaign/shareDefaultIn',
    doCampaignShare:'campaign/doShare',
    campaignShareIn:'campaign/shareIn',

		discountCodeIndex:'discountCode/index',
		createDiscountCode:'discountCode/create',
		useDiscountCode:'discountCode/useCode',

		fileUpload:'file/upload'
	}
}

