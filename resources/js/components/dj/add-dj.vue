<template>
	<form method="post" @submit.prevent="Add_DJ">
		<div class="row">
			<div class="col-lg-6">
				<div class="form-group">
					<label>User :</label>
					<select class="form-control" name="user" required></select>
				</div>
			</div>
			<div class="col-lg-6">
				<label>DJ Name :</label>
				<input v-model="dj_name" type="text" name="dj_name" class="form-control" required placeholder="Enter DJ Name here">
			</div>
			<div class="col-lg-12">
				<button class="btn btn-outline-success" type="submit rounded-0">Add</button>
			</div>
		</div>
	</form>
</template>

<script>
	export default {
		props : [ 'lists' ],
		data() {
			return {
				user : 0,
				dj_name : '',
			}
		},
		created() {
			this.$parent.loading = false;
		},
		methods : {
			Add_DJ(){
				this.$parent.loading = true;
				this.user = $('select[name="user"]').val();
				var load = this.$toasted.show('Loading...');
				axios.post('/dj/list/add', { user_id : this.user, dj_name : this.dj_name }).then( response => {
					this.$parent.loading = false;
					load.goAway();
					if(response.data.status==1){
						this.$parent.lists.unshift(response.data.text);
						$('select[name="user"], input[name="dj_name"]').val('').trigger('change');
						this.$toasted.success('DJ has been added.', { duration : 1500 });
					}else{
						this.$toasted.error(response.data.text, { duration : 1500 });
					}
				});
			}
		}
	}
</script>