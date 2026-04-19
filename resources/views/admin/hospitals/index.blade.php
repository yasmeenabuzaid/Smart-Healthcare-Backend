@extends('layouts.admin')

@section('content')
<style>
    /* تأثيرات النيون الجذابة المتوافقة مع الدارك مود */
    .bg-neon-green {
        background-color: rgba(57, 255, 20, 0.15) !important;
        color: #39ff14 !important;
        border: 1px solid #39ff14;
        box-shadow: 0 0 8px rgba(57, 255, 20, 0.3);
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
        <h4 class="mb-3 mb-md-0 text-uppercase" style="letter-spacing: 1px;">{{ __('Hospital Management') }}</h4>
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
                            <input type="text" id="searchInput" class="form-control" placeholder="{{ __('Search by name, email, or phone...') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select id="typeFilter" class="form-select">
                            <option value="">{{ __('All Types') }}</option>
                            <option value="private">{{ __('Private') }}</option>
                            <option value="public">{{ __('Public') }}</option>
                            <option value="specialized">{{ __('Specialized') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="statusFilter" class="form-select">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="approved">{{ __('Approved') }}</option>
                            <option value="suspended">{{ __('Suspended') }}</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('Hospital Name') }}</th>
                                <th>{{ __('Contact') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Status') }}</th>
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

<div class="modal fade" id="hospitalDetailsModal" tabindex="-1" aria-labelledby="hospitalDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0" style="box-shadow: 0 0 20px rgba(0, 243, 255, 0.1);">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="hospitalDetailsModalLabel">{{ __('Hospital Details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <div class="row" id="detailsContent">
                    </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
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

    // مصفوفة الترجمة للجافاسكريبت
    const trans = {
        private: "{{ __('Private') }}",
        public: "{{ __('Public') }}",
        specialized: "{{ __('Specialized') }}",
        details: "{{ __('Details') }}",
        delete: "{{ __('Delete') }}",
        email: "{{ __('Email:') }}",
        phone: "{{ __('Phone:') }}",
        address: "{{ __('Address:') }}",
        gov: "{{ __('Governorate:') }}",
        license: "{{ __('License Number:') }}",
        desc: "{{ __('Description:') }}",
        not_spec: "{{ __('Not specified') }}",
        confirm_del: "{{ __('Are you sure you want to delete this hospital? It will be moved to the trash (Soft Delete).') }}"
    };

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

    function renderTable(hospitals) {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if(hospitals.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-muted">{{ __('No matching hospitals found') }}</td></tr>`;
            return;
        }

        hospitals.forEach(hospital => {
            let statusBadge = hospital.status === 'approved' ? 'bg-neon-green' : 'bg-danger text-white';
            let typeText = hospital.type === 'private' ? trans.private : (hospital.type === 'public' ? trans.public : trans.specialized);

            tbody.innerHTML += `
                <tr>
                    <td class="fw-bolder" style="color: #e0e0e0;">${hospital.name}</td>
                    <td>
                        <span class="d-block mb-1"><i data-feather="mail" class="icon-sm text-muted me-1"></i> ${hospital.email}</span>
                        <span class="d-block"><i data-feather="phone" class="icon-sm text-muted me-1"></i> ${hospital.phone}</span>
                    </td>
                    <td>${typeText}</td>
                    <td><span class="badge ${statusBadge} px-2 py-1 rounded-pill">${hospital.status}</span></td>
                    <td>
                        <button onclick="showDetails(${hospital.id})" class="btn btn-xs btn-neon-blue me-2">
                            <i data-feather="eye" class="icon-xs me-1"></i> ${trans.details}
                        </button>
                        <button onclick="deleteHospital(${hospital.id})" class="btn btn-xs btn-neon-danger">
                            <i data-feather="trash-2" class="icon-xs me-1"></i> ${trans.delete}
                        </button>
                    </td>
                </tr>
            `;
        });

        // تفعيل أيقونات Feather بعد رسم الجدول
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
                paginationLinks.innerHTML += `<button onclick="fetchHospitals(${pageNum})" class="btn btn-sm ${link.active ? 'btn-primary' : 'btn-outline-secondary'} mx-1">${link.label}</button>`;
            }
        });
    }

    async function showDetails(id) {
        const response = await fetch(`/admin/hospitals/${id}`);
        const h = await response.json();

        let typeText = h.type === 'private' ? trans.private : (h.type === 'public' ? trans.public : trans.specialized);
        let statusBadge = h.status === 'approved' ? 'bg-neon-green' : 'bg-danger text-white';

        document.getElementById('detailsContent').innerHTML = `
            <div class="col-md-6 mb-4">
                <h6 class="text-uppercase text-muted mb-2">${h.name}</h6>
                <p class="mb-1"><strong>${trans.email}</strong> <span class="text-info">${h.email}</span></p>
                <p class="mb-1"><strong>${trans.phone}</strong> ${h.phone}</p>
                <p class="mb-1"><strong>${trans.type}</strong> ${typeText}</p>
            </div>
            <div class="col-md-6 mb-4">
                <h6 class="text-uppercase text-muted mb-2">${trans.address}</h6>
                <p class="mb-1">${h.address}</p>
                <p class="mb-1"><strong>${trans.gov}</strong> ${h.governorate || trans.not_spec}</p>
                <p class="mb-1"><strong>${trans.license}</strong> ${h.license_number || trans.not_spec}</p>
                <p class="mb-1 mt-2"><strong>${trans.status}</strong> <span class="badge ${statusBadge}">${h.status}</span></p>
            </div>
            ${h.description ? `<div class="col-12 mt-2"><div class="p-3 rounded" style="background: rgba(255,255,255,0.05);"><strong>${trans.desc}</strong><p class="mb-0 mt-1">${h.description}</p></div></div>` : ''}
        `;

        new bootstrap.Modal(document.getElementById('hospitalDetailsModal')).show();
    }

    async function deleteHospital(id) {
        if(confirm(trans.confirm_del)) {
            const res = await fetch(`/admin/hospitals/${id}`, {
                method: 'DELETE',
                headers: headers
            });
            if(res.ok) fetchHospitals(currentPage);
        }
    }

    document.getElementById('searchInput').addEventListener('input', () => fetchHospitals(1));
    document.getElementById('typeFilter').addEventListener('change', () => fetchHospitals(1));
    document.getElementById('statusFilter').addEventListener('change', () => fetchHospitals(1));

    document.addEventListener('DOMContentLoaded', () => fetchHospitals(1));
</script>
@endsection
