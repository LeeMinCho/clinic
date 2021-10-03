@extends('layout.template')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('patient') }}" class="info-box">
                                <span class="info-box-icon">
                                    <i class="fas fa-book-medical"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Admission</span>
                                </div>
                                <!-- /.info-box-content -->
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="#" class="info-box">
                                <span class="info-box-icon">
                                    <i class="fas fa-stethoscope"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">EMR</span>
                                </div>
                                <!-- /.info-box-content -->
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="#" class="info-box">
                                <span class="info-box-icon">
                                    <i class="fas fa-prescription"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pharmacy</span>
                                </div>
                                <!-- /.info-box-content -->
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="#" class="info-box">
                                <span class="info-box-icon">
                                    <i class="fas fa-cash-register"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Chasier</span>
                                </div>
                                <!-- /.info-box-content -->
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection