<template>
    <ul class="products-list product-list-in-card pl-2 pr-2" v-if="$can('rs-list')">
        <li class="item" v-if="data.length > 0" v-for="(rs,i) in data" :data-mainid="rs.id">
            <div class="product-img">
                <img :src="getArtworks(rs.artist + ' - ' + rs.song,i)" :data-id="'img-' + i" class="img-size-50">
            </div>
            <div class="product-info">
                <a :href="'https://www.youtube.com/results?search_query='+rs.artist+' - '+rs.song" target="_blank" class="product-title">{{ rs.artist }} - {{ rs.song }}</a>
                <span class="product-description">
                    Requested By : <i><span class="r-name">{{ rs.user.name }}</span></i>
                    <span class="badge badge-danger float-right p-1" style="cursor:pointer;" v-b-tooltip.hover data-toggle="tooltip" data-placement="top" title="Mark as Unavailable"@click.prevent="removeRequest(i,rs.id,rs.artist +' - '+ rs.song,rs.user.name)" v-if="$can('rs-update')"><i class="fas fa-times"></i></span>
                    <span class="badge badge-success float-right p-1 mr-1" style="cursor:pointer;" v-b-tooltip.hover data-toggle="tooltip" data-placement="top" title="Mark as Played" @click.prevent="playRequest(i,rs.id,rs.artist +' - '+ rs.song,rs.user.name)" v-if="$can('rs-update')"><i class="fas fa-play"></i></span>
                </span>
            </div>
        </li>
        <li v-else class="item">
        	<div class="alert alert-danger rounded-0">
        		No requested song yet.
        	</div>
        </li>
    </ul>	
</template>
<script>
	export default {
		props : [ 'data' ],
		mounted(){
			Echo.channel('update-songrequest').listen('UpdateSongRequest', (e) => {
				if(e.status==1){
					this.data.unshift(e.text);
				}
			});
			this.getData();
		},
		methods : {
			getArtworks(y,i){
		        axios.get('https://itunes.apple.com/search?term='+y+'&country=PH&media=music&limit=1').then( response => {
		          if(response.data.resultCount > 0){
		            var x = response.data.results[0].artworkUrl100;
		            x = x.split('/');
		            x.splice(-1,1);
		            x.push('400x400.jpg');
		            x = x.join('/');
		          }else{
		            var x = 'https://cdn1.iconfinder.com/data/icons/style-2-stock/807/iTunes-01.png';
		          }
		          $('img[data-id="img-'+i+'"]').attr('src',x);
		        });
			},
			getData(){
				return this.$parent.getRData();
			},
			removeRequest(i,id,title,name){
				var jeez = this;
				bootbox.confirm({
				    message: "Are you sure you want to mark this song request as Unavailable?",
				    buttons: {
				        confirm: {
				            label: 'Yes',
				            className: 'btn-success'
				        },
				        cancel: {
				            label: 'No',
				            className: 'btn-danger'
				        }
				    },
				    callback: function (result) {
				        if(result==true){
				        	var loading  = jeez.$toasted.show('Loading...');
							axios.post('/request/song/remove', { id : id, name : name, title : title }).then( response => {
								loading.goAway();
								if(response.data.status==1){
									jeez.data.splice(i,1);
									app.$toasted.success('Song request  <b>'+title+'</b> has been marked as Unavailable.', { duration : 1500 });
								}else{
									app.$toasted.error(response.data.text, { duration : 1500 });
								}
							});
				        }
				    }
				});				
			},
			playRequest(i,id,title,name){
				var jeez = this;
				bootbox.confirm({
				    message: "Are you sure you want to mark this song request as being Played?",
				    buttons: {
				        confirm: {
				            label: 'Yes',
				            className: 'btn-success'
				        },
				        cancel: {
				            label: 'No',
				            className: 'btn-danger'
				        }
				    },
				    callback: function (result) {
				        if(result==true){
				        	var loading  = jeez.$toasted.show('Loading...');
							axios.post('/request/song/play', { id : id, name : name, title : title }).then( response => {
								loading.goAway();
								if(response.data.status==1){
									jeez.data.splice(i,1);
									app.$toasted.success('Song request  &nbsp;<b>'+title+'</b>&nbsp; has been marked as being Played.', { duration : 1500 });
								}else{
									app.$toasted.error(response.data.text, { duration : 1500 });
								}
							});
				        }
				    }
				});	
			}
		}
	}
</script>