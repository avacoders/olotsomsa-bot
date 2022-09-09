<footer class="d-sm-none1">

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-5 w-md-50">
                <div class="container-fluid">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-lg-2">
                            <img src="{{ asset('image/logo-white.png') }}" alt="">
                        </div>
                        <div class="col-lg-3">
                            @foreach(cache('branches')??[] as $branch)
                                <div class="branch">{{ strtok($branch->title, " ") }}</div>
                            @endforeach
                        </div>
                        <div class="col-lg-7">
                            <div class="text-3">@lang('site.buyurtma')</div>
                            <a href="tel:  +998903035935" class="text-1 my-5 d-block"> 90 303 59 35</a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-2 d-md-none"></div>
            <div class="col-lg-5 w-md-50">
                <div class="container-fluid">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-lg-7 w-md-50">
                            <div class="text-3">@lang('site.ishvaqti')</div>
                            <a href="tel:  90 555 77 85" class="text-1 my-2 d-block">09:30 - 23:00</a>
                            <a href="tel:  90 555 77 85" class="text-1 my-2 d-block">10:30 - 22:30</a>
                        </div>
                        <div class="col-lg-5">
                            <div class="branch my-4  ">@lang('site.olot')</div>
                            <div class="branch mt-lg-4 pt-lg-3">@lang('site.osh')</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<footer class="d-lg-none">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-5">
                <div class="container-fluid">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-lg-7">
                            <div class="text-3">@lang('site.buyurtma')</div>
                            <a href="tel:  90 555 77 85" class="text-1 my-5 d-block"> 90 303 59 35</a>
                        </div>
                        <div class="col-lg-7 ">
                            <div class="text-3 mt-3 mb-5">@lang('site.ishvaqti')</div>
                            <div class="branch my-4  ">@lang('site.olot')</div>

                            <a href="tel:  90 555 77 85" class="text-1 mt-2 mb-4 d-block">09:30 - 23:00</a>

                            <div class="branch mt-lg-4 pt-lg-3 mt-4">@lang('site.osh')</div>
                            <a href="tel:  90 555 77 85" class="text-1 my-2 d-block">10:30 - 22:30</a>
                        </div>
                        <div class="col-lg-2">
                            <img src="{{ asset('image/logo-white.png') }}" class="d-block mx-auto mt-4" alt="">
                            <div class="branch">2022</div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2"></div>
            <div class="col-lg-5">
                <div class="container-fluid">
                    <div class="row justify-content-between align-items-center">

                        <div class="col-lg-5">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
