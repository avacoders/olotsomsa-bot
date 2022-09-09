@extends('layouts.site')



@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
@endsection

@section('content')

    <x-cart></x-cart>
    <div id="carousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carousel" data-slide-to="0" class="active"></li>
            <li data-target="#carousel" data-slide-to="1"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="{{ asset('image/back.jpg') }}" alt="First slide">
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="{{ asset('image/back.jpg') }}" alt="First slide">
            </div>
        </div>

        <div class="text" id="mainText">
            <img data-aos="fade-down" src="{{ asset('image/olot-min.png') }}" alt="">
        </div>


        <div class="welcum">@lang('site.welcum')</div>
        <div class="osh-header" data-aos="fade-right" data-aos-easing="ease-out">
            <img src="{{ asset("image/osh-min.png") }}" alt="">
        </div>
        <div class="img1">
            <div data-aos="fade-right" data-aos-duration="1000" data-aos-delay="400">
                <img src="{{ asset('image/olot.png') }}" alt="">
                <img src="{{ asset('image/ellipse.png') }}" alt="">

            </div>
        </div>
        <div class="img2">
            <div data-aos="fade-left" data-aos-duration="1000" data-aos-delay="400">

                <img src="{{ asset('image/osh.png') }}" alt="">
                <img src="{{ asset('image/ellipse.png') }}" style="" alt="">

            </div>

        </div>

        <div class="socials">
            <a target="_blank" href="https://t.me/olotsom_bot" class="social-link" data-aos="fade-up"><img
                    src="{{ asset('image/telegram.png') }}" alt=""></a>
            <a target="_blank" href="https://instagram.com/olotsomsaa" class="social-link" data-aos="fade-up"
               data-aos-delay="100"><img
                    src="{{ asset('image/youtube.png') }}" alt=""></a>
            <a target="_blank" href="https://www.youtube.com/channel/UCkluaXgJqzcKwXl7y5gbaJg" class="social-link"
               data-aos="fade-up" data-aos-delay="200"><img
                    src="{{ asset('image/instagram.png') }}" alt=""></a>
        </div>


    </div>



    <div id="index">
        <div class="mt-100">

            <div class="header text-center" data-aos="flip-up">@lang('site.raqamlar')</div>
            <div class="header text-center" data-aos="flip-up" data-aos-delay="300">@lang('site.raqamlar2')</div>

        </div>


        <div class="statistics">

            <div class="mx-lg-5 px-lg-5">
                <div class="d-lg-flex justify-content-between" id="counter" data-counted="0">
                    <div class="count-text" data-aos="flip-up">@lang('site.delivery')</div>
                    <div class="count count1" data-count="1"><span class="counter" data-stop="95">0</span>%</div>
                    <div class="count-text" data-aos="flip-up">@lang('site.filial')</div>
                    <div class="count text-center " data-count="2"><span class="counter" data-stop="5">0</span></div>
                    <div class="count-text" data-aos="flip-up">@lang('site.gosht')</div>
                    <div class="count count3" data-count="3"><span class="counter" data-stop="555">0</span></div>
                    <div class="count-text" data-aos="flip-up">@lang('site.somsa')</div>
                    <div class="count count4" data-count="4"><span class="counter" data-stop="9999">0</span></div>
                    <div class="count-text" data-aos="flip-up">@lang('site.tavsiya')</div>
                    <div class="count count5" data-count="5"><span class="counter" data-stop="95">0</span>%</div>
                </div>
            </div>

        </div>

        <div class="mx-lg-5 mt-3 mb-5 px-lg-5">
            <div class="d-lg-flex d-sm-none justify-content-between">
                <div class="count-text count-text1" data-aos="flip-up">@lang('site.delivery')</div>
                <div class="count-text count-text2" data-aos="flip-up" data-aos-delay="100">@lang('site.filial')
                </div>
                <div class="count-text count-text3" data-aos="flip-up" data-aos-delay="200">@lang('site.gosht')
                </div>
                <div class="count-text count-text4" data-aos="flip-up" data-aos-delay="300">@lang('site.somsa')
                </div>
                <div class="count-text count-text5" data-aos="flip-up" data-aos-delay="400">@lang('site.tavsiya')
                </div>
            </div>
        </div>


        <div class="mt-100">
            <div class="menu-text" data-aos="fade-down" data-aos-easing="ease-out">@lang('site.menu')</div>

            <div class="osh" data-aos="fade-down" data-aos-easing="ease-out">@lang('site.osh')</div>

            <div class="menu-content">
                <div class="row justify-content-between ">
                    @foreach($products as $key => $product)
                        <div class="col-lg-6 col-md-12 col-12">
                            <div class="item" data-aos="{{ $key % 2 == 0 ? 'fade-right':'fade-left' }}">
                                <div class="row h-lg-100">
                                    <div class="col-lg-6">
                                        <img class="item-img product-img-{{ $product->id }}" src="{{ $product->image }}"
                                             alt="">
                                    </div>
                                    <div class="col-lg-6 h-lg-100 h-sm-75 p-lg-0">
                                        <div class="item-name mb-lg-5">{{ ucfirst(strtolower($product->name)) }}</div>


                                        <div class="button-section">
                                            <div class=" mt-2 d-flex pr-lg-2 justify-content-between">
                                                <div
                                                    class="price">{{ number_format($product->price,0,',','.') }} </div>
                                                <div
                                                    class="unit">{{ strtoupper($product->unit??"") }}
                                                </div>
                                            </div>
                                            <div class="mx-auto mt-3 actions actions-{{ $product->id }}">
                                                <button class="minus btn mr-4 minus1" data-id="{{ $product->id }}"><img
                                                        src="/image/minus.svg"
                                                        alt=""></button>
                                                <span class=" mx-4 my-2">x<span
                                                        id="order-counter-{{ $product->id }}">1</span></span>
                                                <button class="plus btn mx-4 plus1" data-id="{{ $product->id }}"><img
                                                        src="/image/plus.svg"
                                                        alt=""></button>
                                            </div>
                                            <button type="button" class="addToCart " data-id="{{ $product->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="33" height="30"
                                                     viewBox="0 0 33 30" fill="none">
                                                    <g clip-path="url(#clip0_1_572)">
                                                        <path
                                                            d="M32.6529 10.5321C31.4827 14.0805 30.2747 17.6289 29.1045 21.2151C28.8025 22.1588 28.2363 22.5741 27.2548 22.5741C21.7812 22.5741 16.3075 22.5741 10.7962 22.5741C9.55044 22.5741 8.9842 21.7814 8.87095 20.8376C8.38021 17.2514 7.88947 13.6275 7.43648 10.0414C7.28548 9.02212 7.13449 7.96514 7.02124 6.94592C6.94574 6.07768 6.41725 5.51145 5.66227 5.1717C4.3033 4.52997 2.94433 3.88823 1.58536 3.24649C0.943619 2.9445 0.41513 2.56701 0.150885 1.88752C-0.0378605 1.43453 -0.0378605 0.981543 0.226384 0.528552C0.490628 0.0378126 0.981368 -0.150933 1.47211 0.0755618C3.81256 1.20804 6.22851 2.26502 8.45571 3.66174C8.71995 3.81273 8.9842 4.03923 9.21069 4.22797C9.55044 4.52997 9.77693 4.86971 9.81468 5.3227C9.92793 6.41743 10.1167 7.4744 10.2299 8.60688C10.3809 8.60688 10.4942 8.60688 10.6074 8.60688C17.2513 8.60688 23.8574 8.60688 30.5012 8.60688C30.6145 8.60688 30.7277 8.60688 30.841 8.60688C31.6715 8.60688 32.351 8.87113 32.5774 9.73936C32.6529 10.0036 32.6529 10.2678 32.6529 10.5321ZM10.5697 11.3626C10.6829 12.2686 10.7962 13.1368 10.8717 14.005C10.9094 14.2315 11.0227 14.1938 11.1737 14.1938C16.7983 14.1938 22.4229 14.1938 28.0475 14.1938C28.274 14.1938 28.3495 14.1183 28.3873 13.9295C28.576 13.2878 28.7648 12.646 28.9535 12.0043C29.029 11.8156 29.0668 11.5891 29.1423 11.3626C22.9891 11.3626 16.7983 11.3626 10.5697 11.3626ZM11.5889 19.3654C16.685 19.3654 21.7434 19.3654 26.8396 19.3654C27.1038 18.4972 27.3681 17.5912 27.6323 16.723C22.1587 16.723 16.7605 16.723 11.2869 16.723C11.3624 17.6289 11.4756 18.4972 11.5889 19.3654Z"
                                                            fill="white"/>
                                                        <path
                                                            d="M15.7409 26.6504C15.7409 28.1226 14.5329 29.3306 13.0607 29.3306C11.5885 29.3306 10.3428 28.0849 10.3428 26.6127C10.3428 25.1404 11.5507 23.9325 13.0607 23.8947C14.5329 23.8947 15.7787 25.1404 15.7409 26.6504Z"
                                                            fill="white"/>
                                                        <path
                                                            d="M26.7263 26.6128C26.7263 28.085 25.556 29.293 24.0461 29.3308C22.5738 29.3685 21.3281 28.1605 21.3281 26.6506C21.3281 25.1783 22.5361 23.9326 24.0461 23.9326C25.5183 23.9326 26.7263 25.1406 26.7263 26.6128Z"
                                                            fill="white"/>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_1_572">
                                                            <rect width="32.6531" height="29.3311" fill="white"/>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </button>
                                        </div>

                                    </div>
                                </div>


                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <x-video></x-video>


        <div class="mt-100" id="osh">
            <div class="container-fluid">
                <div class="row justify-content-center gap-60">
                    <div class="col-lg-4 p-0 col-12" data-aos="fade-right">
                        <div class="header-text-sm d-lg-none" data-aos="fade-down">@lang('site.osh')</div>

                        <div class="osh">
                            <div class="osh-border">
                                <img src="{{ asset('image/ellipse.png') }}" alt="">
                            </div>
                            <img class="osh-img" style="z-index: 100" src="{{ asset('image/osh.png') }}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-1"></div>
                    <div class="col-lg-4 col-12">
                        <div class="header-text" data-aos="fade-left">@lang('site.osh')</div>
                        <p data-aos="fade-up">@lang('site.osh-description')</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-100 mx-auto" id="olot">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-4 ml-auto stol-text">
                        <div class="header-text text-h1-switch">@lang('site.olot')</div>
                        <div class="text text-h2-switch">Mazali va kafolatlangan ta'm</div>
                        <p class="text-p-switch">
                            Taomlarni tayyorlashda sifatli <br> mahsulotlar ishlatiliniladi
                        </p>
                    </div>
                    <div class="col-lg-6  h-900 overflow-hidden position-relative p-4">
                        <div class="stol ">
                            <div id="stol">
                                <div class="d-flex justify-content-center">
                                    <img src="{{ asset('image/zigir.png') }}" alt="">
                                </div>
                                <div class="d-flex justify-content-between">
                                    <img src="{{ asset('image/olot.png') }}" alt="">
                                    <img src="{{ asset('image/vogguri.png') }}" alt="">

                                </div>
                                <div class="d-flex justify-content-center">
                                    <img src="{{ asset('image/somsa.png') }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="header text-center my-lg-5 py-lg-5 my-5 px-3">
            @lang('site.wait')
        </div>


        <div class="owl-carousel owl-theme my-lg-5 py-lg-5  my-3">
            <div class="item"><img src="{{ asset('image/owlc2.jpg') }}" alt=""></div>
            <div class="item"><img src="{{ asset('image/owlc1.jpg') }}" alt=""></div>
            <div class="item"><img src="{{ asset('image/owlc3.jpg') }}" alt=""></div>
        </div>

    </div>

@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="{{ asset("js/main.js") }}"></script>



@endsection
