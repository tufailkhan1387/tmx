<header class="navbar pcoded-header navbar-expand-lg navbar-light header-blue">
    <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
        <a href="#!" class="b-brand">
            <!-- ========   change your logo hear   ============ -->
            <img src="{{ asset('assets/theme2/images/logo.png') }}" alt="" class="logo">
            <img src="{{ asset('assets/theme2/images/logo-icon.png') }}" alt="" class="logo-thumb">
        </a>
        <a href="#!" class="mob-toggler">
            <i class="feather icon-more-vertical"></i>
        </a>
    </div>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto d-none">
            <li class="nav-item">
                <a href="#!" class="pop-search"><i class="feather icon-search"></i></a>
                <div class="search-bar">
                    <input type="text" class="form-control border-0 shadow-none" placeholder="Search hear">
                    <button type="button" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li>
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i
                            class="icon feather icon-bell"></i></a>
                    <div class="dropdown-menu dropdown-menu-right notification">
                        <div class="noti-head">
                            <h6 class="d-inline-block m-b-0">Notifications</h6>
                            <div class="float-right">
                                <!-- <a href="#!" class="m-r-10">mark as read</a> -->
                                <a href="javascript:void(0);"
                                    class="text-decoration-underline read-all-notification">clear all</a>
                            </div>
                        </div>
                        <ul class="noti-body">
                            @php
                                use Carbon\Carbon;
                                $currentUser = Auth::user();
                                $newLabelDisplayed = false;
                                $earlierLabelDisplayed = false;
                            @endphp

                            @forelse($notifications as $notification)
                                @php
                                    $notificationTime = Carbon::parse($notification->created_at);
                                    $currentTime = Carbon::now();
                                    $timeDifference = $currentTime->diffInMinutes($notificationTime);

                                    $timePassed = '';
                                    if ($timeDifference < 60) {
                                        $timePassed = $timeDifference . ' min';
                                    } else {
                                        $hours = floor($timeDifference / 60);
                                        $minutes = $timeDifference % 60;
                                        $timePassed = $hours . ' hours ' . $minutes . ' min';
                                    }
                                $isNew = $timeDifference < 30; @endphp @if ($isNew && !$newLabelDisplayed)
                                    <li class="n-title">
                                        <p class="m-b-0">NEW</p>
                                    </li>
                                    @php $newLabelDisplayed = true; @endphp
                                @elseif(!$isNew && !$earlierLabelDisplayed)
                                    <li class="n-title">
                                        <p class="m-b-0">EARLIER</p>
                                    </li>
                                    @php $earlierLabelDisplayed = true; @endphp
                                @endif

                                <li class="notification">
                                    <a href="{{ route('notifications.read', base64_encode($notification->id)) }}">
                                        <div class="media">
                                            <img class="img-radius"
                                                src="{{ asset('storage/profile_pics/' . Auth()->User()->profile_pic) }}"
                                                alt="Generic placeholder image">
                                            <div class="media-body">
                                                <p><strong>{{ $notification->title }}</strong><span
                                                        class="n-time text-muted"><i
                                                            class="icon feather icon-clock m-r-10"></i>{{ $timePassed }}</span>
                                                </p>
                                                <p>{{ $notification->message ?? '' }}</p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="notification">
                                    <div class="media">
                                        <div class="media-body">
                                            <p>No notifications found.</p>
                                        </div>
                                    </div>
                                </li>
                            @endforelse
                        </ul>

                        <div class="noti-footer">
                            <a href="{{ route('notifications.list') }}">show all</a>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="dropdown drp-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="feather icon-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-notification">
                        <div class="pro-head">
                            <img src="{{ asset('storage/profile_pics/' . Auth()->User()->profile_pic) }}" alt="Profile"
                                class="img-radius" alt="User-Profile-Image">
                            <span>{{ Auth()->User()->name }}</span>
                            <a href="{{ route('logout') }}" class="dud-logout" title="Logout"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="feather icon-log-out"></i>
                            </a>

                        </div>
                        <ul class="pro-body">
                            <!-- <li><a href="" class="dropdown-item"><i class="feather icon-user"></i> Profile</a></li> -->
                            <!-- <li><a href="email_inbox.html" class="dropdown-item"><i class="feather icon-mail"></i> My Messages</a></li> -->
                            <li><a href="{{ route('logout') }}" class="dropdown-item"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i
                                        class="feather icon-lock"></i> Lock Screen</a></li>
                        </ul>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</header>
