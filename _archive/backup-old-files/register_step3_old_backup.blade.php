@extends('layouts.app')

@section('head-tag')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .location-container {
        direction: rtl;
        max-width: 800px;
        margin: 0;
    }
    .select2-selection__rendered{
    background-color: #6bb48945;
  }
    .location-path {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 0.5rem;
        margin: 1rem 0;
        border: 1px solid #dee2e6;
    }

    .location-path span {
        display: inline-block;
        margin: 0 0.5rem;
        color: #0d6efd;
        cursor: pointer;
    }

    .location-path span:hover {
        text-decoration: underline;
    }

    .location-select {
        margin-bottom: 1rem;
    }

    .select2-container {
        width: 100% !important;
    }

    .alert {
        margin-bottom: 1rem;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .btn-submit {
        padding: 0.5rem 2rem;
        font-size: 1.1rem;
    }
    .main-section{
            display: flex;
    align-items: center;
    justify-content: center;
    }
    .disabled-btn {
    pointer-events: none;
    opacity: 0.6;
}

.location-path span.last {
  pointer-events: none;
  color: #212529;
  font-weight: bold;
  cursor: default;
}


</style>
@endsection

@section('content')
<div class="location-container" >
    <div class="card">
        <div class="card-header text-center bg-primary text-white fs-5">
            مرحله سوم: انتخاب مکان
        </div>
        
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                لطفاً مکان خود را با دقت انتخاب نمایید. از این اطلاعات برای گروه‌بندی مکانی شما استفاده می‌شود.
                وارد کردن اطلاعات تا سطح محله الزامی است.
            </div>

            <form action="{{ route('register.step3.process') }}" method="POST">
                @csrf
                
                <div class="location-path" id="location_path_display"></div>

                <div id="location-selects">
                    <div class="mb-3">
                        <label class="form-label">انتخاب قاره</label>
                        <select class="form-select location-select" name="continent_id" data-level="1" id="continent-select">
                            <option value="">انتخاب کنید</option>
                            @foreach($continents as $continent)
                                <option value="{{ $continent->id }}" {{ $continent->id == 4 ? 'selected' : '' }}>
                                    {{ $continent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-submit disabled-btn" id="continueBtn" disabled>
                        <i class="fas fa-check"></i>
                        ثبت و ادامه
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('partials.add_location_modal', ['type' => 'region', 'label' => 'منطقه/ روستا'])
@include('partials.add_location_modal', ['type' => 'neighborhood', 'label' => 'محله'])
@include('partials.add_location_modal', ['type' => 'street', 'label' => 'خیابان'])
@include('partials.add_location_modal', ['type' => 'alley', 'label' => 'کوچه'])

@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showErrorAlert('{{ session('success') }}');
    });
@endif

function updateSubmitButtonState() {
    const neighborhoodId = pathValues[7]; // محله = سطح هشتم = index 7
    const $btn = $('#continueBtn');

    if (neighborhoodId) {
        $btn.prop('disabled', false).removeClass('disabled-btn');
    } else {
        $btn.prop('disabled', true).addClass('disabled-btn');
    }
}

const levelLabels = ['قاره', 'کشور', 'استان', 'شهرستان', 'بخش', 'شهر / دهستان', 'منطقه/ روستا', 'محله', 'خیابان', 'کوچه'];
const levelKeys = ['continent', 'country', 'province', 'county', 'section', 'city', 'region', 'neighborhood', 'street', 'alley'];
const nameKeys = ['continent_id', 'country_id', 'province_id', 'county_id', 'section_id', 'city_id', 'region_id', 'neighborhood_id', 'street_id', 'alley_id'];
const allowAddModal = ['region', 'neighborhood', 'street', 'alley'];

let pathParts = [];
let pathValues = [];

$(document).ready(function() {
    // تنظیمات Select2
    $('.location-select').select2({
        width: '100%',
        placeholder: 'انتخاب کنید',
        allowClear: true
    });

    // مدیریت تغییر انتخاب‌ها
    $(document).on('change', '.location-select', function() {
        const level = $(this).data('level');
        const value = $(this).val();

        if (value === '__add_new__') {
            const type = levelKeys[level - 1];
            const parentId = pathValues[level - 2];
            openAddModal(type, parentId, level);
            return;
        }

        if (value) {
            pathParts[level - 1] = $(this).find('option:selected').text();
            pathValues[level - 1] = value;
            
            // حذف سطوح بعدی
            for (let i = level; i < pathParts.length; i++) {
                pathParts[i] = null;
                pathValues[i] = null;
            }
            
            // بارگذاری سطح بعدی
            if (level < levelKeys.length) {
                loadNextLevel(value, level + 1);
            }
        } else {
            // حذف سطوح بعدی در صورت خالی شدن انتخاب
            for (let i = level - 1; i < pathParts.length; i++) {
                pathParts[i] = null;
                pathValues[i] = null;
            }
            // نمایش مجدد سطوح قبلی
            for (let i = 1; i < level; i++) {
                $(`select[data-level="${i}"]`).closest('.mb-3').show();
            }
        }
        
        updatePathDisplay();
    });

    // مدیریت کلیک روی مسیر
    $(document).on('click', '#location_path_display span', function() {
        const level = $(this).data('level');
        const value = $(this).data('value');
        
            if (level === 0) {
        // بازنشانی کل مسیر
        pathParts = [];
        pathValues = [];

        // حذف همه‌ی سلکت‌ها بجز سطح قاره
        $('#location-selects').html('');

        // افزودن مجدد سلکت قاره
        const continentSelect = `
            <div class="mb-3">
                <label class="form-label">انتخاب قاره</label>
                <select class="form-select location-select" name="continent_id" data-level="1" id="continent-select">
                    <option value="">انتخاب کنید</option>
                    @foreach($continents as $continent)
                        <option value="{{ $continent->id }}">{{ $continent->name }}</option>
                    @endforeach
                </select>
            </div>
        `;
        $('#location-selects').append(continentSelect);
        $('#continent-select').select2();

        updatePathDisplay();
        return;
    }

        // حذف سطوح بعدی
        for (let i = level; i < pathParts.length; i++) {
            pathParts[i] = null;
            pathValues[i] = null;
        }
        
        // حذف تمام select های بعد از سطح انتخاب شده
        for (let i = level + 1; i <= levelKeys.length; i++) {
            $(`select[data-level="${i}"]`).closest('.mb-3').remove();
        }
        
        // نمایش مجدد select های قبلی
        for (let i = 1; i <= level; i++) {
            $(`select[data-level="${i}"]`).closest('.mb-3').show();
        }
        
        // بارگذاری مجدد سطوح
        loadNextLevel(value, level + 1);
        updatePathDisplay();
    });

    // تنظیم مقادیر پیش‌فرض
    const defaultContinentId = '4'; // آسیا
    const defaultCountryId = '74';  // ایران

    // بارگذاری کشورهای قاره آسیا
    loadNextLevel(defaultContinentId, 2);

// انتخاب خودکار ایران
const observer = new MutationObserver(() => {
    const $continentSelect = $('select[data-level="1"]');
    const $countrySelect = $('select[data-level="2"]');
    
    if ($continentSelect.length > 0) {
        // انتخاب پیش‌فرض قاره
        $continentSelect.val(defaultContinentId).trigger('change');
    }

    if ($countrySelect.length > 0) {
        // انتخاب پیش‌فرض کشور
        $countrySelect.val(defaultCountryId).trigger('change');
        
        // پنهان کردن سلکت باکس کشور
        $countrySelect.addClass('d-none');

    }

    observer.disconnect();
});

observer.observe(document.getElementById('location-selects'), {
    childList: true,
    subtree: true
});



    // مدیریت فرم افزودن مکان جدید
    $('.add-location-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const type = form.data('type');
        const parentId = form.find('input[name="parent_id"]').val();
        const name = form.find('input[name="name"]').val();
        
        // نمایش وضعیت بارگذاری
        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> در حال افزودن...');
        
        // ارسال درخواست به API
        $.ajax({
            url: `/api/add-${type}`,
            method: 'POST',
            data: {
                name: name,
                parent_id: parentId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // بستن مودال و حذف backdrop
                $(`#add-${type}-modal`).modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                
                // مخفی کردن فقط فرم مربوط به type فعلی
                $(`.add-location-form[data-type="${type}"]`).hide();
                
                // پاک کردن فرم
                form[0].reset();
                
                // پیدا کردن select box مربوطه
                let select;
                if (type === 'region') {
                    select = $('select[name="region_id"]');
                } else if (type === 'neighborhood') {
                    select = $('select[name="neighborhood_id"]');
                } else if (type === 'street') {
                    select = $('select[name="street_id"]');
                } else if (type === 'alley') {
                    select = $('select[name="alley_id"]');
                }
                
                if (select.length > 0) {
                    // اضافه کردن گزینه جدید به select box
                    const newOption = new Option(name, response.id, true, true);
                    select.append(newOption);
                    
                    // به‌روزرسانی Select2
                    select.trigger('change.select2');
                    
                    // به‌روزرسانی مسیر
                    const level = select.data('level');
                    pathParts[level - 1] = name;
                    pathValues[level - 1] = response.id;
                    updatePathDisplay();
                    
                    // بارگذاری سطح بعدی
                    if (level < levelKeys.length) {
                        loadNextLevel(response.id, level + 1);
                    }
                }
            },
            error: function(xhr) {
                // نمایش پیام خطا
                const errorMessage = xhr.responseJSON?.message || 'خطا در افزودن مکان جدید';
                alert(errorMessage);
            },
            complete: function() {
                // بازگرداندن وضعیت دکمه
                submitBtn.prop('disabled', false).html('افزودن');
            }
        });
    });
});

function updatePathDisplay() {
    const display = [
        `<span data-level="0" data-value="world">زمین</span>`,
        ...pathParts
            .filter(part => part !== null)
            .map((part, index, arr) => {
                const level = index + 1;
                const isLast = index === arr.length - 1;
                const extraClass = isLast ? 'last' : '';
                return `<span data-level="${level}" data-value="${pathValues[index]}" class="${extraClass}">${part}</span>`;
            })
    ].join(' / ');

    $('#location_path_display').html(display || 'مسیر انتخاب نشده');
    updateSubmitButtonState();
}

let loadingLevel = null;

function loadNextLevel(parentId, level) {
    if (loadingLevel === level) return;
    loadingLevel = level;
    
    const key = levelKeys[level - 1];
    const label = levelLabels[level - 1];
    const name = nameKeys[level - 1];
    
    if (!key) return;

    // جلوگیری از ساختن دوباره select برای قاره
    if (level === 1) return;

    $.get(`/api/locations?level=${key}&parent_id=${parentId}`, function(data) {
        const hasAdd = allowAddModal.includes(key);
        const showLabel = ['خیابان', 'کوچه'].includes(label) ? `${label} (اختیاری)` : label;
        
        let optionsHtml = `<option value="">انتخاب ${showLabel}</option>`;
        data.forEach(item => {
            const value = item.type ? `${item.type}_${item.id}` : item.id;
            optionsHtml += `<option value="${value}">${item.name}</option>`;
        });

        if (hasAdd) {
            optionsHtml += `<option value="__add_new__">+ افزودن ${label}</option>`;
        }

        // حذف select قبلی اگر وجود دارد
        $(`select[data-level="${level}"]`).remove();

        // مخفی کردن تمام سطوح قبلی
        for (let i = 1; i < level; i++) {
            $(`select[data-level="${i}"]`).closest('.mb-3').hide();
        }

        const $select = $(`
            <div class="mb-3">
                <label class="form-label">${showLabel}</label>
                <select class="form-select location-select" name="${name}" data-level="${level}">
                    ${optionsHtml}
                </select>
            </div>
        `);

        $('#location-selects').append($select);
        $select.find('select').select2();
    });
}

function openAddModal(type, parentId, currentLevel) {
    const modalId = `#add${capitalize(type)}Modal`;
    $(`${modalId} .parent-id`).val(parentId).data('current-level', currentLevel);
    $(modalId).modal('show');
}

function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}
</script>
@endsection

