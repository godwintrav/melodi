@extends('listener.master')

   @section('name', auth()->user()->firstname)
   @section('channels', 'active')

      

      @section('content')
         <div class="single-channel-page" id="content-wrapper">
            <div class="single-channel-image">
               <img class="img-fluid" alt="" src="{{ asset('img/channel-banner.png') }}">
               <div class="channel-profile">
                  <img class="channel-profile-img" alt="" src="{{ asset('storage/'.$channel->img ?? '') }}">
                  <div class="social hidden-xs">
                     Social &nbsp;
                     <a class="fb" >Facebook</a>
                     <a class="tw" >Twitter</a>
                     <a class="gp" >Google</a>
                  </div>
               </div>
            </div>
            <div class="single-channel-nav">
               <nav class="navbar navbar-expand-lg navbar-light">
                  <a class="channel-brand" href="#">{{$channel->title ?? ''}} Channel <span title="" data-placement="top" data-toggle="tooltip" data-original-title="Verified"><i class="fas fa-check-circle text-success"></i></span></a>
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                     <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                           <a class="nav-link" href="#">Songs <span class="sr-only">(current)</span></a>
                        </li>
                     </ul>
                     <form style="display: none;" class="form-inline my-2 my-lg-0">
                        <input class="form-control form-control-sm mr-sm-1" type="search" placeholder="Search" aria-label="Search"><button class="btn btn-outline-success btn-sm my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button> &nbsp;&nbsp;&nbsp; <button class="btn btn-outline-danger btn-sm" type="button">Subscribe <strong>1.4M</strong></button>
                     </form>
                  </div>
               </nav>
            </div>
            <div class="container-fluid">
               <div class="video-block section-padding">
                  <div class="row">
                     <div class="col-md-12">
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
                           <h6>Songs</h6>
                        </div>
                     </div>
                     @if(isset($channel))
                        @foreach($channel->medias()->get() as $media)
                              <div class="col-xl-3 col-sm-6 mb-3">
                                 <div class="video-card">
                                    <div class="video-card-image">
                                       <a class="play-icon" href="/listener/ad/{{$media->id}}"><i class="fas fa-play-circle"></i></a>
                                       <a href="/listener/ad/{{$media->id}}"><img class="img-fluid" src="{{ asset('storage/'.$media->thumbnail ?? '') }}" alt=""></a>
                                       <div class="time"></div>
                                    </div>
                                    <div class="video-card-body">
                                       <div class="video-title">
                                          <a href="/listener/ad/{{$media->id}}">{{$media->title}}</a>
                                       </div>
                                       <div class="video-page text-success">
                                          {{$media->channel->title}}  <a title="" data-placement="top" data-toggle="tooltip" href="/listener/media/{{$media->id}}" data-original-title="Verified"><i class="fas fa-check-circle text-success"></i></a>
                                       </div>
                                       <div class="video-view">
                                       {{count(json_decode($media->views))}} views &nbsp;<i class="fas fa-calendar-alt"></i> {{$media->created_at->format('M d Y') ?? ''}}
                                       </div>
                                    </div>
                                 </div>

                              </div>
                        @endforeach
                     @endif
                    
                  </div>
               </div>
            </div>
            <!-- /.container-fluid -->
            <!-- Sticky Footer -->
            <!-- <footer class="sticky-footer ml-0">
               <div class="container">
                  <div class="row no-gutters">
                     <div class="col-lg-6 col-sm-6">
                         <p class="mt-1 mb-0"><strong class="text-dark">Vidoe</strong>. 
                           <small class="mt-0 mb-0"><a target="_blank" href="https://www.templateshub.net">Templates Hub</a>
                           </small>
                        </p>
                     </div>
                     <div class="col-lg-6 col-sm-6 text-right">
                        <div class="app">
                           <a href="#"><img alt="" src="img/google.png"></a>
                           <a href="#"><img alt="" src="img/apple.png"></a>
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