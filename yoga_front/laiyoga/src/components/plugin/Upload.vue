<template>
  <div>
    <ul class="img-list">
      <li v-for="(data, index) in fileList">
        <img :src="appConfig.resourceDomain+data.FilePath">
        <div class="delete-uploaded-img">
          <span @click="deleteFile(index)">删除</span>
        </div>
      </li>
      <li class="add-file">
        <input type="file" class="upload-img" v-on:change="uploadImg">
      </li>
    </ul>
  </div>
</template>

<script>
import Vue from 'vue';
import ImageCompressor from 'image-compressor.js';
import appConfig from '../../config/app.config'
import ApiService from '../../service/request/Api.service';

export default {
	props: {
		setFileList: {
			type: Array,
			default: []
		},
	},
  components: {
  },
  data() {
    return {
			fileList: [],
			appConfig:appConfig
    }
  },
  methods: {
    uploadImg: function (e) {
			let target = e.target;
			let src = target.value;
			if (this.fileList.length >= 6) {
				this.$vux.toast.show({
					text: '最多上传6张！',
					type: 'warn'
				});
				return false;
			}
			if (!src && !this.isImage(src)) {
				this.$vux.toast.show({
					text: '请选择图片！',
					type: 'warn'
				});
				return;
			}
			let file = target.files[0];
			if (file.size > this.maxSize) {
				this.$vux.toast.show({
					text: '图片超出尺寸！',
					type: 'warn'
				});
				return false;
			}
			this.$vux.loading.show({
				text: '上传中..'
			});

			new ImageCompressor(file, {
				quality: .6,
				success:(result) => {

					let formData = new FormData();
					formData.append('FileType', 1);
					formData.append('file', result);

					let url = ApiService.getRequestUrl("fileUpload");
					Vue.http.post(url, formData, {
						responseType: 'json',
						emulateJSON: true
					}).then(response => {
						this.$vux.loading.hide();
						let data = response.body;
						if (data.code == 200) {
							this.fileList.push(data.result);
							this.outPutFile();
							// alert("上传成功");
						} else {
							this.$vux.toast.show({
								text: data.message,
								type: 'warn'
							});
						}
						target.value = "";

					}, errorData => {
						this.$vux.toast.show({
							text: '上传失败！',
							type: 'warn'
						});
					});

				},
				error(e) {
					console.log(e.message);
				},
			});


		},
    outPutFile:function () {
			if (this.fileList.length > 0) {
				let fileId = [];
				for (let key in this.fileList) {
					fileId.push(this.fileList[key].FileId);
				}
				this.$emit('outputFile', fileId);
			}
		},
		isImage: function (src) {
			let imgReg = /\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/;
			if (imgReg.test(src)) {
				return true;
			}
			return false;
		},
		deleteFile:function (index) {
			this.fileList.splice(index, 1);
			this.outPutFile();
		}
  },
  created: function () {
  },
	watch:{
		setFileList: function (fileList) {
			this.fileList = fileList;
		},
	}
}

</script>

<style>
  .img-list{
    display: flex;
    flex-flow: wrap;
    padding: 10px 20px;
    justify-content:space-between;
  }
  .img-list li{
    width: 45%;
    list-style: none;
    margin-right: 10px;
  }
  .img-list li.last{
    margin-right: 0px;
  }
  .img-list li img{
    width: 100%;
  }
  .img-list li.add-file {
    cursor: pointer;
    width: 110px;
    height: 110px;
    float: left;
    position: relative;
    top:2px;
    background: url('../../assets/img/add-file.png');
  }
  .img-list li.add-file .upload-img {
    display: inline-block;
    width: 100%;
    height: 100%;
    filter: alpha(opacity=0);
    -moz-opacity: 0;
    -khtml-opacity: 0;
    opacity: 0;
  }

  .delete-uploaded-img {
    margin-top: 6px;
    text-align: center;
  }

  .delete-uploaded-img span {
    cursor: pointer;
    font-size: 18px;
    color: red;
  }


</style>
