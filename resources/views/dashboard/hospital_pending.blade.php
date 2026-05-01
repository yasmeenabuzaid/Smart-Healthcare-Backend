@extends('layouts.admin')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6 text-center">
        <div class="card border-warning">
            <div class="card-body py-5">
                <i data-feather="clock" class="text-warning mb-3" style="width: 60px; height: 60px;"></i>
                <h3 class="mb-3">طلبك قيد المراجعة</h3>
                <p class="text-muted">لقد استلمنا طلب تسجيل المستشفى الخاص بك وهو الآن قيد المراجعة من قبل إدارة النظام. سيتم تفعيل الميزات (مثل إضافة الموظفين) فور الموافقة عليه.</p>
            </div>
        </div>
    </div>
</div>
@endsection
