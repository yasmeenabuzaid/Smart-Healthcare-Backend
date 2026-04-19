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
    .bg-neon-warning {
        background-color: rgba(255, 215, 0, 0.15) !important;
        color: #ffd700 !important;
        border: 1px solid #ffd700;
        box-shadow: 0 0 8px rgba(255, 215, 0, 0.3);
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
    .btn-neon-success {
        background: transparent;
        border: 1px solid #39ff14;
        color: #39ff14;
        transition: all 0.3s ease;
    }
    .btn-neon-success:hover {
        background: rgba(57, 255, 20, 0.1);
        color: #39ff14;
        box-shadow: 0 0 12px rgba(57, 255, 20, 0.5);
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
        <h4 class="mb-3 mb-md-0 text-uppercase" style="letter-spacing: 1px;">{{ __('Hospital Join Requests') }}</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <select id="statusFilter" class="form-select form-select-sm" style="min-width: 150px;">
            <option value="pending">{{ __('Pending Requests') }}</option>
            <option value="approved">{{ __('Approved Requests') }}</option>
            <option value="rejected">{{ __('Rejected Requests') }}</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('Applicant') }}</th>
                                <th>{{ __('Hospital Name') }}</th>
                                <th>{{ __('Request Date') }}</th>
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

<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="box-shadow: 0 0 20px rgba(0, 243, 255, 0.1);">
            <div class="modal-header border-bottom">
                <h5 class="modal-title">{{ __('Request Details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailsBody">
                </div>
            <div class="modal-footer border-top" id="actionButtons">
                <button type="button" class="btn btn-neon-success" id="btnApprove">
                    <i data-feather="check-circle" class="icon-sm me-1"></i> {{ __('Approve') }}
                </button>
                <button type="button" class="btn btn-neon-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i data-feather="x-circle" class="icon-sm me-1"></i> {{ __('Reject') }}
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0" style="box-shadow: 0 0 20px rgba(255, 0, 60, 0.2);">
            <div class="modal-header border-bottom">
                <h5 class="modal-title text-danger">{{ __('Rejection Reason') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea id="rejectionReason" class="form-control" rows="4" placeholder="{{ __('Write rejection reason here...') }}"></textarea>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-neon-danger" id="btnConfirmReject">{{ __('Confirm Reject') }}</button>
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

    const trans = {
        pending: "{{ __('Pending') }}",
        approved: "{{ __('Approved') }}",
        rejected: "{{ __('Rejected') }}",
        view: "{{ __('View & Review') }}",
        no_data: "{{ __('No requests found') }}",
        owner: "{{ __('Owner Name:') }}",
        email: "{{ __('Email:') }}",
        phone: "{{ __('Phone:') }}",
        hospital: "{{ __('Hospital:') }}",
        address: "{{ __('Address:') }}",
        reason: "{{ __('Rejection Reason:') }}",
        confirm_approve: "{{ __('Are you sure you want to approve this hospital and create an account for it?') }}",
        req_reason: "{{ __('Rejection reason is required') }}"
    };

    async function fetchRequests(page = 1) {
        const status = document.getElementById('statusFilter').value;
        const response = await fetch(`{{ route('admin.approvals.fetch') }}?page=${page}&status=${status}`);
        const data = await response.json();

        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = '';

        if(data.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-muted">${trans.no_data}</td></tr>`;
            return;
        }

        data.data.forEach(req => {
            let statusBadge = req.status === 'pending' ? 'bg-neon-warning' : (req.status === 'approved' ? 'bg-neon-green' : 'bg-danger text-white');
            let transStatus = req.status === 'pending' ? trans.pending : (req.status === 'approved' ? trans.approved : trans.rejected);
            let date = new Date(req.created_at).toLocaleDateString("{{ app()->getLocale() == 'ar' ? 'ar-EG' : 'en-US' }}");

            tbody.innerHTML += `
                <tr>
                    <td style="color: #e0e0e0;">${req.requester_name}</td>
                    <td class="fw-bolder text-info">${req.hospital_name}</td>
                    <td>${date}</td>
                    <td><span class="badge ${statusBadge} px-2 py-1 rounded-pill">${transStatus}</span></td>
                    <td>
                        <button onclick="showDetails(${req.id})" class="btn btn-xs btn-neon-blue">
                            <i data-feather="eye" class="icon-xs me-1"></i> ${trans.view}
                        </button>
                    </td>
                </tr>
            `;
        });

        if (feather) { feather.replace(); }
        renderPagination(data);
    }

    function renderPagination(data) {
        const paginationLinks = document.getElementById('paginationLinks');
        paginationLinks.innerHTML = '';
        data.links.forEach(link => {
            if(link.url) {
                let pageNum = new URL(link.url).searchParams.get("page");
                paginationLinks.innerHTML += `<button onclick="fetchRequests(${pageNum})" class="btn btn-sm ${link.active ? 'btn-primary' : 'btn-outline-secondary'} mx-1">${link.label}</button>`;
            }
        });
    }

    async function showDetails(id) {
        currentRequestId = id;
        const response = await fetch(`/admin/approvals/${id}`);
        const req = await response.json();

        document.getElementById('detailsBody').innerHTML = `
            <div class="p-3 rounded mb-3" style="background: rgba(255,255,255,0.05);">
                <p class="mb-2"><strong>${trans.owner}</strong> <span class="text-light">${req.requester_name}</span></p>
                <p class="mb-2"><strong>${trans.email}</strong> <span class="text-info">${req.requester_email}</span></p>
                <p class="mb-0"><strong>${trans.phone}</strong> <span class="text-light">${req.requester_phone}</span></p>
            </div>
            <div class="p-3 rounded" style="background: rgba(255,255,255,0.05);">
                <p class="mb-2"><strong>${trans.hospital}</strong> <span class="text-light">${req.hospital_name}</span></p>
                <p class="mb-2"><strong>${trans.address}</strong> <span class="text-light">${req.hospital_address}</span></p>
                ${req.rejection_reason ? `<hr><p class="mb-0 text-danger"><strong>${trans.reason}</strong> <br>${req.rejection_reason}</p>` : ''}
            </div>
        `;

        document.getElementById('actionButtons').style.display = req.status === 'pending' ? 'flex' : 'none';
        new bootstrap.Modal(document.getElementById('detailsModal')).show();
    }

    document.getElementById('btnApprove').addEventListener('click', async () => {
        if(!confirm(trans.confirm_approve)) return;

        const res = await fetch(`/admin/approvals/${currentRequestId}/approve`, { method: 'POST', headers: headers });
        if(res.ok) {
            bootstrap.Modal.getInstance(document.getElementById('detailsModal')).hide();
            fetchRequests();
        }
    });

    document.getElementById('btnConfirmReject').addEventListener('click', async () => {
        const reason = document.getElementById('rejectionReason').value;
        if(!reason) return alert(trans.req_reason);

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

    document.getElementById('statusFilter').addEventListener('change', () => fetchRequests(1));
    document.addEventListener('DOMContentLoaded', () => fetchRequests(1));
</script>
@endsection
