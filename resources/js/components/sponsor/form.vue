<template>
	<div>
	 	<form method="post">
	 		<div class="form-group">
	 			<label>Select Image :</label>
				<div class="input-group">
					<div class="custom-file">
						<input name="sponsor_image" type="file" class="custom-file-input text-truncate rounded-0" id="exampleInputFile" accept="image/x-png,image/gif,image/jpeg" @change="changeSponsorFile">
						<label class="custom-file-label fimages_label text-truncate rounded-0" for="exampleInputFile">{{ form.label }}</label>
					</div>
				</div>	
	 		</div>
	 		<button v-if="work" class="btn btn-success rounded-0 mb-1" type="submit" @click.prevent="submitMona">START UPLOAD</button>
	 	</form>
	 	<div class="row results">
	 		<div class="col-12 preview-image" v-if="form.file != ''">
	 			<img :src="form.label" class="" width="350px" height="500px" >
	 			<div class="progress mt-1" style="width: 350px"><div class="pkuppbr progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;">0%</div></div>
	 		</div>
	 	</div>
	</div>
</template>

<script>
	export default {
		data(){
			return {
				work : false,
				form : {
					file : '',
					label : 'Choose File'
				}
			}
		},
		methods : {
			changeSponsorFile : function(e){
				this.form.label = URL.createObjectURL(e.currentTarget.files[0]);
				this.form.file = e.currentTarget.files[0];
				this.work = true;
			},
			submitMona : function(){
				var load = this.$toasted.show('Uploading...');
				let data = new FormData();
				data.append('image', this.form.file);
				axios.post('/site/images/sponsor', data, {
		          headers : {
		            'Content-Type' : 'multipart/form-data'
		          },
		          onUploadProgress : function(progressEvent){
		            const totalLength = progressEvent.lengthComputable ? progressEvent.total : progressEvent.target.getResponseHeader('content-length') || progressEvent.target.getResponseHeader('x-decompressed-content-length');
		            if (totalLength !== null) {
		                var t = Math.round( (progressEvent.loaded * 100) / totalLength );
		                $('div.pkuppbr').css("width", t + "%").html(t+"%");
		                if(t > 99){
		                  $('div.pkuppbr').addClass('bg-success');
		                }
		            }
		          }
		    	}).then( res => {
		    		console.log(res.data);
				});
			}
		}
	}
</script>