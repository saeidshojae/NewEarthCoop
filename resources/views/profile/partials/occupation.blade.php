<div class="toggle-box">
  <div class="toggle-header" onclick="toggleBox(this)">
      <span>صنف و تخصص</span>
      <i class="fas fa-chevron-down toggle-icon"></i>
  </div>
  <div class="toggle-content">
      <hr>
      <b style="color: #c94343db">توجه داشته باشید تغییر تخصص و صنف شما به منزله خروج از گروه های فعلی تان می‌باشد</b><br><br>
        <a class='btn' href='/profile/edit-oc'>از این قسمت اقدام به تغییر کنید</a>
      <!--<form action="{{ route('profile.update.experience') }}" method="POST" novalidate>-->
      <!--    @csrf-->
      <!--    @method('PUT')-->

      <!--    {{-- زمینه فعالیت صنفی (occupational) --}}-->
      <!--    <div class="mb-3">-->
      <!--        <label class="form-label">زمینه فعالیت صنفی:</label>-->
      <!--        <select id="occupational_fields" class="form-control" data-level="1" style="width: 100%">-->
      <!--            <option value="">انتخاب کنید</option>-->
      <!--            @foreach($occupationalFields as $field)-->
      <!--                <option value="{{ $field->id }}">{{ $field->name }}</option>-->
      <!--            @endforeach-->
      <!--        </select>-->

      <!--        <select id="occupational_subfields" class="form-control mt-2 d-none" data-level="2">-->
      <!--            <option value="">انتخاب کنید</option>-->
      <!--        </select>-->

      <!--        <select id="occupational_finalfields" class="form-control mt-2 d-none" data-level="3">-->
      <!--            <option value="">انتخاب کنید</option>-->
      <!--        </select>-->

      <!--        <div id="selected_occupational_fields" class="mt-3">-->
      <!--            @foreach($user->specialties as $specialty)-->
      <!--                <span class="badge bg-primary text-white mx-1 my-1" data-id="{{ $specialty->id }}">-->
      <!--                    {{ $specialty->name }}-->
      <!--                    <button type="button" class="btn btn-sm btn-danger remove-selection" style='width: 1.5rem;'>×</button>-->
      <!--                    <input type="hidden" name="occupational_fields[]" value="{{ $specialty->id }}">-->
      <!--                </span>-->
      <!--            @endforeach-->
      <!--        </div>-->
      <!--        <div class="error-message" id="error_occupational">حداکثر می‌توانید ۲ مورد انتخاب کنید.</div>-->
      <!--        <div class="error-message text-danger" id="duplicate_error_occupational" style="display: none;">-->
      <!--            این گزینه قبلاً انتخاب شده است.-->
      <!--        </div>-->

      <!--        <button type="button" class="btn btn-outline-secondary btn-sm mt-2"-->
      <!--                data-bs-toggle="modal"-->
      <!--                data-bs-target="#addOccupationalModal">-->
      <!--            افزودن زمینه صنفی جدید-->
      <!--        </button>-->
      <!--    </div>-->

      <!--    {{-- زمینه تجربی و تخصصی (experience) --}}-->
      <!--    <div class="mb-3">-->
      <!--        <label class="form-label">زمینه تجربی و تخصصی:</label>-->
      <!--        <select id="experience_fields" class="form-control" data-level="1" style="width: 100%">-->
      <!--            <option value="">انتخاب کنید</option>-->
      <!--            @foreach($experienceFields as $field)-->
      <!--                <option value="{{ $field->id }}">{{ $field->name }}</option>-->
      <!--            @endforeach-->
      <!--        </select>-->

      <!--        <select id="experience_subfields" class="form-control mt-2 d-none" data-level="2">-->
      <!--            <option value="">انتخاب کنید</option>-->
      <!--        </select>-->

      <!--        <select id="experience_finalfields" class="form-control mt-2 d-none" data-level="3">-->
      <!--            <option value="">انتخاب کنید</option>-->
      <!--        </select>-->

      <!--        <div id="selected_experience_fields" class="mt-3">-->
      <!--            @foreach($user->experiences as $experience)-->
      <!--                <span class="badge bg-primary text-white mx-1 my-1" data-id="{{ $experience->id }}">-->
      <!--                    {{ $experience->name }}-->
      <!--                    <button type="button" class="btn btn-sm btn-danger remove-selection" style='width: 1.5rem;'>×</button>-->
      <!--                    <input type="hidden" name="experience_fields[]" value="{{ $experience->id }}">-->
      <!--                </span>-->
      <!--            @endforeach-->
      <!--        </div>-->
      <!--        <div class="error-message" id="error_experience">حداکثر می‌توانید ۲ مورد انتخاب کنید.</div>-->
      <!--        <div class="error-message text-danger" id="duplicate_error_experience" style="display: none;">-->
      <!--            این گزینه قبلاً انتخاب شده است.-->
      <!--        </div>-->

      <!--        <button type="button" class="btn btn-outline-secondary btn-sm mt-2"-->
      <!--                data-bs-toggle="modal"-->
      <!--                data-bs-target="#addExperienceModal">-->
      <!--            افزودن زمینه تخصصی جدید-->
      <!--        </button>-->
      <!--    </div>-->

      <!--    <div class="text-center">-->
      <!--        <input type="submit" value="ذخیره تغییرات" class="btn btn-primary" style="background-color: #518dbdcc !important;">-->
      <!--    </div>-->
      <!--</form>-->
  </div>
</div>