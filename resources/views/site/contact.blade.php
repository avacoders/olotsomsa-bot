@extends('layouts.site')
@section('content')

    <div id="contact">
        <div class="header" data-aos="fade-up" data-aos-delay="300">@lang('site.belong')</div>


        <div class="row mt-80 gap-lg-60 gap-sm-20">
            <div class="col-lg-8"  data-aos="fade-up" data-aos-delay="500">
                <div class="card">
                    <div class="d-lg-flex justify-content-between">
                        <div>
                            <div class="header-text">OLOTSOMSA</div>
                            <div class="text">Yakkasaroy Boshliq 8</div>
                        </div>
                        <div>
                            <a href="" class="text mt-lg-0">+998 90 303 59 35</a>
                            <a href="" class="text">+998 90 303 59 35</a>
                        </div>
                    </div>
                    <div class="line"></div>
                </div>
            </div>
            <div class="col-lg-8"  data-aos="fade-up" data-aos-delay="600">
                <div class="card">
                    <div class="d-lg-flex justify-content-between">
                        <div>
                            <div class="header-text">OLOTSOMSA</div>
                            <div class="text">Yakkasaroy Boshliq 8</div>
                        </div>
                        <div>
                            <a href="" class="text mt-lg-0">+998 90 303 59 35</a>
                            <a href="" class="text">+998 90 303 59 35</a>
                        </div>
                    </div>
                    <div class="line"></div>
                </div>
            </div>

        </div>
    </div>


    @include('components.video')

@endsection

@section('script')

    <script>
        $(document).ready(function() {

            var $videoSrc;
            $('.video-btn').click(function() {
                $videoSrc = $(this).data( "src" );
            });
            $('#myModal').on('shown.bs.modal', function (e) {

                $("#video").attr('src',$videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0" );
            })



            $('#myModal').on('hide.bs.modal', function (e) {
                $("#video").attr('src',$videoSrc);
            })

        });
    </script>

@endsection
