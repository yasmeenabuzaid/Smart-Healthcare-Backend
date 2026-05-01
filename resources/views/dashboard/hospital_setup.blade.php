@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="card-title text-primary mb-4">إكمال بيانات المستشفى</h4>
                <p class="text-muted mb-4">أهلاً بك! لإكمال عملية التسجيل وتفعيل حسابك، يرجى تعبئة بيانات المستشفى الخاصة بك لتقديمها للإدارة.</p>

                <form action="{{ route('hospital.setup.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">اسم المستشفى (عربي) *</label>
                            <input type="text" name="name_ar" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">اسم المستشفى (إنجليزي) *</label>
                            <input type="text" name="name_en" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">رقم الهاتف *</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">نوع المستشفى *</label>
                            <select name="hospital_type_id" class="form-control" required>
                                <option value="">اختر النوع...</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name_ar }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">المدينة *</label>
                            <select name="city_id" class="form-control" required>
                                <option value="">اختر المدينة...</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name_ar ?? $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold">العنوان التفصيلي (عربي) *</label>
                            <input type="text" name="address_ar" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">رقم الترخيص (إن وجد)</label>
                        <input type="text" name="license_number" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-3 py-2">تقديم الطلب للإدارة</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
