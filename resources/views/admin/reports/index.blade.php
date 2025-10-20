@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
<br><br><br>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-flag mr-2"></i>
                        گزارش‌های پیام‌ها
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id='myTable'>
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>پیام/ گروه</th>
                                    <th>گزارش‌دهنده</th>
                                    <th>دلیل گزارش</th>
                                    <th>تاریخ گزارش</th>
                                    <th>وضعیت</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $report)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($report->message_id != null)
                                            <div class="message-preview">
                                                <div class="sender-info">
                                                    <span class="sender-name">{{ $report->message->user->fullName() }}</span>
                                                    <span class="message-time">{{ $report->message->created_at->format('H:i Y/m/d') }}</span>
                                                </div>
                                                <div class="message-content">
                                                    @if($report->message->file_path)
                                                        @if(str_starts_with($report->message->file_type, 'image/'))
                                                            <img src="{{ asset($report->message->file_path) }}" alt="تصویر پیام" class="img-thumbnail" style="max-width: 100px;">
                                                        @else
                                                            <a href="{{ asset($report->message->file_path) }}" class="file-link">
                                                                <i class="fas fa-file"></i> {{ $report->message->file_name }}
                                                            </a>
                                                        @endif
                                                    @elseif($report->message->voice_message)
                                                        <audio controls class="voice-message">
                                                            <source src="{{ $report->message->voice_message }}" type="audio/wav">
                                                        </audio>
                                                    @else
                                                        {{ Str::limit($report->message->message, 100) }}
                                                    @endif
                                                </div>
                                            </div>
                                            @elseif($report->group_id != null)
                                            <div class="message-preview">
                                                <div class="sender-info">
                                                    <span class="sender-name">{{ \App\Models\Group::find($report->group_id)->name }}</span>
                                                    <span class="message-time">{{ $report->created_at->format('H:i Y/m/d') }}</span>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle" style="background-color: {{ $report->reporter->avatar_color }}">
                                                {{ substr($report->reporter->name, 0, 1) }}
                                            </div>
                                            <a href="{{ route('profile.member.show', $report->reporter) }}" class="ml-2 text-dark">
                                                {{ $report->reporter->first_name }} {{ $report->reporter->last_name }}
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{ $report->reason }}</td>
                                    <td>{{ $report->created_at->format('H:i Y/m/d') }}</td>
                                    <td>
                                        <span class="badge @if($report->status == 'pending') bg-warning @elseif($report->status == 'resolved') bg-success @elseif($report->status == 'rejected') bg-danger @endif">
                                            {{ $report->status == 'pending' ? 'در انتظار بررسی' : ($report->status == 'resolved' ? 'بررسی شده' : 'رد شده') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-success" onclick="updateReportStatus({{ $report->id }}, 'resolved')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="updateReportStatus({{ $report->id }}, 'rejected')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info" onclick="deleteReport({{ $report->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.message-preview {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px;
    max-width: 300px;
}

.sender-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
    font-size: 0.8rem;
}

.sender-name {
    font-weight: bold;
    color: #333;
}

.message-time {
    color: #666;
}

.message-content {
    font-size: 0.9rem;
    color: #444;
}

.avatar-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}

.file-link {
    color: #007bff;
    text-decoration: none;
}

.file-link:hover {
    text-decoration: underline;
}

.voice-message {
    width: 100%;
    max-width: 200px;
}

.badge {
    padding: 5px 10px;
    border-radius: 15px;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
}
</style>

    <script>
        new DataTable('#myTable', {
            language: {
            "decimal":        "",
            "emptyTable":     "هیچ داده‌ای در جدول وجود ندارد",
            "info":           "نمایش _START_ تا _END_ از _TOTAL_ رکورد",
            "infoEmpty":      "نمایش 0 تا 0 از 0 رکورد",
            "infoFiltered":   "(فیلتر شده از مجموع _MAX_ رکورد)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "نمایش _MENU_ رکورد",
            "loadingRecords": "در حال بارگذاری...",
            "processing":     "در حال پردازش...",
            "search":         "جستجو:",
            "zeroRecords":    "رکوردی یافت نشد",
            "paginate": {
                "first":      "اولین",
                "last":       "آخرین",
                "next":       "بعدی",
                "previous":   "قبلی"
            },
            "aria": {
                "sortAscending":  ": فعال‌سازی مرتب‌سازی صعودی",
                "sortDescending": ": فعال‌سازی مرتب‌سازی نزولی"
            }
        }
        });
    </script>
<script>
function updateReportStatus(reportId, status) {
    if (!confirm('آیا از تغییر وضعیت این گزارش اطمینان دارید؟')) return;
    
    fetch(`/admin/reports/${reportId}`, {
        method: 'POST', // تغییر به POST
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            alert('خطا در بروزرسانی وضعیت گزارش');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('خطا در بروزرسانی وضعیت گزارش');
    });
    location.reload();

}


function deleteReport(reportId) {
    if (!confirm('آیا از حذف این گزارش اطمینان دارید؟')) return;
    
    fetch(`/admin/reports/${reportId}`, {
        method: 'POST', // تغییر به DELETE
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            alert('خطا در حذف گزارش');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('خطا در حذف گزارش');
    });
    location.reload();

}

</script>
@endsection 