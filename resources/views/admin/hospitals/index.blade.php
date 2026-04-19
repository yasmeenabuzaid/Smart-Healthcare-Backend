@extends('layouts.admin')

@section('content')
<div class="container-xxl grow container-p-y">
    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="mb-3">إدارة المستشفيات</h5>

            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control" placeholder="بحث بالاسم، الإيميل، أو الهاتف...">
                </div>
                <div class="col-md-4">
                    <select id="typeFilter" class="form-select">
                        <option value="">جميع الأنواع</option>
                        <option value="private">خاص</option>
                        <option value="public">حكومي</option>
                        <option value="specialized">تخصصي</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="statusFilter" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="approved">معتمد</option>
                        <option value="suspended">موقوف</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>اسم المستشفى</th>
                        <th>التواصل</th>
                        <th>النوع</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    </tbody>
            </table>
        </div>

        <div class="card-footer d-flex justify-content-center" id="paginationLinks"></div>
    </div>
</div>

<div class="modal fade" id="hospitalDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تفاصيل المستشفى</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row" id="detailsContent">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const headers = { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' };
    let currentPage = 1;

    // 1. جلب بيانات المستشفيات
    async function fetchHospitals(page = 1) {
        currentPage = page;
        const search = document.getElementById('searchInput').value;
        const type = document.getElementById('typeFilter').value;
        const status = document.getElementById('statusFilter').value;

        const response = await fetch(`{{ route('admin.hospitals.fetch') }}?page=${page}&search=${search}&type=${type}&status=${status}`);
        const data = await response.json();

        renderTable(data.data);
        renderPagination(data);
    }

    // 2. رسم الجدول
    function renderTable(hospitals) {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if(hospitals.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">لا توجد مستشفيات مطابقة</td></tr>';
            return;
        }

        hospitals.forEach(hospital => {
            let statusBadge = hospital.status === 'approved' ? 'bg-label-success' : 'bg-label-danger';
            let typeText = hospital.type === 'private' ? 'خاص' : (hospital.type === 'public' ? 'حكومي' : 'تخصصي');

            tbody.innerHTML += `
                <tr>
                    <td><strong>${hospital.name}</strong></td>
                    <td>
                        <small class="d-block"><i class="bx bx-envelope"></i> ${hospital.email}</small>
                        <small class="d-block"><i class="bx bx-phone"></i> ${hospital.phone}</small>
                    </td>
                    <td>${typeText}</td>
                    <td><span class="badge ${statusBadge}">${hospital.status}</span></td>
                    <td>
                        <button onclick="showDetails(${hospital.id})" class="btn btn-sm btn-info">التفاصيل</button>
                        <button onclick="deleteHospital(${hospital.id})" class="btn btn-sm btn-danger">حذف</button>
                    </td>
                </tr>
            `;
        });
    }

    // 3. رسم أزرار الترقيم
    function renderPagination(data) {
        const paginationLinks = document.getElementById('paginationLinks');
        paginationLinks.innerHTML = '';
        data.links.forEach(link => {
            if(link.url) {
                let pageNum = new URL(link.url).searchParams.get("page");
                paginationLinks.innerHTML += `<button onclick="fetchHospitals(${pageNum})" class="btn btn-sm ${link.active ? 'btn-primary' : 'btn-outline-primary'} mx-1">${link.label}</button>`;
            }
        });
    }

    // 4. عرض تفاصيل المستشفى في المودل
    async function showDetails(id) {
        const response = await fetch(`/admin/hospitals/${id}`);
        const h = await response.json();

        let typeText = h.type === 'private' ? 'خاص' : (h.type === 'public' ? 'حكومي' : 'تخصصي');

        document.getElementById('detailsContent').innerHTML = `
            <div class="col-md-6 mb-3">
                <p><strong>اسم المستشفى:</strong> ${h.name}</p>
                <p><strong>البريد الإلكتروني:</strong> ${h.email}</p>
                <p><strong>الهاتف:</strong> ${h.phone}</p>
                <p><strong>النوع:</strong> ${typeText}</p>
            </div>
            <div class="col-md-6 mb-3">
                <p><strong>العنوان:</strong> ${h.address}</p>
                <p><strong>المحافظة:</strong> ${h.governorate || 'غير محدد'}</p>
                <p><strong>رقم الترخيص:</strong> ${h.license_number || 'غير محدد'}</p>
                <p><strong>الحالة:</strong> <span class="badge bg-label-primary">${h.status}</span></p>
            </div>
            ${h.description ? `<div class="col-12"><hr><strong>الوصف:</strong><p>${h.description}</p></div>` : ''}
        `;

        new bootstrap.Modal(document.getElementById('hospitalDetailsModal')).show();
    }

    // 5. حذف مستشفى
    async function deleteHospital(id) {
        if(confirm('هل أنت متأكد من حذف هذا المستشفى؟ سيتم نقل بياناته لسلة المهملات (Soft Delete).')) {
            const res = await fetch(`/admin/hospitals/${id}`, {
                method: 'DELETE',
                headers: headers
            });
            if(res.ok) fetchHospitals(currentPage);
        }
    }

    // مستمعات الأحداث للفلترة التلقائية
    document.getElementById('searchInput').addEventListener('input', () => fetchHospitals(1));
    document.getElementById('typeFilter').addEventListener('change', () => fetchHospitals(1));
    document.getElementById('statusFilter').addEventListener('change', () => fetchHospitals(1));

    // جلب البيانات عند فتح الصفحة
    document.addEventListener('DOMContentLoaded', () => fetchHospitals(1));
</script>
@endsection
