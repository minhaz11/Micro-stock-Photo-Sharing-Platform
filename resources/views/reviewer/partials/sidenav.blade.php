<div class="sidebar capsule--rounded bg_img overlay--dark"
     data-background="{{getImage('assets/admin/images/sidebar/2.jpg','400x800')}}">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{route('reviewer.dashboard')}}" class="sidebar__main-logo"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="image"></a>
            <a href="{{route('reviewer.dashboard')}}" class="sidebar__logo-shape"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}" alt="image"></a>
            <button type="button" class="navbar__expand"></button>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{menuActive('reviewer.dashboard')}}">
                    <a href="{{route('reviewer.dashboard')}}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                        
                    </a>
                </li>
                <li class="sidebar-menu-item {{menuActive('reviewer.pending.review')}}">
                    <a href="{{route('reviewer.pending.review')}}" class="nav-link ">
                        <i class="menu-icon las la-hourglass-start"></i>
                        <span class="menu-title">@lang('Pending Reviews')</span>
                        @if(0 < $pendingReviews)
                        <span class="menu-badge pill bg--primary ml-auto">
                            {{$pendingReviews}}
                        </span>
                        @endif
                    </a>
                </li>
                <li data-toggle="tooltip" title="Pending Report Reviews" class="sidebar-menu-item {{menuActive('reviewer.pending.report.review')}}">
                    <a  href="{{route('reviewer.pending.report.review')}}" class="nav-link ">
                        <i class="menu-icon las la-hourglass-start"></i>
                        <span class="menu-title">@lang('Pending Rep. Rev.')</span>
                        @if(0 < $pendingReportReviews)
                        <span class="menu-badge pill bg--primary ml-auto">
                            <i class="las la-exclamation"></i>
                        </span>
                        @endif
                    </a>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('reviewer.photo*',3)}}">
                        <i class="menu-icon la la-list"></i>
                        <span class="menu-title">@lang('Review Photos') </span>
                        @if(0 < $pendingPhoto)
                            <span class="menu-badge pill bg--primary ml-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>
                    <div class="sidebar-submenu {{menuActive('reviewer.photo*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('reviewer.photo.pending')}}">
                                <a href="{{route('reviewer.photo.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending photos')</span>
                                    @if(0 < $pendingPhoto)
                                    <span class="menu-badge pill bg--primary ml-auto">
                                        <i class="fa fa-exclamation"></i>
                                    </span>
                                    @endif
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('reviewer.photo.approved')}}">
                                <a href="{{route('reviewer.photo.approved')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Approved photos')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('reviewer.photo.approved.me')}}">
                                <a href="{{route('reviewer.photo.approved.me')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Approved by me')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('reviewer.photo.rejected.me')}}">
                                <a href="{{route('reviewer.photo.rejected.me')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Rejected by me')</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('reviewer.reported*',3)}}">
                        <i class="menu-icon lab la-font-awesome-flag"></i>
                        <span class="menu-title">@lang('Reported Photos') </span>
                        @if(0 < $pendingReport)
                        <span class="menu-badge pill bg--primary ml-auto">
                            <i class="fa fa-exclamation"></i>
                        </span>
                        @endif
                    </a>
                    <div class="sidebar-submenu {{menuActive('reviewer.reported*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive(['reviewer.reported.pending','reviewer.reported.action'])}}">
                                <a href="{{route('reviewer.reported.pending')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending reports')</span>
                                    @if(0 < $pendingReport)
                                    <span class="menu-badge pill bg--primary ml-auto">
                                        <i class="fa fa-exclamation"></i>
                                    </span>
                                    @endif
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('reviewer.reported.reviewedby')}}">
                                <a href="{{route('reviewer.reported.reviewedby')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Reviewed by me')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('reviewer.reported.bannedby')}}">
                                <a href="{{route('reviewer.reported.bannedby')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Banned by me')</span>
                                </a>
                            </li>
                            

                        </ul>
                    </div>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('reviewer.predefined*',3)}}">
                        <i class="menu-icon las la-venus-double"></i>
                        <span class="menu-title">@lang('Predefined Reasons') </span>
                      
                    </a>
                    <div class="sidebar-submenu {{menuActive('reviewer.predefined*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{menuActive('reviewer.predefined.reasons')}}">
                                <a href="{{route('reviewer.predefined.reasons')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All reasons')</span>
                                   
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('reviewer.predefined.reasons.me')}}">
                                <a href="{{route('reviewer.predefined.reasons.me')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Created by you')</span>
                                </a>
                            </li>
                           
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- sidebar end -->
