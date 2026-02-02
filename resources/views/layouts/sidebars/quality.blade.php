<div id="sidebar" class="active" data-bs-theme="{{ auth()->check() ? (auth()->user()->theme ?? 'light') : 'light' }}">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('assets/compiled/jpg/logo metinca.jpg') }}" alt="Logo"
                                style="height: 70px; width: auto; margin-right: 12px;">
                        <span style="font-size: 15px; font-weight: 600; color: #333;">
                            QUALITY DEPT
                        </span>
                    </a>
                </div>
                <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        aria-hidden="true" role="img" class="iconify iconify--system-uicons" width="20"
                        height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                        <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                opacity=".3"></path>
                            <g transform="translate(-210 -1)">
                                <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                <circle cx="220.5" cy="11.5" r="4"></circle>
                                <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2">
                                </path>
                            </g>
                        </g>
                    </svg>
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input me-0" type="checkbox" id="toggle-dark"
                            style="cursor: pointer">
                        <label class="form-check-label"></label>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        aria-hidden="true" role="img" class="iconify iconify--mdi" width="20"
                        height="20" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                        </path>
                    </svg>
                </div>
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i
                            class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        
        <div class="sidebar-menu">
            <ul class="menu">
                <!-- Dashboard -->
                <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Quality Dashboard</span>
                    </a>
                </li>

                <!-- QUALITY WORKFLOW -->
                <li class="sidebar-title">Customer Return</li>
                <li class="sidebar-item has-sub {{ request()->routeIs('quality-reinspection.*') ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-search text-warning"></i>
                        <span>Quality Reinspection</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item {{ request()->routeIs('quality-reinspection.index') ? 'active' : '' }}">
                            <a href="{{ route('quality-reinspection.index') }}" class="submenu-link">
                                <i class="bi bi-clipboard-data me-2"></i>Pending Inspection
                            </a>
                        </li>
                        <li class="submenu-item {{ request()->routeIs('quality-reinspection.create') ? 'active' : '' }}">
                            <a href="{{ route('quality-reinspection.create') }}" class="submenu-link">
                                <i class="bi bi-plus-circle me-2"></i>Start Inspection
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- USER PROFILE & LOGOUT SECTION -->
                <li class="sidebar-title mt-3">Account</li>
                <li class="sidebar-item">
                    <div class="sidebar-link d-flex align-items-center p-3 mx-2 mb-2" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 8px;">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}?t={{ time() }}" 
                                 alt="{{ auth()->user()->name }}"
                                 class="me-3 rounded-circle"
                                 style="width: 44px; height: 44px; object-fit: cover;">
                        @else
                            <div class="me-3 bg-{{ auth()->user()->getRoleBadgeColor() }}" style="width: 44px; height: 44px; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                                <span class="text-white" style="font-weight: 600; font-size: 15px; margin: 0; padding: 0; display: block; text-align: center;">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-dark" style="font-size: 13px;">{{ auth()->user()->name }}</h6>
                            <span class="badge bg-{{ auth()->user()->getRoleBadgeColor() }}" style="font-size: 10px;">
                                {{ auth()->user()->getRoleDisplayName() }}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="sidebar-item">
                    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="sidebar-link btn btn-link text-danger w-100 text-start" style="text-decoration: none;">
                            <i class="bi bi-box-arrow-left"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>