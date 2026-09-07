@extends('layouts.app')

@section('title')
    KPI | PSL Track
@endsection

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0">
                                PSL KPI Tracker
                            </h3>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="javascript: void(0);">Tracker</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        <a href="{{ route('kpi.index') }}">KPI Tracker</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="card-header d-flex justify-content-end mb-4">
                            <button type="button" class="btn btn-primary waves-effect btn-label waves-light"
                                onclick="window.location.href='{{ route('kpi.create') }}'"><i
                                    class="bx bx-plus label-icon"></i>Tambah KPI</button>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Card title</h5>
                                        <p class="card-text">This card has a regular title and short paragraphy of text
                                            below it.</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="card-text mb-0">
                                                    <small class="text-muted">Last updated 3 mins ago</small>
                                                </p>
                                            
                                                <a href="javascript: void(0);" class="card-link">
                                                    Detail >>
                                                </a>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Card title</h5>
                                        <p class="card-text">This card has a regular title and short paragraphy of text
                                            below it.</p>
                                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Card title</h5>
                                        <p class="card-text">This card has a regular title and short paragraphy of text
                                            below it.</p>
                                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
