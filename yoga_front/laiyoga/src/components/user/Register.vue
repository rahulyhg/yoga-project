<template>
  <div>
    <grid>
      <div class="user-header">
        <img class="user-avatar" :src="userData.Avatar" />
        <img src="../../assets/img/user-bg.jpg" class="user-register-bg"/>
      </div>
    </grid>
    <p class="description">
      {{params.userType == 2 ? '初次见面，请通过手机号注册，注册完手机就可以报名活动！':
      '初次见面，请通过手机号注册，审核后就可轻松发布活动信息'}}
    </p>
    <!--<group title="用户类型">
      <radio :options="userType" value="params.userType" v-model="params.userType"></radio>
    </group>-->

    <group class="register-form" title="用户信息">
      <x-input title="手机号" v-model="params.phoneNumber"  :show-clear=true placeholder="请输入手机号"></x-input>

      <x-input title="验证码" v-model="params.code" :show-clear=true placeholder="请输入验证码" :max=6></x-input>

      <button class="weui-btn weui-btn_mini weui-btn_primary send-btn" @click="sendCode">发送验证码</button>
    </group>

    <label class="agree-form"><!--
      <input  type="checkbox" class="checkbox-common" v-model="isAgree">-->
      <span>
        <agree v-model="isAgree">阅读并同意<a href="javascript:void(0);">《相关条款》</a></agree>
      </span>
    </label>

    <button class="weui-btn weui-btn_primary common-btn reg-btn " v-on:click="submitReg">完成注册</button>
  </div>
</template>

<script>
import { Group, XInput,Grid,Agree,Radio } from 'vux'

import UserModelService from '../../service/model/UserModel.service';

export default {
  components: {
    Agree,
    Group,
    XInput,
    Grid,
    Radio
  },
  data() {
    return {
      userType:[{
        icon: '',
        key: '2',
        value: '学生'
      }, {
        icon: '',
        key: '1',
        value: '教师'
      }],
      isAgree: true,
      params:{
				phoneNumber: '',
				userType:'2',
				code:''
      }
    }
  },
  methods: {
  	sendCode:function () {
			UserModelService.sendCode({phoneNumber:this.params.phoneNumber}, (data)=>{
				if(data.code == 200) {
					this.$vux.toast.show({
						text: '发送成功！'
					});
        } else {
					this.$vux.toast.show({
						text: data.message,
						type:'warn'
					});
        }
      });
		},
    submitReg: function () {
  		if(this.isAgree) {
				UserModelService.register(this.params, (data)=>{
					if(data.code == 200) {
						this.$cookie.set('userData', JSON.stringify(data.result));
						this.$vux.toast.show({
							text: '注册成功！'
						});
						if(this.params.userType == 1) {
							this.$router.push('/teacher/inReview');
            } if(this.params.userType == 2) {
							let locationUrl = '/campaign/find';
              if(this.$route.query.locationUrl) {
								locationUrl = decodeURIComponent(this.$route.query.locationUrl);
              }
							this.$router.push(locationUrl);
						}
					} else {
						this.$vux.toast.show({
							text: data.message,
							type:'warn'
						});
					}
				});
      } else {
				this.$vux.toast.show({
					text: '请同意相关条款',
					type:'warn'
				});
      }

    }
  },
	created: function () {
		if (this.$route.params.userType) {
			this.params.userType = this.$route.params.userType;
		}

		let userData = this.$cookie.get('userData');
		this.userData = userData ? JSON.parse(userData):{};
		if(this.userData.Username) {
			let locationUrl = '/';
			if(this.userData.UserType == 1) {
				locationUrl = '/teacher/inReview';
      } else if(this.userData.UserType == 2) {
				locationUrl = '/campaign/find';
			}
			this.$router.push(locationUrl);
		}
	}
}

</script>

<style>
  .user-header{
    position: relative;
  }
  .user-register-bg{
    max-height: 340px;
    width: 100%; display: block;
  }
  .user-avatar{
    position: absolute;
    width:100px;
    height:100px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 50%;
  }
  .description{
    font-size:16px;
    text-align: center;
    margin-top: 20px;
    color: #666;
  }
  .register-form{
    position: relative;
  }
  .send-btn{
    float: right;
    margin-top:-37px;
    margin-right: 1em !important;
  }
  .agree-form{
    display: block;
    padding: 10px 10px;
  }
  .agree-form a {
    color:#586C94;
  }
  .reg-btn{
    margin-top: 20px;
  }
</style>
