<!doctype html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OLOTSOMSA - ORIGINAL</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
          integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
        <link href="{{ asset("fonts/font.woff2") }}"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/preloader.css') }}">

    @yield('header')

</head>

<body id="stop-scrolling">


<div class="preloader">
    <div class="preloader-img">
        <div class="animate-naqsh" style="top: 30%">
            <img src="{{ asset('image/ellipse.png') }}" class="" alt="">
        </div>
        <img src="{{ asset("image/olot.png") }}"  style="top: 30%" class="preloader-osh" alt="">
    </div>
    <div class="preloader-img" style="">
        <div class="animate-naqsh"  style="top: 70%">
            <img src="{{ asset('image/ellipse.png') }}" class="" alt="">
        </div>
        <img src="{{ asset("image/osh.png") }}"  style="top: 70%" class="preloader-osh" alt="">
    </div>
</div>
<x-site-navbar></x-site-navbar>

<x-login></x-login>

<div>
    @yield('content')
</div>

<x-site-footer></x-site-footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"
        integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2"
        crossorigin="anonymous"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://unpkg.com/imask"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

{{--<script src="//cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>--}}
{{--<script src="{{ asset("js/countup.js") }}"></script>--}}


{{--<script src="{{ asset('js/jquery.preloadinator.js') }}"></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/gsap.min.js"></script>--}}

<script src="{{ asset("js/footer.js") }}">

</script>
@yield('script')


</body>
</html>
