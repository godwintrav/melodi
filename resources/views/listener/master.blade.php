<!DOCTYPE html>
<html lang="en">
   
<head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="Askbootstrap">
      <meta name="author" content="Askbootstrap">
      <title>Melodi by Aminaami</title>
      <!-- Favicon Icon -->
      <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
      <!-- Bootstrap core CSS-->
      <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
      <!-- Custom fonts for this template-->
      <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
      <!-- Custom styles for this template-->
      <link href="{{ asset('css/osahan.css') }}" rel="stylesheet">
      <!-- Owl Carousel -->
      <link rel="stylesheet" href="{{ asset('vendor/owl-carousel/owl.carousel.css') }}">
      <link rel="stylesheet" href="{{ asset('vendor/owl-carousel/owl.theme.css') }}">
      @yield('pay_links')
   </head>
   <body id="page-top">
   <nav class="navbar navbar-expand navbar-light bg-white static-top osahan-nav sticky-top">
         &nbsp;&nbsp; 
         <button class="btn btn-link btn-sm text-secondary order-1 order-sm-0" id="sidebarToggle">
         <i class="fas fa-bars"></i>
         </button> &nbsp;&nbsp;
         <a class="navbar-brand mr-1" href="index.html">Melodi</a>
         <!-- Navbar Search -->
         <form method="post" action="/listener/search" class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-5 my-2 my-md-0 osahan-navbar-search">
            <div class="input-group">
               <input required type="text" name="search" class="form-control" placeholder="Search for..." value="@yield('searchVal')">
               {{ csrf_field()}}
               <div class="input-group-append">
                  <button class="btn btn-light" type="submit">
                  <i class="fas fa-search"></i> 
                  </button>
               </div>
            </div>
         </form>
         <!-- Navbar -->
         <ul class="navbar-nav ml-auto ml-md-0 osahan-right-navbar">
            <li style="display: none;" class="nav-item mx-1">
               <a class="nav-link" href="upload.html">
               <i class="fas fa-plus-circle fa-fw"></i>
               Upload Video
               </a>
            </li>
            <li style="display: none;" class="nav-item dropdown no-arrow mx-1">
               <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <i class="fas fa-bell fa-fw"></i>
               <span class="badge badge-danger">9+</span>
               </a>
               <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
                  <a class="dropdown-item" href="#"><i class="fas fa-fw fa-edit "></i> &nbsp; Action</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-fw fa-headphones-alt "></i> &nbsp; Another action</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#"><i class="fas fa-fw fa-star "></i> &nbsp; Something else here</a>
               </div>
            </li>
            <li style="display: none;" class="nav-item dropdown no-arrow mx-1">
               <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <i class="fas fa-envelope fa-fw"></i>
               <span class="badge badge-success"></span>
               </a>
               <div class="dropdown-menu dropdown-menu-right" aria-labelledby="messagesDropdown">
                  <a class="dropdown-item" href="#"><i class="fas fa-fw fa-edit "></i> &nbsp; Action</a>
                  <a class="dropdown-item" href="#"><i class="fas fa-fw fa-headphones-alt "></i> &nbsp; Another action</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#"><i class="fas fa-fw fa-star "></i> &nbsp; Something else here</a>
               </div>
            </li>
            <li class="nav-item dropdown no-arrow osahan-right-navbar-user">
               <a class="nav-link dropdown-toggle user-dropdown-link" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <img alt="Avatar" src="{{ asset('img/user.png') }}">
                  @yield('name')
               </a>
               <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">

                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal"><i class="fas fa-fw fa-sign-out-alt"></i> &nbsp; Logout</a>
               </div>
            </li>
         </ul>
      </nav>
      <div id="wrapper">
         <!-- Sidebar -->
         <ul class="sidebar navbar-nav">
            <li class="nav-item @yield('index')" >
               <a class="nav-link" href="/listener/index">
               <i class="fas fa-fw fa-home"></i>
               <span>Home</span>
               </a>
            </li>
            <li class="nav-item @yield('channels')">
               <a class="nav-link" href="/listener/channels">
               <i class="fas fa-fw fa-users"></i>
               <span>Channels</span>
               </a>
            </li>
            <li class="nav-item @yield('playlist')">
               <a class="nav-link" href="/listener/playlist">
               <i class="fas fa-fw fa-list-alt"></i>
               <span>Playlist</span>
               </a>
            </li>
            
            <!-- <li class="nav-item">
               <a class="nav-link" href="history-page.html">
               <i class="fas fa-fw fa-history"></i>
               <span>History Page</span>
               </a>
            </li> -->

            <li class="nav-item">
               <a class="nav-link" href="/listener/logout">
               <i class="fas fa-fw fa-sign-out-alt"></i>
               <span>Logout Out</span>
               </a>
            </li>
            
            <li class="nav-item channel-sidebar-list">
               <h6>{{ auth()->user()->firstname." ".auth()->user()->lastname }}</h6>
            </li>
         </ul>


    @yield('content')



    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
      <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
      <!-- Core plugin JavaScript-->
      <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
      <!-- Owl Carousel -->
      <script src="{{ asset('vendor/owl-carousel/owl.carousel.js') }}"></script>
      <!-- Custom scripts for all pages-->
      <script src="{{ asset('js/custom.js') }}"></script>
   </body>

</html>

