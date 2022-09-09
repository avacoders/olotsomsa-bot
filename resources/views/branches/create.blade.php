@extends('layouts.main')

@section('style')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
          integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
          crossorigin=""/>

@endsection


@section('content')

    <div class="card shadow rounded">
        <div class="card-header">
            <h3>Kategoriya qo'shish</h3>
        </div>

        <div class="card-body">

            <form action="{{ route('branch.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="title" class="">Nomi</label>
                            <input name="title" id="title" type="text" class="form-control">
                            @error('title')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="origin" class="">Kelib chiqishi</label>
                            <input name="origin" id="origin" type="text" class="form-control">
                            @error('latitude')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>



                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="starts" class="">Ish boshlanish vaqti</label>
                            <input name="starts" id="starts" type="text" class="form-control">
                            @error('starts')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="ends" class="">Ish tugash vaqti</label>
                            <input name="ends" id="ends" type="text" class="form-control">
                            @error('ends')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div id="map" style="height: 400px"></div>

                <div class="form-row">

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="latitude" class="">Logitude</label>
                            <input name="latitude" id="latitude" type="text" class="form-control">
                            @error('latitude')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="longitude" class="">Longitude</label>
                            <input name="longitude" id="longitude" type="text" class="form-control">
                            @error('longitude')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                </div>
                <input type="submit" class="mt-2 btn btn-primary" value="Qo'shish">
            </form>


        </div>

    </div>


@endsection


@section('script')
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
            integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
            crossorigin=""></script>

    <script>
        var map = L.map('map').setView([41.328829, 69.255767], 11.6);
        var theMarker = null;
        var API_KEY = "AIzaSyCZpt7uFORIrboUSXLpHTFehR5OJIEyeYY"
        var lat
        var lng

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);


        map.on('click', function (e) {
            lat = e.latlng.lat;
            lon = e.latlng.lng;

            $("#longitude").val(lon)
            $("#latitude").val(lat)

            if (theMarker != undefined) {
                map.removeLayer(theMarker);
            }
            //Add a marker to show where you clicked.
            theMarker = L.marker([lat, lon]).addTo(map);
        });

        console.log(getCoordinates("Tashkent"));
        $("#title").change(function () {
            var coordinates = getCoordinates($(this).val())

            $("#longitude").val(coordinates[0])
            $("#latitude").val(coordinates[1])
            map.setView(coordinates)
            if (theMarker != undefined) {
                map.removeLayer(theMarker);
            }
            theMarker = L.marker(coordinates).addTo(map);

        })


        var latlng = []

        function getCoordinates(address) {
            $.ajax(
                {
                    url: "https://maps.googleapis.com/maps/api/geocode/json?address=" + address + '&key=' + API_KEY,
                    type: "get",
                    async: false,
                    dataType: "json",
                    success: function (data) {
                        if (data.results.length) {
                            const latitude = data.results[0].geometry.location.lat;
                            const longitude = data.results[0].geometry.location.lng;
                            latlng = [latitude, longitude]
                        }

                    },
                })

            return latlng

        }


    </script>

@endsection
