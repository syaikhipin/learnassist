@extends('layouts.base')
@section('base_head')
    @yield('head')
@endsection
@section('base_content')

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand">

                    <a href="{{$base_url}}/app/dashboard" class="app-brand-link">
              <span class="">
                  @if(!empty($super_settings['backend_logo']) && ($super_settings['backend_logo'] !== 'system/backend-logo.png'))
                      <img src="{{getUploadsUrl()}}/{{$super_settings['backend_logo']}}?v=3"
                           alt="{{$super_settings['workspace_name'] ?? config('app.name')}}" class="max-height-40 py-1 my-1">
                  @else
                      <img src="{{config('app.url')}}/uploads/system/backend-logo.png?v=4"
                           alt="{{$super_settings['workspace_name'] ?? config('app.name')}}" class="max-height-35 py-1 my-1">
                  @endif
              </span>
                    </a>


                    <a href="#" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bi bi-chevron-left align-middle"></i>
                    </a>
                </div>


                <div class="menu-inner-shadow"></div>


                <ul class="menu-inner py-1">
                    <!-- Dashboard -->

                    {{--                    <li class="menu-item @if(($navigation ?? null) == 'dashboard') active @endif">--}}
                    {{--                        <a href="{{$base_url}}/app/dashboard" class="menu-link">--}}
                    {{--                            <div class="avatar flex-shrink-0">--}}
                    {{--                                <span class="avatar-initial  avatar-xs rounded bg-label-info text-white">Y</span>--}}
                    {{--                            </div> {{__(' workbook')}}--}}
                    {{--                        </a>--}}
                    {{--                    </li>--}}


                    <li class="menu-item @if(($navigation ?? null) == 'dashboard') active @endif">
                        <a href="{{$base_url}}/app/dashboard" class="menu-link">
                            <i class="menu-icon bi bi-house-fill"></i> {{__('Dashboard')}}
                        </a>
                    </li>

{{--                    <li class="menu-item @if(($navigation ?? null) == 'my-tutors') active @endif">--}}
{{--                        <a href="{{$base_url}}/tutor/my-tutors" class="menu-link">--}}
{{--                            <i class="text-primary menu-icon bi bi-person-fill"></i> {{ __('My Tutors') }}--}}
{{--                        </a>--}}
{{--                    </li>--}}


                    <li class="menu-item @if(($navigation ?? null) == 'todos') active @endif">
                        <a href="{{$base_url}}/app/todos" class="menu-link">
                            <i class="text-info menu-icon bi bi-list-check"></i> {{ __('To-dos') }}
                        </a>
                    </li>
                    <li class="menu-item @if(($navigation ?? null) == 'study-goals') active @endif">
                        <a href="{{$base_url}}/app/studygoals" class="menu-link">
                            <i class="text-primary menu-icon bi bi-gem"></i> {{ __('Study Goals') }}
                        </a>
                    </li>
                    <li class="menu-item @if(($navigation ?? null) == 'projects') active @endif">
                        <a href="{{$base_url}}/app/assignments" class="menu-link">
                            <i class="text-success menu-icon bi bi-box-seam-fill"></i> {{ __('Assignments') }}
                        </a>
                    </li>
                    <li class="menu-item @if(($navigation ?? null) == 'calendar') active @endif">
                        <a href="{{$base_url}}/app/calendar" class="menu-link">
                            <i class="text-warning menu-icon bi bi-calendar-week-fill"></i> {{ __('Calendar') }}
                        </a>
                    </li>

                    <li class="menu-item @if(($navigation ?? null) == 'ai_chat') active @endif">
                        <a href="{{$base_url}}/app/ai-chat" class="menu-link">
                            <i class="text-white menu-icon bi bi-chat-dots-fill"></i> {{ __('AI Tutor') }}
                        </a>

                    </li>

                    <li class="menu-item @if(($navigation ?? null) == 'ai_image') active @endif">
                        <a href="{{$base_url}}/app/ai-image" class="menu-link">
                            <i class="text-success menu-icon bi bi-images"></i> {{ __('AI Image') }}
                        </a>
                    </li>

                    <li class="menu-item @if(($navigation ?? null) == 'ai_image_studio') active @endif">
                        <a href="{{$base_url}}/app/ai-image/studio" class="menu-link">
                            <i class="text-primary menu-icon bi bi-camera-fill"></i> {{ __('AI Studio') }}
                        </a>
                    </li>

{{--                    <li class="menu-item @if(($navigation ?? null) == 'photo_booth') active @endif">--}}
{{--                        <a href="{{$base_url}}/app/photo-booth" class="menu-link">--}}
{{--                            <i class="text-primary menu-icon bi bi-camera-fill"></i> {{ __('Photo Booth') }}--}}
{{--                        </a>--}}
{{--                    </li>--}}

                    <li class="menu-item @if(($navigation ?? null) == 'ai_image_headshot') active @endif">
                        <a href="{{$base_url}}/app/ai-image/headshot" class="menu-link">
                            <i class="text-warning menu-icon bi bi-file-person"></i> {{ __('Headshot') }}
                        </a>
                    </li>

                    <!-- Layouts -->

                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">{{ __('MY Desk') }}</span>
                    </li>




                    <li class="menu-item @if(($navigation ?? null) == 'documents') active @endif">
                        <a href="{{$base_url}}/app/documents" class="menu-link">
                            <i class="menu-icon bi bi-file-earmark-word-fill"></i> {{ __('Notes') }}
                        </a>
                    </li>


                    <li class="menu-item @if(($navigation ?? null) == 'flashcards') active @endif">
                        <a href="{{$base_url}}/app/flashcards" class="menu-link">
                            <i class="menu-icon bi bi-grid-3x3-gap"></i> {{ __('Flashcards') }}
                        </a>
                    </li>

                    <li class="menu-item @if(($navigation ?? null) == 'spreadsheets') active @endif">
                        <a href="{{$base_url}}/app/spreadsheets" class="menu-link">
                            <i class="menu-icon bi bi-file-earmark-spreadsheet-fill"></i> {{ __('Sheets') }}
                        </a>
                    </li>



                    <li class="menu-item @if(($navigation ?? null) == 'digital-assets') active @endif">
                        <a href="{{$base_url}}/app/digital-assets" class="menu-link">
                            <i class="menu-icon bi bi-hdd-fill"></i> {{ __('Resources') }}
                        </a>
                    </li>
                    <li class="menu-item @if(($navigation ?? null) == 'address-book') active @endif">
                        <a href="{{$base_url}}/app/address-book" class="menu-link">
                            <i class="menu-icon bi bi-person-badge"></i> {{ __('Contacts') }}
                        </a>
                    </li>
                    <li class="menu-item @if(($navigation ?? null) == 'quick_share') active open @endif">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon bi bi-share-fill"></i> {{__('Share')}}
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item @if(($sub_navigation ?? null) == 'quick_share_new') active @endif">
                                <a href="{{$base_url}}/app/quick-share?tab=new"
                                   class="menu-link">{{__('New Share')}}</a>
                            </li>
                            <li class="menu-item @if(($sub_navigation ?? null) == 'quick_share_shares') active @endif">
                                <a href="{{$base_url}}/app/quick-share?tab=shares"
                                   class="menu-link">{{__('Shares')}}</a>
                            </li>
                            <li class="menu-item @if(($sub_navigation ?? null) == 'quick_share_access_logs') active @endif">
                                <a href="{{$base_url}}/app/quick-share?tab=access_logs"
                                   class="menu-link">{{__('Access Logs')}}</a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">{{ __('Setup') }}</span>
                    </li>

                    <li class="menu-item @if(($navigation ?? null) == 'settings') active open @endif">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon bi bi-gear-wide-connected"></i> {{__('Settings')}}
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item @if(($sub_navigation ?? null) == 'settings_general') active @endif">
                                <a href="{{$base_url}}/app/settings?tab=general"
                                   class="menu-link">{{__('General Settings')}}</a>
                            </li>
                            <li class="menu-item @if(($sub_navigation ?? null) == 'settings_users') active @endif">
                                <a href="{{$base_url}}/app/settings?tab=users" class="menu-link">{{__('Users')}}</a>
                            </li>
                            <li class="menu-item @if(($sub_navigation ?? null) == 'settings_api') active @endif">
                                <a href="{{$base_url}}/app/settings?tab=api" class="menu-link">{{__('API')}}</a>
                            </li>


                            @if($saas)
                                <li class="menu-item @if(($sub_navigation ?? null) == 'settings_billing') active @endif">
                                    <a href="{{$base_url}}/app/billing" class="menu-link">{{__('Billing')}}</a>
                                </li>
                            @endif

                            @if(!$saas)
                                <li class="menu-item @if(($sub_navigation ?? null) == 'settings_about') active @endif">
                                    <a href="{{$base_url}}/app/settings?tab=about" class="menu-link">{{__('About')}}</a>
                                </li>
                            @endif


                        </ul>

                    </li>

                </ul>

            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav
                        class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                        id="layout-navbar"
                >
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bi bi-list"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <h5 class="fw-bold mb-0">
                                    @if(!empty($page_title) && !empty($page_subtitle))
                                        <span class="text-muted fw-light">{{$page_title}} /</span> {{$page_subtitle}}
                                    @else
                                        {{$page_title ?? ''}}
                                    @endif

                                </h5>
                            </div>
                        </div>
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <li class="nav-item lh-1 me-3">
                                <button id="btnTimer" class="btn rounded-pill btn-dark">
                                    @if(empty($timer))
                                        <i class="bi bi-play"></i> {{__('Timer Start')}}
                                    @else
                                        <i class="bi bi-pause-fill"></i> <span id="timer_count"></span>
                                    @endif
                                </button>
                            </li>
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                   data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <span class="app-avatar-text">{{$user->first_name[0]}}{{$user->last_name[0]}}</span>
                                    </div>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <span class="app-avatar-text">{{$user->first_name[0]}}{{$user->last_name[0]}}</span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">{{$user->first_name}} {{$user->last_name}}</span>
                                                    <small class="text-muted">{{$user->email}}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{$base_url}}/app/user/me">{{__('Profile')}}</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                           href="{{$base_url}}/app/settings?tab=general">{{__('Settings')}}</a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>

                                    @if($saas && $user->is_super_admin)
                                        <li>
                                            <a class="dropdown-item"
                                               href="{{$base_url}}/super-admin/dashboard">{{__('Go to Super Admin')}}</a>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                    @endif

                                    <li>
                                        <a class="dropdown-item" href="{{$base_url}}/app/logout">{{__('Logout')}}</a>
                                    </li>
                                </ul>

                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <div class="container-xxl flex-grow-1 container-p-y">

                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                <strong>{{__('Success')}}!</strong> {{session()->get('success')}}
                            </div>
                        @endif

                        @if(session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                <strong>{{__('Error')}}!</strong> {{session()->get('error')}}
                            </div>
                        @endif

                        <div id="liveAlertPlaceholder"></div>

                        @yield('content')


                    </div>


                    @if(empty($layout_remove_footer))
                        <footer class="content-footer footer bg-footer-theme">
                            <div class="container-xxl py-2">
                                <div class="text-center">
                                    © {{date('Y')}} <a href="{{$base_url}}"
                                                       class="footer-link">{{$super_settings['workspace_name'] ?? config('app.name')}}</a>
                                    | {{__('Version')}}: {{config('app.version')}}
                                </div>
                            </div>
                        </footer>
                    @endif

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

@endsection

@section('base_scripts')
    @yield('scripts')
    <script>
        (function () {
            "use strict";
            window.addEventListener('DOMContentLoaded', () => {
                const btnTimer = document.getElementById('btnTimer');
                const timer_count = document.getElementById('timer_count');
                const timer = new Timer();
                btnTimer.addEventListener('click', () => {
                    btnTimer.disabled = true;
                    window.location.href = '{{$base_url}}/app/handle-timer';
                });

                if (timer_count) {
                    timer.start({
                        precision: 'seconds',
                        startValues: {seconds: {{$duration ?? 0}}}
                    });
                    timer.addEventListener('secondsUpdated', function () {
                        timer_count.innerHTML = timer.getTimeValues().toString();
                    });
                }

            });
        })();
    </script>
@endsection
