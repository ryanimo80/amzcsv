<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.4/css/bulma.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.7/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<style type="text/css">
    .has-background-blue{
        background-color: blue;
    }
    .has-background-green{
        background-color: green;
    }    
    .has-background-red{
        background-color: red;
    }    
    .has-background-navy{
        background-color: navy;
    }    
    .has-background-royal{
        background-color: #27579F;
    }    
    .has-background-sapphia{
        background-color: #0184B4;
    } 
    .has-background-sky{
        background-color: #85C6E0;
    } 
    .has-background-berry{
        background-color: #832251;
    }                             
</style>    

    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content">
                <div class="container">

<nav class="navbar" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <a class="navbar-item" href="https://bulma.io">
      <img src="https://bulma.io/images/bulma-logo.png" width="112" height="28">
    </a>

    <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>

  <div id="navbarBasicExample" class="navbar-menu">
    <div class="navbar-start">
      <a class="navbar-item" href="/">
        Home
      </a>

      <a class="navbar-item" href="/teezily/scan/">
        Teezily Scan T-Shirt
      </a>

      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          Amazon CSV
        </a>

        <div class="navbar-dropdown">
          <a class="navbar-item" href="/amz/listing">
            Listing CSV
          </a>
          <a class="navbar-item" href="/amz/">
            Upload PNGs
          </a>
          <a class="navbar-item" href="/amz/profile">
            Create new profile
          </a>
          <a class="navbar-item" href="/amz/keyword">
            Create new keyword
          </a>
          <a class="navbar-item" href="/amz/brandmanager">
            Brand Manager
          </a>
          <a class="navbar-item" href="/amz/clearqueue">
            Clear Queue
          </a>
          <hr class="navbar-divider">
        </div>
      </div>
    </div>

  </div>
</nav>

<!--                     <div class="box">
                        <a class="button" href="/amz/">Upload PNG</a>
                        <a class="button" href="/amz/profile">Create new profile</a>
                        <a class="button" href="/amz/keyword">Create new keyword</a>
                        <a class="button" href="/amz/export">Export CSV</a>                        
                        <a class="button" href="/amz/clearqueue" onclick="javascript:return confirm('Do you want to clear?')">Clear Queue</a>
                        </div> -->
<h1 class="title">
<?php echo $title ?>
</h1>

@if ($message_type == 1)
<article class="message is-success">
  <div class="message-body">
    {{ $subtitle }}
  </div>
</article>

@endif
@if ($errors->any())
<article class="message is-danger">
  <div class="message-body">
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
  </div>
</article>    
@endif


                    @yield('content')
                </div>
            </div>
        </div>
    </body>
</html>
