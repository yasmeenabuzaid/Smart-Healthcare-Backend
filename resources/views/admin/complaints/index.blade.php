@extends('layouts.admin')

@section('content')
<style>
    /* ---------------------------------------------------
       Cyberpunk / Neon Theme Styles
       --------------------------------------------------- */
    .neon-wrapper {
        background-color: #12121a; /* خلفية داكنة جداً */
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 0 20px rgba(0, 243, 255, 0.05);
    }

    .neon-title {
        color: #00f3ff;
        text-shadow: 0 0 10px rgba(0, 243, 255, 0.5);
        font-weight: 700;
        margin-bottom: 20px;
    }

    .neon-card {
        background: linear-gradient(145deg, #1a1a2e, #161625);
        border: 1px solid rgba(0, 243, 255, 0.3);
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .neon-card:hover {
        border-color: #00f3ff;
        box-shadow: 0 0 15px rgba(0, 243, 255, 0.2), inset 0 0 10px rgba(0, 243, 255, 0.1);
    }

    .neon-table-container {
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid rgba(0, 255, 102, 0.2);
    }

    .table-neon {
        margin-bottom: 0;
        background-color: transparent;
        color: #e2e2e2;
    }

    .table-neon thead th {
        background-color: #0f0f16;
        color: #00ff66;
        border-bottom: 2px solid #00ff66;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-shadow: 0 0 5px rgba(0, 255, 102, 0.4);
    }

    .table-neon tbody tr {
        border-bottom: 1px solid rgba(0, 243, 255, 0.1);
        transition: background-color 0.2s;
    }

    .table-neon tbody tr:hover {
        background-color: rgba(0, 243, 255, 0.05);
    }

    .table-neon td {
        vertical-align: middle;
        color: #b0b0c0;
    }

    /* أزرار النيون */
    .btn-neon-blue {
        background-color: transparent;
        color: #00f3ff;
        border: 1px solid #00f3ff;
        border-radius: 8px;
        padding: 5px 15px;
        transition: all 0.3s;
    }
    .btn-neon-blue:hover {
        background-color: #00f3ff;
        color: #000;
        box-shadow: 0 0 10px #00f3ff;
    }

    .btn-neon-green {
        background-color: transparent;
        color: #00ff66;
        border: 1px solid #00ff66;
        border-radius: 8px;
        padding: 5px 15px;
        transition: all 0.3s;
    }
    .btn-neon-green:hover {
        background-color: #00ff66;
        color: #000;
        box-shadow: 0 0 10px #00ff66;
    }

    /* علامات الحالة (Badges) */
    .badge-neon-pending {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        border: 1px solid #ffc107;
        box-shadow: 0 0 5px rgba(255, 193, 7, 0.4);
    }

    .badge-neon-resolved {
        background-color: rgba(0, 255, 102, 0.1);
        color: #00ff66;
        border: 1px solid #00ff66;
        box-shadow: 0 0 5px rgba(0, 255, 102, 0.4);
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="neon-wrapper">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="neon-title mb-0">
                    <i class="bx bx-message-alt-error fs-3 me-2 align-middle"></i>
                    صندوق الشكاوى والاقتراحات
                </h4>
                <button class="btn btn-neon-green">
                    <i class="bx bx-export me-1"></i> تصدير التقرير
                </button>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card neon-card p-3 d-flex flex-row align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary text-primary" style="background: rgba(0,243,255,0.1) !important; color: #00f3ff !important; border: 1px solid #00f3ff;">
                                <i class="bx bx-envelope fs-4"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0 text-white">إجمالي الرسائل</h6>
                            <h3 class="mb-0" style="color: #00f3ff;">124</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card neon-card p-3 d-flex flex-row align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-warning text-warning" style="background: rgba(255,193,7,0.1) !important; color: #ffc107 !important; border: 1px solid #ffc107;">
                                <i class="bx bx-time-five fs-4"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0 text-white">قيد الانتظار</h6>
                            <h3 class="mb-0 text-warning">18</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card neon-card p-3 d-flex flex-row align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-success text-success" style="background: rgba(0,255,102,0.1) !important; color: #00ff66 !important; border: 1px solid #00ff66;">
                                <i class="bx bx-check-double fs-4"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0 text-white">تم الحل</h6>
                            <h3 class="mb-0" style="color: #00ff66;">106</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="neon-table-container">
                <div class="table-responsive">
                    <table class="table table-neon">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>المرسل</th>
                                <th>النوع</th>
                                <th>الموضوع</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($complaints as $complaint)
                            <tr>
                                <td><strong style="color: #00f3ff;">#{{ $complaint->id }}</strong></td>
                                <td class="text-white">{{ $complaint->sender_name }}</td>
                                <td>
                                    @if($complaint->type == 'شكوى')
                                        <span class="text-danger"><i class="bx bx-error-circle me-1"></i> شكوى</span>
                                    @else
                                        <span style="color: #00f3ff;"><i class="bx bx-bulb me-1"></i> اقتراح</span>
                                    @endif
                                </td>
                                <td>{{ $complaint->subject }}</td>
                                <td>{{ $complaint->date }}</td>
                                <td>
                                    @if($complaint->status == 'pending')
                                        <span class="badge badge-neon-pending rounded-pill px-3">قيد المراجعة</span>
                                    @else
                                        <span class="badge badge-neon-resolved rounded-pill px-3">تم الحل</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-neon-blue me-2" title="عرض التفاصيل">
                                        <i class="bx bx-show"></i>
                                    </button>
                                    <button class="btn btn-sm btn-neon-green" title="تحديد كمنجز">
                                        <i class="bx bx-check"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">لا توجد شكاوى أو اقتراحات حالياً.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
