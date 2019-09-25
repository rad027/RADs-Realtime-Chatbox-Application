<template>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>ID</th>
				<th>QUESTION</th>
				<th>ANSWER</th>
				<th>REWARD POINT</th>
				<th>CREATED DATE</th>
				<th>ACTIONS</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="(l,index) in list">
				<td>{{ l.id }}</td>
				<td>{{ l.question }}</td>
				<td>{{ l.answer }}</td>
				<td>{{ l.point }}</td>
				<td>{{ l.created_date }}</td>
				<td>
					<button v-if="$can('trivia-edit')" class="btn btn-sm btn-warning rounded-0 mr-1 mb-1 default-button" type="button" :data-id="'sd-'+l.id" @click.prevent="editMe(index)">EDIT</button>
					<button v-if="$can('trivia-delete')" class="btn btn-sm btn-danger rounded-0 mr-1 mb-1 default-button" type="button" :data-id="'sd-'+l.id" @click.prevent="deleteMe(index)">DELETE</button>
					<button v-if="$can('trivia-edit')" class="btn btn-sm btn-success rounded-0 mr-1 default-button" :data-id="'sd-'+l.id" type="button" @click.prevent="setDefault(index)">SET DEFAULT</button>
				</td>
			</tr>
		</tbody>
	</table>
</template>

<script>
	export default {
		props : [ 'list' ],
		created(){
			this.getList();
		},
		methods : {
			getList : function(){
				axios.post('/cbox/trivia/list').then( res => {
					if(res.data.status==1){ 
						this.$parent.trivia_list = res.data.data;
						axios.post('/cbox/trivia/default/check').then( res => {
							$('button.default-button').prop('disabled',false);
							$('button[data-id="sd-'+res.data.id+'"]').prop('disabled',true);
						});
					}else{
						this.$toasted.error(res.data.text, { duration : 1500 });
					}
				});
			},
			editMe : function(index){
				var jeez = this;
				var row = jeez.$parent.trivia_list[index];
				var t = '';
					t+= '';
					t+= '<form method="post" class="submitFormTriviaEdit">';
						t+= '<div class="form-group">';
							t+= '<label>Question : </label>';
							t+= '<input type="text" name="question" class="form-control rounded-0 question" placeholder="Enter question here." value="'+row.question+'">';
						t+= '</div>';
						t+= '<div class="form-group">';
							t+= '<label>Answer : </label>';
							t+= '<input type="text" name="answer" class="form-control rounded-0 answer" placeholder="Enter answer here." value="'+row.answer+'">';
						t+= '</div>';
						t+= '<div class="form-group">';
							t+= '<label>Reward Chat Point : </label>';
							t+= '<input type="number" name="reward" step="0.01" class="form-control rounded-0 reward" placeholder="Enter Reward chat point here." value="'+row.point+'">';
						t+= '</div>';
						t+= '<button class="btn btn-sm btn-outline-success rounded-0" type="submit">SAVE</button>';
					t+= '</form>	';				
				var dialog = bootbox.dialog({
					title : 'Edit Trivia ID : '+row.id,
					message : t
				});
				dialog.init(function(){
					$('form.submitFormTriviaEdit').submit(function(){
						var load = jeez.$toasted.show('Loading...');
						var data = {
							id : jeez.$parent.trivia_list[index].id,
							question : $('input.question').val(),
							answer : $('input.answer').val(),
							point : $('input.reward').val()
						}
						axios.post('/cbox/trivia/edit', data).then( res => {
							load.goAway();
							if(res.data.status==1){
								jeez.$parent.trivia_list[index].question = $('input.question').val();
								jeez.$parent.trivia_list[index].answer = $('input.answer').val();
								jeez.$parent.trivia_list[index].point = $('input.reward').val();
								dialog.modal('hide');
								jeez.$toasted.success('Update successfully processed.', { duration : 1500 });
							}else{
								if(Object.prototype.toString.call(res.data.text) === '[object Object]'){
									$.each(res.data.text, function(key,value){
										jeez.$toasted.error(value, { duration : 1500 });
									});
								}else{
									jeez.$toasted.error(res.data.text, { duration : 1500 });
								}
							}
						});
						return false;
					});
				});
			},
			deleteMe : function (index){
				var jeez = this;
				var id = jeez.$parent.trivia_list[index].id;
				bootbox.confirm({
				    message: "Are you sure you want to Trivia ID "+id+"?",
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
							axios.post('/cbox/trivia/delete', { id: id }).then( res =>{
								loading.goAway();
								if(res.data.status==1){
									jeez.$parent.trivia_list.splice(index,1);
									jeez.$toasted.success(res.data.text, { duration : 1500 });
								}else{
									if(Object.prototype.toString.call(res.data.text) === '[object Object]'){
										$.each(res.data.text, function(key,value){
											jeez.$toasted.error(value, { duration : 1500 });
										});
									}else{
										jeez.$toasted.error(res.data.text, { duration : 1500 });
									}
								}
							});
				        }
				    }
				});		
			},
			setDefault : function(index){
				var jeez = this;
				var id = jeez.$parent.trivia_list[index].id;
				bootbox.confirm({
				    message: "Are you sure you want to set Trivia ID "+id+" as default question?",
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
							axios.post('/cbox/tivia/default', { id : id }).then( res =>{
								loading.goAway()
								if(res.data.status==1){
									$('button.default-button').prop('disabled',false);
									$('button[data-id="sd-'+id+'"]').prop('disabled',true);
									jeez.$toasted.success('Trivia ID : <b>'+id+'</b> has been set as default trivia question.', { duration : 1500 });
								}else{
									if(Object.prototype.toString.call(res.data.text) === '[object Object]'){
										$.each(res.data.text, function(key,value){
											jeez.$toasted.error(value, { duration : 1500 });
										});
									}else{
										jeez.$toasted.error(res.data.text, { duration : 1500 });
									}
								}
							});
				        }
				    }
				});	
			}
		}
	}
</script>