@extends('layouts.admin')

@section('content')
<div class="container-xxl grow container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">طلبات انضمام المستشفيات</h5>
            <select id="statusFilter" class="form-select w-auto">
                <option value="pending">الطلبات المعلقة</option>
                <option value="approved">الطلبات المقبولة</option>
                <option value="rejected">الطلبات المرفوضة</option>
            </select>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>مقدم الطلب</th>
                        <th>اسم المستشفى</th>
                        <th>تاريخ الطلب</th>
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

<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تفاصيل الطلب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailsBody">
                </div>
            <div class="modal-footer" id="actionButtons">
                <button type="button" class="btn btn-success" id="btnApprove">موافقة (Approve)</button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">رفض (Reject)</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">سبب الرفض</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <textarea id="rejectionReason" class="form-control" rows="3" placeholder="اكتب سبب الرفض هنا..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnConfirmReject">تأكيد الرفض</button>
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

    // 1. جلب البيانات
    async function fetchRequests(page = 1) {
        const status = document.getElementById('statusFilter').value;
        const response = await fetch(`{{ route('admin.approvals.fetch') }}?page=${page}&status=${status}`);
        const data = await response.json();

        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if(data.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">لا توجد طلبات</td></tr>';
            return;
        }

        data.data.forEach(req => {
            let statusBadge = req.status === 'pending' ? 'bg-label-warning' : (req.status === 'approved' ? 'bg-label-success' : 'bg-label-danger');
            let date = new Date(req.created_at).toLocaleDateString('ar-EG');

            tbody.innerHTML += `
                <tr>
                    <td>${req.requester_name}</td>
                    <td><strong>${req.hospital_name}</strong></td>
                    <td>${date}</td>
                    <td><span class="badge ${statusBadge}">${req.status}</span></td>
                    <td>
                        <button onclick="showDetails(${req.id})" class="btn btn-sm btn-info">عرض ومراجعة</button>
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
                paginationLinks.innerHTML += `<button onclick="fetchRequests(${pageNum})" class="btn btn-sm ${link.active ? 'btn-primary' : 'btn-outline-primary'} mx-1">${link.label}</button>`;
            }
        });
    }

    // 2. عرض التفاصيل
    async function showDetails(id) {
        currentRequestId = id;
        const response = await fetch(`/admin/approvals/${id}`);
        const req = await response.json();

        document.getElementById('detailsBody').innerHTML = `
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>اسم المالك:</strong> ${req.requester_name}</li>
                <li class="list-group-item"><strong>الإيميل:</strong> ${req.requester_email}</li>
                <li class="list-group-item"><strong>رقم الهاتف:</strong> ${req.requester_phone}</li>
                <li class="list-group-item"><strong>المستشفى:</strong> ${req.hospital_name}</li>
                <li class="list-group-item"><strong>العنوان:</strong> ${req.hospital_address}</li>
                ${req.rejection_reason ? `<li class="list-group-item text-danger"><strong>سبب الرفض:</strong> ${req.rejection_reason}</li>` : ''}
            </ul>
        `;

        // إخفاء أزرار الأكشن إذا لم يكن الطلب معلقاً
        document.getElementById('actionButtons').style.display = req.status === 'pending' ? 'flex' : 'none';

        new bootstrap.Modal(document.getElementById('detailsModal')).show();
    }

    // 3. الموافقة
    document.getElementById('btnApprove').addEventListener('click', async () => {
        if(!confirm('هل أنت متأكد من قبول هذا المستشفى وإنشاء حساب له؟')) return;

        const res = await fetch(`/admin/approvals/${currentRequestId}/approve`, { method: 'POST', headers: headers });
        if(res.ok) {
            bootstrap.Modal.getInstance(document.getElementById('detailsModal')).hide();
            fetchRequests();
        }
    });

    // 4. الرفض
    document.getElementById('btnConfirmReject').addEventListener('click', async () => {
        const reason = document.getElementById('rejectionReason').value;
        if(!reason) return alert('يجب كتابة سبب الرفض');

        const res = await fetch(`/admin/approvals/${currentRequestId}/reject`, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({ rejection_reason: reason })
        });

        if(res.ok) {
            document.getElementById('rejectionReason').value = '';
            bootstrap.Modal.getInstance(document.getElementById('rejectModal')).hide();
            bootstrap.Modal.getInstance(document.getElementById('detailsModal')).hide();
            fetchRequests();
        }
    });

    // Event Listeners
    document.getElementById('statusFilter').addEventListener('change', () => fetchRequests(1));
    document.addEventListener('DOMContentLoaded', () => fetchRequests(1));
</script>
@endsection
