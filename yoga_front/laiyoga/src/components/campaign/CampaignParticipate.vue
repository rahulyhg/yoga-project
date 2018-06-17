<template>
  <div style="padding-bottom: 20px;">
    <h3 class="content-title">请确认活动信息</h3>
    <group class="form-detail">
      <cell title="活动名称：" :value="campaignIn.Name" :value-align="'left'"></cell>
      <cell title="主办方：" :value="campaignIn.HostName" :value-align="'left'"></cell>
      <cell title="活动地址：" :value="campaignIn.Address" :value-align="'left'"></cell>
      <cell title="开始时间：" :value="campaignIn.StartTime" :value-align="'left'"></cell>
      <cell title="结束时间：" :value="campaignIn.EndTime" :value-align="'left'"></cell>
    </group>

    <h3 class="content-title">参会人信息</h3>
    <group class="form-detail">
      <cell title="微信昵称：" :value="userData.Nickname" :value-align="'left'"></cell>
      <cell title="手机号：" :value="userData.Mobile" :value-align="'left'"></cell>
    </group>

    <div v-if="campaignIn.IsFreeOfCharge != 1 && payIn.andDiscountCode == 1">
      <h3 class="content-title">优惠码</h3>
      <group class="form-detail">
        <x-input title="优惠码" v-model="discountCode"  :show-clear=true @keyup.native="useDiscountCode"></x-input>
      </group>
    </div>
    <h3 class="content-title">确认金额</h3>
    <group  class="form-detail">
      <cell title="应付金额" :value="'￥'+payIn.payableAmount"></cell>
      <cell-form-preview :list="payIn.priceList" v-if="campaignIn.IsFreeOfCharge == 0"></cell-form-preview>
    </group>
    <button class="weui-btn weui-btn_primary common-btn pay-btn" v-on:click="doParticipateCampaign">
      {{payIn.payableAmount == 0 ?'参与活动':'微信支付'}}
    </button>
  </div>
</template>

<script>
import { Group, Cell, CellFormPreview, XInput} from 'vux'
import CampaignModelService from '../../service/model/CampaignModel.service';
import DiscountCodeModel from '../../service/model/DiscountCodeModel.service';

export default {
	components: {
		Group,
    Cell,
		CellFormPreview,
		XInput
	},
	data() {
		return {
			discountCode:'',
			campaignIn:{
				IsFreeOfCharge:0,
      },
			priceList:[
      ],
			userData:{},
      payIn:{
				andDiscountCode:1,
				payableAmount:0.00,
        priceList:[]
      },
		}
	},
	methods: {
		getCampaignIn:function (campaignId) {
			CampaignModelService.getParticipateCampaignIn({CampaignId:campaignId}, (data) => {
				if(data.code == 200) {
					this.campaignIn = data.result.campaignIn;
					this.priceList = data.result.priceList;
					if(this.campaignIn.IsFreeOfCharge == 0) {
						this.setPriceIn();
          }
        } else {
					this.$vux.toast.show({
						text: data.message,
						type:'warn'
					});
        }
      });
		},
    setPriceIn:function () {
			this.payIn.priceList = [{label:'原价格', value:'0.00'}];
			if(this.priceList.length > 1) {  //有早鸟价
				this.payIn.payableAmount = this.priceList[0].Price;
				this.payIn.priceList[0].value = '￥'+this.priceList[1].Price;
				this.payIn.priceList.push({label:'早鸟价', value:'￥'+this.payIn.payableAmount});
				this.payIn.andDiscountCode = this.priceList[0].AndDiscountCode;
      } else {
				this.payIn.andDiscountCode = this.priceList[0].AndDiscountCode;
				this.payIn.payableAmount = this.priceList[0].Price;
        this.payIn.priceList[0].value = '￥'+this.payIn.payableAmount;
      }
		},
		doParticipateCampaign:function () {
      CampaignModelService.participateCampaign({
					CampaignId:this.$route.params.campaignId,
					shareFromUserId:this.$route.params.shareFromUserId,
					DiscountCode:this.discountCode
        },
        (data) => {
					if(data.code == 200) {
            if(data.result.PayableAmount == 0) {
							this.$vux.toast.show({
								text: '参与活动成功！'
							});
							this.$router.push('/campaign/detail/'+this.$route.params.campaignId);
            } else {    //执行支付
              window.location.href='/api/pay/do?OrderNo='+data.result.OrderNo;
            }
					} else {
						this.$vux.toast.show({
							text: data.message,
							type:'warn'
						});
					}
        }
      );
		},
		useDiscountCode:function () {
			if(this.discountCode.length == 8) {
				DiscountCodeModel.useDiscountCode(
					{
						DiscountCode: this.discountCode,
						CampaignId: this.$route.params.campaignId
					}, (data) => {
						if (data.code == 200) {
							if(this.payIn.priceList.length > 2){
								this.setPriceIn();
								this.payIn.priceList.slice(2, 1);
              }
							let payableAmount = parseFloat(this.payIn.payableAmount);
							if(data.result.DiscountType == 1) { //优惠金额
								this.payIn.payableAmount = payableAmount - parseFloat(data.result.DiscountAmount);
								this.payIn.priceList.push({label:'优惠', value:'-￥'+data.result.DiscountAmount});
              } else if(data.result.DiscountType == 2) {  //优惠折扣
								this.payIn.payableAmount = payableAmount * (parseFloat(data.result.DiscountPercent)/10);
								this.payIn.priceList.push({label:'优惠', value:'-￥'+(payableAmount-this.payIn.payableAmount)});
              } else if(data.result.DiscountType == 3) {  //免费
								this.payIn.priceList.push({label:'优惠', value:'-￥'+payableAmount});
								this.payIn.payableAmount = 0;
							}
						} else {
							this.$vux.toast.show({
								text: data.message,
								type: 'warn'
							});
						}
					});
			} else{
				this.setPriceIn();
      }
		}
  },
	created: function () {
		let userData = this.$cookie.get('userData');
		this.userData = userData ? JSON.parse(userData):null;

		if (this.$route.params.campaignId) {
      this.getCampaignIn(this.$route.params.campaignId);
		}
	}
}

</script>

<style>
  .content-title{
    padding-left: 20px;
    margin-top: 10px;
  }
  .form-detail{
    margin-top: -10px;
  }
  .pay-btn{
    margin-top: 20px !important;
  }


</style>
