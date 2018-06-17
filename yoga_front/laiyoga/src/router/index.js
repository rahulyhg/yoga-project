import Vue from 'vue'
import VueRouter from 'vue-router'

import appConfig from '../config/app.config'

import InReview from '../components/teacher/InReview'
import Register from '../components/user/Register'
import Home from '../components/user/Home'
import TeacherCampaign from '../components/teacher/Campaign'
import CampaignFind from '../components/campaign/find'
import CampaignParticipate from '../components/campaign/CampaignParticipate'
import DoFeedback from '../components/campaign/DoFeedback'
import DoCampaign from '../components/teacher/DoCampaign'
import CampaignDetail from '../components/campaign/CampaignDetail'
import DiscountCode from '../components/teacher/DiscountCode'
import DoDiscountCode from '../components/teacher/DoDiscountCode'
import ShareCampaign from '../components/share/shareCampaign'
import ShareCampaignDetail from '../components/share/shareCampaignDetail'

Vue.use(VueRouter);


const routes = [
	{
		path: '/user/home',
		component: Home,
		meta: {title: '首页'}
	},
	{
		path: '/',
		redirect: '/campaign/find'
	},
	{
		path: '/user/register',
		component: Register,
		meta: {title: '用户注册'}
	},
	{
		path: '/user/register/:userType',
		component: Register,
		meta: {title: '用户注册'}
	},
	{
		path: '/teacher/inReview',
		component: InReview,
		meta: {title: '审核状态'}
	},
	{
		path: '/teacher/campaign',
		component: TeacherCampaign,
		meta: {title: '我的活动'}
	},
	{
		path: '/campaign/find',
		component: CampaignFind,
		meta: {title: '发现活动'}
	},
  {
    path: '/campaign/find/:status',
    component: CampaignFind,
		meta: {title: '发现活动'}
  },
	{
		path: '/teacher/doCampaign',
		component: DoCampaign,
		meta: {title: '创建活动'}
	},
	{
		path: '/campaign/doCampaign/:campaignId',
		component: DoCampaign,
		meta: {title: '编辑活动'}
	},
	{
		path: '/campaign/detail/:campaignId/:shareFromUserId?',
		component: CampaignDetail,
		meta: {title: '活动详情'}
	},
	{
		path: '/campaign/participate/:campaignId/:shareFromUserId?',
		component: CampaignParticipate,
		meta: {title: '参与活动'}
	},
	{
		path: '/campaign/feedback/:campaignId',
		component: DoFeedback,
		meta: {title: '活动反馈'}
	},
	{
		path: '/campaign/share/:campaignId',
		component: ShareCampaign,
		meta: {title: '分享活动'}
	},
  {
    path: '/campaign/shareDetail/:id',
    component: ShareCampaignDetail,
		meta: {title: '分享活动详情'}
  },
	{
		path: '/discountCode/do/:campaignId',
		component: DoDiscountCode,
		meta: {title: '创建优惠码'}
	},
	{
		path: '/discountCode/index',
		component: DiscountCode,
		meta: {title: '我的优惠码'}
	},
	{
		path: '/discountCode/index/:campaignId',
		component: DiscountCode,
		meta: {title: '我的优惠码'}
	}
];


const router = new VueRouter({
  routes
});

router.beforeEach((to, from, next) => {
	document.title = to.meta.title;
	if(!appConfig.isProd) {
		next();
	} else {

		if (to.fullPath.indexOf('campaign/detail') !== -1
			|| to.fullPath.indexOf('campaign/shareDetail') !== -1) {
			next();
		} else {
			let userData = Vue.cookie.get('userData');
			if (userData) {
				next();
			} else {
				if(!appConfig.debug) {
				   window.location.href="/api/login";
				} else {
					next();
				}
			}
		}
	}
});
export default router;