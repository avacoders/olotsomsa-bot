<div style="    position: fixed; right: 0; left: 0;
    top: 0;
    z-index: 10001;">
    <nav class="navbar fixed navbar-expand-lg " id="navbar" data-aos="fade-down">
        <button class="navbar-toggler" type="button"
                aria-expanded="false" aria-label="Toggle navigation">
            <i class="toggle-line toggle-line1 "></i>
            <i class="toggle-line toggle-line2 "></i>
            <i class="toggle-line toggle-line3 "></i>
        </button>
        <a class="navbar-brand " data-aos="fade-down" href="/"><img src="{{ asset('image/logo.png')  }}" alt=""></a>

        <a class="nav-link reverse-sm d-lg-none" data-toggle="modal" data-target="#loginModal"
           href="#">@lang('site.enter')</a>
        <div class="dropdown d-lg-none">
            <button class="nav-link reverse" type="button" id="username-sm"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            </button>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route("myOrders") }}">@lang('site.my-orders')</a>
                <a class="dropdown-item" href="{{ route("cabinet") }}">@lang('site.cabinet')</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item logout" id="logout" href="#">@lang('site.exit')</a>
            </div>
        </div>
        <div class=" navbar-collapse" style="height: 0;" data-opened="0">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item" data-aos="fade-down" data-aos-delay="50">
                    <a class="nav-link {{ request()->is('1') ? 'active':'' }}" href="{{ route('site') }}">@lang('site.main-window')</a>
                </li>
                <li class="nav-item" data-aos="fade-down" data-aos-delay="100">
                    <a class="nav-link {{ request()->is('branches') ? 'active':'' }}" href="{{ route('branches') }}">@lang('site.branches')</a>
                </li>
                <li class="nav-item" data-aos="fade-down" data-aos-delay="150">
                    <a class="nav-link {{ request()->is('about') ? 'active':'' }}" href="{{ route('about') }}">@lang('site.about')</a>
                </li>
                <li class="nav-item " data-aos="fade-down" data-aos-delay="200">
                    <a class="nav-link {{ request()->is('contact') ? 'active':'' }}"
                       href="{{ route('contact') }}">@lang('site.contact')</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto d-sm-none1 ">
                <li class="nav-item" data-aos="fade-down" data-aos-delay="250">
                    <a class="nav-tel" href="tel: +998903035935">+998 90 303 59 35</a>
                </li>
{{--                <li class="nav-item" id="lang" data-aos="fade-down" data-aos-delay="300">--}}
{{--                    <a class="nav-tel" href="/{{ app()->getLocale() == "uz" ? "ru": "uz" }}">--}}
{{--                        <img src="{{ asset('image/'.app()->getLocale().'.png') }}" alt="" style="width: 35px"></a>--}}
{{--                </li>--}}
                <li class="nav-item" id="kirish" data-aos="fade-down" data-aos-delay="350">
                    <a class="nav-link reverse" data-toggle="modal" data-target="#loginModal"
                       href="#">@lang('site.enter')</a>
                </li>

                <li class="nav-item dropdown" data-aos="fade-down" data-aos-delay="350" id="username-block"
                    style="display:none;">
                    <button class="nav-link reverse" type="button" id="username"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    </button>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route("myOrders") }}">@lang('site.my-orders')</a>
                        <a class="dropdown-item" href="{{ route("cabinet") }}">@lang('site.cabinet')</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item logout" id="logout" href="#">@lang('site.exit')</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

</div>
