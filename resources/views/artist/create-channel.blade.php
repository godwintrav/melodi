@extends('artist.master')

   @section('name', auth()->user()->firstname)
   @section('create_channel', 'active')


   @section('content')
   
         <div id="content-wrapper">
            <div class="container-fluid upload-details">
                     <form action="/artist/create_channel" method="post" enctype="multipart/form-data">
                     <div class="row">
                        <div class="col-lg-12">
                           <div class="main-title">
                              <h4>Create Channel</h4>
                           </div>
                        </div>
                        <div class="col-lg-10">
                           <div class="osahan-title">Enter details for Channel</div>
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-lg-12">
                           <div class="osahan-form">
                              <div class="row">
                                 <div class="col-lg-12">
                                    
                                    <div class="form-group">
                                       <label for="e1">Channel Name</label>
                                       <input required type="text" placeholder="Enter channel name" id="e1" class="form-control" name="title">
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
                                       <textarea placeholder="Enter Description" required rows="3" id="e2" class="form-control" name="description"></textarea>
                                    </div>
                                    @error('description')
                                       <span >
                                       <p style="color: red;">{{ $message }}</p>
                                       </span>
                                    @enderror
                                    <div class="col-lg-12">
                                    
                                    <div class="form-group">
                                       <label for="e1">Channel Profile Picture</label>
                                       <input required type="file" placeholder="Select Channel Image" id="e1" class="form-control" name="img">
                                    </div>
                                    @error('img')
                                       <span >
                                       <p style="color: red;">{{ $message }}</p>
                                       </span>
                                    @enderror
                                 </div>

                                    @if(isset($err_msg))
                                       <span >
                                       <p style="color: red;">{{ $err_msg }}</p>
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
                           <div class="terms text-center">
                              <p class="mb-0">There are many variations of passages of Lorem Ipsum available, but the majority <a href="#">Terms of Service</a> and <a href="#">Community Guidelines</a>.</p>
                              <p class="hidden-xs mb-0">Ipsum is therefore always free from repetition, injected humour, or non</p>
                           </div>
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