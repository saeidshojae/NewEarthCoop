<!-- resources/views/register/step2.blade.php -->
@extends('layouts.app')

@section('head-tag')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

<style>
  .remove-selection {
    padding: 0 0.4rem;
    margin: 0.2rem 0.3rem 0.2rem 0.1rem;
  }
  .select2-selection__rendered{
    background-color: #6bb48945;
  }
  .badge {
    background-color: #57a1d7bf !important;
  }
  .error-message {
    color: red;
    font-size: 0.9rem;
    display: none;
  }
  .select2-container {
    margin-bottom: 1rem !important;
    width: 100% !important;
  }
  .select2-selection {
    width: 100% !important;
  }

  #selected_occupational_fields{
    margin-bottom: 1rem;
    border-radius: .25rem;
    border: 1px solid #333333a8;
  }
  #selected_experience_fields{
    margin-bottom: 1rem;
    border-radius: .25rem;
    border: 1px solid #333333a8;
  }
 .select2-container:nth-of-type(2) {
    width: 95% !important;
    margin-right: 5%;
}

 .select2-container:nth-of-type(3) {
    width: 90% !important;
    margin-right: 10%;
}

</style>
@endsection

@section('content')
<div class="container" style="direction: rtl; text-align: right;">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header text-center bg-primary text-white fs-5">
          مرحله دوم: انتخاب زمینه‌های صنفی و تخصصی
        </div>
        
        <label style='text-align: center; margin: 1rem'>لطفا زمینه های صنفی و تخصصی خود را تا سه سطح وارد نمایید، از این اطلاعات برای ایجاد گروه های صنفی و تخصصی شما استفاده میشود</label>

        <div class="card-body">
          <form action="{{ route('register.step2.process') }}" method="POST" novalidate>
            @csrf

            <input type="hidden" id="hidden_occupational_fields" name="occupational_fields[]">
            <input type="hidden" id="hidden_experience_fields" name="experience_fields[]">

{{-- صنفی --}}
<div id="occupational_container" class="mb-4">
  <label class="form-label">زمینه فعالیت صنفی:</label>
  <div id="selected_occupational_fields"></div>

  <select id="occupational_fields" class="form-control" data-level="1">
    <option value="">انتخاب کنید</option>
    @foreach($occupationalFields as $field)
      <option value="{{ $field->id }}">{{ $field->name }}</option>
    @endforeach
    <option value="">+ ایجاد کنید</option>
  </select>

  <select id="occupational_subfields" class="form-control mt-2 d-none" data-level="2"></select>
  <select id="occupational_finalfields" class="form-control mt-2 d-none" data-level="3"></select>
</div>

{{-- تجربی --}}
<div id="experience_container" class="mb-4">
  <label class="form-label">زمینه تجربی و تخصصی:</label>
  <div id="selected_experience_fields"></div>

  <select id="experience_fields" class="form-control" data-level="1">
    <option value="">انتخاب کنید</option>
    @foreach($experienceFields as $field)
      <option value="{{ $field->id }}">{{ $field->name }}</option>
    @endforeach
    <option value="">+ ایجاد کنید</option>
  </select>

  <select id="experience_subfields" class="form-control mt-2 d-none" data-level="2"></select>
  <select id="experience_finalfields" class="form-control mt-2 d-none" data-level="3"></select>
</div>


<div class="text-center">
  <button type="submit" class="btn btn-primary" id="continueBtn" disabled>ادامه</button>
</div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Create Field (برای صنفی و تجربی) -->
<div class="modal fade" id="createFieldModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="createFieldForm">
        <div class="modal-header">
          <h5 class="modal-title">ایجاد زمینه فعالیت جدید</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="field_level" name="level">
          <input type="hidden" id="parent_id" name="parent_id">
          <div class="mb-3">
            <label for="field_name" class="form-label">نام زمینه فعالیت</label>
            <input type="text" class="form-control" name="name" id="field_name" required>
          </div>
          <div id="create_field_error" class="text-danger d-none">خطا در ثبت اطلاعات. لطفاً دوباره تلاش کنید.</div>
        </div>
        <div class="modal-footer">
<div class="text-center">
  <button type="submit" class="btn btn-primary" id="continueBtn" disabled>ادامه</button>
</div>

          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">لغو</button>
        </div>
      </form>
    </div>
  </div>
</div>

@error('occupational_fields')
    <script>
        alert('{{ $message }}')
    </script>
@enderror

@error('experience_fields')
    <script>
        alert('{{ $message }}')
    </script>
@enderror

@endsection

@section('scripts')



  {{-- ✅ jQuery همیشه باید اول لود بشه --}}
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  {{-- ✅ Bootstrap --}}
  {{-- ✅ Select2 (بعد از jQuery، بدون defer) --}}
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script >// public/js/register-step2.js

@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showErrorAlert('{{ session('success') }}');
    });
@endif
function updateContinueButtonState() {
  const hasOccupational = selectedOccupationalFields.length > 0;
  const hasExperience = selectedExperienceFields.length > 0;

  if (hasOccupational && hasExperience) {
    $('#continueBtn').prop('disabled', false);
  } else {
    $('#continueBtn').prop('disabled', true);
  }
}


// public/js/register-step2.js

let selectedOccupationalFields = [];
let selectedExperienceFields = [];
let currentLevel = 1;
let currentParentId = null;
let isExperienceMode = false;

$(document).ready(function () {
  // فقط سطح اول‌ها رو از اول Select2 کن
  $('#occupational_fields, #experience_fields').select2();

  // Occupational
  $('#occupational_fields').on('change', function () {
    const selectedText = $(this).find('option:selected').text().trim();
    const selectedId = $(this).val();
    if (selectedText === '+ ایجاد کنید') return openCreateFieldModal(1, null, false);
    if (selectedId) loadSubfields(selectedId, 2, false);
  });

  $('#occupational_subfields').on('change', function () {
    const selectedText = $(this).find('option:selected').text().trim();
    const selectedId = $(this).val();
    if (selectedText === '+ ایجاد کنید') return openCreateFieldModal(2, $('#occupational_fields').val(), false);
    if (selectedId) loadSubfields(selectedId, 3, false);
  });

  $('#occupational_finalfields').on('change', function () {
    const selectedText = $(this).find('option:selected').text().trim();
    const selectedId = $(this).val();
    if (selectedText === '+ ایجاد کنید') return openCreateFieldModal(3, $('#occupational_subfields').val(), false);
    if (selectedId) addSelectedField(selectedId, selectedText, false);
  });

  // Experience
  $('#experience_fields').on('change', function () {
    const selectedText = $(this).find('option:selected').text().trim();
    const selectedId = $(this).val();
    if (selectedText === '+ ایجاد کنید') return openCreateFieldModal(1, null, true);
    if (selectedId) loadSubfields(selectedId, 2, true);
  });

  $('#experience_subfields').on('change', function () {
    const selectedText = $(this).find('option:selected').text().trim();
    const selectedId = $(this).val();
    if (selectedText === '+ ایجاد کنید') return openCreateFieldModal(2, $('#experience_fields').val(), true);
    if (selectedId) loadSubfields(selectedId, 3, true);
  });

  $('#experience_finalfields').on('change', function () {
    const selectedText = $(this).find('option:selected').text().trim();
    const selectedId = $(this).val();
    if (selectedText === '+ ایجاد کنید') return openCreateFieldModal(3, $('#experience_subfields').val(), true);
    if (selectedId) addSelectedField(selectedId, selectedText, true);
  });

  $('form').on('submit', function () {
    // حذف هر گونه input مخفی قبلی (جلوگیری از تکرار)
    $('input[name="occupational_fields[]"], input[name="experience_fields[]"]').remove();

    // اضافه کردن فقط فیلدهایی که کاربر انتخاب کرده
    selectedOccupationalFields.forEach(id => {
      $('<input>').attr({ type: 'hidden', name: 'occupational_fields[]', value: id }).appendTo('form');
    });
    selectedExperienceFields.forEach(id => {
      $('<input>').attr({ type: 'hidden', name: 'experience_fields[]', value: id }).appendTo('form');
    });
  });
});
function loadSubfields(parentId, level, isExp) {
  const url = isExp
    ? `/api/experience-fields/${parentId}/children`
    : `/api/occupational-fields/${parentId}/children`;

  const selectId = isExp
    ? (level === 2 ? '#experience_subfields' : '#experience_finalfields')
    : (level === 2 ? '#occupational_subfields' : '#occupational_finalfields');
  const $select = $(selectId);
  const container = isExp ? '#experience_container' : '#occupational_container';

  // ⚠️ انتقال مخفی‌سازی به بعد از لود داده‌ها
  $.get(url, function (data) {
    $select.empty().append('<option value="">زیر دسته این دسته را انتخاب کنید</option>');
    data.forEach(field => {
      $select.append(`<option value="${field.id}">${field.name}</option>`);
    });
    $select.append('<option value="create_new">+ ایجاد کنید</option>');
    $select.select2();

    // 👇 حالا که داده‌ها لود شدن، سطح قبلی رو مخفی و فعلی رو نمایش بده
    $(`${container} select`).each(function () {
      const thisLevel = parseInt($(this).data('level'));

      if (thisLevel == level) {
        $(this).removeClass('d-none');
      } else {
        console.log($(this).addClass('d-none'))
        $(this).addClass('d-none');
      }
    });
  });
}



function addSelectedField(id, name, isExp) {
  const selected = isExp ? selectedExperienceFields : selectedOccupationalFields;
  const maxErrorId = isExp ? '#error_experience' : '#error_occupational';
  const dupErrorId = isExp ? '#duplicate_error_experience' : '#duplicate_error_occupational';
  const wrapperId = isExp ? '#selected_experience_fields' : '#selected_occupational_fields';

  if (selected.includes(id)) return $(dupErrorId).show();
  if (selected.length >= 2) return $(maxErrorId).show();

  selected.push(id);
  $(wrapperId).append(`<span class="badge bg-primary m-1">${name}<span onclick="removeField('${id}', this, ${isExp})" style="cursor:pointer;">&times;</span></span>`);
  updateContinueButtonState();

}

function removeField(id, el, isExp) {
  if (isExp) {
    selectedExperienceFields = selectedExperienceFields.filter(x => x !== id);
    $('#error_experience, #duplicate_error_experience').hide();
  } else {
    selectedOccupationalFields = selectedOccupationalFields.filter(x => x !== id);
    $('#error_occupational, #duplicate_error_occupational').hide();
  }
  $(el).parent().remove();
  updateContinueButtonState();

}

function openCreateFieldModal(level, parentId, isExp) {
  currentLevel = level;
  currentParentId = parentId;
  isExperienceMode = isExp;

  $('#field_level').val(level);
  $('#parent_id').val(parentId ?? '');
  $('#field_name').val('');
  $('#create_field_error').addClass('d-none');

  const modal = new bootstrap.Modal(document.getElementById('createFieldModal'));
  modal.show();
}

$('#createFieldForm').on('submit', function (e) {
  e.preventDefault();
  const name = $('#field_name').val();
  const parentId = $('#parent_id').val();
  const url = isExperienceMode ? '/api/experience-fields' : '/api/occupational-fields';

  $.post(url, { name, parent_id: parentId }, function (newField) {
    const selectId = getCurrentSelectId();
    const $select = $(selectId);

    const createOption = $select.find('option').filter(function () {
      return $(this).text().trim() === '+ ایجاد کنید';
    }).detach();

    $select.append(`<option value="${newField.id}">${newField.name}</option>`);
    $select.append(createOption);
    $select.val(newField.id);

    if (currentLevel < 3) {
      loadSubfields(newField.id, currentLevel + 1, isExperienceMode);
    } else {
      addSelectedField(newField.id, newField.name, isExperienceMode);
    }

    bootstrap.Modal.getInstance(document.getElementById('createFieldModal')).hide();
    isExperienceMode = false;
  }).fail(() => {
    $('#create_field_error').removeClass('d-none');
  });
});

function getCurrentSelectId() {
  if (isExperienceMode) {
    return currentLevel === 1 ? '#experience_fields' : currentLevel === 2 ? '#experience_subfields' : '#experience_finalfields';
  } else {
    return currentLevel === 1 ? '#occupational_fields' : currentLevel === 2 ? '#occupational_subfields' : '#occupational_finalfields';
  }
}

</script>
@endsection
