<template>
	<span>
		<span v-for="ol in onlines">
	        <div class="media online-box">
	          <img :src=" ol.avatar ? ol.avatar : '/images/icon-user.png' " class="mr-2" style="width: 45px">
	          <div class="media-body text-truncate">
	            <h5 class="m-0 text-truncate">{{ ol.display_name }}</h5>
	            {{ ol.user.roles[0].name }} 
	            <cite>
	            	<span v-if="ol.status==1" class="bg-success text-white rounded" style="padding: 2px">Online</span>
	            	<span v-else class="bg-warning text-white rounded" style="padding: 2px">Idle</span>
	            </cite>
	          </div>
	        </div>
	        <hr class="my-1" />
		</span>
	</span>
</template>

<script>
	export default {
		props : [ 'onlines' ]
	}
</script>