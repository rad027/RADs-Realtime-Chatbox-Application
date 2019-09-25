<template>
	<div class="card-body p-2">
		<form method="post">
			<div class="form-group">
				<label>Question :</label>
				<input type="text" name="question" class="form-control rounded-0" placeholder="Enter Question here." required v-model="question">
			</div>
			<div class="form-group">
				<label>Answer :</label>
				<input type="text" name="answer" class="form-control rounded-0" placeholder="Enter Answer here." required v-model="answer">
			</div>
			<div class="form-group">
				<label>Reward Chat Points :</label>
				<input type="number" name="reward" class="form-control rounded-0" placeholder="Enter Reward chat point here." required v-model="point">
			</div>
			<button class="btn btn-success rounded-0" type="submit" @click.prevent="createQ">ADD</button>
		</form>
	</div>
</template>

<script>
	export default {
		data(){
			return {
				question : '',
				answer : '',
				point : 0
			}
		},
		methods : {
			createQ : function(){
				var jeez = this;
				var data = {
					question : this.question,
					answer : this.answer,
					point : this.point
				}
				var load = jeez.$toasted.show('Loading...');
				axios.post('/cbox/trivia', data).then( res => {
					load.goAway();
					if(res.data.status==1){
						this.question = '';
						this.answer = '';
						this.point = 0;
						jeez.$parent.trivia_list.unshift(res.data.data);
						jeez.$toasted.success('New Trivia added successfully.', { duration : 1500 });
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
	}
</script>