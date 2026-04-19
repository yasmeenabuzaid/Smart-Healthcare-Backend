@extends('layouts.admin')

@section('content')
<div class="container-xxl grow container-p-y">
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="mb-3">إدارة طلبات التأمين</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control" placeholder="بحث بالاسم، رقم الهاتف، أو شركة التأمين...">
                </div>
                <div class="col-md-6">
                    <select id="statusFilter" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="pending" selected>قيد المراجعة (Pending)</option>
                        <option value="approved">مقبول</option>
                        <option value="rejected">مرفوض</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>اسم المريض</th>
                        <th>التواصل</th>
                        <th>شركة التأمين</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-center" id="paginationLinks"></div>
    </div>
</div>

<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">مراجعة بطاقة التأمين</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 text-center mb-3">
                        <h6 class="fw-bold">صورة البطاقة</h6>
                        <img id="insuranceImage" src="" alt="Insurance Card" class="img-fluid rounded border p-1" style="max-height: 300px; cursor: pointer;" onclick="window.open(this.src, '_blank')">
                        <small class="d-block mt-2 text-muted">اضغط على الصورة لتكبيرها</small>
                    </div>

                    <div class="col-md-6">
                        <h6 class="fw-bold">بيانات المريض</h6>
                        <p class="mb-1"><strong>الاسم:</strong> <span id="r_name"></span></p>
                        <p class="mb-1"><strong>الهاتف:</strong> <span id="r_phone"></span></p>
                        <p class="mb-1"><strong>شركة التأمين:</strong> <span id="r_company"></span></p>
                        <p class="mb-3"><strong>رقم البوليصة:</strong> <span id="r_number"></span></p>

                        <hr>
                        <h6 class="fw-bold">قرار الإدارة</h6>
                        <div class="mb-3">
                            <label class="form-label">ملاحظات (تظهر للمريض):</label>
                            <textarea id="adminNotes" class="form-control" rows="2" placeholder="اكتب سبب الرفض أو ملاحظات إضافية..."></textarea>
                        </div>
                        <div class="d-flex gap-2" id="actionButtons">
                            <button class="btn btn-success flex-fill" onclick="submitStatus('approved')">قبول البطاقة</button>
                            <button class="btn btn-danger flex-fill" onclick="submitStatus('rejected')">رفض البطاقة</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const headers = { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' };
    let currentRequestId = null;

    async function fetchInsurances(page = 1) {
        const search = document.getElementById('searchInput').value;
        const status = document.getElementById('statusFilter').value;

        const response = await fetch(`{{ route('admin.insurance.fetch') }}?page=${page}&search=${search}&status=${status}`);
        const data = await response.json();

        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if(data.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">لا توجد طلبات تأمين</td></tr>';
            return;
        }

        data.data.forEach(req => {
            let statusBadge = req.status === 'pending' ? 'bg-label-warning' : (req.status === 'approved' ? 'bg-label-success' : 'bg-label-danger');

            tbody.innerHTML += `
                <tr>
                    <td><strong>${req.patient_name}</strong></td>
                    <td>${req.patient_phone}</td>
                    <td>${req.insurance_company}</td>
                    <td><span class="badge ${statusBadge}">${req.status}</span></td>
                    <td>
                        <button onclick="reviewInsurance(${req.id})" class="btn btn-sm btn-info">عرض ومراجعة</button>
                    </td>
                </tr>
            `;
        });

        renderPagination(data);
    }

    function renderPagination(data) {
        const paginationLinks = document.getElementById('paginationLinks');
        paginationLinks.innerHTML = '';
        data.links.forEach(link => {
            if(link.url) {
                let pageNum = new URL(link.url).searchParams.get("page");
                paginationLinks.innerHTML += `<button onclick="fetchInsurances(${pageNum})" class="btn btn-sm ${link.active ? 'btn-primary' : 'btn-outline-primary'} mx-1">${link.label}</button>`;
            }
        });
    }

    async function reviewInsurance(id) {
        currentRequestId = id;
        const response = await fetch(`/admin/insurance/${id}`);
        const req = await response.json();

        // تعبئة البيانات في المودل
        document.getElementById('r_name').innerText = req.patient_name;
        document.getElementById('r_phone').innerText = req.patient_phone;
        document.getElementById('r_company').innerText = req.insurance_company;
        document.getElementById('r_number').innerText = req.insurance_number || 'غير محدد';
        document.getElementById('adminNotes').value = req.admin_notes || '';

        // عرض الصورة عبر الـ Accessor الذي أنشأناه
        document.getElementById('insuranceImage').src = req.image_url || 'https://via.placeholder.com/300?text=No+Image';

        // إخفاء أزرار الإجراءات إذا كان الطلب تمت معالجته مسبقاً (اختياري)
        document.getElementById('actionButtons').style.display = req.status === 'pending' ? 'flex' : 'none';

        new bootstrap.Modal(document.getElementById('reviewModal')).show();
    }

    async function submitStatus(status) {
        const notes = document.getElementById('adminNotes').value;
        if(status === 'rejected' && !notes.trim()) {
            alert('يجب كتابة ملاحظات عند رفض بطاقة التأمين لتوضيح السبب للمريض.');
            return;
        }

        const res = await fetch(`/admin/insurance/${currentRequestId}/status`, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({ status: status, admin_notes: notes })
        });

        if(res.ok) {
            bootstrap.Modal.getInstance(document.getElementById('reviewModal')).hide();
            fetchInsurances();
        }
    }

    document.getElementById('searchInput').addEventListener('input', () => fetchInsurances(1));
    document.getElementById('statusFilter').addEventListener('change', () => fetchInsurances(1));
    document.addEventListener('DOMContentLoaded', () => fetchInsurances(1));
</script>
@endsection
