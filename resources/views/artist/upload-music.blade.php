@extends('artist.master')

   @section('name', auth()->user()->firstname)
   @section('upload_music', 'active')


   @section('content')
   
         <div id="content-wrapper">
            <div class="container-fluid upload-details">
               <form action="/artist/set_upload_music" method="post" enctype="multipart/form-data">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="main-title">
                        <h6>Upload Details</h6>
                     </div>
                  </div>
                  <div class="col-lg-10">
                     <div class="osahan-title">Enter details for Song</div>
                   </div>
               </div>
               <hr>
               <div class="row">
                  <div class="col-lg-12">
                     <div class="osahan-form">
                        <div class="row">
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="e1">Song Title</label>
                                 <input required type="text" placeholder="Enter Title" id="e1" class="form-control" name="title">
                              </div>
                              @error('title')
                                 <span >
                                 <p style="color: red;">{{ $message }}</p>
                                 </span>
                              @enderror
                           </div>
                           <div class="col-lg-12">
                              <div class="form-group">
                                 <label for="e2">Description</label>
                                 <textarea placeholder="Description" required rows="3" id="e2" class="form-control" name="description"></textarea>
                              </div>
                              @error('description')
                                 <span >
                                 <p style="color: red;">{{ $message }}</p>
                                 </span>
                              @enderror
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-lg-5">
                              <div class="form-group">
                                 <label for="e7">Genre</label>
                                 <input required type="text" placeholder="R&B, POP" name="tags" id="e7" class="form-control" style="width: 1060px;">
                              </div>
                              @error('tags')
                                 <span >
                                 <p style="color: red;">{{ $message }}</p>
                                 </span>
                              @enderror
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-lg-5">
                              <div class="form-group">
                                 <label for="e7">Song Image</label>
                                 <input required type="file" placeholder="Select Thumbnail Image" name="thumbnail" class="form-control" style="width: 1060px;">
                              </div>
                              @error('thumbnail')
                                 <span >
                                 <p style="color: red;">{{ $message }}</p>
                                 </span>
                              @enderror
                              @if(isset($err_msg))
                           <span >
                            <p style="color: red; text-align: center;">{{ $err_msg }}</p>
                           </span>
                           @endif
                           </div>
                        </div>
                        <div class="row category-checkbox">
                        </div>
                     </div>
                     {{ csrf_field()}}
                     <div class="osahan-area text-center mt-3">
                        <button type="submit" name="submit" class="btn btn-outline-primary">Save Changes</button>
                     </div>
                     <hr>
                     <!-- <div class="terms text-center">
                        <p class="mb-0">There are many variations of passages of Lorem Ipsum available, but the majority <a href="#">Terms of Service</a> and <a href="#">Community Guidelines</a>.</p>
                        <p class="hidden-xs mb-0">Ipsum is therefore always free from repetition, injected humour, or non</p>
                     </div> -->
                  </div>
               </div>
               </form>
            </div>
            <!-- /.container-fluid -->
            <!-- Sticky Footer -->
            <!-- <footer class="sticky-footer">
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
                  <a class="btn btn-primary" href="/artist/logout">Logout</a>
               </div>
            </div>
         </div>
      </div>
      @endsection   