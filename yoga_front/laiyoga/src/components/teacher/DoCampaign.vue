<template>
  <div>
    <h3 class="content-title">活动信息</h3>
    <group class="content-form">
      <x-input title="活动名称" v-model="campaignIn.Name"  :show-clear=true placeholder="请输入活动名称" required></x-input>
      <x-input title="主办方" v-model="campaignIn.HostName"  :show-clear=true required></x-input>
      <x-input title="活动地址" v-model="campaignIn.Address" :show-clear=true required ref="campaignAddress" @on-focus="setMap" ></x-input>
      <div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto;z-index: 500; display: none;"></div>
      <selector title="活动类型" v-model="campaignIn.Type" :options="typeList" required></selector>
      <x-input title="人数上限" v-model="campaignIn.MaxPerson"  :show-clear=true type="number" required></x-input>
      <!--<calendar title="活动开始时间" v-model="campaignIn.StartTime" :value="campaignIn.StartTime"></calendar>-->
      <datetime  title="活动开始"  v-model="campaignIn.StartTime" :format="dateFormat"  required></datetime>
      <datetime title="活动结束"  v-model="campaignIn.EndTime" :format="dateFormat" required></datetime>
      <x-switch title="免费活动" v-model="campaignIn.IsFreeOfCharge"  placeholder="否"></x-switch>
      <cell title="活动价格" is-link @click.native="openPriceForm" v-if="!campaignIn.IsFreeOfCharge"></cell>

      <div v-transfer-dom >
        <popup v-model="showPriceForm" @on-hide="doPrice(0)" @on-show="doPrice(1)">
          <div v-if="showPriceForm" style="height: 620px; ">
            <group>
              <h3 class="content-title">原价</h3>
              <x-input title="票价"  placeholder="请填写活动价格" v-model="campaignIn.priceList[0].Price"  :show-clear=true type="number" required></x-input>
              <datetime  title="报名开始"  v-model="campaignIn.priceList[0].StartTime" :format="dateFormat"  required></datetime>
              <datetime title="报名结束"  v-model="campaignIn.priceList[0].EndTime" :format="dateFormat" required></datetime>

              <h3 class="content-title">早鸟票</h3>
              <x-switch title="开启" v-model="isHaveEarlyPrice"  placeholder="开启" @on-change="doEarlyPrice"></x-switch>
              <div style="margin-bottom: 20px;">
                <div v-if="isHaveEarlyPrice">
                  <x-input title="票价" placeholder="请填写早鸟价格" v-model="campaignIn.priceList[1].Price"  :show-clear=true type="number"></x-input>
                  <datetime  title="价格截止" v-model="campaignIn.priceList[1].EndTime"  :format="dateFormat"></datetime>
                  <x-switch title="是否叠加优惠券" v-model="campaignIn.priceList[1].AndDiscountCode" ></x-switch>
                </div>
              </div>
              <button class="weui-btn weui-btn_primary common-btn save-date-btn" @click="showPriceForm=false">完成</button>
            </group>
          </div>
        </popup>
      </div>
      <div id="l-map"></div>
      
    </group>

    <h3 class="content-title">活动信息</h3>
    <group class="content-form">
      <x-textarea title=活动描述 v-model="campaignIn.Description"></x-textarea>
    </group>
    <h3 class="content-title">活动图片上传</h3>
    <upload @outputFile="setFileIn" :setFileList="pictureList"></upload>
    <button class="weui-btn weui-btn_primary common-btn" @click="savePublishCampaign">保存并发布</button>
    <button class="weui-btn weui-btn_primary common-btn sec-btn" @click="saveCampaign" v-if="campaignIn.Status == 0">保存草稿</button>
    <button class="weui-btn weui-btn_default common-btn cancel-btn" @click="cancelCampaign">取消</button>
  </div>
</template>

<script>

  import { TransferDom,Group, XInput,XSwitch,Selector, Agree,Datetime,
    Calendar,Cell,XTextarea,Popup} from 'vux';

	import Upload from '../plugin/Upload'
	import appConfig from '../../config/app.config'

	import CampaignModelService from '../../service/model/CampaignModel.service';
  import MapService from '../../service/common/map.service';

  export default {
    directives: {
      TransferDom
    },
    components: {
      Group, XInput, XSwitch, Agree, Selector, Datetime, Calendar,
      Cell, XTextarea, Popup,
			Upload
    },
    data() {
      return {
        appConfig: appConfig,
        showPriceForm: false,
				campaignAddressId:'',
        maxDay: 30,
        startDate: '',
        endDate: '',
        dateFormat: 'YYYY-MM-DD HH:mm',
        isHaveEarlyPrice: false,
        maxSize: 1024 * 20 * 1024,
        pictureList:[],
				memberList:[],
        campaignIn: {
					Status:0,
					UniqueId:'',
					IsFreeOfCharge:false,
          Poster: '',
          StartTime: '',
          EndTime: '',
          priceList: [
            {Level: 1, StartTime: '', EndTime: '', Price: ''},
            {Level: 2, StartTime: '', EndTime: '', Price: '', AndDiscountCode: false}
          ]
        },
        typeList: [
          '工作坊', '公开课', 'Retreat', '户外', '静修'
        ]
      }
    },
    methods: {
      setMap:function (val, $event) {
				this.campaignAddressId = $event.srcElement.id;

			/*	MapService.setMap().then(BMap => {
					this.initMapSearch(BMap);
				});*/

      },
      setEndDate: function (now) {
        let dateObj = new Date(now.getTime() + this.maxDay * 24 * 3600 * 1000);
        this.endDate = dateObj.getFullYear() + '-' + (dateObj.getMonth() + 1) + '-' + dateObj.getDate();
      },
      openPriceForm: function () {
        this.showPriceForm = true;
        if(!this.campaignIn.UniqueId) {
					//this.setPriceIn();
        }
      },
      setPriceIn: function () {
        this.campaignIn.priceList[0]['StartTime'] = this.campaignIn.StartTime;
        this.campaignIn.priceList[0]['EndTime'] = this.campaignIn.EndTime;
        this.campaignIn.priceList[1]['StartTime'] = this.campaignIn.StartTime;
        this.campaignIn.priceList[1]['EndTime'] = this.campaignIn.StartTime;
      },
      doPrice: function (status) {

      },
      doEarlyPrice: function () {

      },
      saveCampaign: function () {
      	console.log(this.campaignIn);
        CampaignModelService.doCampaign(this.campaignIn, (data) => {
          if (data.code == 200) {
          	let campaignId = '';
          	if(this.campaignIn.UniqueId) {
							campaignId = this.$route.params.campaignId;
							this.$vux.toast.show({
								text: '保存活动成功！'
							});
            } else {
							campaignId = data.result.campaignId;
							this.$vux.toast.show({
								text: '创建活动成功！'
							});
            }
            this.$router.push('/campaign/detail/' + campaignId);
          } else {
						if(this.campaignIn.UniqueId) {
							this.$vux.toast.show({
								text: '保存活动失败！',
								type: 'warn'
							});
						} else {
							this.$vux.toast.show({
								text: '创建活动失败！',
								type: 'warn'
							});
            }
          }
        });
      },
      savePublishCampaign: function () {
        this.campaignIn.Status = 1;
        this.saveCampaign();
      },
			cancelCampaign:function () {
				this.$router.push('/teacher/campaign');
			},

			setFileIn:function(fileIdArr) {
      	if(fileIdArr) {
					this.campaignIn.Poster = fileIdArr.join(',');
        }
      },
      setCampaignIn:function () {
				CampaignModelService.getCampaignIn({CampaignId:this.$route.params.campaignId}, (data) => {
					if (data.code == 200 && data.result) {
						delete data.result.StatusName;
						data.result.IsFreeOfCharge = data.result.IsFreeOfCharge == 1 ? true:false;

						if(!data.result.IsFreeOfCharge) {
							if( data.result.priceList.length > 1) {
								this.isHaveEarlyPrice = true;
								for(let key in data.result.priceList) {
									data.result.priceList[key].AndDiscountCode =
										data.result.priceList[key].AndDiscountCode == 1 ? true: false;
								}
							} else {
								if( !data.result.priceList[1]) {
									data.result.priceList[1] = {
										Level: 2, StartTime:data.result.priceList[0]['StartTime'],
										EndTime: data.result.priceList[0]['EndTime'], Price: '', AndDiscountCode: false
									};
								}
							}
            }

            this.pictureList = data.result.pictureList;
						this.memberList = data.result.memberList;
            delete data.result.pictureList;
						delete data.result.memberList;
            this.campaignIn = data.result;
					} else {
						this.initData();
          }
				});
			},
			initData() {
				let date = new Date();
				let year = date.getFullYear();
				let month = date.getMonth() + 1;
				let strDate = date.getDate();
				let hour = date.getHours();
				let minutes = date.getMinutes();
				this.setEndDate(date);

				this.startDate = year + '-' + month + '-' + strDate+' 00:00';

				this.campaignIn.StartTime = this.startDate;
				this.campaignIn.EndTime = this.campaignIn.StartTime;

				this.setPriceIn();
      },
      
      initMapSearch:function (BMap) {
        var map = new BMap.Map("l-map");
        map.centerAndZoom("北京", 12);                   // 初始化地图,设置城市和地图级别。

        let ac = new BMap.Autocomplete(    //建立一个自动完成的对象
          { "input" : this.campaignAddressId, "location" : map});

        ac.addEventListener("onhighlight", function(e) {  //鼠标放在下拉列表上的事件
          let str = "";
          let _value = e.fromitem.value;
          let value = "";
          if (e.fromitem.index > -1) {
            value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
          }
          str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;

          value = "";
          if (e.toitem.index > -1) {
            _value = e.toitem.value;
            value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
          }
          str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
          //G("searchResultPanel").innerHTML = str;
        });

        let myValue;
        ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
          let _value = e.item.value;
          console.log(_value, '>??????');
          myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
          //G("searchResultPanel").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;

          //setPlace();
        });
      }
    },
    created:function () {
			if (this.$route.params.campaignId) {
        this.setCampaignIn();
			} else {
				this.initData();
      }
		},
    mounted: function () {
      this.$nextTick(function () {

      });
    }
  }

</script>

<style>

  .content-title{
    padding-left: 20px;
    margin-top: 10px;
  }
  .content-form{
    margin-top: -10px;
  }
  .save-btn{
    margin-top: 15px;
  }
  .cancel-btn{
    margin-bottom: 20px;
  }
  .save-date-btn{
    margin-bottom: 20px;
  }

</style>
