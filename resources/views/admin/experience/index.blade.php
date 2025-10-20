@extends('layouts.app')

@section('content')
<div class="container py-4" dir="rtl">
    <h2 class="text-center mb-4 fw-bold">تأیید تخصص‌ها و صنف‌های جدید</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif

    <!-- Bulk Actions -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label" for="selectAll">
                                    انتخاب همه
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-success me-2" onclick="bulkApprove()">
                                تأیید انتخاب شده‌ها
                            </button>
                            <button type="button" class="btn btn-danger" onclick="bulkDelete()">
                                حذف انتخاب شده‌ها
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="bulkForm" method="POST" style="display: none;">
        @csrf
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th width="50">
                        <input type="checkbox" id="selectAllHeader" onchange="toggleAll(this)">
                    </th>
                    <th>#</th>
                    <th>نام</th>
                    <th>والد</th>
                    <th>سطح</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($combines as $key => $combine)
                    <tr>
                        <td>
                            <input type="checkbox" class="item-checkbox" value="{{ $combine->getTable() }}_{{ $combine->id }}" onchange="updateSelectAll()">
                        </td>
                        <form action='{{ route('admin.active.experience.update', $combine) }}' method='POST'>
                            @method('PUT')
                            @csrf
                            
                        <td>{{ $key + 1 }}</td>
                        <td><input type='text' name='name' class='form-control' value='{{ $combine->name }}'></td>
                        <input type='hidden' name='table' value='{{ $combine->getTable() }}'>

                        <td>
                            @php
                                $parents = [];
                                if($combine->getTable() == 'experience_fields'){
                                    if($combine->parent_id != null){
                                        $secParent = \App\Models\ExperienceField::find($combine->parent_id);
                                        if($secParent) {
                                            array_push($parents, $secParent->name);
                                            
                                            if($secParent->parent_id != null){
                                                $lastParent = \App\Models\ExperienceField::find($secParent->parent_id);
                                                if($lastParent) {
                                                    array_push($parents, $lastParent->name);
                                                }
                                            }
                                        }
                                    }
                                }elseif($combine->getTable() == 'occupational_fields'){
                                    if($combine->parent_id != null){
                                        $secParent = \App\Models\OccupationalField::find($combine->parent_id);
                                        if($secParent) {
                                            array_push($parents, $secParent->name);
                                            
                                            if($secParent->parent_id != null){
                                                $lastParent = \App\Models\OccupationalField::find($secParent->parent_id);
                                                if($lastParent) {
                                                    array_push($parents, $lastParent->name);
                                                }
                                            }
                                        }
                                    }
                                }
                            @endphp
                            
                            <ul>
                                @if(count($parents) == 0)
                                فاقد والد
                                @else
                                
                                @foreach($parents as $parent)
                                    <li>{{ $parent }}</li>
                                @endforeach
                                @endif
                            </ul>
                        </td>
                        <td>
                            @switch($combine->getTable())
                                @case('experience_fields')
                                    تجربه
                                    @break
                                @case('occupational_fields')
                                    صنف
                                    @break
                                @default
                                    -
                            @endswitch
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class='btn btn-success'>ویرایش</button>
                                <a href="{{ route('admin.active.experience.edit', [$combine->id, 'table' => $combine->getTable()]) }}" class="btn btn-success">تأیید</a>
                                <a href="{{ route('admin.active.experience.delete', [$combine->id, 'table' => $combine->getTable()]) }}" class="btn btn-danger">حذف</a>
                            </div>
                        </td>
                        </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleAll(checkbox) {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    updateSelectAll();
}

function updateSelectAll() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
    const selectAllCheckbox = document.getElementById('selectAll');
    const selectAllHeaderCheckbox = document.getElementById('selectAllHeader');
    
    selectAllCheckbox.checked = checkboxes.length === checkedBoxes.length;
    selectAllHeaderCheckbox.checked = checkboxes.length === checkedBoxes.length;
}

function bulkApprove() {
    const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('لطفاً حداقل یک آیتم را انتخاب کنید.');
        return;
    }
    
    if (confirm('آیا از تأیید آیتم‌های انتخاب شده اطمینان دارید؟')) {
        const selectedItems = Array.from(checkedBoxes).map(cb => cb.value);
        
        // Create form data
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        selectedItems.forEach(item => {
            formData.append('items[]', item);
        });
        
        // Send AJAX request
        fetch('{{ route("admin.active.experience.bulk.approve") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('خطا در تأیید آیتم‌ها');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('خطا در ارسال درخواست');
        });
    }
}

function bulkDelete() {
    const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('لطفاً حداقل یک آیتم را انتخاب کنید.');
        return;
    }
    
    if (confirm('آیا از حذف آیتم‌های انتخاب شده اطمینان دارید؟ این عمل قابل بازگشت نیست.')) {
        const selectedItems = Array.from(checkedBoxes).map(cb => cb.value);
        
        // Create form data
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        selectedItems.forEach(item => {
            formData.append('items[]', item);
        });
        
        // Send AJAX request
        fetch('{{ route("admin.active.experience.bulk.delete") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('خطا در حذف آیتم‌ها');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('خطا در ارسال درخواست');
        });
    }
}
</script>
@endsection
