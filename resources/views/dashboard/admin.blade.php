@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">{{ __('Welcome to Smart Healthcare System! 🎉') }}</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card grid-margin">
            <div class="card bg-primary text-white">
                <div class="card-body d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-2">{{ __('You have') }} <span class="fw-bolder fs-4">5</span> {{ __('new join requests waiting for review.') }}</h5>
                        <p class="text-white-50 mb-0">{{ __('Review and approve hospitals to expand your network.') }}</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="{{ route('admin.approvals.index') }}" class="btn btn-light fw-bolder">{{ __('View Pending Requests') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-4 stretch-card grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">{{ __('Approved Hospitals') }}</h6>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-12 col-xl-5">
                            <h3 class="mb-2">12</h3>
                            <div class="d-flex align-items-baseline">
                                <p class="text-success">
                                    <span>+3.3%</span>
                                    <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                </p>
                            </div>
                        </div>
                        <div class="col-6 col-md-12 col-xl-7 d-flex justify-content-end align-items-center">
                            <i data-feather="activity" class="text-primary" style="width: 40px; height: 40px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 stretch-card grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">{{ __('Rejected Requests') }}</h6>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-12 col-xl-5">
                            <h3 class="mb-2">3</h3>
                            <div class="d-flex align-items-baseline">
                                <p class="text-danger">
                                    <span>-2.8%</span>
                                    <i data-feather="arrow-down" class="icon-sm mb-1"></i>
                                </p>
                            </div>
                        </div>
                        <div class="col-6 col-md-12 col-xl-7 d-flex justify-content-end align-items-center">
                            <i data-feather="x-circle" class="text-danger" style="width: 40px; height: 40px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
