@extends('layouts.app-b')

@section('template_title', strtoupper($username).'`s Profile')

@section('content')
<div class="card card-widget widget-user border-0 rounded-0 no-shadow mt-2">
    <!-- Add the bg color to the header using any of the bg-* classes -->
    <div class="widget-user-header text-white p-5 rounded-0" style="background: url('{{ empty($profile->extra->cover_photo) ? 'https://i.imgur.com/tNDY2Do.jpg' : $profile->extra->cover_photo  }}') top center no-repeat;">
        <h3 class="widget-user-username text-right" style="text-shadow : 0px 1px #000">{{ $profile->info->name }}</h3>
        <h5 class="widget-user-desc text-right">{{ $profile->role->name }}</h5>
    </div>
    <div class="widget-user-image">
        <img class="img-circle lazy-load" style="width: 90px;height: 90px" data-src="{{ empty($profile->extra->avatar) ? 'https://i.imgur.com/tNDY2Do.jpg' : $profile->extra->avatar  }}" alt="User Avatar">
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-sm-6 border-right">
                <div class="description-block">
                    <h5 class="description-header">{{ $profile->info->chats()->count() }}</h5>
                    <span class="description-text">CHATS</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
                <div class="description-block">
                    <h5 class="description-header">0</h5>
                    <span class="description-text">POSTS</span>
                </div>
                <!-- /.description-block -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
</div>
<div class="row my-3">
    <div class="col-md-3">

        <!-- Profile Image -->
        <div class="card card-primary card-outline rounded-0 no-shadow">
            <div class="card-body box-profile">
            	@if(Auth::user()->name == $profile->info->name)
                <a href="{{ url('settings') }}" class="btn btn-primary btn-block"><b><i class="fas fa-cogs"></i> Settings</b></a>
            	@endif
                <a href="#" class="btn btn-primary btn-block"><b><i class="fas fa-user-plus"></i> Follow</b></a>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- About Me Box -->
        <div class="card card-primary rounded-0 no-shadow">
            <h5 class="card-header">About</h5>
            <!-- /.card-header -->
            <div class="card-body">
                <strong><i class="fas fa-user mr-1"></i> Display Name</strong>

                <p class="text-muted">
                    {{ $profile->extra->display_name }}
                </p>

                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                <p class="text-muted">{{ $profile->extra->location }}</p>

                <hr>

                <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                <p class="text-muted">
                    {{ $profile->extra->skill }}
                </p>

                <hr>

                <strong><i class="far fa-file-alt mr-1"></i> Bio</strong>

                <p class="text-muted">{{ $profile->extra->bio }}</p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="card rounded-0">
            <div class="card-header p-2 rounded-0">
                <ul class="nav nav-pills">
                    {{--<li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>--}}
                    <li class="nav-item"><a class="nav-link active" href="#timeline" data-toggle="tab">Timeline</a></li>
                </ul>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    {{--<div class="tab-pane" id="activity">
                        <!-- Post -->
                        <div class="post">
                            <div class="user-block">
                                <img class="img-circle img-bordered-sm" data-src="../../dist/img/user1-128x128.jpg" alt="user image">
                                <span class="username">
                          <a href="#">Jonathan Burke Jr.</a>
                          <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                        </span>
                                <span class="description">Shared publicly - 7:30 PM today</span>
                            </div>
                            <!-- /.user-block -->
                            <p>
                                Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.
                            </p>

                            <p>
                                <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                                <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                                <span class="float-right">
                          <a href="#" class="link-black text-sm">
                            <i class="far fa-comments mr-1"></i> Comments (5)
                          </a>
                        </span>
                            </p>

                            <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                        </div>
                        <!-- /.post -->

                        <!-- Post -->
                        <div class="post clearfix">
                            <div class="user-block">
                                <img class="img-circle img-bordered-sm" data-src="../../dist/img/user7-128x128.jpg" alt="User Image">
                                <span class="username">
                          <a href="#">Sarah Ross</a>
                          <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                        </span>
                                <span class="description">Sent you a message - 3 days ago</span>
                            </div>
                            <!-- /.user-block -->
                            <p>
                                Lorem ipsum represents a long-held tradition for designers, typographers and the like. Some people hate it and argue for its demise, but others ignore the hate as they create awesome tools to help create filler text for everyone from bacon lovers to Charlie Sheen fans.
                            </p>

                            <form class="form-horizontal">
                                <div class="input-group input-group-sm mb-0">
                                    <input class="form-control form-control-sm" placeholder="Response">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-danger">Send</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.post -->

                        <!-- Post -->
                        <div class="post">
                            <div class="user-block">
                                <img class="img-circle img-bordered-sm" data-src="../../dist/img/user6-128x128.jpg" alt="User Image">
                                <span class="username">
                          <a href="#">Adam Jones</a>
                          <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                        </span>
                                <span class="description">Posted 5 photos - 5 days ago</span>
                            </div>
                            <!-- /.user-block -->
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <img class="img-fluid" data-src="../../dist/img/photo1.png" alt="Photo">
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <img class="img-fluid mb-3" data-src="../../dist/img/photo2.png" alt="Photo">
                                            <img class="img-fluid" data-src="../../dist/img/photo3.jpg" alt="Photo">
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-6">
                                            <img class="img-fluid mb-3" data-src="../../dist/img/photo4.jpg" alt="Photo">
                                            <img class="img-fluid" data-src="../../dist/img/photo1.png" alt="Photo">
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.row -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                            <p>
                                <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                                <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                                <span class="float-right">
                          <a href="#" class="link-black text-sm">
                            <i class="far fa-comments mr-1"></i> Comments (5)
                          </a>
                        </span>
                            </p>

                            <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                        </div>
                        <!-- /.post -->
                    </div>--}}
                    <!-- /.tab-pane -->
					<div class="tab-pane active" id="timeline">
					    <!-- The timeline -->
					    <div class="timeline timeline-inverse">

					        @if($profile->timeline->count())
					        @php
					        	$tm = $profile->timeline->orderBy('timelines.created_at','desc')->paginate(10);
					        	$lastdate = date('Y-d-M',(time() + (60*60*24)));
					        @endphp
						        @foreach($tm as $tl)
							        @if($lastdate != date('Y-d-M',strtotime($tl->created_at)))
								        <!-- timeline time label -->
								        <div class="time-label">
								            <span class="bg-danger">
								                {{ date('Y-d-M',strtotime($tl->created_at)) == date('Y-d-M',time()) ? 'Today' : date('M d, Y',strtotime($tl->created_at)) }}
								            </span>
								        </div>
								        <!-- /.timeline-label -->
							        @endif
							        <!-- timeline item -->
							        <div>
							            <i class="fas fa-{{ config('app.tl_type.'.$tl->type.'.icon') }} bg-{{ config('app.tl_type.'.$tl->type.'.status') }}"></i>

							            <div class="timeline-item">
							                <span class="time"><i class="far fa-clock"></i> <span class="tfm-time" data-unix-time="{{ $tl->created_at }}">...</span></span>

							                <h3 class="timeline-header">{!! $tl->title !!}</h3>
							                @if(!empty($tl->content))
							                <div class="timeline-body">
							                    {!! $tl->content !!}
							                </div>
							                @endif
							            </div>
							        </div>
							        <!-- END timeline item -->
							        @php $lastdate = date('Y-d-M',strtotime($tl->created_at)); @endphp
						        @endforeach
						    @else
						    @php $tm = []; @endphp
					        @endif
					        <div>
					            <i class="far fa-clock bg-gray"></i>
					        </div>
					    </div>
					    <div class="my-3">
					        {!! count($tm) ? $tm->links() : '' !!}
					    </div>
					</div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
</div>
@stop

@section('js')
<script type="text/javascript">
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
@stop