<div class="row p-0 m-0 no-gutters">
    <div class="col-lg-3 pr-lg-2">
        <button class="btn btn-block btn-primary mt-3 rounded-0 no-shadow" @click.prevent="requestSong">
          REQUEST A SONG
        </button>
        <div class="card card-secondary mt-3 rounded-0 no-shadow border-0">
          <h5 class="card-header rounded-0">Online</h5>
          <div class="card-body online-wrapper p-1">
            <online-users :onlines="onlines"></online-users>
          </div>
        </div>
    </div>
    <div class="col-lg-6 pr-lg-2">
        <div class="card my-3 rounded-0 no-shadow border-0">
            <div class="card-body p-0">
                <chat-form :reply="replied" v-on:messagesent="addMessage" :members="members" v-on:clearmsg="clearMsgs"></chat-form>
                <chat-message v-on:rplyclicked="freply" :messages="messages"></chat-message>
            </div>
        </div>
    </div>
    <div class="col-lg-3">  
    	
    </div>
</div>