<template>
  <div>
    <div class="share-form" v-if="showType == 1" >
      <div class="share-content" id="shareContent" v-html="sharedContent" >
      </div>
    </div>
    <div class="share-form" v-if="showType == 2" >
      <img :src="appConfig.resourceDomain+'app/campaign-share/'+shareId+'.png'" style="width: 100%;"/>
    </div>

  </div>
</template>

<script>
import {XButton  } from 'vux'
import CampaignModelService from '../../service/model/CampaignModel.service';
import appConfig from '../../config/app.config'

export default {
	components: {
		XButton
	},
	data() {
		return {
			shareId:'',
			showType:2,
			sharedContent:'',
			userData:{},
      appConfig:appConfig
		}
	},
	methods: {
    getShareContent() {
      CampaignModelService.getCampaignShareIn({Id:this.$route.params.id}, (data)=>{
        if(data.code == 200 && data.result) {
          this.sharedContent = data.result.ShareContent;
        }
      });
    }
	},
	created: function () {
    if (this.$route.params.id) {

    	this.shareId = this.$route.params.id;
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
    width:100px;
    height:100px;
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
