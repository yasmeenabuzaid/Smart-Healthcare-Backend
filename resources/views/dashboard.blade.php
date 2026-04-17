@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-8 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">أهلاً بك في نظام الرعاية الذكية! 🎉</h5>
                            <p class="mb-4">
                                لديك <span class="fw-bold">5</span> طلبات انضمام جديدة من مستشفيات مختلفة بانتظار المراجعة والموافقة.
                            </p>
                            <a href="#" class="btn btn-sm btn-outline-primary">عرض الطلبات المعلقة</a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded" />
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">المستشفيات المعتمدة</span>
                            <h3 class="card-title mb-2">12</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/wallet-info.png') }}" alt="Credit Card" class="rounded" />
                                </div>
                            </div>
                            <span>الطلبات المرفوضة</span>
                            <h3 class="card-title text-nowrap mb-1">3</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
