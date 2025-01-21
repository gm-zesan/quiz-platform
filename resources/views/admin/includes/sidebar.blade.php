<div class="sidebar sidebar-navigation active">
    <div class="logo_content">
        <a href="{{ route('dashboard') }}" class="logo">
            <img class="logo-icon" src="{{ asset('admin/images/logo.png') }}">
            <div class="logo_name">
                <img style="max-height: 45px; width: 130px; object-fit: contain;"
                    src="{{ asset('admin/images/logo.png') }}" alt="">
            </div>
        </a>
    </div>
    <ul class="nav_list ps-0 scrollbar">
        <li class="category-li">
            <span class="link_names">Dashboard</span>
        </li>
        <li>
            @if(Auth::user()->hasRole('admin'))
                <a href="{{ route('admin.dashboard') }}" class="{{ Route::is('admin.dashboard') ? ' active-focus' : '' }}">
            @elseif (Auth::user()->hasRole('user'))
                <a href="{{ route('dashboard') }}" class="{{ Route::is('dashboard') ? ' active-focus' : '' }}">
            @endif
                <i class="ri-home-4-line"></i>
                <span class="link_names">Dashboard</span>
            </a>
        </li>


        <li class="category-li">
            <span class="link_names">Main</span>
        </li>

        @if(Auth::user()->hasRole('user'))
            <li>
                <a href="{{ route('quizzes.index') }}"
                   class="{{ in_array(Route::currentRouteName(), ['quizzes.index', 'quizzes.create', 'quizzes.edit']) ? 'active-focus' : '' }}">
                    <i class="ri-questionnaire-line"></i>
                    <span class="link_names">My Quizzes</span>
                </a>
            </li>
        @endif
        
        @if(Auth::user()->hasRole('admin'))
            <li>
                <a href="{{ route('admin.quizzes.index') }}"
                   class="{{ in_array(Route::currentRouteName(), ['admin.quizzes.index', 'admin.quizzes.edit', 'admin.quizzes.show']) ? 'active-focus' : '' }}">
                    <i class="ri-questionnaire-line"></i>
                    <span class="link_names">Manage Quizzes</span>
                </a>
            </li>
            

            @canany(['user-list', 'user-create', 'user-edit', 'user-delete', 'role-list', 'role-create', 'role-edit', 'role-delete'])
                <li class="category-li">
                    <span class="link_names">Users</span>
                </li>
            @endcan
            @canany(['user-list', 'user-create', 'user-edit', 'user-delete'])
                <li class="drop-item">
                    <a href="{{ route('admin.users.index') }}"
                        class="{{ in_array(Route::currentRouteName(), ['admin.users.index', 'admin.users.create', 'admin.users.edit']) ? 'active-focus' : '' }}">
                        <i class="ri-user-3-line"></i>
                        <span class="link_names">User List</span>
                    </a>
                </li>
            @endcan
            @canany(['role-list', 'role-create', 'role-edit', 'role-delete'])
                <li class="drop-item">
                    <a href="{{ route('admin.roles.index') }}"
                        class="{{ in_array(Route::currentRouteName(), ['admin.roles.index', 'admin.roles.create', 'admin.roles.edit']) ? 'active-focus' : '' }}">
                        <i class="ri-shield-user-line"></i>
                        <span class="link_names">Role</span>
                    </a>
                </li>
                <li class="drop-item">
                    <a href="{{ route('admin.assign-roles.index') }}"
                        class="{{ in_array(Route::currentRouteName(), ['admin.assign-roles.index']) ? 'active-focus' : '' }}">
                        <i class="ri-user-settings-line"></i>
                        <span class="link_names">Assign Role</span>
                    </a>
                    <span class="tooltip">Assign Role</span>
                </li>
            @endcan
        @endif
    </ul>

    <div class="profile_content">
        <div class="profile">
            <div class="profile_details">
                @if (Auth::user()->image)
                    <img id="sidebarImageDB" src="{{ asset('storage/' . Auth::user()->image) }}" alt="img" width="30"
                        height="30" class="rounded-circle">
                @else
                    <i class="ri-user-3-line profile-icon"></i>
                @endif

                <div class="name_job">
                    <div class="name">{{ Auth::user()->name }}</div>
                    <div class="job">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" class="d-flex"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="ri-logout-box-r-line" id="log_out"></i>
                </a>
            </form>
        </div>
    </div>
</div>
