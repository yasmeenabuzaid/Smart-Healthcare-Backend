@extends('layouts.admin')

@section('content')
<style>
    /* تنسيقات السايبر بانك / النيون الخاصة بالإدارة */
    .neon-wrapper {
        background-color: #12121a;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 0 20px rgba(0, 243, 255, 0.05);
    }

    .neon-border-green {
        border: 1px solid rgba(0, 255, 102, 0.3);
        box-shadow: 0 0 15px rgba(0, 255, 102, 0.05);
        background: linear-gradient(145deg, #1a1a2e, #161625);
        transition: all 0.3s ease;
        border-radius: 12px;
    }

    .neon-border-green:hover {
        box-shadow: 0 0 20px rgba(0, 255, 102, 0.15);
        border-color: rgba(0, 255, 102, 0.6);
    }

    .neon-text-blue { color: #00f3ff; text-shadow: 0 0 8px rgba(0, 243, 255, 0.5); }
    .neon-text-green { color: #00ff66; text-shadow: 0 0 8px rgba(0, 255, 102, 0.5); }

    .form-control-neon {
        background: #0f0f16;
        border: 1px solid rgba(0, 243, 255, 0.2);
        color: #fff;
        border-radius: 8px;
    }

    .form-control-neon:focus {
        background: #1a1a2e;
        border-color: #00f3ff;
        box-shadow: 0 0 10px rgba(0, 243, 255, 0.3);
        color: #fff;
    }

    .btn-submit-neon {
        background: transparent;
        border: 2px solid #00ff66;
        color: #00ff66;
        font-weight: bold;
        border-radius: 8px;
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-submit-neon:hover {
        background: #00ff66;
        color: #000;
        box-shadow: 0 0 15px #00ff66;
        transform: translateY(-2px);
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
    }

    .table-neon tbody tr {
        border-bottom: 1px solid rgba(0, 243, 255, 0.1);
        transition: background-color 0.2s;
    }

    .table-neon tbody tr:hover {
        background-color: rgba(0, 243, 255, 0.05);
    }

    .status-badge-active {
        background: rgba(0, 255, 102, 0.1);
        border: 1px solid #00ff66;
        color: #00ff66;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.85rem;
    }

    .status-badge-maintenance {
        background: rgba(255, 193, 7, 0.1);
        border: 1px solid #ffc107;
        color: #ffc107;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.85rem;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="neon-wrapper">

            @if(session('success'))
            <div class="alert alert-success bg-dark border-success text-success d-flex align-items-center mb-4" style="box-shadow: 0 0 10px rgba(0,255,102,0.2);" role="alert">
                <i class="bx bx-check-circle fs-4 me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
            @endif

            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card neon-border-green p-4 h-100">
                        <h5 class="neon-text-blue mb-4 d-flex align-items-center">
                            <i class="bx bx-layer-plus fs-3 me-2"></i> إضافة قسم للمستشفيات
                        </h5>
                        <form action="{{ route('admin.departments.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-muted">اسم القسم / الخدمة</label>
                                <input type="text" class="form-control-neon form-control" name="name" placeholder="مثال: قسم الأشعة..." required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted">التصنيف</label>
                                <select class="form-select form-control-neon" name="type" required>
                                    <option value="" disabled selected>اختر التصنيف...</option>
                                    <option value="قسم طبي">قسم طبي (سريري)</option>
                                    <option value="خدمة مساندة">خدمة طبية مساندة</option>
                                    <option value="قسم إداري">قسم إداري</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted">حالة التشغيل الافتراضية</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input name="status" class="form-check-input" type="radio" value="active" id="statusActive" checked>
                                        <label class="form-check-label text-white" for="statusActive"> مفعل </label>
                                    </div>
                                    <div class="form-check">
                                        <input name="status" class="form-check-input" type="radio" value="maintenance" id="statusMaintenance">
                                        <label class="form-check-label text-muted" for="statusMaintenance"> غير مفعل </label>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-submit-neon w-100 py-2 d-flex justify-content-center align-items-center">
                                <i class="bx bx-save me-2"></i> حفظ في النظام
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-8 mb-4">
                    <div class="card neon-border-green p-0 overflow-hidden h-100">
                        <div class="p-4 border-bottom border-secondary d-flex justify-content-between align-items-center">
                            <h5 class="neon-text-green mb-0 d-flex align-items-center">
                                <i class="bx bx-network-chart fs-4 me-2"></i> القاموس الطبي للأقسام
                            </h5>
                        </div>
                        <div class="table-responsive" style="min-height: 400px;">
                            <table class="table table-neon table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اسم القسم</th>
                                        <th>التصنيف</th>
                                        <th>تاريخ الإضافة</th>
                                        <th>الحالة</th>
                                        <th class="text-center">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($departments as $index => $dept)
                                    <tr>
                                        <td class="text-muted">{{ $index + 1 }}</td>
                                        <td><strong class="neon-text-blue">{{ $dept->name }}</strong></td>
                                        <td>
                                            <i class="bx {{ $dept->type == 'قسم طبي' ? 'bx-plus-medical text-danger' : 'bx-cog text-secondary' }} me-1"></i>
                                            {{ $dept->type }}
                                        </td>
                                        <td class="text-muted"><small>{{ $dept->created_at }}</small></td>
                                        <td>
                                            @if($dept->status == 'active')
                                                <span class="status-badge-active">مفعل</span>
                                            @else
                                                <span class="status-badge-maintenance">غير مفعل</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-icon btn-outline-info me-1" title="تعديل"><i class="bx bx-edit-alt"></i></button>
                                            <button class="btn btn-sm btn-icon btn-outline-danger" title="حذف"><i class="bx bx-trash"></i></button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            لا يوجد أقسام مسجلة في القاموس حالياً.
                                        </td>
                                    </tr>
                                    @endforelse
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
