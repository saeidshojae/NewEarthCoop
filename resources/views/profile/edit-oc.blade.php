<!-- resources/views/register/step2.blade.php -->
@extends('layouts.app')

@section('head-tag')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Bootstrap CSS -->
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
          تغییر زمینه های صنفی و تجربی شما
        </div>
        <div class="card-body">
          <form action="{{ route('profile.update.experience') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="hidden_occupational_fields" name="occupational_fields[]">
            <input type="hidden" id="hidden_experience_fields" name="experience_fields[]">
            
            {{-- صنفی --}}
            <div id="occupational_container" class="mb-4">
              <label class="form-label">زمینه فعالیت صنفی:</label>
              <div id="selected_occupational_fields">
                @foreach($user->specialties as $specialty)
                @if(isset($specialty->parent) AND isset($specialty->parent->parent))
                  <span class="badge bg-primary m-1">
                    {{ $specialty->name }}
                    <span onclick="removeField({{ $specialty->id }}, this, false)" style="cursor:pointer;">×</span>
                  </span>
                  @endif
                @endforeach
              </div>

              <select id="occupational_fields" class="form-control" data-level="1">
                <option value="">انتخاب کنید</option>
                @foreach($occupationalFields as $field)
                  <option value="{{ $field->id }}">{{ $field->name }}</option>
                @endforeach
                <option value="create_new">+ ایجاد کنید</option>
              </select>

              <select id="occupational_subfields" class="form-control mt-2 d-none" data-level="2"></select>
              <select id="occupational_finalfields" class="form-control mt-2 d-none" data-level="3"></select>

              <div class="error-message" id="error_occupational">حداکثر می‌توانید ۱۰ مورد انتخاب کنید.</div>
              <div class="error-message text-danger" id="duplicate_error_occupational">
                این گزینه قبلاً انتخاب شده است.
              </div>
            </div>

            {{-- تجربی --}}
            <div id="experience_container" class="mb-4">
              <label class="form-label">زمینه تجربی و تخصصی:</label>
              <div id="selected_experience_fields">
                @foreach($user->experiences as $experience)
                @if(isset($experience->parent) AND isset($experience->parent->parent))
                  <span class="badge bg-primary m-1">
                    {{ $experience->name }}
                    <span onclick="removeField({{ $experience->id }}, this, true)" style="cursor:pointer;">×</span>
                  </span>
                  @endif
                @endforeach
              </div>

              <select id="experience_fields" class="form-control" data-level="1">
                <option value="">انتخاب کنید</option>
                @foreach($experienceFields as $field)
                  <option value="{{ $field->id }}">{{ $field->name }}</option>
                @endforeach
                <option value="create_new">+ ایجاد کنید</option>
              </select>

              <select id="experience_subfields" class="form-control mt-2 d-none" data-level="2"></select>
              <select id="experience_finalfields" class="form-control mt-2 d-none" data-level="3"></select>

              <div class="error-message" id="error_experience">حداکثر می‌توانید ۱۰ مورد انتخاب کنید.</div>
              <div class="error-message text-danger" id="duplicate_error_experience">
                این گزینه قبلاً انتخاب شده است.
              </div>
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-primary">ثبت تغییرات</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Create Field -->
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
          <button type="submit" class="btn btn-primary">ثبت</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">لغو</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  {{-- ✅ jQuery همیشه باید اول لود بشه --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  {{-- ✅ Select2 (بعد از jQuery، بدون defer) --}}
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
  {{-- ✅ Bootstrap --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // public/js/register-step2.js

    @if(session('success'))
      document.addEventListener('DOMContentLoaded', function() {
        showSuccessAlert('{{ session('success') }}');
      });
    @endif

    let selectedOccupationalFields = @json($user->specialties->pluck('id')->toArray());
    let selectedExperienceFields = @json($user->experiences->pluck('id')->toArray());

    let currentLevel = 1;
    let currentParentId = null;
    let isExperienceMode = false;

    $(document).ready(function () {
      // فقط سطح اول‌ها رو Select2 کن
      $('#occupational_fields, #experience_fields').select2();

      // Occupational سطح ۱
$('#occupational_fields').on('change', function () {
  const selectedText = $(this).find('option:selected').text().trim();
  const selectedId   = $(this).val();
  if (selectedText === '+ ایجاد کنید')
    return openCreateFieldModal(1, null, false);

  if (selectedId) {
    // همینجا اضافه می‌کنیم
    addSelectedField(selectedId, selectedText, false);
    // و بعد زیرسطح رو لود می‌کنیم
    loadSubfields(selectedId, 2, false);
  }
});

// Occupational سطح ۲
$('#occupational_subfields').on('change', function () {
  const selectedText = $(this).find('option:selected').text().trim();
  const selectedId   = $(this).val();
  if (selectedText === '+ ایجاد کنید')
    return openCreateFieldModal(2, $('#occupational_fields').val(), false);

  if (selectedId) {
    addSelectedField(selectedId, selectedText, false);
    loadSubfields(selectedId, 3, false);
  }
});

// Occupational سطح ۳ (همون قبلی)
$('#occupational_finalfields').on('change', function () {
  const selectedText = $(this).find('option:selected').text().trim();
  const selectedId   = $(this).val();
  if (selectedText === '+ ایجاد کنید')
    return openCreateFieldModal(3, $('#occupational_subfields').val(), false);

  if (selectedId) {
    addSelectedField(selectedId, selectedText, false, true);
  }
});

// برای Experience هم مشابه:
$('#experience_fields').on('change', function () {
  const txt = $(this).find('option:selected').text().trim();
  const id  = $(this).val();
  if (txt === '+ ایجاد کنید')
    return openCreateFieldModal(1, null, true);
  if (id) {
    addSelectedField(id, txt, true);
    loadSubfields(id, 2, true);
  }
});
$('#experience_subfields').on('change', function () {
  const txt = $(this).find('option:selected').text().trim();
  const id  = $(this).val();
  if (txt === '+ ایجاد کنید')
    return openCreateFieldModal(2, $('#experience_fields').val(), true);
  if (id) {
    addSelectedField(id, txt, true);
    loadSubfields(id, 3, true);
  }
});
$('#experience_finalfields').on('change', function () {
  const txt = $(this).find('option:selected').text().trim();
  const id  = $(this).val();
  if (txt === '+ ایجاد کنید')
    return openCreateFieldModal(3, $('#experience_subfields').val(), true);
  if (id) addSelectedField(id, txt, true, true);
});


      $('form').on('submit', function () {
        $('input[name="occupational_fields[]"], input[name="experience_fields[]"]').remove();
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
        ? (level === 2 ? '#experience_subfields'  : '#experience_finalfields')
        : (level === 2 ? '#occupational_subfields': '#occupational_finalfields');
      const $select  = $(selectId);
      const container = isExp ? '#experience_container' : '#occupational_container';

      $.get(url, function (data) {
        $select.empty().append('<option value="">زیر دسته این دسته را انتخاب کنید</option>');
        data.forEach(field => {
          $select.append(`<option value="${field.id}">${field.name}</option>`);
        });
        $select.append('<option value="create_new">+ ایجاد کنید</option>');
        $select.select2();

        $(`${container} select`).each(function () {
          const thisLevel = parseInt($(this).data('level'));
          if (thisLevel == level) $(this).removeClass('d-none');
          else                 $(this).addClass('d-none');
        });
      });
    }

    function addSelectedField(id, name, isExp, addToHidden = false) {
      const selected    = isExp ? selectedExperienceFields : selectedOccupationalFields;
      const maxErrorId  = isExp ? '#error_experience'       : '#error_occupational';
      const dupErrorId  = isExp ? '#duplicate_error_experience' : '#duplicate_error_occupational';
      const wrapperId   = isExp ? '#selected_experience_fields' : '#selected_occupational_fields';
      const inputName   = isExp ? 'experience_fields[]' : 'occupational_fields[]';

      if (selected.includes(id)) {
        $(dupErrorId).show(); return;
      }
      if (selected.length >= 1000) {
        $(maxErrorId).show(); disableSelects(isExp); return;
      }

      selected.push(id);
      if(addToHidden){
      $(wrapperId).append(`
        <span class="badge bg-primary m-1">
          ${name}
          <span onclick="removeField('${id}', this, ${isExp})" style="cursor:pointer;">×</span>
        </span>
      `);          
      }

    }

    function removeField(id, el, isExp) {
      if (isExp) {
        selectedExperienceFields = selectedExperienceFields.filter(x => x != id);
        $('#error_experience, #duplicate_error_experience').hide();
      } else {
        selectedOccupationalFields = selectedOccupationalFields.filter(x => x != id);
        $('#error_occupational, #duplicate_error_occupational').hide();
      }
      enableSelects(isExp);
      $(el).parent().remove();
    }

    function disableSelects(isExp) {
      const container = isExp ? '#experience_container' : '#occupational_container';
      $(`${container} select`).prop('disabled', true);
    }
    function enableSelects(isExp) {
      const container = isExp ? '#experience_container' : '#occupational_container';
      $(`${container} select`).prop('disabled', false);
    }

    function openCreateFieldModal(level, parentId, isExp) {
      currentLevel     = level;
      currentParentId  = parentId;
      isExperienceMode = isExp;
      $('#field_level').val(level);
      $('#parent_id'   ).val(parentId ?? '');
      $('#field_name'  ).val('');
      $('#create_field_error').addClass('d-none');
      new bootstrap.Modal(document.getElementById('createFieldModal')).show();
    }

    $('#createFieldForm').on('submit', function(e) {
      e.preventDefault();
      const name     = $('#field_name').val();
      const parentId = $('#parent_id').val();
      const url      = isExperienceMode ? '/api/experience-fields' : '/api/occupational-fields';

      $.post(url, { name, parent_id: parentId }, function (newField) {
        const selectId = getCurrentSelectId();
        const $select  = $(selectId);
        const createOpt = $select.find('option').filter(function(){
          return $(this).text().trim()==='+ ایجاد کنید';
        }).detach();

        $select.append(`<option value="${newField.id}">${newField.name}</option>`);
        $select.append(createOpt).val(newField.id);

        if (currentLevel < 3)
          loadSubfields(newField.id, currentLevel+1, isExperienceMode);
        else
          addSelectedField(newField.id, newField.name, isExperienceMode);

        bootstrap.Modal.getInstance(document.getElementById('createFieldModal')).hide();
        isExperienceMode = false;
      }).fail(() => {
        $('#create_field_error').removeClass('d-none');
      });
    });

    function getCurrentSelectId() {
      if (isExperienceMode) {
        return currentLevel===1 ? '#experience_fields'
             : currentLevel===2 ? '#experience_subfields'
             : '#experience_finalfields';
      } else {
        return currentLevel===1 ? '#occupational_fields'
             : currentLevel===2 ? '#occupational_subfields'
             : '#occupational_finalfields';
      }
    }
  </script>
@endsection
