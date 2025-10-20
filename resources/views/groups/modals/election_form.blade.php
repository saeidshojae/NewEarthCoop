<div id="electionOptionsBox" style="display: none; direction: rtl;">
    <form id="pollForm" action="{{ route('groups.poll.store', $group) }}" method="POST">
        @csrf
        <input type="text" name="question" placeholder="موضوع انتخابات" class="form-control mb-2">

        <!-- انتخاب نوع نظرسنجی -->
        <label>نوع انتخابات:</label>
        <select name="type" id="poll_election_type" class="form-control mb-2" onchange="handlePollTypeChange2()">
            <option value="0">عمومی</option>
            <option value="1">تخصصی</option>
        </select>

        <!-- انتخاب تخصص (فقط در حالت تخصصی نمایش داده می‌شود) -->
        <div id="el_specialties_box" style="display: none;">
            <label>تخصص‌ها:</label>
            <select name="skill_id" id="specialties2" class="form-control mb-2">
                @foreach ($specialities as $speciality)
                    <option value="{{ $speciality->id }}">{{ $speciality->name }}</option>
                @endforeach
            </select><br>
        </div>

        <input type="hidden" name="main_type" value="0">

        <!-- مدت زمان فعال بودن نظرسنجی -->
        <label>مدت زمان فعال بودن (به روز):</label>
        <input type="number" name="expires_at" class="form-control mb-2" min="1" placeholder="مثلاً ۳">

        <label>نامزد ها:</label>
        <div id="el_dynamic-inputs">
            <input type="text" name="options[]" placeholder="نامزد ۱" class="form-control mb-2" />
        </div>
        <button type="button" onclick="addInput2()" class="btn btn-sm btn-success">➕ افزودن نامزد جدید</button>

        <button type="submit" class="btn btn-primary w-100 mt-2">ارسال</button>
        <button type="button" class="btn btn-secondary w-100 mt-2" onclick="cancelPollForm()">لغو</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#specialties2').select2();
    });
    
    let optionCount2 = 1;

    function addInput2() {
        optionCount2++;
        const container = document.getElementById('el_dynamic-inputs');
        const newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.name = 'options[]';
        newInput.placeholder = 'گزینه ' + optionCount2;
        newInput.className = 'form-control mb-2';
        container.appendChild(newInput);
    }

    function handlePollTypeChange2() {
        const type = document.getElementById('poll_election_type').value;
        const specialtiesBox = document.getElementById('el_specialties_box');
        specialtiesBox.style = (type === '1') ? 'display: block;' : 'display: none;';
        
    }
</script>
