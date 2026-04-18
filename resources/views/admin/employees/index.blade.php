@extends('layouts.admin') @section('content')
<div class="container-xxl grow container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">إدارة الموظفين</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">إضافة موظف +</button>
        </div>

        <div class="card-body border-bottom">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control" placeholder="بحث بالاسم أو الإيميل...">
                </div>
                <div class="col-md-4">
                    <select id="roleFilter" class="form-select">
                        <option value="">جميع الأدوار</option>
                        <option value="admin">مدير نظام</option>
                        <option value="manager">مدير مستشفى</option>
                        <option value="doctor">طبيب</option>
                        <option value="receptionist">موظف استقبال</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="hospitalFilter" class="form-select">
                        <option value="">جميع المستشفيات</option>
                        @foreach($hospitals as $hospital)
                            <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الإيميل</th>
                        <th>الدور</th>
                        <th>المستشفى</th>
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

<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addEmployeeForm">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة موظف جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">الاسم</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الإيميل</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">كلمة المرور</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الدور الوظيفي</label>
                        <select name="role" class="form-select" required>
                            <option value="admin">مدير نظام</option>
                            <option value="manager">مدير مستشفى</option>
                            <option value="doctor">طبيب</option>
                            <option value="receptionist">موظف استقبال</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">المستشفى</label>
                        <select name="hospital_id" class="form-select" required>
                            @foreach($hospitals as $hospital)
                                <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="saveBtn">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // التأكد من إرسال CSRF Token مع كل طلب
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const headers = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
    };

    let currentPage = 1;

    // دالة جلب البيانات
    async function fetchEmployees(page = 1) {
        currentPage = page;
        const search = document.getElementById('searchInput').value;
        const role = document.getElementById('roleFilter').value;
        const hospital_id = document.getElementById('hospitalFilter').value;

        const response = await fetch(`{{ route('admin.employees.fetch') }}?page=${page}&search=${search}&role=${role}&hospital_id=${hospital_id}`);
        const data = await response.json();

        renderTable(data.data);
        renderPagination(data);
    }

    // رسم الجدول
    function renderTable(employees) {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if(employees.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">لا توجد بيانات</td></tr>';
            return;
        }

        employees.forEach(emp => {
            tbody.innerHTML += `
                <tr>
                    <td>${emp.name}</td>
                    <td>${emp.email}</td>
                    <td><span class="badge bg-label-primary">${emp.role}</span></td>
                    <td>${emp.hospital ? emp.hospital.name : '-'}</td>
                    <td>
                        <button onclick="deleteEmployee(${emp.id})" class="btn btn-sm btn-danger">حذف</button>
                    </td>
                </tr>
            `;
        });
    }

    // رسم أزرار الترقيم
    function renderPagination(data) {
        const paginationLinks = document.getElementById('paginationLinks');
        paginationLinks.innerHTML = '';

        data.links.forEach(link => {
            if(link.url) {
                let pageNum = new URL(link.url).searchParams.get("page");
                paginationLinks.innerHTML += `<button onclick="fetchEmployees(${pageNum})" class="btn btn-sm ${link.active ? 'btn-primary' : 'btn-outline-primary'} mx-1">${link.label}</button>`;
            }
        });
    }

    // إضافة موظف
    document.getElementById('addEmployeeForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        const btn = document.getElementById('saveBtn');
        btn.disabled = true;

        try {
            const res = await fetch(`{{ route('admin.employees.store') }}`, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(data)
            });

            if(res.ok) {
                // إغلاق المودل وتحديث الجدول
                var modal = bootstrap.Modal.getInstance(document.getElementById('addEmployeeModal'));
                modal.hide();
                this.reset();
                fetchEmployees(1);
            } else {
                const errors = await res.json();
                alert(errors.message || 'حدث خطأ في الإدخال');
            }
        } catch (error) {
            console.error('Error:', error);
        }
        btn.disabled = false;
    });

    // حذف موظف
    async function deleteEmployee(id) {
        if(confirm('هل أنت متأكد من الحذف؟')) {
            const res = await fetch(`/admin/employees/${id}`, {
                method: 'DELETE',
                headers: headers
            });
            if(res.ok) fetchEmployees(currentPage);
        }
    }

    // مستمعات الأحداث للبحث والفلترة
    document.getElementById('searchInput').addEventListener('input', () => fetchEmployees(1));
    document.getElementById('roleFilter').addEventListener('change', () => fetchEmployees(1));
    document.getElementById('hospitalFilter').addEventListener('change', () => fetchEmployees(1));

    // جلب البيانات عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', () => fetchEmployees(1));
</script>
@endsection
