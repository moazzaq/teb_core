<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
              <span class="app-brand-logo demo">
                <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                      fill="#7367F0" />
                  <path
                      opacity="0.06"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                      fill="#161616" />
                  <path
                      opacity="0.06"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                      fill="#161616" />
                  <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                      fill="#7367F0" />
                </svg>
              </span>
            <span class="app-brand-text demo menu-text fw-bold">{{ env('APP_NAME') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->is(app()->getLocale().'/admin') ? 'active' : '' }}">
            <a href="{{route('admin.dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>{{__('cp.dashboard')}}</div>
            </a>
        </li>

        <!-- Dashboards -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Shop</span>
        </li>
        <li class="menu-item {{ request()->is(app()->getLocale().'/admin/categories*') || request()->is(app()->getLocale().'/admin/sub-categories*') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-id"></i>
                <div>{{__('cp.categories')}}</div>
                <div class="badge bg-primary rounded-pill ms-auto">3</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is(app()->getLocale().'/admin/categories*') ? 'active' : '' }}">
                    <a href="{{route('admin.categories.index')}}" class="menu-link">
                        <div>{{__('cp.main_categories')}}</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is(app()->getLocale().'/admin/sub-categories*') ? 'active' : '' }}">
                    <a href="{{route('admin.sub-categories.index')}}" class="menu-link">
                        <div>{{__('cp.sub_categories')}}</div>
                    </a>
                </li>
            </ul>

        <!-- Apps & Pages -->
        <li class="menu-header small text-uppercase">
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{__('cp.location')}}</span>
        </li>
        <li class="menu-item {{ request()->is(app()->getLocale().'/admin/countries*') || request()->is(app()->getLocale().'/admin/cities*') ||
request()->is(app()->getLocale().'/admin/areas*') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-id"></i>
                <div>{{__('cp.location')}}</div>
                <div class="badge bg-primary rounded-pill ms-auto">3</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is(app()->getLocale().'/admin/countries*') ? 'active' : '' }}">
                    <a href="{{route('admin.countries.index')}}" class="menu-link">
                        <div>{{__('cp.countries')}}</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is(app()->getLocale().'/admin/cities*') ? 'active' : '' }}">
                    <a href="{{route('admin.cities.index')}}" class="menu-link">
                        <div>{{__('cp.cities')}}</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is(app()->getLocale().'/admin/areas*') ? 'active' : '' }}">
                    <a href="{{route('admin.areas.index')}}" class="menu-link">
                        <div>{{__('cp.areas')}}</div>
                    </a>
                </li>
            </ul>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{__('cp.treatments')}}</span>
        </li>
        <li class="menu-item {{ request()->is(app()->getLocale().'/admin/treatments*') || request()->is(app()->getLocale().'/admin/cases*') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-id"></i>
                <div>{{__('cp.treatments')}}</div>
                <div class="badge bg-primary rounded-pill ms-auto">3</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is(app()->getLocale().'/admin/treatments*') ? 'active' : '' }}">
                    <a href="{{route('admin.treatments.index')}}" class="menu-link">
                        <div>{{__('cp.treatments')}}</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is(app()->getLocale().'/admin/cases*') ? 'active' : '' }}">
                    <a href="{{route('admin.cases.index')}}" class="menu-link">
                        <div>{{__('cp.cases')}}</div>
                    </a>
                </li>
            </ul>
        <li class="menu-item {{ request()->is(app()->getLocale().'/admin/nationality') ? 'active' : '' }}">
            <a href="{{route('admin.nationality.index')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>{{__('cp.nationality')}}</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is(app()->getLocale().'/admin/insurance') ? 'active' : '' }}">
            <a href="{{route('admin.insurance.index')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>{{__('cp.insurance')}}</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is(app()->getLocale().'/admin/currencies') ? 'active' : '' }}">
            <a href="{{route('admin.currencies.index')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>{{__('cp.currencies')}}</div>
            </a>
        </li>
        <li class="menu-item {{ request()->is(app()->getLocale().'/admin/services') ? 'active' : '' }}">
            <a href="{{route('admin.services.index')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>{{__('cp.services')}}</div>
            </a>
        </li>
            <!-- Apps & Pages -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Members</span>
        </li>

        <li class="menu-item {{ request()->is(app()->getLocale().'/admin/users*') ? 'active' : '' }}">
            <a href="{{route('admin.users.index')}}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-users"></i>
                <div>{{__('cp.users')}}</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Logout</span>
        </li>

        <li class="menu-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a class="menu-link" href="{{route('logout')}}"
                   onclick="event.preventDefault();
                                        this.closest('form').submit();">
                    <i class="ti ti-logout me-2 ti-sm"></i>
                    <div>{{__('cp.logout')}}</div>
                </a>
            </form>
        </li>
    </ul>
</aside>
