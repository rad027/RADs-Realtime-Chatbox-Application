<template>
	<form class="formChatMessage">
	    <div class="input-group border border-primary position-relative">
	        <input type="text" name="msg" class="form-control border-0 rounded-0 no-shadow formTextBox text" placeholder="Send a message...." v-model="newMessage">
	        <div class="input-group-append rounded-0">
			    <emoji-picker @emoji="append" :search="search">
	            	<button @click.stop="clickEvent" slot-scope="{ events: { click: clickEvent } }" slot="emoji-invoker" class="btn btn-outline-secondary rounded-0 no-shadow border-0"  v-b-tooltip.hover data-toggle="tooltip" data-placement="top" title="Emoticons" type="button" id="button-addon2"><i class="fa fa-smile-o" aria-hidden="true"></i></button>
			      <div slot="emoji-picker" slot-scope="{ emojis, insert, display }">
			        <div class="emoji-picker" :style="{ top: '40px', right: '20px' }">
			          <div>
			            <div v-for="(emojiGroup, category) in emojis" :key="category">
			              <h5>{{ category }}</h5>
			              <div class="emojis">
			                <span
			                  v-for="(emoji, emojiName) in emojiGroup"
			                  :key="emojiName"
			                  @click="insert(emoji)"
			                  :title="emojiName"
			                >{{ emoji }}</span>
			              </div>
			            </div>
			          </div>
			        </div>
			      </div>
			    </emoji-picker>       	
	            <button class="btn btn-outline-secondary rounded-0 no-shadow border-0" v-b-tooltip.hover data-toggle="tooltip" data-placement="top" title="Refresh Chatbox" type="button" id="button-addon2" @click.prevent="refreshBox"><i class="fa fa-refresh" aria-hidden="true"></i></button>
	            <button class="btn btn-outline-secondary rounded-0 no-shadow border-0" v-b-tooltip.hover data-toggle="tooltip" data-placement="top" title="Sidebar Tools" type="button" id="button-addon2"><i class="fas fa-th-large" aria-hidden="true"></i></button>
	            <button class="btn btn-outline-secondary rounded-0 no-shadow border-0 btnsend" v-b-tooltip.hover data-toggle="tooltip" data-placement="top" title="Send" @click.prevent="sendMessage" type="submit" id="button-addon2"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
	        </div>
	    </div>
	    <!-- Reply Div -->
	    <div class="w-100 bg-secondary divReplyTo p-2 position-absolute" style="display: none;z-index: 99999">
	    	<span class="float-right" style="cursor: pointer;" @click="removeReply">X</span>
	    	<span>Replying to <b class="name"></b> : </span><div class="text-truncate"></div>
	    	<span class="clearfix"></span>
	    </div>
	    <!-- Reply Div end -->
	</form>

</template>
<script>
	export default {
		props : [ 'reply' ],
		data() {
			return {
				newMessage : '',
				replyInfo : {
					status : 0,
					to_name : '',
					from_name : '',
					text : ''
				},
				search: '',
			}
		},
		watch : {
			reply : function(newVal, oldVal){
				$('div.divReplyTo').addClass('blinkeu').fadeIn();
				$('div.divReplyTo div').html(newVal.text);
				$('b.name').html(newVal.name);
				this.replyInfo = {
					status : newVal.status,
					to_name : newVal.to_name,
					from_name : newVal.from_name,
					text : newVal.text
				};
			}
		},
		created(){
			Echo.channel('update-member').listen('UpdateMembers', (e) => {
				app.members.push(e.name);
			});
		},
		methods : {
		    append(emoji) {
		      this.newMessage += emoji
		    },
			removeReply(){
				$('div.divReplyTo').removeClass('blinkeu');
				$('div.divReplyTo').css({ 'display' : 'none' });
				$('div.divReplyTo div').html('');
				$('b.name').html('');
				this.replyInfo = {
					status : 0,
					to_name : '',
					from_name : '',
					text : ''
				};
			},
            sendMessage() {
                this.newMessage = $('input.formTextBox').val();
                if(this.newMessage.length < 2 || this.newMessage.length==''){
                	var load = this.$toasted.error('You must enter 2 or more letters to send.');
                    $('.text').prop('disabled',true).val('You must enter 2 or more letters to send.');
                    $('.btnsend').prop('disabled',true);
                    setTimeout(function(){
                        $('.text').prop('disabled',false).val('').focus();
                        $('.btnsend').prop('disabled',false);
                        load.goAway();
                    },1000);
                    return false;
                }
                var d = new Date();
                var datenow = ("00" + (d.getFullYear())).slice(2) + "-" + 
                    ("00" + (d.getMonth() + 1)).slice(-2) + "-" + 
                    d.getDate() + " " + 
                    ("00" + d.getHours()).slice(-2) + ":" + 
                    ("00" + d.getMinutes()).slice(-2) + ":" + 
                    ("00" + d.getSeconds()).slice(-2);   
                this.$emit('messagesent', {
                	user : app.user,
                    message: this.newMessage,
                    created_at : datenow,
                    replied : this.replyInfo
                });
                this.newMessage = '';
                this.removeReply();
            },
            refreshBox(){
            	this.$emit('clearmsg');
            }
		}
	}
</script>