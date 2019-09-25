<template>
	<span class="update-hashtag">
        <blockquote class="blockquote p-1 m-1" v-for="(hash,wii) in hashtags">
          <p class="mb-0" style="font-size: 14px">{ {{ wii + 1 }} }&nbsp;<a href="#">{{ hash.hash }}</a></p>
          <footer class="blockquote-footer" style="font-size: 11px">Posts/Chats : <cite>{{ hash.total }}</cite></footer>
        </blockquote>
	</span>
</template>
<script>
	export default {
		props : [ 'hashtags' ]
	}
</script>