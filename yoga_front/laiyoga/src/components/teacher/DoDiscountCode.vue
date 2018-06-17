<template>
  <div>
    <h3 class="content-title">优惠码信息</h3>
    <group class="content-form">
      <x-switch title="开启" v-model="discountCodeStatus"  placeholder="开启"></x-switch>
      <x-input title="优惠码名称" v-model="discountCodeIn.DiscountName"  :show-clear=true ></x-input>
     <!-- <x-input title="优惠码数量" v-model="discountCodeIn.MaxUsage"  :show-clear=true type="number"></x-input>-->
      <x-input title="可使用次数" v-model="discountCodeIn.MaxUsage"  :show-clear=true type="number"></x-input>
    </group>

    <h3 class="content-title">优惠方式</h3>
    <group class="content-form">
      <selector v-model="discountCodeIn.DiscountType" :options="discountTypeList"></selector>
      <x-input title="金额" v-model="discountCodeIn.DiscountAmount" type="number"  :show-clear=true v-if="discountCodeIn.DiscountType == 1"></x-input>
      <x-input title="折扣率" v-model="discountCodeIn.DiscountPercent"  type="number" :show-clear=true v-if="discountCodeIn.DiscountType == 2"></x-input>
    </group>
    <h3 class="content-title">优惠时间</h3>
    <group class="content-form">
      <datetime  title="开始时间" :format="dateFormat" v-model="discountCodeIn.StartTime"></datetime>
      <datetime title="结束时间"  :format="dateFormat" v-model="discountCodeIn.EndTime" ></datetime>
    </group>
    <button class="weui-btn weui-btn_primary common-btn save-btn" v-on:click="saveDiscountCode">保存</button>
  </div>
</template>

<script>
	import { Group, XInput,XSwitch,Selector,Datetime, Calendar,Cell,XTextarea} from 'vux'
	import DiscountCodeModelService from '../../service/model/DiscountCodeModel.service';

	export default {
		components: {
			Group, XInput, XSwitch, Selector,Datetime,Calendar,Cell,XTextarea
		},
		data() {
			return {
				maxDay:30,
				startDate:'',
				endDate:'',
				discountCodeStatus:true,
        dateFormat: 'YYYY-MM-DD HH:mm',
				discountCodeIn: {
					CampaignId:'',
					Status:1,
					DiscountType:1,
					StartTime:'',
					EndTime:''
				},
				discountTypeList:[
          {key:1, value:'优惠金额'},
					{key:2, value:'优惠折扣'},
          {key:3, value:'免费'}
        ]
			}
		},
		methods: {
			setEndDate:function (now) {
				let dateObj = new Date(now.getTime() + this.maxDay * 1 * 3600 * 1000);
				this.endDate =  dateObj.getFullYear()+'-'+(dateObj.getMonth() + 1)+'-'+ dateObj.getDate();
			},
			saveDiscountCode:function () {
				this.discountCodeIn.Status = this.discountCodeStatus ?1:0;

				DiscountCodeModelService.createDiscountCode(this.discountCodeIn, (data)=>{
					if(data.code == 200) {
						this.$vux.toast.show({
							text: '创建优惠码成功！'
						});
						this.$router.push('/discountCode/index/'+this.discountCodeIn.CampaignId);
					} else {
						this.$vux.toast.show({
							text: '创建优惠码失败！',
							type:'warn'
						});
					}
        })
			}
		},
		mounted: function () {

			let date = new Date();
			let year = date.getFullYear();
			let month = date.getMonth() + 1;
			let strDate = date.getDate();
			let hour = date.getHours();
			let minutes =  date.getMinutes();

			this.startDate = year+'-'+month+'-'+strDate;
			this.setEndDate(date);

			this.discountCodeIn.StartTime = this.startDate+' 00:00';
			this.discountCodeIn.EndTime = this.endDate+' 00:00';

		},
		created: function () {
			if (this.$route.params.campaignId) {
				this.discountCodeIn.CampaignId = this.$route.params.campaignId;
			}
		}
	}

</script>

<style>
  .img-list{
    display: flex;
    padding: 10px 20px;
    justify-content:space-between;
  }
  .img-list li{
    list-style: none;
    margin-right: 10px;
  }
  .img-list li.last{
    margin-right: 0px;
  }
  .img-list li img{
    width: 100%;
  }
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
</style>
