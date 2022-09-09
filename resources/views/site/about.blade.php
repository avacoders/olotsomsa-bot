@extends('layouts.site')

@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
@endsection

@section('content')

    <div id="about">
        <div class="back-img">
            <img src="{{ asset('image/about-img.jpg') }}" alt="">
        </div>

        <div class="mt-100">
            <div class="header" data-aos="fade-up">@lang('site.about')</div>
            <p class="mt-50 w-lg-50"  data-aos="fade-up"  data-aos-delay="200" >
                @lang('site.about-text-1')
            </p>
            <p class="mt-20  w-lg-50"  data-aos="fade-up" data-aos-delay="400" >
                @lang('site.about-text-1')
            </p>
        </div>

        <div class="mt-100 mx-0" id="about-owl">
            <div class="owl-carousel">
                <div><img src="{{ asset('image/owl1.jpg') }}" data-aos="fade-up"  alt=""></div>
                <div><img src="{{ asset('image/owl2.jpg') }}" data-aos="fade-up"  data-aos-delay="200" alt=""></div>
                <div><img src="{{ asset('image/owl3.jpg') }}" data-aos="fade-up"  data-aos-delay="400" alt=""></div>
            </div>
        </div>
    </div>



    @include('components.video')

@endsection

@section('script')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>
        $(document).ready(function(){
            $(".owl-carousel").owlCarousel({
                autoplay: true,
                margin: 20,
                items:3,
                autoplayTimeout:2000,
                responsive : {
                    600 : {
                        items:1,
                    },
                    0 : {
                        items:1,
                    },
                    768: {
                        items:3,
                    }
                }
            });

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
