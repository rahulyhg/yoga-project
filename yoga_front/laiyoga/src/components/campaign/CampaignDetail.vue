<template>
  <div style="padding-bottom: 20px;">
    <div class="campaign-list">
      <swiper :height="'300px'" v-model="swiperItemIndex" :dots-position="'center'">
        <swiper-item class="swiper-demo-img" v-for="(picture, index) in campaignIn.pictureList" :key="index">
          <img :src="appConfig.resourceDomain+picture.FilePath" />
        </swiper-item>
      </swiper>
    </div>

    <group class="form-detail">
      <cell title="活动状态：" :value="campaignIn.StatusName" :value-align="'left'"></cell>
      <cell title="活动名称：" :value="campaignIn.Name" :value-align="'left'"></cell>
      <cell title="主办方：" :value="campaignIn.HostName" :value-align="'left'"></cell>
      <cell title="活动地址：" :value="campaignIn.Address" :value-align="'left'"></cell>
      <cell title="开始时间：" :value="campaignIn.StartTime" :value-align="'left'"></cell>
      <cell title="结束时间：" :value="campaignIn.EndTime" :value-align="'left'"></cell>
      <cell title="活动价格：" :value="campaignIn.price" :value-align="'left'"></cell>
      <cell title="活动介绍：" :value="campaignIn.Description" :value-align="'left'"></cell>
      <cell title="已报名：" :value="campaignIn.SignedUpPerson+'/'+campaignIn.MaxPerson" :value-align="'left'"></cell>
    </group>

    <ul class="member-list">
      <li v-for="(member, index) in campaignIn.memberList" >
        <img :src="member.Avatar" />
      </li>
    </ul>
    <button class="weui-btn weui-btn_primary common-btn" v-on:click="shareCampaign">分享活动</button>
    <button class="weui-btn weui-btn_primary common-btn" v-if="isCanParticipate" v-on:click="participateCampaign">参与活动</button>
    <button class="weui-btn weui-btn_primary common-btn" v-if="isCanAddFeedback" @click="createFeedback">活动反馈</button>

    <div class="handle-wrapper" v-if="this.userData.UserType == 1 && (isCanPublish || isCanEdit || isCanShowDiscountCode)">
      <div class="toolbar">
        <div :class="'handle-button'+(showMenu?' active': '')" @click="toggleMenu"></div>
        <ul :class="'icons'+(showMenu?' open': '')" >
          <li>
            <i class="fa fa-instagram fa-2x" aria-hidden="true" v-if="isCanPublish" @click="publishCampaignIn"></i>
          </li>
          <li>
            <i class="fa fa-edit fa-2x" aria-hidden="true"  v-if="isCanEdit" @click="editCampaignIn"></i>
          </li>
          <li>
            <i class="fa fa-qrcode fa-2x" aria-hidden="true"  v-if="isCanShowDiscountCode" @click="toDiscountCodeIndex"></i>
          </li>
          <li>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import appConfig from '../../config/app.config'
import {Swiper, SwiperItem, Group, Cell} from 'vux'
import CampaignModelService from '../../service/model/CampaignModel.service';

export default {
	components: {
		Swiper,
		SwiperItem,
		Group,
    Cell
	},
	data() {
		return {
			showTabList:[],
			showMenu:false,
			userData:{},
			isCanEdit:false,
			isCanPublish:false,
			isCanShowDiscountCode:false,
      isCanParticipate:false,
			isCanAddFeedback:false,
			appConfig:appConfig,
			campaignIn:{
				pictureList:[]
      },
			swiperItemIndex:1,
			imgList:[{src:''},{src:''},{src:''}]
		}
	},
	methods: {
		toggleMenu:function () {
			this.showMenu = !this.showMenu;
		},
		editCampaignIn:function () {

		},
		getCampaignIn:function (campaignId) {
			CampaignModelService.getCampaignIn({CampaignId:campaignId}, (data) => {
				if(data.code == 200) {
					this.campaignIn = data.result;
					if(this.campaignIn.IsFreeOfCharge == 1) {
						this.campaignIn.price = '免费';
          } else {
						this.campaignIn.price = '￥'+(this.campaignIn.priceList && this.campaignIn.priceList[0]
							? this.campaignIn.priceList[0]['Price'] : 0);
          }

					if(this.campaignIn.CreatedById == this.userData.Id) {
            this.showHandleButton();
          }

          if(this.campaignIn.Status == 0 && this.campaignIn.CreatedById == this.userData.Id) {
            this.isCanEdit = true;
          }

          if(this.campaignIn.MaxPerson > this.campaignIn.SignedUpPerson) {
						if(this.campaignIn.CreatedById != this.userData.Id && this.campaignIn.Status == 1) {
							if(!this.isParticipate()) {
								this.isCanParticipate = true;
							}
						}
          }
					if(this.campaignIn.Status == 3) {
						this.isCanAddFeedback = true;
          }
        } else {
					this.$vux.toast.show({
						text: data.message,
						type:'warn'
					});
        }
      });
		},
    isParticipate:function(){
			let participateStatus = false;
      if(this.campaignIn.memberList) {
        for(let key in this.campaignIn.memberList) {
        	if(this.campaignIn.memberList[key]['Id'] == this.userData.Id) {
						participateStatus = true;
						break;
          }
        }
      }

      return participateStatus;
    },
    showHandleButton:function () {
			if(this.campaignIn.Status > 0) {
				this.isCanShowDiscountCode = true;
			} else {
				this.isCanShowDiscountCode = false;
			}

			if(this.campaignIn.Status < 2) {
				this.isCanEdit = true;
			} else {
				this.isCanEdit = false;
			}

      if (this.campaignIn.Status == 0) {
        this.isCanPublish = true;
      } else {
        this.isCanPublish = false;
      }
    },
    participateCampaign: function() {
      this.$router.push(
        "/campaign/participate/" + this.$route.params.campaignId + '/' + this.$route.params.shareFromUserId
      );
    },
    toDiscountCodeIndex: function() {
      this.$router.push("/discountCode/index/" + this.$route.params.campaignId);
    },
    editCampaignIn: function() {
      this.$router.push(
        "/campaign/doCampaign/" + this.$route.params.campaignId
      );
    },
    shareCampaign: function() {
      this.$router.push("/campaign/share/" + this.$route.params.campaignId);
    },
		createFeedback:function () {
			this.$router.push('/campaign/feedback/'+this.$route.params.campaignId);
		},
		publishCampaignIn:function () {
			if(confirm('确定要发布活动！')) {
				CampaignModelService.publishCampaign(
					{
						CampaignId: this.$route.params.campaignId,
						Status: 1
					}, (data) => {
            if(data.code == 200) {
            	this.campaignIn.Status = 1;
							this.showHandleButton();
							this.$vux.toast.show({
								text: '发布活动成功！'
							});
            } else {
							this.$vux.toast.show({
								text: '发布活动失败！',
								type: 'warn'
							});
            }
					});
			}
		},
  },
  
  created: function() {
    if (this.$route.params.campaignId) {
      this.getCampaignIn(this.$route.params.campaignId);

      let userData = this.$cookie.get("userData");
      this.userData = userData ? JSON.parse(userData) : {};
    }
    if(this.$route.params.shareFromUserId && this.$route.params.campaignId){
        CampaignModelService.receiveShareCampaignIn(
          {
            campaignId:this.$route.params.campaignId,
            shareFromUserId:this.$route.params.shareFromUserId
          }, 
          data => {
            if(data.code == 200){
            }
      });
    }
    
    var campaignId = this.$route.params.campaignId;
    if (window.wx) {
      //api get options
      CampaignModelService.getShareConf({url:window.location.href}, data => {
        if(data.code == 200){
          wx.config(data.result);

          var link = '';
          if(this.userData){
              link = appConfig.resourceDomain+'campaign/detail/'+campaignId+'/'+this.userData.Id;
          }else{
              link = appConfig.resourceDomain+'campaign/detail/'+campaignId;
          }
          wx.ready(() => {
            wx.onMenuShareTimeline({
              title: this.campaignIn.name, // 分享标题
              link: link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
              imgUrl: appConfig.resourceDomain+this.campaignIn.pictureList[0].FilePath, // 分享图标
              success: function() {
                // 用户确认分享后执行的回调函数
                CampaignModelService.saveShareCampaignIn({
                  CampaignId:campaignId,
                  shareMethod:'微信',
                  shareUrl:link
                },
                function(data){
                    
                });
              },
              cancel: function() {}
            });
            
            wx.onMenuShareAppMessage({
              title: this.campaignIn.name, // 分享标题
              desc: this.campaignIn.Description, // 分享描述
              link: link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致//+'/shareFromId/'+this.userData.Id
              imgUrl: appConfig.resourceDomain+this.campaignIn.pictureList[0].FilePath, // 分享图标
              type: "", // 分享类型,music、video或link，不填默认为link
              dataUrl: "", // 如果type是music或video，则要提供数据链接，默认为空
              success: function() {
                // 用户确认分享后执行的回调函数
                CampaignModelService.saveShareCampaignIn({
                  campaignId:this.$route.params.campaignId,
                  shareMethod:'微信',
                  shareUrl:link
                },
                function(data){
                   
                });
              },
              cancel: function() {
                // 用户取消分享后执行的回调函数
              }
            });
          });

        }
      });


      
    }
  }
};
</script>

<style>
  .campaign-list{
    width:100%;
    background-color: #ffffff;
    padding: 10px 0px;
  }
  .swiper-demo-img{
    position: relative;
    width: 100%;
    text-align: center;
  }
  .campaign-list img{
    max-height: 400px;
    display: inline-block;
  }
  .member-list{
    background-color: #FFFFFF;
    padding: 10px 20px 10px 20px;
    display: flex;
  }
  .member-list li{
    list-style: none;
    margin-left: 20px;
  }
  .member-list li img{
    width: 50px;
    height: 50px;
    border-radius: 50%;
  }
  .form-detail{
    margin-top: -17px;
  }

</style>
