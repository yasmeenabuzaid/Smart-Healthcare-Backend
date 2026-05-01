@extends('layouts.admin')

@section('content')
<style>
    .neon-bg { background-color: #0d1117; color: #c9d1d9; }
    .hospital-card {
        background: rgba(22, 27, 34, 0.8);
        border: 1px solid #30363d;
        border-radius: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .hospital-card:hover {
        border-color: #00f3ff;
        box-shadow: 0 0 15px rgba(0, 243, 255, 0.2);
        transform: translateY(-5px);
    }
    .neon-text-primary { color: #00f3ff; }
    .neon-text-success { color: #39ff14; }

    /* تنسيق المودال */
    .modal-content.neon-modal {
        background-color: #161b22;
        border: 1px solid #00f3ff;
        box-shadow: 0 0 20px rgba(0, 243, 255, 0.2);
        color: #fff;
    }
    .modal-header { border-bottom: 1px solid #30363d; }
    .modal-footer { border-top: 1px solid #30363d; }
    .btn-neon-close {
        background: transparent; border: 1px solid #ff003c; color: #ff003c; transition: 0.3s;
    }
    .btn-neon-close:hover { background: #ff003c; color: #fff; box-shadow: 0 0 10px #ff003c; }
</style>

<div class="container-fluid neon-bg p-4 rounded">
    <h3 class="mb-4 neon-text-primary fw-bold">{{ __('Hospitals Directory') }}</h3>

    <div id="hospitalsContainer" class="row g-4">
        <div class="col-12 text-center" id="loadingSpinner">
            <div class="spinner-border text-info" role="status"></div>
            <p class="mt-2">{{ __('Loading hospitals...') }}</p>
        </div>
    </div>
</div>

<div class="modal fade" id="hospitalDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content neon-modal">
            <div class="modal-header">
                <h5 class="modal-title neon-text-success fw-bold" id="modalHospitalName"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3" id="modalLoader">
                    <div class="spinner-border text-success" role="status"></div>
                </div>
                <div id="modalContent" class="d-none">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img id="modalLogo" src="" alt="Logo" class="img-fluid rounded border border-secondary p-1" style="max-height: 150px;">
                        </div>
                        <div class="col-md-8">
                            <p><strong>{{ __('City') }}:</strong> <span id="modalCity"></span></p>
                            <p><strong>{{ __('Type') }}:</strong> <span id="modalType"></span></p>
                            <p><strong>{{ __('Phone') }}:</strong> <span id="modalPhone"></span></p>
                            <p><strong>{{ __('Emergency') }}:</strong> <span id="modalEmergency" class="text-danger"></span></p>
                            <p><strong>{{ __('Address') }}:</strong> <span id="modalAddress"></span></p>
                        </div>
                    </div>
                    <hr class="border-secondary">
                    <h6 class="neon-text-primary">{{ __('Description') }}</h6>
                    <p id="modalDescription" class="text-muted"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-neon-close px-4" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    const locale = "{{ app()->getLocale() }}"; // لمعرفة اللغة الحالية
    const apiToken = localStorage.getItem('accessToken'); // جلب التوكن من التخزين

    document.addEventListener('DOMContentLoaded', function() {
        loadHospitals();
    });

    async function loadHospitals() {
        try {
            // المسار العام لا يحتاج توكن
            const response = await fetch("{{ url('api/hospital') }}");
            const result = await response.json();

            const container = document.getElementById('hospitalsContainer');
            container.innerHTML = ''; // تفريغ التحميل

            if(result.status === 'success' && result.data) {
                const groupedHospitals = result.data;

                // التكرار على المدن
                for (const [cityName, hospitals] of Object.entries(groupedHospitals)) {
                    // إضافة عنوان المدينة
                    container.innerHTML += `<div class="col-12 mt-4 mb-2"><h4 class="border-bottom border-secondary pb-2">${cityName}</h4></div>`;

                    // إضافة المستشفيات التابعة للمدينة
                    hospitals.forEach(hospital => {
                        const name = locale === 'ar' ? hospital.name_ar : hospital.name_en;
                        container.innerHTML += `
                            <div class="col-md-6 col-lg-4">
                                <div class="card hospital-card p-3 h-100" onclick="openHospitalDetails(${hospital.id})">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-secondary rounded-circle me-3 ms-3" style="width: 40px; height: 40px;"></div>
                                        <h5 class="mb-0 fw-bold">${name}</h5>
                                    </div>
                                    <p class="text-muted small mb-0 mt-2 text-end text-sm-start"><i class="feather icon-map-pin"></i> {{ __('Click to view details') }}</p>
                                </div>
                            </div>
                        `;
                    });
                }
            }
        } catch (error) {
            document.getElementById('hospitalsContainer').innerHTML = `<div class="col-12 text-danger text-center">{{ __('Failed to load data.') }}</div>`;
        }
    }

    async function openHospitalDetails(id) {
        // إظهار المودال وحالة التحميل
        const modal = new bootstrap.Modal(document.getElementById('hospitalDetailsModal'));
        modal.show();

        document.getElementById('modalLoader').classList.remove('d-none');
        document.getElementById('modalContent').classList.add('d-none');

        try {
            // مسار التفاصيل يحتاج توكن لأنك وضعته داخل auth:sanctum
            const response = await fetch(`{{ url('api/hospital') }}/${id}`, {
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'Accept': 'application/json'
                }
            });
            const result = await response.json();

            if(response.ok && result.status === 'success') {
                const data = result.data;
                const isAr = locale === 'ar';

                document.getElementById('modalHospitalName').innerText = isAr ? data.name_ar : data.name_en;
                document.getElementById('modalLogo').src = data.logo || 'https://via.placeholder.com/150';
                document.getElementById('modalCity').innerText = data.city ? (isAr ? data.city.name_ar : data.city.name_en) : '-';
                document.getElementById('modalType').innerText = data.type ? (isAr ? data.type.name_ar : data.type.name_en) : '-';
                document.getElementById('modalPhone').innerText = data.phone || '-';
                document.getElementById('modalEmergency').innerText = data.emergency_phone || '-';
                document.getElementById('modalAddress').innerText = isAr ? data.address_ar : data.address_en;
                document.getElementById('modalDescription').innerText = isAr ? data.description_ar : data.description_en;

                document.getElementById('modalLoader').classList.add('d-none');
                document.getElementById('modalContent').classList.remove('d-none');
            } else {
                // في حال عدم وجود صلاحية أو توكن
                document.getElementById('modalLoader').innerHTML = `<p class="text-danger">${result.message || '{{ __('Unauthorized or error occurred.') }}'}</p>`;
            }
        } catch (error) {
            document.getElementById('modalLoader').innerHTML = `<p class="text-danger">{{ __('Failed to fetch details.') }}</p>`;
        }
    }
</script>
@endsection
