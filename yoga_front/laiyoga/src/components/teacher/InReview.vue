<template>
  <div>
    <div class="user-header">
      <img class="user-avatar" :src="userData.Avatar" />
      <img src="../../assets/img/user-bg.jpg" class="user-register-bg"/>
    </div>

    <div v-if="status == -3">
      <h1 class="view-status">老师身份审核中</h1>
      <p class="view-description">请耐心等待，或者在公众号留言了解最新进度</p>
    </div>

    <div v-if="status == -2">
      <h1 class="view-status">已封号</h1>
    </div>

    <button class="weui-btn weui-btn_primary common-btn back-btn " v-on:click="goBack">返回</button>

  </div>
</template>

<script>
import {XButton  } from 'vux'
import UserModelService from '../../service/model/UserModel.service';
export default {
	components: {
		XButton
	},
	data() {
		return {
			status:0,
			userData:{}
		}
	},
	methods: {
		goBack: function () {
			window.history.go(-1);
		},
    getTeacherStatus() {
      UserModelService.getTeacherStatus((data)=>{
        //alert(data.code+'>>>'+data.message);
        if(data.code == 200) {
          this.status = data.result.status
          //审核通过
          if(this.status == 1) {
						this.$router.push('/teacher/campaign');
          }
        }
      });
    }
	},
	created: function () {
		let userData = this.$cookie.get('userData');
		this.userData = userData ? JSON.parse(userData):{};

		this.getTeacherStatus();
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
    width:150px;
    height:150px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 50%;
  }
  .view-status{
    font-size:20px;
    text-align: center;
    margin-top: 20px;
    color: #999;
  }
  .view-description{
    font-size:15px;
    text-align: center;
    margin-top: 20px;
    color: #666;
  }
  .back-btn{
    margin-top: 30px;
  }

</style>
