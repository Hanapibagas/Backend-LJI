@extends('layouts.app')

@section('title')
Jam dan lokasi
@endsection

@push('add-style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin="" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
    integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
    crossorigin=""></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.0.0/dist/geosearch.css" />
<script src="https://unpkg.com/leaflet-geosearch@3.1.0/dist/geosearch.umd.js"></script>
<style>
    #leafletMap-registration {
        height: 300px;
    }
</style>
@endpush

@section('content')
@if (session('status'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Sukses!',
        text : "{{ session('status') }}",
    });
</script>
@endif
<div class="main_content_iner ">
    <div class="container-fluid p-0 sm_padding_15px">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="white_card card_height_100 mb_30">
                    <div class="white_card_header">
                        <div class="box_header m-0">
                            <div class="main-title">
                                <h3 class="m-0">Setting Jam pulang dan jam masuk</h3>
                            </div>
                        </div>
                    </div>
                    <div class="white_card_body">
                        <form id="edit-jam" name="edit-jam" class="forms-sample">
                            <input type="hidden" name="id" value="{{ $datas->id }}">
                            <div class="mb-3">
                                <label class="form-label" for="exampleInputEmail1">Jam masuk</label>
                                <input type="time" class="form-control" name="clock_in" value="{{ $datas->clock_in }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="exampleInputEmail1">Jam pulang</label>
                                <input type="time" class="form-control" name="home_time"
                                    value="{{ $datas->home_time }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Perbarui jam</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="white_card card_height_100 mb_30">
                    <div class="white_card_header">
                        <div class="box_header m-0">
                            <div class="main-title">
                                <h3 class="m-0">Setting Lokasi Kerja</h3>
                            </div>
                        </div>
                    </div>
                    <div class="white_card_body">
                        <form class="forms-sample" id="form-tambah-edit" name="form-tambah-edit">
                            <input type="hidden" name="id" value="{{ $datas->id }}">
                            <input type="hidden" class="form-control" id="koordinat" name="koordinat"
                                value="{{ $datas->location }}">
                            <input type="hidden" id="location" name="location">
                            <div class="mb-3">
                                <label class="form-label" for="exampleInputEmail1">Lokasi Kerja</label>
                                <input type="string" name="ket" value="{{ $datas->ket }}" class="form-control"
                                    id="exampleInputEmail1" aria-describedby="emailHelp">
                            </div>
                            <div class="mb-3">
                                <div id="leafletMap-registration"></div>
                                <p class="mb-0 text-danger" id="latlong"></p>
                            </div>
                            <div class=" row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Simpan Lokasi Kerja</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('add-script')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script type="text/javascript">
    $(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            });

        $('#edit-jam').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                var form = $('form');
                $.ajax({
                    type: "POST",
                    url: "{{ route('update_setting_jam_kantor') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: 'Data berhasil di update!'
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr);
                    }
                });
            });

        $('#form-tambah-edit').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            var form = $('form');
            $.ajax({
                type: "POST",
                url: "{{ route('lokasiKantor.update') }}",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: 'Data berhasil di update!'
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops....',
                        text: 'Terjadi Kesalahan'
                    })
                }
            });
        });

        const providerOSM = new GeoSearch.OpenStreetMapProvider();

        var lokasi = document.getElementById('koordinat').value;
        var koordinat = lokasi.split(',');

        var leafletMap = L.map('leafletMap-registration', {
            fullscreenControl: true,
            // OR
            fullscreenControl: {
                pseudoFullscreen: false
            },
            minZoom: 2
        }).setView([koordinat[0], koordinat[1]], 14);

        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(leafletMap);

        theMarker = L.marker([koordinat[0], koordinat[1]]).addTo(leafletMap);

        leafletMap.on('click', function(e) {
            let latitude = e.latlng.lat.toString().substring(0, 15);
            let longitude = e.latlng.lng.toString().substring(0, 15);

            if (theMarker != undefined) {
                leafletMap.removeLayer(theMarker);
            };
            theMarker = L.marker([latitude, longitude]).addTo(leafletMap);

            let lokasiKantor = latitude + ',' + longitude;

            document.getElementById("location").value = lokasiKantor;
        });

        const search = new GeoSearch.GeoSearchControl({
            provider: providerOSM,
            style: 'bar',
            searchLabel: 'c a r i',
            showMarker: false
        });

        leafletMap.addControl(search);

</script>
@endpush