<div id="pollOptionsBox" style="display: none; direction: rtl;">
    <form id="pollForm" action="{{ route('groups.poll.store', $group) }}" method="POST">
        @csrf
        <!-- انتخاب نوع نظرسنجی -->
        <label>نوع نظرسنجی:</label>
        <select name="type" id="poll_type" class="form-control mb-2" onchange="handlePollTypeChange()">
            <option value="0">عمومی</option>
            <option value="1">تخصصی</option>
        </select>
         <!-- انتخاب تخصص (فقط در حالت تخصصی نمایش داده می‌شود) -->
        <div id="specialties_box" style="display: none;">
            <label>تخصص‌ها:</label>
            <select name="skill_id" id="specialties" class="form-control mb-2">
                @foreach ($specialities as $speciality)
                    <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
                @endforeach
            </select><br>
        </div>

        <!-- مدت زمان فعال بودن نظرسنجی -->
        <label>مدت زمان فعال بودن (به روز):</label>
        <input type="number" name="expires_at" class="form-control mb-2" min="1" placeholder="مثلاً ۳">
        <label>سوال نظرسنجی را وارد کنید:</label>
        <input type="text" name="question" placeholder="سوال نظرسنجی" class="form-control mb-2">

       
        <input type="hidden" name="main_type" value="1">


        <label>گزینه‌ها:</label>
        <div id="dynamic-inputs">
            <input type="text" name="options[]" placeholder="گزینه ۱" class="form-control mb-2" />
        </div>
        <button type="button" onclick="addInput()" class="btn btn-sm btn-success">➕ افزودن گزینه جدید</button>

        <button type="submit" class="btn btn-primary w-100 mt-2">ارسال</button>
        <button type="button" class="btn btn-secondary w-100 mt-2" onclick="cancelPollForm()" style='background-color: #c96056'>لغو</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#specialties').select2();
    });
    
    let optionCount = 1;

    function addInput() {
        optionCount++;
        const container = document.getElementById('dynamic-inputs');
        const newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.name = 'options[]';
        newInput.placeholder = 'گزینه ' + optionCount;
        newInput.className = 'form-control mb-2';
        container.appendChild(newInput);
    }

    function handlePollTypeChange() {
        const type = document.getElementById('poll_type').value;
        const specialtiesBox = document.getElementById('specialties_box');
        specialtiesBox.style.display = (type === '1') ? 'block' : 'none';
    }
</script>
