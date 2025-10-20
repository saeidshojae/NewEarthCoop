@extends('layouts.app')

@section('title', 'مشارکت های شما')

@section('head-tag')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <style>
    .toggle-box {
      border: 2px solid #518476;
      border-radius: 8px;
      padding: 1rem;
      margin: 1rem 0;
      transition: all 0.3s ease;
      width: 100%;
      background-color: #fff;

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
  a{
    text-decoration: none;
    color: #333
  }

    </style>
@endsection

@section('content')
<div class="container " style="direction: rtl; text-align: right;">
    <h1 class="mb-4 text-center">مشارکت های شما</h1>

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


    <div class="toggle-box">
        <div class="toggle-header" onclick="toggleBox(this)">
            <span>پست های شما</span>
            <i class="fas fa-chevron-down toggle-icon" id="toggleIcon"></i>
        </div>
        <div class="toggle-content" id="toggleContent">
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>نام پست</th>
                            <th>تعداد لایک</th>
                            <th>تعداد دیسلایک</th>
                            <th>تعداد نظر</th>
                            <th>گروه</th>
                            <th>تاریخ ایجاد</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($blogs as $key => $blog)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><a href="{{ route('groups.chat', [$blog->group->id, '#blog-' . $blog->id]) }}">{{ $blog->title }}</a></td>
                                <td>{{ $blog->likes()->count() }}</td>
                                <td>{{ $blog->dislikes()->count() }}</td>
                                <td>{{ $blog->comments()->count() }}</td>
                                <td>{{ $blog->group->name }}</td>
                                <td>{{ verta($blog->created_at)->format('Y-m-d') }}</td>
                               
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <div class="toggle-box">
        <div class="toggle-header" onclick="toggleBox(this)">
            <span>نظرات شما</span>
          <i class="fas fa-chevron-down toggle-icon" id="toggleIcon"></i>
        </div>
        <div class="toggle-content" id="toggleContent">
            <hr>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>محتوا</th>
                            <th>تعداد لایک</th>
                            <th>تعداد دیسلایک</th>
                            <th>تعداد ریپلای</th>
                            <th>پست</th>
                            <th>گروه</th>
                            <th>تاریخ ایجاد</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comments as $key => $comment)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><a href="{{ route('groups.comment', [$comment->blog->id, '#msg-' . $comment->id]) }}">{{ $comment->message }}</a></td>
                                <td>{{ $comment->likes()->count() }}</td>
                                <td>{{ $comment->dislikes()->count() }}</td>
                                <td>{{ $comment->childs()->count() }}</td>
                                <td>{{ $comment->blog->title }}</td>
                                <td>{{ $comment->blog->group->name }}</td>
                                <td>{{ verta($comment->created_at)->format('Y-m-d') }}</td>
                               
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
          
        </div>
    </div>

    <div class="toggle-box">
        <div class="toggle-header" onclick="toggleBox(this)">
            <span>پاسخ ها به شما</span>
          <i class="fas fa-chevron-down toggle-icon" id="toggleIcon"></i>
        </div>
        <div class="toggle-content" id="toggleContent">
            <hr>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>محتوا</th>
                            <th>تعداد لایک</th>
                            <th>تعداد دیسلایک</th>
                            <th>تعداد ریپلای</th>
                            <th>نظر</th>
                            <th>پست</th>
                            <th>گروه</th>
                            <th>تاریخ ایجاد</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($comments as $key => $comment)
                            @foreach ($comment->childs as $key2 =>  $child)
                                <tr>
                                    <td>{{ $key * $key2 + 1 }}</td>
                                    <td><a href="{{ route('groups.comment', [$comment->blog->id, '#msg-' . $comment->id]) }}">{{ $child->message }}</a></td>
                                    <td>{{ $child->likes()->count() }}</td>
                                    <td>{{ $child->dislikes()->count() }}</td>
                                    <td>{{ $child->childs()->count() }}</td>
                                    <td>{{ $comment->message }}</td>
                                    <td>{{ $child->blog->title }}</td>
                                    <td>{{ $child->blog->group->name }}</td>
                                    <td>{{ verta($child->created_at)->format('Y-m-d') }}</td>
                                
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
          
        </div>
    </div>

    <div class="toggle-box">
        <div class="toggle-header" onclick="toggleBox(this)">
            <span>واکنش های شما</span>
          <i class="fas fa-chevron-down toggle-icon" id="toggleIcon"></i>
        </div>
        <div class="toggle-content" id="toggleContent">
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>نوع</th>
                            <th>پست/نظر</th>
                            <th>برای</th>
                            <th>گروه</th>
                            <th>تاریخ ثبت</th>
                        </tr>
                    </thead>
                    <tbody>
@foreach($reactions as $key => $reaction)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $reaction->type == 1 ? 'لایک' : 'دیسلایک' }}</td>
        <td>{{ $reaction->react_type == 1 ? 'کامنت' : 'پست' }}</td>

        <td>
            @if($reaction->react_type == 1 && $reaction->comment?->blog?->id)
                <a href="{{ route('groups.comment', [$reaction->comment->blog->id, '#msg-' . $reaction->comment->id]) }}">
                    {{ $reaction->comment?->message ?? '—' }}
                </a>
            @elseif($reaction->react_type == 2 && $reaction->blog?->group?->id)
                <a href="{{ route('groups.chat', [$reaction->blog->group->id, '#blog-' . $reaction->blog->id]) }}">
                    {{ $reaction->blog?->title ?? '—' }}
                </a>
            @else
                —
            @endif
        </td>

        <td>
            @if($reaction->react_type == 1)
                {{ $reaction->comment?->blog?->group?->name ?? '—' }}
            @else
                {{ $reaction->blog?->group?->name ?? '—' }}
            @endif
        </td>

        <td>{{ $reaction->created_at ? verta($reaction->created_at)->format('Y-m-d') : '—' }}</td>
    </tr>
@endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="toggle-box">
        <div class="toggle-header" onclick="toggleBox(this)">
            <span>نظرسنجی های شما</span>
          <i class="fas fa-chevron-down toggle-icon" id="toggleIcon"></i>
        </div>
        <div class="toggle-content" id="toggleContent">
            <hr>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>رای شما</th>
                            <th>نظر سنجی</th>
                            <th>گروه</th>
                            <th>تاریخ ثبت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($polls as $key => $poll)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><a href="{{ route('groups.chat', [$poll->poll->group->id, '#poll-' . $poll->poll->id]) }}">{{ $poll->option->text }}</a></td>
                                <td>{{ $poll->poll->question }}</td>
                                <td>{{ $poll->poll->group->name }}</td>
                                <td>{{ verta($poll->created_at)->format('Y-m-d') }}</td>
                               
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="toggle-box">
        <div class="toggle-header" onclick="toggleBox(this)">
            <span>رای های شما</span>
          <i class="fas fa-chevron-down toggle-icon" id="toggleIcon"></i>
        </div>
        <div class="toggle-content" id="toggleContent">
            <hr>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>رای به</th>
                            <th>به عنوان</th>
                            <th>گروه</th>
                            <th>تاریخ ثبت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($elections as $key => $election)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $election->user->fullName() }}</td>
                                <td>{{ $election->position == 0 ? 'بازرس' : 'مدیر' }}</td>
                                <td>{{ $election->election->group->name }}</td>
                                <td>{{ verta($election->created_at)->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
