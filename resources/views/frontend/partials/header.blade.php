<header>
    <div class="container d-flex align-items-center justify-content-between">
        <!--logo start-->
        <a href="{{ route('frontend.home') }}" class="sm_logo">
            <img src="{{ asset('frontend/img/sm_logo.png') }}" class="img-fluid" alt="">
        </a>
        <!--menu start-->
        <ul id="menu">
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="nav-link" href="{{route('logout')}}" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                    </form>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
            @endauth
        </ul>
        <!-- menu toggler -->
        <div class="hamburger-menu">
            <span class="line-top"></span>
            <span class="line-center"></span>
            <span class="line-bottom"></span>
        </div>
    </div>
</header>