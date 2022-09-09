@extends('layouts.site')

@section('header')
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
@endsection


@section('content')

    <div id="branches">
        <div class="row gap-lg-60 gap-sm-20">
            @foreach($branches as $branch)
                <div class="col-12" data-aos="fade-up">
                    <div class="card">
                        <div class="d-lg-flex justify-content-between">
                            <div>
                                <div class="header-text">{{ $branch->origin }}</div>
                                <div class="text">{{ $branch->title }}</div>
                            </div>
                            <div>
                                <div class="header-text">@lang('site.ishvaqtlari')</div>
                                <div class="text">{{ $branch->starts }} __ {{ $branch->ends }}</div>
                            </div>
                            <div>
                                <div class="header-text">@lang('site.ishkunlari')</div>
                                <div class="text">@lang('site.harkuni')</div>
                            </div>
                            <div class="d-sm-none1">
                                <button class="bg-transparent border-0 d-block m-auto placemark" data-id="{{ $branch->id }}">
                                    <img src="{{ asset('image/location.png') }}" alt="">
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="d-lg-none card mt-2 w-75 mx-auto">
                        <button class="bg-transparent border-0 d-block m-auto placemark" data-id="{{ $branch->id }}">
                            <img src="{{ asset('image/location.png') }}" alt="">
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-100"></div>

        <div id="map"  style="width: 100%; height: 400px; border-radius: 20px; overflow: hidden"></div>

    </div>




@endsection


@section('script')

    <script>

        ymaps.ready(init);
        var regions
        var locations  = getLocations()

        function init() {
            var coordinates = []
            var myMap = new ymaps.Map("map", {
                center: [locations[0].latitude, locations[0].longitude],
                zoom: 11,
                controls: ['zoomControl', 'typeSelector', 'fullscreenControl']
            });
            for (let i =0; i < locations.length; i++)
            {
                coordinates = [locations[i].latitude,locations[i].longitude]
                myGeoObject = new ymaps.GeoObject({
                    // The geometry description.
                    geometry: {
                        type: "Point",
                        coordinates
                    },
                })
                myMap.geoObjects
                    .add(myGeoObject)
            }

            myMap.geoObjects
                .add(myGeoObject)

            $(".placemark").click(function (){
                var id = $(this).data('id')
                $('html, body').animate({
                    scrollTop: $("#map").offset().top - 200
                }, 1000);

                var location = getLocationByID(id)
                setTimeout(function (){
                    myMap.setCenter([location.latitude, location.longitude], 13)
                },1000)

            })

        }

        $(".placemark").click(function (){
            var id = $(this).data('id')
            $('html, body').animate({
                scrollTop: $("#map").offset().top
            }, 1000);

        })

        function getLocationByID(id)
        {
            for(let i =0 ; i < locations.length; i++)
            {
                if (id === locations[i].id)
                    return locations[i]
            }
            return  false
        }


        function getLocations() {

            var locations = null

            $.ajax({
                url: "/api/locations",
                type: "GET",
                async: false,
                success: function (response) {
                    locations = response.data
                }
            });


            return locations
        }


    </script>

@endsection
