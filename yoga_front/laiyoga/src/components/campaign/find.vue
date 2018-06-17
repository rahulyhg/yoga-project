<template>
  <div>
    <tab  :value="currentIndex">
      <tab-item v-for="(data, index) in tabList" @on-item-click="showStatusData(data.status)" >
        {{data.name}}
        <span class="vux-tab-item-badge" v-if="data.noticeCount>0">{{data.noticeCount}}</span>
      </tab-item>
    </tab>
    <div>
      <div v-for="(campaignIn, date) in campaignList" class="campaign-form" @click="goToDetail(campaignIn.UniqueId)" v-if="campaignList.length > 0">
        <masker class="campaign-mask" :color="'#636363'">
          <img class="campaign-img" :src="appConfig.resourceDomain+(campaignIn.pictureList ? campaignIn.pictureList[0].FilePath:'')" />
          <div slot="content" class="campaign-content">
            <div style="height: 30px;">
              <span class="campaign-title">{{campaignIn.Name}}</span>
              <span class="campaign-price" v-if="campaignIn.IsFreeOfCharge == 0">
                ￥{{campaignIn.priceIn&&campaignIn.priceIn[0]?campaignIn.priceIn[0]['Price']:0}}
              </span>
              <span class="campaign-price" v-if="campaignIn.IsFreeOfCharge == 1">
                免费
              </span>
            </div>
            <p class="campaign-desc">
              {{campaignIn.Description}}
            </p>
          </div>
        </masker>

        <!--<h2>{{date}}</h2>
        <div class="campaign" v-for="(campaign, index) in campaignIn" >
          <img src="../../assets/img/yj-bg.png" />
          <ul>
            <li class="campaign-title">{{campaign.Name}}</li>
            <li class="campaign-date">{{campaign.CreatedDate}}</li>
            <li class="campaign-address">{{campaign.Address}}</li>
          </ul>
        </div>-->
        <div class="campaign-address">
          {{campaignIn.Address}}
        </div>
      </div>

      <div v-if="campaignList.length == 0 && !showRegister" class="empty-in">{{showCampaignResult}}</div>
      <div class="regInfo" v-if="showRegister" >
        <h3>您尚未注册账号，执行注册后可查看。</h3>
        <button class="weui-btn weui-btn_primary common-btn reg-btn " v-on:click="doRegister">
          注册账号
        </button>
      </div>

    </div>
  </div>
</template>

<script>
import { Tab, TabItem,Masker } from 'vux'
import CampaignModel from '../../service/model/CampaignModel.service';
import appConfig from '../../config/app.config'

export default {
  components: {
    Tab,
    TabItem,
    Masker
  },
  data() {
    return {
      currentIndex: 0,
      showRegister:false,
      tabList: [
        {name: '最热', noticeCount: 0, status:1},
        {name: '附近', noticeCount: 0, status:2},
        {name: '我参加的', noticeCount: 0, status:3}
      ],
			campaignList: [],
			appConfig:appConfig
    }
  },
  methods: {
    showList: function (status) {
			this.showCampaignResult = '活动加载中..';
      this.currentIndex = parseInt(status) - 1;
			CampaignModel.findCampaign({status:status}, (data)=>{
				if(data.code == 200) {
					this.campaignList = data.result;
					this.showCampaignResult = '暂无活动';
        }
      });
    },
		goToDetail:function (campaignId) {
			this.$router.push('/campaign/detail/'+campaignId);
		},
    doRegister() {
      this.$router.push('/user/register');
    },
    showStatusData(status) {
      let userData = this.$cookie.get('userData');
			userData = userData ? JSON.parse(userData):null;
			this.campaignList = [];
      if(status == 3 && (!userData || !userData.Id)) {
        this.showRegister = true;
      } else {
				this.showRegister = false;
        this.showList(status);
      }
    }
  },
  created: function () {
    let status = this.$route.params.status ? this.$route.params.status:1;
    this.showStatusData(status);
  },
  watch:{
		$route: function (route) {
			this.showStatusData(route.params.status);
		},
  }
}

</script>

<style>
  .campaign-mask{
    width: 100%;
    height:220px;
    overflow: hidden;
    background-color:rgba(141,141,141,0.2) !important;
  }
  .campaign-form{
    background-color: #ffffff;
    margin-top: 15px;
    padding: 20px 20px 10px 20px;
  }
  .campaign-form h2 {
    padding: 0px;
    font-size: 16px;
  }
  .campaign {
    display: flex;
    border-bottom: 1px solid #f0f0f0;
    padding: 10px 0px 15px 0px;
    position: relative;
  }
  .campaign.no-border{
    border:0px;
  }
  .campaign-content{
    padding: 10px 20px;
  }
  .campaign-form img{
    width:  100%;
  }
  .campaign-form ul {
    text-align: left;
    margin-left: 30px;
  }
  .campaign-form ul li{
    list-style: none;
    margin-bottom: 5px;
  }
  .campaign-title {
    float: left;
    font-size: 18px;
    color: #FFFFFF;
  }
  .campaign-price{
    float: right;
    font-size: 18px;
    color: #FFFFFF;
  }
  .campaign-desc{
    display: block;
    font-size: 16px;
    color: #FFFFFF;
    position: relative;
    text-align: left;
  }
  .campaign-address{
    color:#666;
    font-size: 16px;
  }
  .regInfo{
    margin: 20px 0px;
  }
  .regInfo h3{
    font-size: 16px;
    font-weight: 500;
    text-align: center;
    color: #666;
    margin-bottom: 13px;
  }
</style>
