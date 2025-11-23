@extends('layouts.app')
@section('head-tag')
<style>
.accordion-button {
  position: relative;
  padding-right: 1.5rem; /* اگه متن بخوره به فلش، اینو افزایش بده */
}



.accordion-button::after {
  transform: scale(0.7) rotate(0deg); /* کوچکتر + در صورت نیاز چرخش */
  position: absolute;
  left: 1rem;
  right: auto;
}
.accordion-button:not(.collapsed)::after {
  transform: scale(0.7) rotate(-180deg);
}

  .accordion-item {
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      margin-bottom: 1rem;
      overflow: hidden;
  }
</style>

@endsection
@section('content')

<div class="container " style="direction: rtl; text-align: right;">
  <div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header text-center bg-primary text-white fs-5">
           اساسنامه و شرایط استفاده
        </div>

        <form class="card-body" action="{{ route('terms.store') }}" id="termsForm" method="POST" enctype="multipart/form-data">
          @csrf

          <p class="mb-4">لطفا اساسنامه سامانه شراکتی ارث کوپ و شرایط استفاده را مطالعه و در صورت موافقت تایید کنید.</p>

    <div class="accordion mb-4" id="termsAccordion">
  @foreach (\App\Models\Term::whereNull('parent_id')->get() as $term)
    <div class="accordion-item">
      <h2 class="accordion-header" id="heading-{{ $term->id }}">
        <button class="accordion-button collapsed" type="button"
                data-bs-toggle="collapse" data-bs-target="#collapse-{{ $term->id }}"
                aria-expanded="false" aria-controls="collapse-{{ $term->id }}">
          {{ $term->title }}
        </button>
      </h2>
      <div id="collapse-{{ $term->id }}" class="accordion-collapse collapse"
           aria-labelledby="heading-{{ $term->id }}" data-bs-parent="#termsAccordion">
        <div class="accordion-body">
          📌 {!! $term->message !!}

          @if ($term->childs->isNotEmpty())
            <div class="accordion mt-3" id="subAccordion-{{ $term->id }}">
              @foreach ($term->childs as $child)
                <div class="accordion-item">
                  <h2 class="accordion-header" id="sub-heading-{{ $child->id }}">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#sub-collapse-{{ $child->id }}"
                            aria-expanded="false" aria-controls="sub-collapse-{{ $child->id }}">
                      {{ $child->title }}
                    </button>
                  </h2>
                  <div id="sub-collapse-{{ $child->id }}" class="accordion-collapse collapse"
                       aria-labelledby="sub-heading-{{ $child->id }}" data-bs-parent="#subAccordion-{{ $term->id }}">
                    <div class="accordion-body">
                      📌 {!! $child->message !!}
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @endif

        </div>
      </div>
    </div>
  @endforeach
</div>


          @php
            $setting = \App\Models\Setting::find(1);
          @endphp

          @if($setting->finger_status == 1)
            <div class="mb-4">
              <label class="form-label fw-bold">آپلود تصویر اثرانگشت:</label>
              <input type="file" name="finger" class="form-control @error('finger') is-invalid @enderror">
              @error('finger')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>
          @endif

          <div class="form-check mb-4">
            <input type="checkbox" id="termsCheck" class="form-check-input" required style="border: 1px solid #333;">
            <label for="termsCheck" class="form-check-label">
              با کلیهٔ مفاد اساسنامه موافقم.
            </label>
          </div>

          <div class="text-center">
            <button type="submit" onclick="acceptTerms()" class="btn btn-success px-4 py-2">موافقت و بازگشت</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  function acceptTerms() {
    localStorage.setItem('termsAccepted', 'true');
  }
</script>
@endsection
