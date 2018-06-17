<template>
  <div>
    <div id="app" class="app-wrap">
      <router-view></router-view>
    </div>

    <div class="handle-wrapper" v-if="showTabList.length > 0">
      <div class="toolbar">
        <div :class="'handle-button'+(showMenu?' active': '')" @click="toggleMenu"></div>
        <ul :class="'icons'+(showMenu?' open': '')" >
          <li v-for="(data, index) in showTabList" @click="goTo(index)">
            <i :class="'fa fa-'+data.icon+' fa-2x'" aria-hidden="true"></i>
          </li>
         <!-- <li  @click="logout">
            退出登录
          </li>-->
        <!--  <li><i class="fa fa-user fa-2x" aria-hidden="true"></i></li>
          <li><i class="fa fa-star fa-2x" aria-hidden="true"></i></li>-->
        </ul>
      </div>
    </div>


  </div>

</template>

<script>
  import appConfig from './config/app.config'
import { Tabbar, TabbarItem } from 'vux'
import UserModelService from './service/model/UserModel.service';

export default {
	components: {
		Tabbar, TabbarItem
	},
	data() {
		return {
			appConfig:appConfig,
		  showMenu:false,
		  loadMain:false,
		  userData:{},
		  showTabList:[],
			teacherTabList:[
      ],
      studentTabList:[
        {title:'我的活动', link:'/campaign/find/3', icon:'home'},
        {title:'发现活动', link:'/campaign/find/1', icon:'search'}
      ]
    }
	},
	methods: {
    toggleMenu:function () {
      this.showMenu = !this.showMenu;
    },
		setMenu:function () {
			if(this.userData && this.userData.Id) {
				if(this.userData.UserType == 1) {
					if(this.userData.IsAxamine == 0) {
						this.showTabList = [];
          } else {
						this.showTabList = this.teacherTabList;
          }
				} else {
					this.showTabList = this.studentTabList;
				}
			}
		},
		logout:function () {
			UserModelService.logout((data) => {
				if(data.code == 200) {
					//this.$router.push('/user/register');
          window.location.href="/api/login";
        }
      });
		},
    setUserData: function () {
      let userData = this.$cookie.get('userData');
      this.userData = userData ? JSON.parse(userData):null;
      if(this.userData) {
      	if(this.userData.IsActive == 1) {
					this.loadMain = true;
					this.setMenu();
        } else {
      		this.showTabList =[];
					this.$vux.toast.show({
						text: '账号已封，请联系管理员！',
						type:'warn'
					});
        }
      } else {
				window.location.href="/api/login";
      }
    },
    goTo(index) {
      this.$router.push(this.showTabList[index]['link']);
      this.showMenu = false;
    }
	},
	created: function () {
    this.setUserData();
	},
	watch:{
		$route: function (route) {
      this.setUserData();
		},
	}
}
</script>

<style lang="less">
@import 'assets/style/weui.less';
@import 'assets/icon/less/font-awesome.less';

body {
  background-color: #fbf9fe;
}


.handle-wrapper {
  text-align:center;
  font-family: 'Lato', sans-serif;
  text-transform:uppercase;
}

.handle-wrapper .toolbar {
  width:100%;
  max-width:670px;
  min-width:550px;
  margin: 70px auto;
}

.handle-wrapper .toolbar .handle-button {
  width:62px;
  height:62px;
  border-radius:50%;
  background-color:#3AB09E;
  color:#ffffff;
  text-align:center;
  font-size:3.2em;
  z-index:1;
  position: fixed;
  right:20px;
  bottom:20px;
}

.handle-wrapper .toolbar .handle-button,.icons{
  -webkit-transition: -webkit-all 0.5s cubic-bezier(.87,-.41,.19,1.44);
  transition:  all 0.5s cubic-bezier(.87,-.41,.19,1.44);
}

.handle-wrapper .toolbar .handle-button:after {
  content:"+";
  top: -14px;
  position: relative;
}

.handle-wrapper .toolbar .handle-button.active {
  -webkit-transform: rotate(45deg);
  transform: rotate(45deg);
}

.handle-wrapper .toolbar .icons {
  width:0%;
  overflow:hidden;
  height:30px;
  list-style:none;
  padding:11px 10px 11px 20px;
  background-color:#ffffff;
  box-shadow: 1px 1px 1px 1px #DCDCDC;
  border-radius: 2em;
  position: fixed;
  right:36px;
  bottom:26px;
}

.handle-wrapper .toolbar .icons.open {
  width:70%;
  overflow:hidden;
}

.handle-wrapper .toolbar .icons li {
  display: none;
  width:10%;
  color:#3AB09E;
}

.handle-wrapper .toolbar .icons.open li {
  width:22%;
  display: inline-block;
}

</style>
