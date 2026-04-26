@extends('layouts.admin')

@section('content')
<style>
    .neon-wrapper { background-color: #0b0b10; border-radius: 15px; padding: 25px; }
    .neon-card { background: #12121c; border: 1px solid rgba(0, 243, 255, 0.3); border-radius: 12px; }
    .neon-text-blue { color: #00f3ff; text-shadow: 0 0 8px rgba(0, 243, 255, 0.4); }
    .neon-text-green { color: #00ff66; text-shadow: 0 0 8px rgba(0, 255, 102, 0.4); }
    .form-control-neon { background: #0f0f16; border: 1px solid rgba(0, 243, 255, 0.2); color: #fff; }
    .form-control-neon:focus { border-color: #00f3ff; box-shadow: 0 0 10px rgba(0, 243, 255, 0.2); color:#fff; background: #1a1a2e; }
    .btn-neon { border: 1px solid #00ff66; color: #00ff66; background: transparent; transition: 0.3s; }
    .btn-neon:hover { background: #00ff66; color: #000; box-shadow: 0 0 15px #00ff66; }
</style>

<div class="neon-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="neon-text-blue mb-0"><i class="bx bx-time-five me-2"></i> إدارة أوقات العمل: {{ $departmentName }}</h4>
        <a href="{{ route('admin.departments.queue', $id) }}" class="btn btn-outline-info">عرض طابور القسم <i class="bx bx-right-arrow-alt"></i></a>
    </div>

    @if(session('success'))
    <div class="alert alert-success bg-dark text-success border-success"><i class="bx bx-check"></i> {{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="neon-card p-4 h-100">
                <h5 class="neon-text-green mb-4">إضافة / تعديل يوم عمل</h5>
                <form action="{{ route('admin.departments.schedule.store', $id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted">اليوم</label>
                        <select class="form-select form-control-neon" name="day" required>
                            <option value="الأحد">الأحد</option>
                            <option value="الإثنين">الإثنين</option>
                            <option value="الثلاثاء">الثلاثاء</option>
                            <option value="الأربعاء">الأربعاء</option>
                            <option value="الخميس">الخميس</option>
                            <option value="الجمعة">الجمعة</option>
                            <option value="السبت">السبت</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label text-muted">وقت البدء</label>
                            <input type="time" class="form-control form-control-neon" name="start_time" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label text-muted">وقت الانتهاء</label>
                            <input type="time" class="form-control form-control-neon" name="end_time" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted">السعة القصوى (عدد المرضى)</label>
                        <input type="number" class="form-control form-control-neon" name="capacity" placeholder="مثال: 50" required>
                    </div>
                    <button class="btn btn-neon w-100 py-2"><i class="bx bx-save me-1"></i> حفظ الإعدادات</button>
                </form>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="neon-card p-0 overflow-hidden h-100">
                <div class="table-responsive">
                    <table class="table text-white mb-0 table-hover">
                        <thead style="background: rgba(0,243,255,0.05); border-bottom: 2px solid #00f3ff;">
                            <tr>
                                <th class="text-info">اليوم</th>
                                <th class="text-info">وقت البدء</th>
                                <th class="text-info">وقت الانتهاء</th>
                                <th class="text-info">سعة المرضى</th>
                                <th class="text-info text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workingDays as $day)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td><strong class="neon-text-blue">{{ $day->day }}</strong></td>
                                <td>{{ $day->start_time }}</td>
                                <td>{{ $day->end_time }}</td>
                                <td><span class="badge bg-dark text-success border border-success">{{ $day->capacity }} مريض</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger"><i class="bx bx-trash"></i></button>
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
