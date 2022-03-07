<!-- Navbar -->
<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <a href="/" class="navbar-brand">
        <img src="{{ asset('/img/emblem.png') }}" alt="MBPS Logo" class="brand-image" style="opacity: .8">
        <span class="brand-text">MBPS</span>
    </a>
    
    <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          @if (session('navbar'))
            @foreach (session('navbar') as $item)
            <li class="nav-item">
              <a href="{!! $item->url !!}" class="nav-link menu1" id="{{ $item->id }}"><i class="{{ $item->icon }}"></i> {{ $item->name }} 
              {!! $icon = ($item->count>0) ? '<i class="fa fa-sort-down"></i>':'' !!}
            </a>
            </li>              
            @endforeach
          @endif
  
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" id="txt"></a>
            </li>
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>

                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <li>
                      <a href="{{ route('mst.employee.create') }}" class="dropdown-item btn btn-sm btn-success modal-show edit" title="Change Password">
                        <i class="fa fa-cogs"></i> {{ __('Change Password') }}
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out-alt"></i> {{ __('Logout') }}
                      </a>

                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          @csrf
                      </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<!-- /.navbar -->
<nav class="main-header navbar navbar-expand-md navbar-light navbar-white" style="display:none; z-index:unset" id="navbar2">
    {{-- <div class="container">  --}}
      <div class="navbar-brand" style="width: 67px"></div>
      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item" >
            <a href="#" class="nav-link" id="up2"><i class="fa fa-level-up-alt"></i> </a>
          </li>
          @if (session('navbar2'))
            @foreach (session('navbar2') as $item)
            <li class="nav-item {{ $item->menu_id }} menus" style="display:none">
              <a href="{{ $item->url }}" class="nav-link menu2" id="{{ $item->id }}"><i class="{{ $item->icon }}"></i> {{ $item->name }} 
                {!! $icon = ($item->count>0) ? '<i class="fa fa-sort-down"></i>':'' !!}
              </a>
            </li>              
            @endforeach
          @endif  
        </ul>
      </div>
    {{-- </div>  --}}
  </nav>
  
  <nav class="main-header navbar navbar-expand-md navbar-light navbar-white" style="display:none; z-index:unset" id="navbar3">
    {{-- <div class="container">  --}}
      <div class="navbar-brand" style="width: 67px"></div>
      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item" >
            <a href="#" class="nav-link" id="up3"><i class="fa fa-level-up-alt"></i> </a>
          </li>
          @if (session('navbar3'))
            @foreach (session('navbar3') as $item)
            <li class="nav-item {{ $item->menu_id }} menus2" style="display:none">
              <a href="{{ $item->url }}" class="nav-link menu3 " id="{{ $item->id }}"><i class="{{ $item->icon }}"></i> {{ $item->name }}
                {!! $icon = ($item->count>0) ? '<i class="fa fa-sort-down"></i>':'' !!}
                
              </a>
            </li>              
            @endforeach
          @endif  
        </ul>
      </div>
    {{-- </div>  --}}
  </nav>
  

  
  @push('scripts')
  <script>
  $('.menu1').click(function (event) {
    var id = $(this).attr('id')
  
    if($("."+id).length){
      $(".menus").hide()
      $("#navbar3").hide()
      $("#navbar2").show()
      $("."+id).show()
    }else{
      $("#navbar2").hide()
      $("#navbar3").hide()
      $("."+id).hide()
    }
  }); 
  
  $('#up2').click(function (event) {
      $("#navbar2").hide()
  }); 
  
  $('.menu2').click(function (event) {
    var id = $(this).attr('id')
  
    if($("."+id).length){
      $(".menus2").hide()
      $("#navbar2").hide()
      $("#navbar3").show()
      $("."+id).show()
    }else{
      $("#navbar3").hide()
      $("."+id).hide()
    }
  }); 
  $('#up3').click(function (event) {
      $("#navbar3").hide()
      $("#navbar2").show()
  }); 

  function startTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('txat').innerHTML =
    h + ":" + m + ":" + s;
    var t = setTimeout(startTime, 500);
  }
  function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
  }

  var serverTime = <?php echo time() * 1000; ?>; //this would come from the server
var localTime = +Date.now();
var timeDiff = serverTime - localTime;

setInterval(function () {
    var realtime = +Date.now() + timeDiff;
    var date = new Date(realtime);
    // hours part from the timestamp
    var hours = date.getHours();
    // minutes part from the timestamp
    var minutes = date.getMinutes();
    // seconds part from the timestamp
    var seconds = date.getSeconds();

    // will display time in 10:30:23 format
    var formattedTime = hours + ':' + minutes + ':' + seconds;

    $('#txt').html(formattedTime);
}, 1000);
  </script>    
  @endpush