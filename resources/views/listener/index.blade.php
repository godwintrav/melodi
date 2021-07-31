@extends('listener.master')
   @section('index', 'active') 
      @section('name', auth()->user()->firstname)
      @section('searchVal', $input ?? '')

      @section('content')
         <div id="content-wrapper">
            <div class="container-fluid pb-0">
               <div class="top-mobile-search">
                  <div class="row">
                     <div class="col-md-12">   
                        <form method="post" action="/listener/search" class="mobile-search">
                           <div class="input-group">
                           {{ csrf_field()}}
                             <input type="text" name="search" placeholder="Search for..." class="form-control">
                               <div class="input-group-append">
                                 <button type="submit" class="btn btn-dark"><i class="fas fa-search"></i></button>
                               </div>
                           </div>
                        </form>   
                     </div>
                  </div>
               </div>
               <div class="top-category section-padding mb-4">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="main-title">
                           <div class="btn-group float-right right-action">
                              <a href="#" class="right-action-link text-gray" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                              </a>
                              <div class="dropdown-menu dropdown-menu-right">
                                 <a class="dropdown-item" href="#"><i class="fas fa-fw fa-star"></i> &nbsp; Top Rated</a>
                                 <a class="dropdown-item" href="#"><i class="fas fa-fw fa-signal"></i> &nbsp; Viewed</a>
                                 <a class="dropdown-item" href="#"><i class="fas fa-fw fa-times-circle"></i> &nbsp; Close</a>
                              </div>
                           </div>
                           <h6>Channels </h6>
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="owl-carousel owl-carousel-category">
                           
                           @if(isset($channels) && $channels != null)
                              @foreach($channels as $channel)
                                 <div class="item">
                                    <div class="category-item">
                                       <a href="/listener/viewChannel/{{$channel->id}}">
                                          <img class="img-fluid" src="{{ asset('storage/'.$channel->img ?? '') }}" alt="">
                                          <h6>{{ $channel->title }} <span title="" data-placement="top" data-toggle="tooltip" data-original-title="Verified"><i class="fas fa-check-circle text-success"></i></span></h6>
                                          <!-- <p>74,853 views</p> -->
                                       </a>
                                    </div>
                                 </div>
                              @endforeach
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
               <hr>
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
                           <h6>Featured Songs</h6>
                        </div>
                     </div>
                     @if(isset($medias))
                        @foreach($medias as $media)
                        <div class="col-xl-3 col-sm-6 mb-3">
                           <div class="video-card">
                              <div class="video-card-image">
                                 <a class="play-icon" href="/listener/ad/{{$media->id}}"><i class="fas fa-play-circle"></i></a>
                                 <a href="/listener/ad/{{$media->id}}"><img class="img-fluid" src="{{ asset('storage/'.$media->thumbnail ?? '') }}" alt=""></a>
                                 <div class="time"></div>
                              </div>
                              <div class="video-card-body">
                                 <div class="video-title">
                                    <a href="/listener/ad/{{$media->id}}">{{ $media->title ?? ''}}</a>
                                 </div>
                                 <div class="video-page text-success">
                                    {{$media->channel->title}} <a title="" data-placement="top" data-toggle="tooltip" href="#" data-original-title="Verified"><i class="fas fa-check-circle text-success"></i></a>
                                 </div>
                                 <div class="video-view">
                                    {{count(json_decode($media->views))}} views &nbsp;<i class="fas fa-calendar-alt"></i> {{$media->created_at->format('M d Y') ?? ''}}
                                 </div>
                              </div>
                           </div>
                        </div>
                        @endforeach
                     @else
                     <div class="col-xl-3 col-sm-6 mb-3">
                        <h4 style="text-align: center;">No Song Found</h4>
                     </div>
                        

                     @endif
                     
                  </div>
               </div>
               
               
            </div>
            <!-- /.container-fluid -->
            <!-- Sticky Footer -->
            <!-- <footer class="sticky-footer">
               <div class="container">
                  <div class="row no-gutters">
                     <div class="col-lg-6 col-sm-6">
                        <p class="mt-1 mb-0"><strong class="text-dark">Vidoe</strong>. 
                           <small class="mt-0 mb-0"><a class="text-primary" target="_blank" href="https://www.templatespoint.net/">Templates Point</a>
                           </small>
                        </p>
                     </div>
                     <div class="col-lg-6 col-sm-6 text-right">
                        <div class="app">
                           <a href="#"><img alt="" src="{{ asset('admin/img/google.png') }}"></a>
                           <a href="#"><img alt="" src="{{ asset('admin/img/apple.png') }}"></a>
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