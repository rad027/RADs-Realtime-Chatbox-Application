<template>
	    <div class="direct-chat-messages">
	        <div v-for="message in messages" :class=" !message.type ? isEqualtoUser(message.user.info.id) ? 'direct-chat-msg right' : 'direct-chat-msg ' : 'direct-chat-msg ' ">
	            <div v-if="!message.type" :class=" isEqualtoUser(message.user.info.id) ? 'direct-chat-infos clearfix' : 'direct-chat-infos clearfix text-truncate'">
	                <span :class=" isEqualtoUser(message.user.info.id) ? 'direct-chat-name float-right neon-effect neon-'+message.user.extra.neon_color : 'direct-chat-name float-left text-truncate neon-effect neon-'+message.user.extra.neon_color " @click="viewUser(message.user.extra.display_name)" style="cursor: pointer">{{ message.user.extra.display_name }}</span>
	                <span :class=" isEqualtoUser(message.user.info.id) ? 'mr-2 text-muted float-right' : 'ml-2 text-muted'">[{{ message.user.role.name }}]</span>
	                <span :class=" isEqualtoUser(message.user.info.id) ? 'mr-2 float-right' : 'ml-2'">[<i class="fab fa-facebook-f text-primary" aria-hidden="true" v-if="message.user.info.provider=='facebook'" v-b-tooltip.hover data-toggle="tooltip" data-placement="top" title="Logged in using Facebook"></i><i class="fab fa-twitter text-success" aria-hidden="true" v-else v-b-tooltip.hover data-toggle="tooltip" data-placement="top" title="Logged in using Twitter"></i>]</span>
	                <span v-b-tooltip.hover :title="'Sent since '+message.created_at" :class=" isEqualtoUser(message.user.info.id) ? 'direct-chat-timestamp float-left tfm-time' : 'direct-chat-timestamp float-right tfm-time' " :data-unix-time="message.created_at">...</span>
	            </div>
	            <img v-if="!message.type && isEqualtoUser(message.user.info.id)" class="direct-chat-img" :src=" message.user.extra.avatar ? message.user.extra.avatar : '/images/icon-user.png' " alt="Message User Image">
	            <div v-else-if="!isEqualtoUser(message.user.info.id) && !message.type" class="direct-chat-img">
		            <div class="direct-chat-buttons text-center">
						<div class="dropdown">
	            		  <img class="direct-chat-img" :src=" message.user.extra.avatar ? message.user.extra.avatar : '/images/icon-user.png' " alt="Message User Image" id="dropdownMenuButton" data-toggle="dropdown">	
						  <div class="dropdown-menu text-left rounded-0 no-shadow" aria-labelledby="dropdownMenuButton">
						    <a class="dropdown-item" href="#" @click.prevent="viewUser(message.user.extra.display_name)">View Profile</a>
						    <a class="dropdown-item" href="#">Report</a>
						  </div>
						</div>		            	
		            </div>
	            </div>
	            <div v-if="!message.type" class="direct-chat-text" v-b-tooltip.hover data-toggle="tooltip" data-placement="top" title="Double tap to reply" :data-to-name="message.user.extra.display_name" :data-from-name="getFromname()" v-on:dblclick="uwuReply">
					<blockquote :class=" isEqualtoUserName(message.replied.to_name) ? 'quote-secondary text-dark bg-warning text-truncate' : 'quote-secondary text-dark text-truncate' " v-if="message.replied.status == 1" style="line-height: 3px;opacity : 0.6">
					    <p>
						    {{ isEqualtoUserName(message.replied.from_name) ? 'You' : message.replied.from_name }} 
						    replied to 
						    {{ isEqualtoUserName(message.replied.to_name) ? message.replied.from_name != message.replied.to_name ? 'You' : 'own message' : message.replied.from_name == message.replied.to_name ? 'own message' : message.replied.to_name }}
						</p>
					    <small class="text-truncate"><i v-html="replaceTag((message.replied.text))"></i></small>
					</blockquote>					
	                <span class="msgtext " v-html="replaceTag(escapeHTML(message.message))"></span>
	            </div>
	            <div v-else class="direct-chat-text annc text-center" style="margin:5px 0px 0px 0px">
	            	<span class="msgtext " v-html="replaceTag((message.message))"></span>
	            </div>
	        </div>        
	        <div id="notif-sound"></div>
	        <div class="d-flex justify-content-center">
	        	<button class="btn btn-sm btn-primary my-1 mx-auto" @click.prevent="getMoreMessage">LOAD MORE MESSAGE</button>
	        </div>
	    </div>
</template>

<style type="text/css">
	div.annc:before, div.annc:after {
		display : none!important;
	}
</style>

<script>
	export default {
		data(){
			return {
				answer : ''
			}
		},
		props : [ 'messages' ],
		mounted(){
			setTimeout(function(){
				$('div.loader-photo').fadeOut();
			},1500);
			
		},
		watch : {
				messages : function(newVal,oldVal){
					if(this.checkMentioned(newVal[0].message,'@'+app.user.extra.display_name)){
						this.playSound('beef');
					}
				}
		},
		methods : {		
		  getFromname(){
		  	return app.user.extra.display_name;
		  },
	      uwuReply (event){
	      	var uwu = {
	      		status : 1,
	      		text : $(event.currentTarget).find('span.msgtext').html(),
	      		to_name : event.currentTarget.getAttribute('data-to-name'),
	      		from_name : event.currentTarget.getAttribute('data-from-name')
	      	};
	        this.$emit('rplyclicked',uwu);
	        $('input[name="msg"]').focus();
	      },
			isEqualtoUser : function(var1){
				return var1 === app.user.info.id;
			},
			isEqualtoUserName : function(var1){
				return var1 === app.user.extra.display_name
			},
			checkMentioned : function(text,word){
			    var x = 0, y=0;
			    for (var i=0;i< text.length;i++)
			        {
			        if(text[i] == word[0])
			            {
			            for(var j=i;j< i+word.length;j++)
			               {
			                if(text[j]==word[j-i])
			                  {
			                    y++;
			                  }
			                if (y==word.length){
			                    x++;
			                }
			            }
			            y=0;
			        }
			    }
			   return x > 0 ? true : false;
			},
			playSound : function(filename){
		        var mp3Source = '<source src="/notif/' + filename + '.mp3" type="audio/mpeg">';
		        var oggSource = '<source src="/notif/' + filename + '.ogg" type="audio/ogg">';
		        var embedSource = '<embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3">';
		        document.getElementById("notif-sound").innerHTML='<audio autoplay="autoplay">' + mp3Source + oggSource + embedSource + '</audio>';
	        },
	        replaceTag : function(text){
	        	text = text.replace(/(?:^|\s)(@)(?!\.)(?!\S*\.\.)(?!\S*\.[\s|$])([a-z0-9\.]+)(?=\s|$)/gm,' <a class="rounded text-white" style="background-color : rgba(189, 195, 199,0.6); padding: 2px; cursor:pointer" onclick="app.viewUser(\'$2\')">@$2</a> ');
	        	text = text.replace(/(#\w+)/gm,' <a href="#" class="rounded text-white" style="text-decoration : underline">$1</a> ');
	        	text = Emoji.toImage(text);
				return text;       	
	        },
	        escapeHTML : function(string){
			  var entityMap = {
			    "&": "&amp;",
			    "<": "&lt;",
			    ">": "&gt;",
			    '"': '&quot;',
			    "'": '&#39;',
			    "/": '&#x2F;'
			  };
			  return String(string).replace(/[&<>"'\/]/g, function (s) {
			    return entityMap[s];
			  });
			},
			getMoreMessage(){
				this.$toasted.clear();
				var loading  = this.$toasted.show('Loading...');
				axios.post('/chat/messages/more', { last_id : app.last_id }).then( response => {
					loading.goAway();
					if(response.data.status==1){
						app.last_id = response.data.text[response.data.text.length - 1].id;
						for(var i = 0; i < response.data.text.length; i++){
							app.messages.push(response.data.text[i]);
						}
						this.$toasted.success('Load successful', { duration : 500 });
					}else if(response.data.status==2){
						this.$toasted.error(response.data.text, { duration : 1000 });
					}else{
						this.$toasted.error(response.data.text, { duration : 1000 });
					}
				});
			}	,
			viewUser(username){
				app.viewUser(username);
			}
		}
	};
    (function TimeoutFunction(){
        liveTime(TimeoutFunction);
    })();
    function liveTime(selfTimeout) {

            $('.tfm-time').each(function() {

                var msgTime = $(this).attr('data-unix-time');
                msgTime = Date.parse(msgTime)/1000;
                var time = Math.round(new Date().getTime() / 1000) - msgTime;

                var day = Math.round(time / (60 * 60 * 24));
                var week = Math.round(day / 7);
                var remainderHour = time % (60 * 60 * 24);
                var hour = Math.round(remainderHour / (60 * 60));
                var remainderMinute = remainderHour % (60 * 60);
                var minute = Math.round(remainderMinute / 60);
                var second = remainderMinute % 60;

                var currentTime = new Date(msgTime*1000);
                var currentHours = ( currentTime.getHours() > 12 ) ? currentTime.getHours() - 12 : currentTime.getHours();
                var currentHours = ( currentHours == 0 ) ? 12 : currentHours;
                var realTime = currentHours+':'+currentTime.getMinutes();
                var timeOfDay = ( currentTime.getHours() < 12 ) ? "AM" : "PM";

                if(day > 7) {
                    var timeAgo = currentTime.toLocaleDateString();
                } else if(day>=2 && day<=7) {
                    var timeAgo =  day+' days ago';
                } else if(day==1) {
                    var timeAgo =  'Yesterday '+realTime+' '+timeOfDay;
                } else if(hour>1) {
                    var timeAgo =  hour+' hours ago';
                } else if(hour==1) {
                    var timeAgo =  'about an hour ago';
                } else if(minute==1) {
                    var timeAgo =  'about a minute ago';
                } else if(minute>1) {
                    var timeAgo =  minute+' minutes ago';
                } else if(second>=10 && second<=30) {
                    var timeAgo =  'lest than a minute ago';
                } else {
                    var timeAgo =  'few seconds ago';
                }

                $(this).html(timeAgo);
            });
            setTimeout(selfTimeout,1000);
        }  
</script>