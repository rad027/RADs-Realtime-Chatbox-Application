/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
window.CryptoJS = require("crypto-js");
import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);
import Toasted from 'vue-toasted';
Vue.use(Toasted);
import Emoji from 'emoji-toolkit';
window.Emoji = Emoji;
import { EmojiPickerPlugin } from 'vue-emoji-picker';
Vue.use(EmojiPickerPlugin);
import VueLazyload from 'vue-lazyload';
Vue.use(VueLazyload);
import Permissions from './mixins/Permissions';
Vue.mixin(Permissions);
$.ajaxSetup({
  headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
});
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('chat-form', require('./components/chatForm.vue').default);
Vue.component('chat-message', require('./components/chatMessage.vue').default);
Vue.component('update-hashtag', require('./components/hashtag.vue').default);
Vue.component('online-users', require('./components/online.vue').default);
Vue.component('request-song', require('./components/requestSong.vue').default);
//dj vue
Vue.component('add-dj', require('./components/dj/add-dj.vue').default);
Vue.component('dj-list', require('./components/dj/list.vue').default);
//fansign vue
Vue.component('fs-list', require('./components/fansigns/list.vue').default);
//sponsor vue
Vue.component('sponsor-form', require('./components/sponsor/form.vue').default);
//trivia vue
Vue.component('trivia-form', require('./components/trivia/form.vue').default);
Vue.component('trivia-list', require('./components/trivia/list.vue').default);
Vue.component('modal-body', {
  template: '#modal-body'
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
window.app = new Vue({
    el: '#app',
    data : {
      replied : {
          status : 0,
          to_name : '',
          from_name : '',
          text : ''
      },  
      userInfo : [],
      messages : [],
      hashtags : [],
      onlines : [],
      last_id  : 0,
      members : [ 'ra.dasalla', 'dhan.dasalla', 'roldhan.pogi' ],
      url_cover : null,
      url_dp : null,
      url_cover_old : null,
      url_dp_old : null,
      data : [],
      loading : true,
      lists : [],
      onboard : {
        dj_id : 0,
        dj_name : 'Auto Tune',
        dj_avatar : '/images/icon-user.png',
        dj_tag : 'No dj is online.'
      },
      images : [],
      images_link : [],
      images_fs : [],
      images_uploaded_fs : [],
      trivia_list : []
    },
    mounted(){
      this.triggerTable();
    },
    methods : {
      triggerTable() {
        $('table.bundle').DataTable({
          order: ['0', 'desc']
        }); 
      },
      showModal() {
        this.isModalVisible = true;
      },
      closeModal() {
        this.isModalVisible = false;
      },
      freply(event){
        this.replied = {
          status : event.status,
          text : event.text,
          to_name : event.to_name,
          from_name : event.from_name
        };
      },
      addMessage(message){
        this.messages.unshift(message);
        axios.post('/chat/send', message).then(response => {
          if(response.data.status != 1){
            this.$toasted.error(response.data.text, { duration : 1500 });
          }
        });
      },
      getMessage(){
        axios.get('/chat/messages').then( response => {
          if(response.data.status==1){
            this.last_id = response.data.text[response.data.text.length - 1].id;
            this.messages = response.data.text;
          }else{
            this.messages = [];
          }
        });
      },
      getHashtag(){
        axios.post('/hashtag/fetch').then( response => {
          if(response.data.status == 1){
            this.hashtags = response.data.text;
          }else{
            this.hashtags = [];
          }
        });
      },
      getArtwork(y){
        axios.get('https://itunes.apple.com/search?term='+y+'&country=PH&media=music&limit=1').then( response => {
          if(response.data.resultCount > 0){
            var x = response.data.results[0].artworkUrl100;
            x = x.split('/');
            x.splice(-1,1);
            x.push('400x400.jpg');
            x = x.join('/');
          }else{
            var x = 'https://static.gigwise.com/gallery/5209864_8262181_JasonDeruloTatGall.jpg';
          }
          return x;
        });
      },
      getOnline(){
        axios.post('/online/users').then( response => {
          if(response.data.status==1){
            this.onlines = response.data.onlines;
          }else{
            this.onlines = [];
          }
        });
      },
      clearMsgs(){
        this.messages = '';
        var dialog = bootbox.dialog({ message: '<div class="text-center"><i class="fa fa-spin fa-spinner"></i> Loading...</div>', closeButton:false });
        this.getMessage();
        setTimeout(function(){
          dialog.modal('hide');
        },1500);
      },
      requestSong() {
        var x = '';
            x+=  '<form class="rsForm">';
              x+=  '<div class="form-group">';
                x+=  '<label>Artist :</label>';
                x+=  '<input type="text" name="artist" class="form-control rounded-0 no-shadow" placeholder="E.g : Red Velvet" required>';
              x+=  '</div>';
              x+=  '<div class="form-group">';
                x+=  '<label>Song Name :</label>';
                x+=  '<input type="text" name="song" class="form-control rounded-0 no-shadow" placeholder="E.g : Zimzalabim" required>';
              x+=  '</div>';
              x+=  '<div class="form-group">';
                x+=  '<button class="btn btn-sm btn-outline-warning rounded-0 mr-1" type="reset">CLEAR</button>';
                x+=  '<button class="btn btn-sm btn-outline-success rounded-0 rs-submit" type="submit">SUBMIT</button>';
              x+=  '</div>';
            x+=  '</form>';
            x+=  '';
            x+=  '';
        var dialog = bootbox.dialog({
            title: 'Request a Song',
            message: x
        });    
        var jeez = this;
        $('form.rsForm').submit(function(){
          var load = jeez.$toasted.show('Loading...'),
              artist = $(this).serialize();
          $('input[name="artist"],input[name="song"],button.rs-submit').attr('disabled',true);
          axios.post('/request/song', artist).then( response => {
            load.goAway();
            dialog.modal('hide');
            if(response.data.status==1){
              jeez.$toasted.success('Request sent.', { duration : 2000 });
            }else{
              jeez.$toasted.error(response.data.text, { duration : 2000 });
            }
          });
          return false;
        });    
      },
      removeAllRequest(){
        var jeez = this;
        bootbox.confirm({
            message: "Are you sure you want to mark all song request as unavailable?",
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
                axios.post('/request/song/clear').then( response => {
                  loading.goAway();
                  if(response.data.status==1){
                    jeez.data = [];
                    jeez.$toasted.success(response.data.text, { duration : 1500 });
                  }else{
                    jeez.$toasted.error(response.data.text, { duration : 1500 });
                  }
                });
              }
            }
        });
      },
      getRData(){
        axios.post('/request/song/list').then( response =>{
          if(response.data.status==1){
            this.data=response.data.text;
          }else{
            this.data = [];
            this.$toasted.error(response.data.text, { duration : 1500 });
          }
        });
      },
      getRequestHistory(){
        var jeez = this;
        if(!jeez.$can('rs-list-history')){
          jeez.$toasted.error('You are not allowed to do it.', { duration : 1500 });
          return false;
        }
        var load = jeez.$toasted.show('Loading...');
              axios.post('/request/song/history').then( response => {
                load.goAway();
                if(response.data.status==1){
                  var x = '';
                      x+= '<div class="table-responsive">';
                      x+= '<table class="table table-striped rs-history bundle">';
                        x+= '<thead>';
                          x+= '<tr>';
                            x+= '<th>ID</th>';
                            x+= '<th>ARTIST/SONG NAME</th>';
                            x+= '<th>REQUESTED BY</th>';
                            x+= '<th>STATUS</th>';
                            x+= '<th>CREATED AT</th>';
                          x+= '</tr>';
                        x+= '</thead>';
                        x+= '<tbody class="historyRequest">';
                  if(response.data.status==1){
                    var l = response.data.text.length;
                    for(var i = 0; i < response.data.text.length; i++){
                      x+= '<tr>';
                        x+= '<td>'+(l)+'</td>';
                        x+= '<td>'+response.data.text[i].artist+'</td>';
                        x+= '<td>'+response.data.text[i].name+'</td>';
                        x+= response.data.text[i].status == 1 ? '<td><span class="bg-success p-2">PLAYED</span></td>' : '<td><span class="bg-danger p-2">UNAVAILABLE</span></td>';
                        x+= '<td>'+response.data.text[i].created_at+'</td>';
                      x+= '</tr>';
                      l--;
                    }
                  }else{
                    x+= '<tr>';
                      x+= '<td colspan="5" class="text-center">NO HISTORY OF REQUEST YET</td>';
                    x+= '</tr>';
                  }
                        x+= '</tbody>';
                      x+= '</table>';
                      x+= '</div>';
                  var dialog = bootbox.dialog({
                      size : 'large',
                      title: 'Request Song History',
                      message: '...'
                  });    
                  dialog.find('.modal-body').html(x);
                  $('table.rs-history').DataTable({
                    order: ['0', 'desc']
                  });
                }else{
                  jeez.$toasted.error(response.data.text, { duration : 1500 });
                }
              });
      },
      onFileChange_cover(e) {
        const file = e.target.files[0];
        this.url_cover = URL.createObjectURL(file);
        $('label.cover_label').html(this.url_cover);
      }   ,
      onFileChange_dp(e) {
        const file = e.target.files[0];
        this.url_dp = URL.createObjectURL(file);
        $('label.dp_label').html(this.url_dp);
      } ,
      clearPPProcess(){
        $('label.cover_label').html('Choose File');
        $('label.dp_label').html('Choose File');
        this.url_cover = this.url_cover_old;
        this.url_dp = this.url_dp_old;
      },
      viewUser(username){
        var load = this.$toasted.show('Loading...');;
        axios.post('/user/info', { username : username }).then( response => {
          load.goAway();
          if(response.data.status==1){
            var x = '';
                x+= '';
                x+= '<div class="card card-widget widget-user border-0 rounded-0 no-shadow">';
                  if(response.data.text.extra.cover_photo){
                    x+= '<div class="widget-user-header text-white p-5 rounded-0" style="background: url('+ response.data.text.extra.cover_photo +') top center no-repeat;">';
                  }
                  else{
                    x+= '<div class="widget-user-header text-white p-5 rounded-0" style="background: url(https://i.imgur.com/tNDY2Do.jpg) top center no-repeat;">';
                  }
                  
                        x+= '<h3 class="widget-user-username text-right" style="text-shadow : 0px 1px #000">'+ escapeHTML(response.data.text.info.name) +'</h3>';
                        x+= '<h5 class="widget-user-desc text-right">'+ response.data.text.roles.name +'</h5>';
                    x+= '</div>';
                    x+= '<div class="widget-user-image">';
                        if(response.data.text.extra.avatar){
                          x+= '<img class="img-circle" style="width: 90px;height: 90px" src="'+ response.data.text.extra.avatar +'" alt="User Avatar">';
                        }else{
                          x+= '<img class="img-circle" style="width: 90px;height: 90px" src="https://i.imgur.com/tNDY2Do.jpg" alt="User Avatar">';
                        }
                    x+= '</div>';
                    x+= '<div class="card-footer">';
                        x+= '<div class="row">';
                            x+= '<div class="col-sm-6 border-right">';
                                x+= '<div class="description-block">';
                                    x+= '<h5 class="description-header">'+response.data.text.c_count+'</h5>';
                                    x+= '<span class="description-text">CHATS</span>';
                                x+= '</div>';
                            x+= '</div>';
                            x+= '<div class="col-sm-6">';
                                x+= '<div class="description-block">';
                                    x+= '<h5 class="description-header">'+response.data.text.extra.chat_points+'</h5>';
                                    x+= '<span class="description-text">CHAT POINTS</span>';
                                x+= '</div>';
                            x+= '</div>';
                            x+= '<div class="col-sm-12 border-top  border-bottom">';
                                x+= '<div class="description-block">';
                                    x+= '<h5 class="description-header">DISPLAY NAME</h5>';
                                    x+= '<span class="description-text neon-effect neon-'+response.data.text.extra.neon_color+'">'+escapeHTML(response.data.text.extra.display_name)+'</span>';
                                x+= '</div>';
                            x+= '</div>';
                            x+= '<div class="col-sm-12 border-bottom">';
                                x+= '<div class="description-block">';
                                    x+= '<h5 class="description-header">Location</h5>';
                                    x+= '<span class="description-text">'+escapeHTML(response.data.text.extra.location)+'</span>';
                                x+= '</div>';
                            x+= '</div>';
                            x+= '<div class="col-sm-12 border-bottom">';
                                x+= '<div class="description-block">';
                                    x+= '<h5 class="description-header">Skills</h5>';
                                    x+= '<span class="description-text">'+escapeHTML(response.data.text.extra.skill)+'</span>';
                                x+= '</div>';
                            x+= '</div>';
                            x+= '<div class="col-sm-12 border-bottom">';
                                x+= '<div class="description-block">';
                                    x+= '<h5 class="description-header">Bio</h5>';
                                    x+= '<span class="description-text">'+escapeHTML(response.data.text.extra.bio)+'</span>';
                                x+= '</div>';
                            x+= '</div>';
                            x+= '<div class="col-sm-12">';
                                x+= '<a class="btn btn-block btn-outline-primary rounded-0" href="/profile/'+response.data.text.extra.display_name+'">VIEW PROFILE</a>';
                            x+= '</div>';
                        x+= '</div>';
                    x+= '</div>';
                x+= '</div>';
            var dialog = bootbox.dialog({
                title: 'Viewing User ['+ escapeHTML(response.data.text.info.name) +']',
                message: x
            });  
            dialog.find('.modal-body').addClass('p-0');  
          }else{
            this.$toasted.error(response.data.text, { duration : 1500 });
          }
        });
      },
      onBoardUpdate(){
        if(this.$can('onboard-update')==false){
          this.$toasted.error('You dont have permission here.');
          return false;
        }
        var load = this.$toasted.show('Loading...'),
            dj_id = $('select[name="dj"]').val(),
            tagline = $('input[name="tagline"]').val();
        axios.post('/onboard/update', { dj : dj_id, tagline : tagline }).then( response => {
          load.goAway();
          if(response.data.status == 1){
            $('select[name="dj"],input[name="tagline"]').val('').trigger('change');
            this.onboard = {
              dj_id : response.data.text.dj_id,
              dj_name : response.data.text.dj_name,
              dj_avatar : response.data.text.dj_avatar,
              dj_tag : response.data.text.dj_tag
            };
            this.$toasted.success('On board has been updated.', { duration : 1500 });
          }else{
            this.$toasted.error(response.data.text, { duration : 1500 });
          }
        });
      },
      getOnBoard(){
        axios.post('/onboard/current').then( response => {
          if(response.data.status==1){
            this.onboard = {
              dj_id : response.data.text.dj_id,
              dj_name : response.data.text.dj_name,
              dj_avatar : response.data.text.dj_avatar,
              dj_tag : response.data.text.dj_tag
            }
          }
        });
      },
      onFileChangeFS : function(e){
        if(e.currentTarget.files.length > 0){
          for(var k = 0; k < e.currentTarget.files.length; k++){
            var ll = URL.createObjectURL(e.currentTarget.files[k]);
            var l = {
              src : ll
            }
            this.images_fs.push(e.currentTarget.files[k]);
            this.images.push(l);
            this.images_link.push(ll);
          }
          $('label.fimages_label').html(this.images_link.join(','));
        }else{
          this.images = [];
        }
      },
      deselectImage : function(i){
        this.images_fs.splice(i,1);
        this.images.splice(i,1);
        this.images_link.splice(i,1);
        $('label.fimages_label').html(this.images_link.join(','));
      },
      submitFSimages : function(){
        let formData = new FormData();
        for(var i = 0; i < this.images_fs.length; i++){
          formData.append('images['+i+']',this.images_fs[i]);
        }
        var jeez = this, x = '';
            x+='';
            x+='<div class="col-12" class="progressingbar-fs">';
              x+='<div class="progress"><div class="pkuppbr progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;">0%</div></div>';
            x+='</div>';        
        $('div.fs-images').prepend(x);
        var load = this.$toasted.show('Loading...');    
        axios.post('/site/images/fansigns', formData, {
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
          load.goAway();
          if(res.data.status==1){
            $('div.progressingbar-fs').remove();
            this.images_fs = [];
            this.images = [];
            this.images_link = [];
            $('input[name="images_file"]').val('').trigger('change');
            $('label.fimages_label').html('Choose Files');
            for(var f = 0; f < res.data.images.length; f++){
              console.log(res.data.images[f]);
              this.images_uploaded_fs.unshift(res.data.images[f]);
            }
            this.$toasted.success('Fansign images has been uploaded successfully.', { duration : 1500 });
          }else{
            $('div.progressingbar-fs').remove();
            this.$toasted.error(res.data.text, { duration : 1500 });
          }
        });
      },
      fetchFS : function(){
        axios.post('/site/images/fansigns/get').then( res =>{
          if(res.data.status==1){
            this.images_uploaded_fs = res.data.images;
          }else{
            this.images_uploaded_fs = [];
          }
        });
      },
      deleteAllMessages : function(){
        var jeez = this;
        bootbox.confirm({
            message: "Are you sure you want to delete all messages?",
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
                  axios.post('/cbox/delete/all/messages').then( res =>{
                    load.goAway();
                    if(res.data.status==1){
                      jeez.$toasted.success(res.data.text, { duration : 1500 });
                    }else{
                      jeez.$toasted.error(res.data.text, { duration : 1500 });
                    }
                  });
                }
            }
        }); 
      },
    }
});
window.escapeHTML = function(string){
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
}