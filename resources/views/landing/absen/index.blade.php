@extends('layouts.app')

@section('title')
Pengumuman
@endsection

@push('add-style')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.13.1/datatables.min.css" />
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
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="white_card card_height_100 mb_30">
                    <div class="white_card_body">
                        <div class="QA_section">
                            <div class="white_box_tittle list_header" style="margin-top: 20px">
                                <h4>Data Absen Crew</h4>
                            </div>
                            <div class="QA_table mb_30">
                                <table class="table lms_table_active ">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nama Crew</th>
                                            <th scope="col">Tanggal</th>
                                            <th scope="col">Jam Masuk</th>
                                            <th scope="col">Jam Pulang</th>
                                            <th scope="col">Foto Masuk</th>
                                            <th scope="col">Foto Pulang</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ( $absen as $data )
                                        <tr>
                                            <th>{{ $data->title }}</th>
                                            <th>{{ $data->date }}</th>
                                            <th>{{ $data->clock_in }}</th>
                                            <th>{{ $data->clock_out }}</th>
                                            <th>{{ $data->incoming_photo }}</th>
                                            <th>{{ $data->home_photo }}</th>
                                            <th>{{ $data->status }}</th>
                                            {{-- <td>
                                                <img src="{{ asset('storage/'.$data->picture) }}" alt=""
                                                    style="width: 150px" class="img-thumbnail">
                                            </td> --}}
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
            </div>
        </div>
    </div>
</div>
@endsection

@push('add-script')
<script>
    $(document).ready( function () {
        $('#myTable').DataTable();
    } );
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
@endpush