<template>
  <div>
  </div>
</template>

<script>

export default {
	created: function () {
		let userData = this.$cookie.get('userData');
		this.userData = userData ? JSON.parse(userData):{};
		if(this.userData.Username) {
			let locationUrl = '/';
			if(this.userData.UserType == 1) {
				if(this.userData.IsAxamine == 0) {
					locationUrl = '/teacher/inReview';
				} else {
					locationUrl = '/teacher/campaign';
        }
      } else if(this.userData.UserType == 2) {
				locationUrl = '/campaign/find';
			}
			this.$router.push(locationUrl);
		} else {
			this.$router.push('/user/register');
    }
	}
}

</script>
