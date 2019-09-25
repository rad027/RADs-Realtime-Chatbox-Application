<template>
	<div class="card card-primary border-0 rounded-0 no-shadow">
		<h5 class="card-header rounded-0">Uploaded Fansigns</h5>
		<div class="card-body p-1">
			<div class="clearfix">
				<div class="mr-1 mt-1 p-2 float-lg-left float-md-left bg-dark" style="width: 350px;" v-if="images.length > 0" v-for="(img,index) in images" :data-key="img.id">
					<img :src="'/images/'+img.image+'/fansigns'" class="img-fluid">
					<button class="btn btn-block btn-danger rounded-0 mt-1" @click.prevent="deleteFS(img.id,index)">DELETE</button>
				</div>
				<div v-else class="alert alert-danger rounded-0">
					No fansign images has been uploaded yet.
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props : [ 'images' ],
		mounted(){
			this.$parent.fetchFS();
		},
		methods : {
			deleteFS : function(id,index){
				var jeez = this;
				bootbox.confirm({
				    message: "Are you sure you want to Fansign Photo?",
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
				        	var load = jeez.$toasted.show('Loading...');
				        	axios.post('/site/images/fansigns/delete', { id : id }).then( res =>{
				        		load.goAway();
				        		if(res.data.status==1){
				        			jeez.images.splice(index,1);
				        			jeez.$toasted.success(res.data.text, { duration : 1500 });
				        		}else{
				        			jeez.$toasted.error(res.data.text, { duration : 1500 });
				        		}
				        	});
				        }
				    }
				});	
			}
		}
	}
</script>