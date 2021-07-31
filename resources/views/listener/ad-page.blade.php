@extends('listener.master')

@section('name', auth()->user()->firstname)

@section('content')
         <div id="content-wrapper">
            <div class="container-fluid pb-0">
               <div class="video-block section-padding">
                  <div class="row">
                     <div class="col-md-8">
                     <div class="single-video-left">
                           <div class="single-video">

                              <img style="display: flex; margin: 0 auto 10px auto;" height="250" src="{{ asset('img/v6.png') }}" alt=""></img>
                              <audio style="width: 100%; margin: 0 auto 10px auto;" width="1000px" height="315" controls autoplay>
                                    <source src="{{ asset('storage/'. $audio_advert['0']->content ?? '') }}" type="audio/mpeg">
                              </audio>
                           </div>
                           <div class="single-video-title box mb-3">
                              <h2><a>Advertisement</a></h2>
                           </div>
                           <div class="single-video-author box mb-3">
                              <div class="float-right"><a href="/listener/media/{{$id}}"><button class="btn btn-danger" type="button">Skip Ad</button></a> <button class="btn btn btn-outline-danger" type="button"><i class="fas fa-thumbs-up"></i></button></div>
                              <img class="img-fluid" src="{{ asset('img/user.png') }}" alt=""/>
                              <p><a href=""><strong>Aminaami Advert</strong></a> <span title="" data-placement="top" data-toggle="tooltip" data-original-title="Verified"><i class="fas fa-check-circle text-success"></i></span></p>
                              <small>Posted on {{$audio_advert['0']->created_at->format('g:ia \o\n l jS F Y') ?? ''}}</small>
                           </div>
                           <div class="single-video-info-content box mb-3">
                             
                              <h6>About :</h6>
                              <p>{{$audio_advert['0']->description ?? ''}} </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="single-video-right">
                           <div class="row">
                              <div class="col-md-12">
                              @if(isset($img_adverts['0']))
                                 <div style="background-image: url({{ asset('storage/'. $img_adverts['0']->content ?? '') }}); background-repeat: no-repeat; background-size: cover;" class="adblock">
                                    <div class="img">
                                       <br>
                                    </div>
                                 </div>
                                 @endif
                                 <div class="main-title">
                                    <div class="btn-group float-right right-action">
                                       <a href="#" class="right-action-link text-gray" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       Sort by <i class="fa fa-caret-down" aria-hidden="true"></i>
                                       </a>
                                       <div class="dropdown-menu dropdown-menu-right">
                                          <a class="dropdown-item" href="#"><i class="fas fa-fw fa-star"></i> &nbsp; Top Rated</a>
                                          <a class="dropdown-item" href="#"><i class="fas fa-fw fa-signal"></i> &nbsp; Viewed</a>
                                          <a class="dropdown-item" href="#"><i class="fas fa-fw fa-times-circle"></i> &nbsp; Close</a>
                                       </div>
                                    </div>
                                    @if(isset($upNexts) && $upNexts != null)
                                    <h6>Up Next</h6>
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <div class="video-card video-card-list">

                                 
                                    @foreach($upNexts as $audio)
                                       <div class="video-card video-card-list">
                                          <div class="video-card-image">
                                             <a class="play-icon" href="/listener/ad/{{$audio->id}}"><i class="fas fa-play-circle"></i></a>
                                             <a href="/listener/ad/{{$audio->id}}"><img class="img-fluid" src="{{ asset('storage/'.$audio->thumbnail ?? '') }}" alt=""></a>
                                             <div class="time"></div>
                                          </div>
                                          <div class="video-card-body">
                                             <div class="video-title">
                                                <a href="/listener/ad/{{$audio->id}}">{{ $audio->title ?? ''}}</a>
                                             </div>
                                             <div class="video-page text-success">
                                             {{$audio->channel->title}} <a title="" data-placement="top" data-toggle="tooltip" href="/listener/ad/{{$audio->id}}" data-original-title="Verified"><i class="fas fa-check-circle text-success"></i></a>
                                             </div>
                                             <div class="video-view">
                                             {{count(json_decode($audio->views))}} views &nbsp;<i class="fas fa-calendar-alt"></i> {{$audio->created_at->format('M d Y') ?? ''}}
                                             </div>
                                          </div>
                                       </div>
                                    @endforeach
                                 @endif

                                 @if(isset($img_adverts['1']))
                                 <div style="background-image: url({{ asset('storage/'. $img_adverts['1']->content ?? '') }} ); background-repeat: no-repeat; background-size: cover;" class="adblock mt-0">
                                    <div class="img">
                                       
                                       <br>
                                    </div>
                                 </div>
                                 @endif
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- /.container-fluid -->
            <!-- Sticky Footer -->
            <!-- <footer class="sticky-footer">
               <div class="container">
                  <div class="row no-gutters">
                  <div class="col-lg-6 col-sm-6">
                        <p class="mt-1 mb-0"><strong class="text-dark">Melodi</strong> by Aminaami
                        </p>
                     </div>
                     <div class="col-lg-6 col-sm-6 text-right">
                        <div class="app">
                           <a href="#"><img alt="" src="{{ asset('img/google.png') }}"></a>
                           <a href="#"><img alt="" src="{{ asset('img/apple.png') }}"></a>
                        </div>
                     </div>
                  </div>
               </div>
            </footer> -->
         </div>
         <!-- /.content-wrapper -->
      </div>
      <!-- /#wrapper -->
      <!-- Scroll to Top Button-->
      <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
      </a>
      <!-- Logout Modal-->
      <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                  </button>
               </div>
               <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
               <div class="modal-footer">
                  <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                  <a class="btn btn-primary" href="/listener/logout">Logout</a>
               </div>
            </div>
         </div>
      </div>
      @endsection   