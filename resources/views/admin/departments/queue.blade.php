@extends('layouts.admin')

@section('content')
<style>
    .queue-wrapper { background-color: #050508; border-radius: 20px; padding: 30px; }
    .card-current {
        background: linear-gradient(145deg, #0a192f, #020c1b);
        border: 2px solid #00f3ff;
        box-shadow: 0 0 30px rgba(0, 243, 255, 0.2);
        text-align: center;
    }
    .ticket-number { font-size: 5rem; font-weight: 900; color: #00f3ff; text-shadow: 0 0 20px rgba(0, 243, 255, 0.8); line-height: 1; }
    .card-waiting { background: #0f0f16; border: 1px solid rgba(0, 255, 102, 0.2); }
    .neon-text-green { color: #00ff66; }
</style>

<div class="queue-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-3">
        <h3 class="text-white mb-0"><i class="bx bx-street-view me-2" style="color:#00f3ff;"></i> شاشة المراقبة المباشرة: {{ $departmentName }}</h3>
        <div>
            <span class="badge bg-success shadow-sm" style="box-shadow: 0 0 10px #00ff66 !important;"><span class="spinner-grow spinner-grow-sm align-middle me-1"></span> البث مباشر</span>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card card-current p-5 h-100 d-flex flex-column justify-content-center">
                <h4 class="text-muted text-uppercase tracking-wide mb-3">جاري الخدمة الآن</h4>
                <div class="ticket-number mb-4">{{ $currentServing->ticket }}</div>
                <h3 class="text-white mb-2">{{ $currentServing->patient_name }}</h3>
                <p class="text-muted mb-0"><i class="bx bx-time"></i> وقت الدخول: {{ $currentServing->time_started }}</p>
                <div class="mt-5">
                    <button class="btn btn-outline-info btn-lg px-4 me-2">النداء التالى <i class="bx bx-skip-next"></i></button>
                    <button class="btn btn-outline-success btn-lg px-4">إنهاء <i class="bx bx-check-double"></i></button>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card card-waiting p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="neon-text-green mb-0"><i class="bx bx-list-ul me-2"></i> قائمة الانتظار الحالية</h4>
                    <span class="badge bg-dark text-white border border-secondary fs-6">{{ count($waitingList) }} مرضى في الانتظار</span>
                </div>

                <div class="table-responsive">
                    <table class="table text-white mb-0">
                        <thead style="border-bottom: 1px solid rgba(0, 255, 102, 0.4);">
                            <tr>
                                <th class="text-success">رقم التذكرة</th>
                                <th class="text-success">اسم المريض</th>
                                <th class="text-success">وقت الانتظار</th>
                                <th class="text-success text-center">إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($waitingList as $patient)
                            <tr style="border-bottom: 1px dashed rgba(255,255,255,0.1);">
                                <td><h5 class="mb-0 text-white" style="letter-spacing: 1px;">{{ $patient->ticket }}</h5></td>
                                <td class="text-muted">{{ $patient->patient_name }}</td>
                                <td><i class="bx bx-timer text-warning me-1"></i> {{ $patient->wait_time }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-dark text-info border-info">تخطي</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
