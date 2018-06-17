<template>
  <div>
    <group style="margin-top: -20px;">
      <selector v-model="params.CampaignId" :options="campaignList" @on-change="getDiscountCode()"></selector>
    </group>
    <tab  :value="currentIndex">
      <tab-item v-for="(data, index) in tabList" @on-item-click="getDiscountCode(data.useStatus)" >
        {{data.name}}
        <span class="vux-tab-item-badge" v-if="data.noticeCount>0">{{data.noticeCount}}</span>
      </tab-item>
    </tab>
    <div>
      <div v-for="(discountCodeIn, date) in discountCodeList" class="discountCode-form">
        <h2>{{date}}</h2>
        <div class="discountCode" v-for="(discountCodeData, index) in discountCodeIn" >
          <div class="discount-in">
            {{discountCodeData['DiscountType'] == 1 ? discountCodeData['DiscountAmount']
            :(discountCodeData['DiscountType'] == 2 ? discountCodeData['DiscountPercent']+'折'
            :(discountCodeData['DiscountType'] == 3?'免费':''))
            }}
          </div>
          <ul>
            <li class="discountCode-title">{{discountCodeData.DiscountName}}</li>
            <li class="discountCode-code">{{discountCodeData.DiscountCode}}</li>
          </ul>
          <button class="weui-btn weui-btn_primary copy-btn"  :data-clipboard-text="discountCodeData.DiscountCode">复制</button>
        </div>
      </div>
    </div>

    <div class="handle-wrapper">
      <div class="toolbar">
        <div :class="'handle-button'" @click="createDiscountCode"  v-if="isCanAddDiscountCode"></div>
      </div>
    </div>

  </div>
</template>

<script>
	import { Group,Selector,Tab, TabItem } from 'vux'
	import Clipboard from 'clipboard'

	import CampaignModel from '../../service/model/CampaignModel.service';
	import DiscountCodeModelService from '../../service/model/DiscountCodeModel.service';

	export default {
		components: {
			Group,Selector,
			Tab,
			TabItem
		},
		data() {
			return {
				isCanAddDiscountCode:false,
				currentIndex:0,
				params:{
					CampaignId:'',
          useStatus:0
        },
				campaignList:[

        ],
				discountCodeList:[],
				tabList: [
					{name: '未使用', useStatus:0},
					{name: '已使用', useStatus:1}
				]
			}
		},
		methods: {
      getDiscountCode:function (status) {
				if(typeof status !== 'undefined') {
					this.params.useStatus = status;
        }

				DiscountCodeModelService.getDiscountCodeList(this.params, (data) => {
					if(data.code == 200) {
						this.isCanAddDiscountCode = false;
						this.campaignList = [{key:'', value:'所有优惠码'}];
						if( data.result.campaignList ){
							for(let key in data.result.campaignList) {
								if(data.result.campaignList[key]['UniqueId'] == this.params.CampaignId && data.result.campaignList[key]['Status'] == 1) {
									this.isCanAddDiscountCode = true;
                }
								this.campaignList.push({key:data.result.campaignList[key]['UniqueId'], value:data.result.campaignList[key]['Name']});
							}
						}
						this.discountCodeList = data.result.codeList;
					}
        });
			},

			setClipboard:function () {

				let clipboard = new Clipboard('.copy-btn');

				clipboard.on('success', (e) => {
					this.$vux.toast.show({
						text: '优惠码拷贝成功！'
					});
				});

				clipboard.on('error', (e) =>  {
					this.$vux.toast.show({
						text: '优惠码拷贝失败！',
            type:'warn'
					});
				});
			},
			createDiscountCode:function () {
				this.$router.push('/discountCode/do/'+this.params.CampaignId);
			}
		},
		created: function () {
			if (this.$route.params.campaignId) {
				this.params.CampaignId = this.$route.params.campaignId;
			}
			this.getDiscountCode(this.tabList[this.currentIndex].status);

			this.setClipboard();
		}
	}

</script>

<style>
  .discountCode-form{
    background-color: #ffffff;
    margin-top: 15px;
    padding: 10px 20px 5px ;
  }
  .discountCode-form h2 {
    padding: 0px;
    font-size: 18px;
  }
  .discountCode {
    display: flex;
    border-bottom: 1px solid #f0f0f0;
    padding: 15px 0px 15px 0px;
    position: relative;
  }
  .discountCode.no-border{
    border:0px;
  }
  .discountCode-form img{
    width:  140px;
    height: 110px;
  }
  .discountCode-form ul {
    text-align: left;
    margin-left: 30px;
    padding-top: 6px;
  }
  .discountCode-form ul li{
    list-style: none;
    margin-bottom: 5px;
  }
  .discountCode-code {
    font-size: 20px;
    color: #333;
  }

  .discountCode-title{
    font-size: 18px;
    color: #999999;
  }
  .discount-in{
    width: 80px;
    height: 40px;
    padding: 20px 0px;
    background-color: #999;
    text-align: center;
    color: #ffffff;
    font-size: 26px;
  }
  .copy-btn{
    width: 80px!important; position: absolute; right:0px;
    top:35px;
    font-size: 16px;
  }

</style>
