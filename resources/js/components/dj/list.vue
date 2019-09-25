<template>
	<table class="table table-striped bundle">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>DJ Name</th>
				<th>Added By</th>
				<th>Created At</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody class="djList">
			<tr v-if="lists.length > 0" v-for="(list,i) in lists" :data-id="'list-'+i">
				<td>
					<center><img class="d-flex justify-content-center align-items-center" :src="list.avatar" width="50px"></center>
				</td>
				<td><span class="d-flex align-items-center" v-html="list.dj_name"></span></td>
				<td><span class="d-flex align-items-center" v-html="list.added_by"></span></td>
				<td><span class="d-flex align-items-center">{{ list.created_at }}</span></td>
				<td>
					<button v-if="$can('dj-update')" class="btn btn-sm btn-outline-warning rounded-0" type="button" @click.prevent="editDj(i,list.id,list.dj_name)">EDIT</button>
					<button v-if="$can('dj-delete')" class="btn btn-sm btn-outline-danger rounded-0" type="button" @click.prevent="deleteDj(i,list.id)">DELETE</button>
				</td>
			</tr>
			<tr v-else>
				<td colspan="5" class="p-0">
					<div class="m-0 alert alert-danger">
						No dj on the list yet.sad
					</div>
				</td>
			</tr>
		</tbody>
	</table>	
</template>

<script>
	export default {
		props : [ 'lists' ],
		created(){
			this.getList();
		},
		methods : {
			editDj : function(i,id,name){
				if(this.$can('dj-update')==false){
					this.$toasted.error('You are not allowed to do this process.');
					return false;
				}
				var jeez = this;
				var load = jeez.$toasted.show('Loading...');
				axios.post('/dj/info', { id : id }).then( response => {
					load.goAway();
					if(response.data.status == 1){
						var x = '';
							x+= '<form class="updateDjForm">';
								x+= '<div class="form-group">';
									x+= '<label>DJ Name :</label>';
									x+= '<input type="text" name="dj_name" class="form-control dj_name" placeholder="Enter DJ Name here." value="'+response.data.text+'" required>';
								x+= '</div>';
								x+= '<button class="btn btn-sm btn-success" type="submit">SAVE</button>';
							x+= '</form>';
						var d = bootbox.dialog({
							title : 'Editing ['+name+']',
							message : x
						});
						$('form.updateDjForm').submit(function(){
							var l = jeez.$toasted.show('Updating...');
							var dj_name =  $('input.dj_name').val();
							axios.post('/dj/update', { id : id, dj_name : dj_name }).then( uwu => {
								l.goAway();
								if(uwu.data.status==1){
									jeez.getList();
									d.modal('hide');
									jeez.$toasted.success(name+' has been updated.', { duration : 1500 });
								}else{
									jeez.$toasted.error(uwu.data.text, { duration : 1500 });
								}
							});
							return false;
						});
					}else{
						jeez.$toasted.error(response.data.text, { duration : 1500 });
					}
				});
			},
			getList : function(){
				axios.post('/dj/list').then( response => {
					if(response.data.status==1){
						this.$parent.lists = '';
						this.$parent.lists = response.data.text;
					}else{
						this.$parent.lists = [];
					}
				});
			},
			deleteDj : function(i,id){
				if(this.$can('dj-delete')==false){
					this.$toasted.error('You are not allowed to do this process.');
					return false;
				}
				var jeez = this;
				bootbox.confirm({
		            message: "Are you sure you want to delete this DJ?",
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
						axios.post('/dj/delete', { id : id }).then( response => {
							load.goAway();
							if(response.data.status==1){
								jeez.$parent.lists.splice(i,1);
								jeez.$toasted.success('You have successfully deleted a dj.', { duration : 1500 });
							}else{
								jeez.$toasted.error(response.data.text, { duration : 1500 });
							}
						});
		              }
		            }
				});
			}
		}
	}
</script>