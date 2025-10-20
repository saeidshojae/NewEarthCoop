@extends('layouts.app')

@section('title', 'انتخابات جاری')

@section('head-tag')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <style>
    .toggle-box {
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 1rem;
      margin: 1rem 0;
      transition: all 0.3s ease;
      width: 100%;
      background-color: #fff
    }

    .toggle-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
      font-weight: bold;
      font-size: 1.1rem;
    }

    .toggle-content {
      margin-top: 1rem;
      display: none;
      font-size: 0.95rem;
      line-height: 1.6;
    }

    .toggle-icon {
      font-size: 1.3rem;
      transition: transform 0.3s;
    }

    .open .toggle-icon {
      transform: rotate(180deg);
    }

    .open .toggle-content {
      display: block;
    }
    input {
        background-color: #fff !important
    }
    select {
        background-color: #fff !important
    }

    textarea {
        background-color: #fff !important
    }

    .remove-selection {
    padding: 0 .4rem;
    margin: .2rem .3rem .2rem .1rem;
  }
  .badge {
    background-color: #57a1d7bf !important;
  }
  .error-message {
    color: red;
    font-size: 0.9rem;
    display: none;
  }

  .select2-container{
    margin-bottom: 1rem !important;
  }

    </style>
@endsection

@section('content')
<div class="container " style="direction: rtl; text-align: right;">
    <h1 class="mb-4 text-center">انتخابات جاری</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>گروه</th>
                <th>وضعیت شما</th>
                <th>رای های شما</th>
                <th>تاریخ ثبت</th>
                <th>عملیات</th>                
            </tr>
        </thead>
        <tbody>
            @foreach($currentElections as $key => $election)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $election->group->name }}</td>
                <td>{{ $election->yourVotes->isEmpty() ? 'رای ندادید' : 'رای دادید' }}</td>
                <td>
                    @foreach ($election->yourVotes as $vote)
                        <li>{{ $vote->user->fullName() }} ({{ $vote->position == 0 ? 'بازرس' : 'مدیر' }})</li>
                    @endforeach
                </td>
                <td>{{ verta($election->created_at)->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ route('groups.chat', [$election->group->id, '#electionRedirect']) }}" class="btn btn-warning">
                        {{ $election->yourVotes->isEmpty() ? 'رای بدهید' : 'رای خود را ویرایش کنید' }}
                    </a>
                </td>
            </tr>
        @endforeach
        
        </tbody>
    </table>
</div>

</div>

@endsection

@section('scripts')
{{-- اسکریپت جی کوئری و Bootstrap --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

{{-- اگر از Bootstrap5 استفاده می‌کنید: --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- بارگذاری jQuery -->
<!-- بارگذاری Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>



<script>
    function toggleBox(header) {
      const content = header.nextElementSibling;
      const icon = header.querySelector('.toggle-icon');
      content.style.display = content.style.display === 'none' ? 'block' : 'none';
      icon.classList.toggle('fa-chevron-down');
      icon.classList.toggle('fa-chevron-up');
    }
  
    // همه رو پیش‌فرض ببنده
    document.querySelectorAll('.toggle-content').forEach(el => el.style.display = 'none');
  </script>
  

<script>
    function updatePlaceholder() {
        const select = document.getElementById('country_code');
        const selected = select.options[select.selectedIndex];
        const placeholder = selected.getAttribute('data-placeholder');
    
        document.getElementById('phone').placeholder = 'برای مثال: ' + placeholder;
    }
    </script>

@endsection
