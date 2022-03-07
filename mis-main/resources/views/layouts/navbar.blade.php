<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <!-- <div class="container"> -->
    <a href="../../index3.html" class="navbar-brand">
        <img src="{{ asset('/img/emblem.png') }}" alt="Mitracomm Logo" class="brand-image img-circle elevation-3"
                    style="opacity: .8">
        <b>MitraComm</b>
    </a>
    
    <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            @foreach (session('navbar') as $item)
            <li class="nav-item">
            <a href="{{ $item->url }}" class="nav-link menu1" id="{{ $item->id }}"><i class="{{ $item->icon }}"></i> {{ $item->name }} 
                {!! $icon = ($item->count>0) ? '<i class="fa fa-sort-down"></i>':'' !!}
            </a>
            </li>              
            @endforeach

        </ul>
    </div>

    <!-- Right navbar links -->
    <ul class="order-1 order-md-3 navbar-nav">
        <li class="user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="{{ asset('/img/user.png') }}" class="user-image" alt="User Image">
                <span class="hidden-xs">{{ session('user')[0]->full_nm }}</span>
            </a>
            <ul class="dropdown-menu">
                <li class="user-header">
                        <img src="{{ asset('/img/user.png') }}" class="img-circle" alt="User Image">
                        <p>
                            {{ session('user')[0]->full_nm }}
                                <small>{{ session('user')[0]->email }}</small>
                        </p>
                </li>
                <li class="user-footer">
                    <div class="float-left" >
                    <a href="{{route('employee.profile',['empl_id' => base64_encode(session('user')[0]->id)])}}" class="btn btn-default btn-flat">Edit Profile</a>
                    </div>
                    <div class="float-right">
                        <a class="btn btn-default btn-flat" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </li>
    </ul>
    <!-- </div> -->
</nav>
    
<nav class="main-header navbar navbar-expand-md navbar-light navbar-white" style="display:none" id="navbar2">
    <div class="container"> 
        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item" >
                    <a href="#" class="nav-link" id="up2"><i class="fa fa-level-up-alt"></i> </a>
                </li>  
                @foreach (session('navbar2') as $item)
                <li class="nav-item {{ $item->menu_id }} menus" style="display:none">
                    <a href="{{ $item->url }}" class="nav-link menu2" id="{{ $item->id }}"><i class="{{ $item->icon }}"></i> {{ $item->name }} 
                        {!! $icon = ($item->count>0) ? '<i class="fa fa-sort-down"></i>':'' !!}
                    
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div> 
</nav>
    
<nav class="main-header navbar navbar-expand-md navbar-light navbar-white" style="display:none" id="navbar3">
    <div class="container"> 
        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item" >
                    <a href="#" class="nav-link" id="up3"><i class="fa fa-level-up-alt"></i> </a>
                </li>  
                @foreach (session('navbar3') as $item)
                <li class="nav-item {{ $item->menu_id }} menus2" style="display:none">
                    <a href="{{ $item->url }}" class="nav-link menu3 " id="{{ $item->id }}"><i class="{{ $item->icon }}"></i> {{ $item->name }}
                        {!! $icon = ($item->count>0) ? '<i class="fa fa-sort-down"></i>':'' !!}
                    
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div> 
</nav>
    
@push('script')
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
    </script>
@endpush