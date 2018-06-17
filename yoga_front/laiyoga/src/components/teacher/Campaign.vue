<template>
  <div>
    <tab  :value="currentIndex">
      <tab-item v-for="(data, index) in tabList" @on-item-click="showList(data.status)" >
        {{data.name}}
        <span class="vux-tab-item-badge" v-if="data.noticeCount>0">{{data.noticeCount}}</span>
      </tab-item>
    </tab>
    <div>
      <div v-for="(campaignIn, date) in campaignList" class="campaign-my-form" v-if="showCampaignList">
       <!-- <masker class="mask">
          <img class="m-img" src="../../assets/img/yj-bg.png" />
          <div slot="content" class="m-title">
            {{item.title}}
            <br/>
            <span class="m-time">2016-03-18</span>
          </div>
        </masker>-->
       <!-- <img :src="item.img" />-->

        <h2>{{date}}</h2>
        <div class="campaign-my" v-for="(campaign, index) in campaignIn"  @click="goToDetail(campaign.UniqueId)">
          <img class="campaign-my-pic" :src="appConfig.resourceDomain+(campaign.pictureList?campaign.pictureList[0].FilePath : '')" />
          <ul>
            <li class="campaign-my-name">{{campaign.Name}}</li>
            <li class="campaign-my-date">{{campaign.CreatedDate}}</li>
            <li class="campaign-my-address">{{campaign.Address}}</li>
          </ul>
        </div>
      </div>
      <div v-if="!showCampaignList" class="empty-in">暂无活动</div>
      <div  class="find-campaign">
        <a href="javascript:;" @click="toFindCampaign">发现更多课程</a>
      </div>
    </div>
    <div class="handle-wrapper">
      <div class="toolbar">
        <div :class="'handle-button'" @click="createCampaign"></div>
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
      tabList: [
        {name: '进行中', noticeCount: 0, status:2},
        {name: '计划中', noticeCount: 0, status:1},
				{name: '草稿', noticeCount: 0, status:0},
        {name: '已完成', noticeCount: 0, status:3}
      ],
			campaignList: [],
			appConfig:appConfig,
      showCampaignList:false
    }
  },
  methods: {
    showList: function (status) {
			CampaignModel.getCampaign({status:status}, (data)=>{
				if(data.code == 200) {
					if(data.result instanceof Array) {
						this.showCampaignList = false;
          } else {
						this.showCampaignList = true;
          }
					this.campaignList = data.result;
        }
      });
    },
		goToDetail:function (campaignId) {
			this.$router.push('/campaign/detail/'+campaignId);
		},
		toFindCampaign:function () {
			this.$router.push('/campaign/find');
		},
		createCampaign:function () {
			this.$router.push('/teacher/doCampaign');
		}

  },
  mounted: function () {
    this.showList(this.tabList[0]['status']);
  }
}

</script>

<style>
  .campaign-my-form{
    background-color: #ffffff;
    margin-top: 15px;
    padding: 10px 20px 5px ;
  }
  .campaign-my-form h2 {
    padding: 0px;
    font-size: 18px;
  }
  .campaign-my {
    display: flex;
    border-bottom: 1px solid #f0f0f0;
    padding: 10px 0px 15px 0px;
    position: relative;
    align-items: center;
  }
  .campaign-my.no-border{
    border:0px;
  }
  .campaign-my-pic{
    max-width:  140px;
    max-height: 130px;
  }
  .campaign-my-form ul {
    text-align: left;
    margin-left: 30px;
  }
  .campaign-my-form ul li{
    list-style: none;
    margin-bottom: 5px;
  }
  .campaign-my-name {
    font-size: 18px;
    color: #333;
  }
  .campaign-my-date{
    font-size: 16px;
    color: #999999;
  }
  .campaign-my-address{
    font-size: 16px;
    color: #999999;
  }
  .campaign-my-count{
    color: #333;
    width: 50px;
    text-align: right;
    font-size: 18px;
    position: absolute;
    right: 10px;
    top:15px;
  }
  .find-campaign{
    text-align: center; padding-top: 20px;
  }
  .find-campaign a{
    font-size:16px;
    color: #04BE02;
  }

</style>
