<template>
  <div class="share-campaign">
    <div class="share-form" >
      <div class="share-content" id="shareContent">
        <div class="share-img-form">
          <div class="share-user-form">
            <div class="share-user-in" >
              <img class="share-user-avatar" :src="userData.Avatar" />
              <span class="user-desc">{{userData.Nickname}}</span>
              <span class="user-desc">向/您/推/荐</span>
            </div>
          </div>
          <img :src="campaignPic" class="share-campaign-pic" v-if="campaignPic != ''" />
        </div>
        <p class="campaign-name">{{campaignIn.Name}}</p>

        <div>
          <img :src="qrCode" class="campaign-code-pic"/>
          <div class="campaign-notice"><span>长按二维码，进入活动详细页面参与活动</span></div>
        </div>
      </div>

      <button class="weui-btn weui-btn_primary common-btn share-save-btn" v-on:click="saveSharedContent">保存</button>
    </div>

    <ul class="share-img-list">
      <li v-for="(picIn, index) in sharePicList" @click="setSharePic(picIn)">
        <img :src="appConfig.resourceDomain+picIn['FilePath']" class="campaign-img" />
      </li>
    </ul>
  </div>
</template>

<script>
import appConfig from '../../config/app.config'
import {XButton  } from 'vux'
import CampaignModelService from '../../service/model/CampaignModel.service';


export default {
	components: {
		XButton
	},
	data() {
		return {
			campaignPic:'',
			campaignIn:{},
      qrCode:'',
			sharedContent:'分享此活动 ',
			status:0,
			userData:{},
			sharePicList:[],
      appConfig:appConfig
		}
	},
	methods: {
    getShareContent() {
      CampaignModelService.getShareCampaignDefaultIn({campaignId:this.$route.params.campaignId}, (data)=>{
        if(data.code == 200 && data.result) {
          this.qrCode = data.result.qrCode;
					this.sharePicList = data.result.picList;
          if(this.sharePicList) {
          	this.campaignPic = this.appConfig.resourceDomain+this.sharePicList[0]['FilePath'];
          }
          this.campaignIn = data.result.campaignIn;
        }
      });
    },
    setShareContent:function (addContet) {
      document.getElementById('qrcode').remove();
      this.sharedContent = document.getElementById('shareContent').innerHTML;
      this.sharedContent += addContet;
      this.sharedContent += this.qrCode;
      document.getElementById('shareContent').innerHTML = this.sharedContent;
    },
		saveSharedContent:function () {
    	let shareContent = document.getElementById('shareContent').innerHTML;
    	if(shareContent != '') {
				this.$vux.loading.show({
					text: '保存中..'
				});
        CampaignModelService.saveShareCampaignIn({
          CampaignId:this.$route.params.campaignId,
          ShareMethod:'微信分享',
          ShareContent:document.getElementById('shareContent').innerHTML
        }, (data) =>{
          if(data.code == 200 && data.result) {
						this.$vux.loading.hide();
            this.$vux.toast.show({
              text: '生成分享成功！'
            });
            this.$router.push('/campaign/shareDetail/'+data.result.shareId);
          }
        });
      } else {
				this.$vux.toast.show({
					text: '分享内容不能为空！',
					type:'warn'
				});
      }
		},
    setSharePic:function (picIn) {
			this.campaignPic = appConfig.resourceDomain+picIn['FilePath'];
    }
	},
	created: function () {
    if (this.$route.params.campaignId) {
      let userData = this.$cookie.get('userData');
      this.userData = userData ? JSON.parse(userData) : {};
      this.getShareContent();
    }
	},
  mounted:function () {

  }
}

</script>

<style>
  .share-campaign-pic{
    width: 100%;
  }
  .share-user-form{
    position: absolute;
    width: 100%; height: 100px;
  }
  .share-user-in{
    margin: 165px auto;
    width:200px;
    height:200px;
  }
  .share-user-avatar{
    width:120px;
    height:120px;
    border-radius: 50%;
    margin-bottom: 10px;
  }
 .share-form { position:relative;text-align: center; font-size: 20px; padding-bottom: 200px;  margin: 0px auto;}
 .share-content{
   width: 100%;
 }
 .share-img-form{
   position: relative;
   max-height: 500px;
   overflow: hidden;
 }
 .campaign-name{
   color: #5f5f5f;
   font-size: 30px;
   margin-top: 40px;
   text-align: center;
   display: block;
 }
 .user-desc{
   display: block;
   color: #ffffff;
   font-size: 20px;
   font-weight: 200;
 }
 .share-campaign .share-img-list {
   width: 100%;
   height:100px;
   position: fixed;
   bottom:0px;
   display: flex;
   padding: 15px;
   background-color: #0D0D0D;
   z-index:1000;
   overflow-x: auto;
 }
 .share-campaign  .share-img-list li{
   list-style: none;
   margin-right: 15px;
 }

 .campaign-code-pic{
   margin-top: 30px;
   max-width: 150px;
 }

 .share-campaign .share-img-list li img{
    height:100px;
 }
  .share-save-btn{
    position: absolute;
    bottom: 140px;
  }
  .campaign-notice {
    width: 92%;
    margin: 30px auto;
    border-top: 1px solid #999;
  }
  .campaign-notice span{
    color: #999999;
    font-size: 16px;
    padding: 0px 30px;
    position: relative;
    top:-19px;
    display: inline-block;

    background-color: #fbf9fe;
  }
</style>
