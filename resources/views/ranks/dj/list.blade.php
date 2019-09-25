@extends('layouts.app-b')

@section('template_title', 'DJ List')

@section('content')
<div class="mt-3 card card-primary rounded-0 border-0 no-shadow">
	<h5 class="card-header rounded-0">
		Add Dj
	</h5>
	<div class="card-body">
		<add-dj :lists="lists"></add-dj>
	</div>
    <div class="overlay loader-photo" v-if="loading">
    	<i class="fas fa-3x fa-sync-alt"></i>
    </div>
</div>
<div class="mt-3 card card-primary rounded-0 border-0 no-shadow">
	<h5 class="card-header rounded-0">
		Dj List
	</h5>
	<div class="card-body table-responsive">
		<dj-list :lists="lists"></dj-list>
	</div>
</div>
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function(){
	$('select[name="user"]').select2({
		theme : 'bootstrap4',
        placeholder: 'Select User',
        ajax: {
          url: '/dj/fetch/user',
          type : 'POST',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
          	if(data.status==1){
	            return {
	              results:  $.map(data.text, function (item) {
	                    return {
	                        text: item.name,
	                        id: item.id
	                    }
	                })
	            };
          	}else{
          		app.$toasted.error(data.text, { duration : 1500 });
          		return {
          			results : []
          		}
          	}
          },
          cache: true
        }
    });
});
</script>
@stop