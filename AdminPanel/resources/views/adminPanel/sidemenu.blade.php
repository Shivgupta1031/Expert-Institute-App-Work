<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header align-items-center">
            <div class="navbar-brand">
                <img src="{{ asset('images/logo.png') }}" class="navbar-brand-img rounded" alt="...">
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">
                    <span class="error_gradient text-center ml-3 mr-3">Sub Admin</span>
                    <li class="nav-item">
                        @if ($activePage == 'paidCourses')
                            <a class="nav-link active" href="{{ route('paidCourses') }}">
                            @else
                                <a class="nav-link" href="{{ route('paidCourses') }}">
                        @endif
                        <i class="fas fa-rocket text-orange"></i>
                        <span class="nav-link-text">Paid Courses</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        @if ($activePage == 'video_categories')
                            <a class="nav-link active" href="{{ route('video_categories') }}">
                            @else
                                <a class="nav-link" href="{{ route('video_categories') }}">
                        @endif
                        <i class="fas fa-rocket text-orange"></i>
                        <span class="nav-link-text">Video Categories</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        @if ($activePage == 'videos')
                            <a class="nav-link active" href="{{ route('videos') }}">
                            @else
                                <a class="nav-link" href="{{ route('videos') }}">
                        @endif
                        <i class="fas fa-rocket text-orange"></i>
                        <span class="nav-link-text">Videos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        @if ($activePage == 'mock_test_category')
                            <a class="nav-link active" href="{{ route('mock_test_category') }}">
                            @else
                                <a class="nav-link" href="{{ route('mock_test_category') }}">
                        @endif
                        <i class="fas fa-rocket text-orange"></i>
                        <span class="nav-link-text">Mock Test Category</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        @if ($activePage == 'mock_tests')
                            <a class="nav-link active" href="{{ route('mock_tests') }}">
                            @else
                                <a class="nav-link" href="{{ route('mock_tests') }}">
                        @endif
                        <i class="fas fa-rocket text-orange"></i>
                        <span class="nav-link-text">Mock Tests</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        @if ($activePage == 'test_questions')
                            <a class="nav-link active" href="{{ route('test_questions') }}">
                            @else
                                <a class="nav-link" href="{{ route('test_questions') }}">
                        @endif
                        <i class="fas fa-rocket text-orange"></i>
                        <span class="nav-link-text">Test Questions</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        @if ($activePage == 'pdf_notes')
                            <a class="nav-link active" href="{{ route('pdf_notes') }}">
                            @else
                                <a class="nav-link" href="{{ route('pdf_notes') }}">
                        @endif
                        <i class="fas fa-rocket text-orange"></i>
                        <span class="nav-link-text">PDF Notes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        @if ($activePage == 'notification')
                            <a class="nav-link active" href="{{ route('notification') }}">
                            @else
                                <a class="nav-link" href="{{ route('notification') }}">
                        @endif
                        <i class="fas fa-rocket text-orange"></i>
                        <span class="nav-link-text">Send Notification</span>
                        </a>
                    </li>
                    @if (\Session::has('admin_type'))
                        @if (\Session::get('admin_type') == 0)
                            <span class="success_gradient text-center ml-3 mr-3">Main Admin</span>
                            <li class="nav-item">
                                @if ($activePage == 'users')
                                    <a class="nav-link active" href="{{ route('users') }}">
                                    @else
                                        <a class="nav-link" href="{{ route('users') }}">
                                @endif
                                <i class="fas fa-rocket text-orange"></i>
                                <span class="nav-link-text">Users</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                @if ($activePage == 'ebooks')
                                    <a class="nav-link active" href="{{ route('ebooks') }}">
                                    @else
                                        <a class="nav-link" href="{{ route('ebooks') }}">
                                @endif
                                <i class="fas fa-rocket text-orange"></i>
                                <span class="nav-link-text">Ebooks</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                @if ($activePage == 'banners')
                                    <a class="nav-link active" href="{{ route('banners') }}">
                                    @else
                                        <a class="nav-link" href="{{ route('banners') }}">
                                @endif
                                <i class="fas fa-rocket text-orange"></i>
                                <span class="nav-link-text">Banners</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                @if ($activePage == 'syllabus')
                                    <a class="nav-link active" href="{{ route('syllabus') }}">
                                    @else
                                        <a class="nav-link" href="{{ route('syllabus') }}">
                                @endif
                                <i class="fas fa-rocket text-orange"></i>
                                <span class="nav-link-text">Syllabus</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                @if ($activePage == 'coupan_codes')
                                    <a class="nav-link active" href="{{ route('coupan_codes') }}">
                                    @else
                                        <a class="nav-link" href="{{ route('coupan_codes') }}">
                                @endif
                                <i class="fas fa-rocket text-orange"></i>
                                <span class="nav-link-text">Coupan Codes</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                @if ($activePage == 'manual_payment_requests')
                                    <a class="nav-link active" href="{{ route('manual_payment_requests') }}">
                                    @else
                                        <a class="nav-link" href="{{ route('manual_payment_requests') }}">
                                @endif
                                <i class="fas fa-rocket text-orange"></i>
                                <span class="nav-link-text">Payment Requests</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                @if ($activePage == 'user_orders')
                                    <a class="nav-link active" href="{{ route('user_orders') }}">
                                    @else
                                        <a class="nav-link" href="{{ route('user_orders') }}">
                                @endif
                                <i class="fas fa-rocket text-orange"></i>
                                <span class="nav-link-text">Users Orders</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                @if ($activePage == 'web_admins')
                                    <a class="nav-link active" href="{{ route('web_admins') }}">
                                    @else
                                        <a class="nav-link" href="{{ route('web_admins') }}">
                                @endif
                                <i class="fas fa-rocket text-orange"></i>
                                <span class="nav-link-text">Web Admins</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                @if ($activePage == 'app_settings')
                                    <a class="nav-link active" href="{{ route('app_settings') }}">
                                    @else
                                        <a class="nav-link" href="{{ route('app_settings') }}">
                                @endif
                                <i class="fas fa-cog text-green"></i>
                                <span class="nav-link-text">App Settings</span>
                                </a>
                            </li>
                        @endif
                    @endif
                    <li class="nav-item">
                        @if ($activePage == 'logout')
                            <a class="nav-link active" href="{{ route('logout') }}">
                            @else
                                <a class="nav-link" href="{{ route('logout') }}">
                        @endif
                        <i class="fa-solid fa-right-from-bracket text-green"></i>
                        <span class="nav-link-text">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
