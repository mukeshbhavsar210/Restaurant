
<div class="startbar d-print-none">
    <div class="brand">
        <a href="{{ route('dashboard') }}" class="logo">
            <span>
                <img src="{{ asset('front-assets/images/logo_small.jpg') }}" alt="logo-small" class="logo-sm">
            </span>
            {{-- <span class="">
                <img src="{{ asset('front-assets/images/logo.jpg') }}" alt="logo-large" class="logo-lg logo-light">
                <img src="{{ asset('front-assets/images/logo.jpg') }}" alt="logo-large" class="logo-lg logo-dark">
            </span> --}}
        </a>
    </div>
    <div class="startbar-menu">
        <div class="startbar-collapse simplebar-scrollable-y" id="startbarCollapse" data-simplebar="init">
            <div class="simplebar-wrapper" style="margin: 0px -16px -16px;">
                <div class="simplebar-height-auto-observer-wrapper">
                    <div class="simplebar-height-auto-observer"></div>
                </div>
                <div class="simplebar-mask">
                    <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                        <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;">
                            <div class="simplebar-content" style="padding: 0px 16px 16px;">
                                <div class="d-flex align-items-start flex-column w-100">
                                <ul class="navbar-nav mb-auto w-100">    
                                    <li class="nav-item">
                                        <a href="{{ route('dashboard') }}" class="nav-link {{ (\Request::route()->getName() == 'admin.dashboard') ? 'active' : '' }}">
                                            <i class="iconoir-home-simple menu-icon"></i>
                                            <span>Dashboard</span>                        
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('categories.index') }}" class="nav-link {{ (\Request::route()->getName() == 'categories.index') || (\Request::route()->getName() == 'menu.edit') ? 'active' : '' }}">
                                            <i class="iconoir-view-grid menu-icon"></i>
                                            <span>Category</span>
                                        </a>
                                    </li>           
                                    <li class="nav-item">
                                        <a href="{{ route('products.index') }}" class="nav-link {{ (\Request::route()->getName() == 'products.index') ? 'active' : '' }}">
                                            <i class="iconoir-compact-disc menu-icon"></i>
                                            <span>Products</span>
                                        </a>
                                    </li>  
                                    <li class="nav-item">
                                        <a href="{{ route('invoice.index') }}" class="nav-link {{ (\Request::route()->getName() == 'invoice.index') ? 'active' : '' }}">
                                            <i class="iconoir-compact-disc menu-icon"></i>
                                            <span>Invoice</span>
                                        </a>
                                    </li>   
                                    <li class="nav-item">
                                        <a href="{{ route('orders.index') }}" class="nav-link {{ (\Request::route()->getName() == 'orders.index') || (\Request::route()->getName() == 'orders.detail') ? 'active' : '' }}">
                                            <i class="iconoir-journal-page menu-icon"></i>
                                            <span>Orders</span>
                                        </a>
                                    </li>

                                    @can('view permissions')
                                        <li class="nav-item">
                                            <a href="{{ route('configurations.index') }}" class="nav-link {{ (\Request::route()->getName() == 'configurations.index') || (\Request::route()->getName() == 'roles.edit') || (\Request::route()->getName() == 'permissions.edit') || (\Request::route()->getName() == 'users.edit') || (\Request::route()->getName() == 'articles.edit') ? 'active' : '' }}">
                                                <i class="iconoir-fingerprint-lock-circle menu-icon"></i>
                                                <span>Settings</span>
                                            </a>
                                        </li>
                                    @endcan    

                                    <li class="nav-item">
                                        <a href="{{ route('profile.index') }}" class="nav-link {{ (\Request::route()->getName() == 'profile.index') || (\Request::route()->getName() == 'profile.update') ? 'active' : '' }}">
                                            <i class="iconoir-fingerprint-lock-circle menu-icon"></i>
                                            <span>Profile</span>
                                        </a>
                                    </li>

                                    {{-- <li class="nav-item">
                                        <a class="nav-link" href="#extra" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarApplications">
                                            <i class="iconoir-page-star menu-icon"></i>
                                            <span>Settings</span>
                                        </a>
                                        <div class="collapse " id="extra">
                                            <ul class="nav flex-column">                        
                                                <li class="nav-item">
                                                    <a href="{{ route('articles.index') }}" class="nav-link {{ (\Request::route()->getName() == 'articles.index') ? 'active' : '' }}">
                                                        <i class="iconoir-journal-page menu-icon"></i>
                                                        <span>Articles</span>
                                                    </a>
                                                </li>                 
                                            </ul>
                                        </div>
                                    </li>     --}}
                                </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="simplebar-placeholder" style="width: 70px; height: 657px;"></div>
            </div>
            <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                <div class="simplebar-scrollbar" style="width: 0px; transform: translate3d(0px, 0px, 0px); display: none;"></div>
            </div>
            <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                <div class="simplebar-scrollbar" style="height: 413px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
            </div>
        </div>
    </div>
</div>