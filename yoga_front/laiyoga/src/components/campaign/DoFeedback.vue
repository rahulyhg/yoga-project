<template>
  <div>
    <h3 class="content-title">活动信息</h3>
    <group class="form-detail">
      <cell title="活动名称：" :value="campaignIn.Name" :value-align="'left'"></cell>
      <cell title="主办方：" :value="campaignIn.HostName" :value-align="'left'"></cell>
      <cell title="活动地址：" :value="campaignIn.Address" :value-align="'left'"></cell>
      <cell title="开始时间：" :value="campaignIn.StartTime" :value-align="'left'"></cell>
      <cell title="结束时间：" :value="campaignIn.EndTime" :value-align="'left'"></cell>
    </group>

    <group class="content-form">
      <x-switch :title="userData.UserType ==1?'是否完成':'是否参加'" v-model="feedbackIn.CampaignStatus" ></x-switch>
      <cell title="整体满意度">
        <rater v-model="feedbackIn.Rating"></rater>
      </cell>
      <x-textarea title=活动描述 v-model="feedbackIn.CampaignSummary"></x-textarea>
    </group>

    <h3 class="content-title">活动图片上传</h3>
    <upload @outputFile="setFileIn"></upload>

    <button class="weui-btn weui-btn_primary common-btn save-btn" v-on:click="saveFeedBack">保存</button>
  </div>
</template>

<script>
	import { Group, Rater, XInput,XSwitch,Selector,Cell,XTextarea} from 'vux'
	import CampaignModelService from '../../service/model/CampaignModel.service';

	import Upload from '../plugin/Upload'

	export default {
		components: {
			Group, Cell, Rater, XInput, XSwitch, Selector,XTextarea,Upload
		},
		data() {
			return {
				userData:{},
				campaignIn:{},
				feedbackIn: {
					CampaignId:'',
					CampaignStatus:true,
					Rating:5,
					CampaignSummary:'',
					CampaignPhotos:''
				}
			}
		},
		methods: {
			setFileIn:function(fileIdArr) {
				if(fileIdArr) {
					this.feedbackIn.CampaignPhotos = fileIdArr.join(',');
				}
			},
			getCampaignIn:function () {
				CampaignModelService.getCampaignBaseIn(
					{CampaignId:this.$route.params.campaignId}, (data) => {
					if(data.code == 200) {
						this.campaignIn = data.result;
					} else {
						this.$vux.toast.show({
							text: data.message,
							type:'warn'
						});
					}
				});
			},

			saveFeedBack:function () {
				CampaignModelService.campaignFeedback(this.feedbackIn, (data) => {
						if(data.code == 200) {
							this.campaignIn = data.result;

							this.$vux.toast.show({
								text: '提交反馈成功'
							});
							this.$router.push('/campaign/detail/'+this.$route.params.campaignId);
						} else {
							this.$vux.toast.show({
								text: data.message,
								type:'warn'
							});
						}
					});
			}
		},
		mounted: function () {

		},
		created: function () {
			if (this.$route.params.campaignId) {
				let userData = this.$cookie.get('userData');
				this.userData = userData ? JSON.parse(userData):{};
				this.feedbackIn.CampaignId = this.$route.params.campaignId;
				this.getCampaignIn();
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
