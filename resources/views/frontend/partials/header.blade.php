<header>
    <div class="container d-flex align-items-center justify-content-between">
        <!--logo start-->
        <a href="index.html" class="sm_logo">
            <img src="{{ asset('frontend/img/sm_logo.png') }}" class="img-fluid" alt="">
        </a>
        <!--menu start-->
        <ul id="menu">
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="/dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/logout">Logout</a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="/login">Login</a>
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