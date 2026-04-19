@extends('layouts.admin')

@section('content')
<style>
    /* تأثيرات النيون */
    .bg-neon-green {
        background-color: rgba(57, 255, 20, 0.15) !important;
        color: #39ff14 !important;
        border: 1px solid #39ff14;
        box-shadow: 0 0 8px rgba(57, 255, 20, 0.3);
    }
    .bg-neon-blue {
        background-color: rgba(0, 243, 255, 0.15) !important;
        color: #00f3ff !important;
        border: 1px solid #00f3ff;
        box-shadow: 0 0 8px rgba(0, 243, 255, 0.3);
    }
    .btn-neon-blue {
        background: transparent;
        border: 1px solid #00f3ff;
        color: #00f3ff;
        transition: all 0.3s ease;
    }
    .btn-neon-blue:hover {
        background: rgba(0, 243, 255, 0.1);
        color: #00f3ff;
        box-shadow: 0 0 12px rgba(0, 243, 255, 0.5);
    }
    .btn-neon-danger {
        background: transparent;
        border: 1px solid #ff003c;
        color: #ff003c;
        transition: all 0.3s ease;
    }
    .btn-neon-danger:hover {
        background: rgba(255, 0, 60, 0.1);
        color: #ff003c;
        box-shadow: 0 0 12px rgba(255, 0, 60, 0.5);
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 243, 255, 0.05);
    }
</style>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0 text-uppercase" style="letter-spacing: 1px;">{{ __('Employee Management') }}</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <button class="btn btn-neon-blue btn-icon-text" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
            <i class="btn-icon-prepend" data-feather="plus"></i>
            {{ __('Add Employee') }}
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent"><i data-feather="search" class="icon-sm"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="{{ __('Search by name or email...') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select id="roleFilter" class="form-select">
                            <option value="">{{ __('All Roles') }}</option>
                            <option value="admin">{{ __('System Admin') }}</option>
                            <option value="manager">{{ __('Hospital Manager') }}</option>
                            <option value="doctor">{{ __('Doctor') }}</option>
                            <option value="receptionist">{{ __('Receptionist') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="hospitalFilter" class="form-select">
                            <option value="">{{ __('All Hospitals') }}</option>
                            @foreach($hospitals as $hospital)
                                <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Role') }}</th>
                                <th>{{ __('Hospital') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4" id="paginationLinks"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="box-shadow: 0 0 20px rgba(0, 243, 255, 0.1);">
            <form id="addEmployeeForm">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="addEmployeeModalLabel">{{ __('Add New Employee') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Name') }}</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Email') }}</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Password') }}</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Job Role') }}</label>
                        <select name="role" class="form-select" required>
                            <option value="admin">{{ __('System Admin') }}</option>
                            <option value="manager">{{ __('Hospital Manager') }}</option>
                            <option value="doctor">{{ __('Doctor') }}</option>
                            <option value="receptionist">{{ __('Receptionist') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Hospital') }}</label>
                        <select name="hospital_id" class="form-select" required>
                            @foreach($hospitals as $hospital)
                                <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-neon-blue" id="saveBtn">
                        <i data-feather="save" class="icon-sm me-1"></i> {{ __('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const headers = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
    };

    let currentPage = 1;

    const trans = {
        admin: "{{ __('System Admin') }}",
        manager: "{{ __('Hospital Manager') }}",
        doctor: "{{ __('Doctor') }}",
        receptionist: "{{ __('Receptionist') }}",
        no_data: "{{ __('No data found') }}",
        delete: "{{ __('Delete') }}",
        confirm_del: "{{ __('Are you sure you want to delete?') }}",
        error: "{{ __('Input error occurred') }}"
    };

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

    function renderTable(employees) {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if(employees.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-muted">${trans.no_data}</td></tr>`;
            return;
        }

        employees.forEach(emp => {
            let roleText = trans[emp.role] || emp.role;
            let roleBadge = emp.role === 'admin' ? 'bg-neon-blue' : (emp.role === 'manager' ? 'bg-neon-green' : 'bg-secondary text-white');
            let hospitalName = emp.hospital ? emp.hospital.name : '<span class="text-muted">-</span>';

            tbody.innerHTML += `
                <tr>
                    <td class="fw-bolder" style="color: #e0e0e0;">${emp.name}</td>
                    <td class="text-info">${emp.email}</td>
                    <td><span class="badge ${roleBadge} px-2 py-1 rounded-pill">${roleText}</span></td>
                    <td>${hospitalName}</td>
                    <td>
                        <button onclick="deleteEmployee(${emp.id})" class="btn btn-xs btn-neon-danger btn-icon-text">
                            <i data-feather="trash-2" class="icon-xs me-1"></i> ${trans.delete}
                        </button>
                    </td>
                </tr>
            `;
        });

        if (feather) {
            feather.replace();
        }
    }

    function renderPagination(data) {
        const paginationLinks = document.getElementById('paginationLinks');
        paginationLinks.innerHTML = '';

        data.links.forEach(link => {
            if(link.url) {
                let pageNum = new URL(link.url).searchParams.get("page");
                paginationLinks.innerHTML += `<button onclick="fetchEmployees(${pageNum})" class="btn btn-sm ${link.active ? 'btn-primary' : 'btn-outline-secondary'} mx-1">${link.label}</button>`;
            }
        });
    }

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
                var modal = bootstrap.Modal.getInstance(document.getElementById('addEmployeeModal'));
                modal.hide();
                this.reset();
                fetchEmployees(1);
            } else {
                const errors = await res.json();
                alert(errors.message || trans.error);
            }
        } catch (error) {
            console.error('Error:', error);
        }
        btn.disabled = false;
    });

    async function deleteEmployee(id) {
        if(confirm(trans.confirm_del)) {
            const res = await fetch(`/admin/employees/${id}`, {
                method: 'DELETE',
                headers: headers
            });
            if(res.ok) fetchEmployees(currentPage);
        }
    }

    document.getElementById('searchInput').addEventListener('input', () => fetchEmployees(1));
    document.getElementById('roleFilter').addEventListener('change', () => fetchEmployees(1));
    document.getElementById('hospitalFilter').addEventListener('change', () => fetchEmployees(1));

    document.addEventListener('DOMContentLoaded', () => fetchEmployees(1));
</script>
@endsection
