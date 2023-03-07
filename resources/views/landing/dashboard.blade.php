@extends('layouts.app')

@section('tile')
Dashboard
@endsection

@section('content')
<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">

        <div class="row">
            <div class="col-12">
                <div class="page_title_box d-flex align-items-center justify-content-between">
                    <div class="page_title_left">
                        <h3 class="f_s_30 f_w_700 text_white">Dashboard</h3>
                        <ol class="breadcrumb page_bradcam mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard </a></li>
                        </ol>
                    </div>
                    <a href="#" class="white_btn3">View All</a>
                </div>
            </div>
        </div>
        <div class="row ">
            <div class="col-lg-12">
                <div class="white_card card_height_100 mb_30 ">
                    <div class="white_card_header">
                        <div class="box_header m-0">
                            <div class="main-title">
                                <h3 class="m-0">Daftar absen masuk hari ini</h3>
                            </div>
                        </div>
                    </div>
                    <div class="white_card_body QA_section">
                        <div class="QA_table ">
                            <table class="table p-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Tanngal</th>
                                        <th scope="col">Jam Masuk</th>
                                        <th scope="col">Jam Pulang</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $datas as $data )
                                    <tr>
                                        <td>
                                            <div class="customer d-flex align-items-center">
                                                <div class="thumb_34 mr_15 mt-0"><img class="img-fluid radius_50"
                                                        src="{{ asset('storage/'.$data->User->foto) }}"
                                                        style="width: 150%; height: 100%;" alt=""></div>
                                                <span class="f_s_14 f_w_400">{{ $data->User->name }}</span>
                                            </div>
                                        </td>
                                        <td class="f_s_14 f_w_400 ">{{ $data->date }}</td>
                                        <td class="f_s_14 f_w_400 ">{{ $data->clock_in }}</td>
                                        <td class="f_s_14 f_w_400 ">{{ $data->clock_out }}</td>
                                        <td class="f_s_14 f_w_400 ">{{ $data->status }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection