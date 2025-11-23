@extends('layouts.app')

@section('head-tag')
<style> .tabs { display: flex; justify-content: space-between; margin-bottom: 0; border-bottom: 2px solid #ccc; direction: rtl } a{ text-decoration: none; color: #333 } .tab { padding: 0.7rem 1.5rem; cursor: pointer; font-weight: bold; border-radius: 1rem 1rem 0 0; background: #e0e0e0; margin-left: 5px; width: 25%; text-align: center; transition: background-color 0.3s ease; } .tab:hover { background-color: #d0d0d0; } .tab.active { background: #E78E5A; background: linear-gradient(225deg, rgba(231, 142, 90, 1) 0%, rgba(249, 186, 95, 1) 100%);    color: white; } .default-avatar{ background: #3f7d58d1 !important; background: linear-gradient(90deg, rgba(63, 125, 88, .8) 0%, rgba(122, 207, 156, .8) 100%) !important; } .tab-content { direction: rtl; display: none; background: white; padding: 1rem; border-radius: 0 0 1rem 1rem; box-shadow: 0 0 10px rgba(0,0,0,0.05); transition: all 0.3s ease-in-out; } .groups-table { width: 100%; } .tab-content.active { display: block; } .collapsible-group{ display: flex; justify-content: space-between; align-items: start } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ccc; padding: 0.5rem; text-align: center; } th { background-color: #f1f1f1; } .collapsible-group { display: none; margin-bottom: 1rem; transition: all 0.3s ease-in-out; } .toggle-header { cursor: pointer; background-color: #f1f1f1; padding: 0.7rem 1rem; border-radius: 0.5rem; margin-top: 1rem; font-weight: bold; } .toggle-header:hover { background-color: #e0e0e0; } .arrow-icon { transition: transform 0.3s ease; margin-right: 8px; } .arrow-icon.rotate { transform: rotate(180deg); } .toggle-header { display: flex; justify-content: space-between; align-items: center; cursor: pointer; background: #f1f1f1; padding: 0.7rem 1rem; border-radius: 0.5rem; margin-top: 1rem; font-weight: bold; } @media screen and (max-width: 990px) { .tabs{ flex-wrap: wrap } .tab{ width: 100%; border-radius: .5rem; margin-bottom: 1rem } .collapsible-group{ flex-direction: column } .location-filters{ width: 100%; margin-left: 0 !important; } .location-filter-wrapper{ width: 100%; margin-bottom: 1rem } .location-filters .roww{ display: flex; justify-content: space-between } .location-tab { width: 48%; } } .location-filter-wrapper { display: flex; justify-content: space-between; margin-bottom: 1rem; flex-wrap: wrap; } .sub-tabs, .location-filters { display: flex; flex-direction: row; gap: 0.5rem; } .location-filters { flex-direction: column; margin-left: 1rem; } .sub-tab, .location-tab { padding: 0.5rem 1rem; background-color: #eee; border-radius: 0.5rem; cursor: pointer; transition: 0.3s; font-size: 0.85rem; white-space: nowrap; margin-bottom: .3rem } .sub-tab.active, .location-tab.active { background: #d99968a9; background: linear-gradient(90deg, rgba(217, 146, 92, 0.723) 0%, rgba(210, 106, 26, 0.721) 100%);   color: white; } .toggle-header{ color: #d99968; border: 1px solid #d99968; background-color: #fff } </style>
<style>
  .tabs {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0;
    border-bottom: 2px solid #ccc;
    direction: rtl;
  }
  a {
    text-decoration: none;
    color: #333;
  }
  .tab {
    padding: 0.7rem 1.5rem;
    cursor: pointer;
    font-weight: bold;
    border-radius: 1rem 1rem 0 0;
    background: #e0e0e0;
    margin-left: 5px;
    width: 25%;
    text-align: center;
    transition: background-color 0.3s ease;
  }
  .tab:hover {
    background-color: #d0d0d0;
  }
  .tab.active {
    background: linear-gradient(225deg, rgba(231, 142, 90, 1) 0%, rgba(249, 186, 95, 1) 100%);
    color: white;
  }
  .tab-content {
    display: none;
    background: white;
    padding: 1rem;
    border-radius: 0 0 1rem 1rem;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
  }
  .tab-content.active {
    display: block;
  }

  /* Mobile accordion */
  .accordion-tab {
    display: none;
    flex-direction: column;
    margin-bottom: 1rem;
  }
  .accordion-header {
    display: none;
    background: #f1f1f1;
    padding: 0.7rem 1rem;
    border-radius: 0.5rem;
    margin-top: 1rem;
    font-weight: bold;
    cursor: pointer;
    justify-content: space-between;
    align-items: center;
  }
  .accordion-header:hover {
    background-color: #e0e0e0;
  }
  .accordion-content {
    display: none;
    padding: 1rem;
    background: white;
    border-radius: 0.5rem;
    margin-top: 0.5rem;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
  }
  .accordion-content.active {
    display: block;
  }
  .arrow-icon {
    transition: transform 0.3s ease;
  }
  .arrow-icon.rotate { 
    transform: rotate(180deg);
  }
  
  .accordion-header{
          background: linear-gradient(225deg, rgba(231, 142, 90, 1) 0%, rgba(249, 186, 95, 1) 100%);
    color: white;
  }

  /* Responsive switch to accordion */
  @media screen and (max-width: 768px) {
    .tabs {
      display: none;
    }
    .accordion-tab {
      display: flex;
    }
    .accordion-header {
      display: flex;
    }
    .tab-content{
        display: none !important;
    }
    .card-body{
        direction: rtl !important;
    }
  }
</style>
@endsection

@section('content')

<div class="card">
  <div class="card-header text-center bg-primary text-white fs-5">
    گروه های شما
  </div>

  <div class="card-body">

    {{-- Desktop Tabs --}}
    <div class="tabs">
      <div class="tab active" data-tab="public">گروه‌های مجمع عمومی</div>
      <div class="tab" data-tab="specialty">گروه‌های تخصصی</div>
      <div class="tab" data-tab="exclusive">گروه‌های اختصاصی</div>
      <div class="tab" data-tab="managed">گفتگوهای خصوصی</div>
    </div>

    {{-- Mobile Accordion --}}
    <div class="accordion-tab">
      <div class="accordion-header" data-tab="public">گروه‌های مجمع عمومی <i class="fas fa-chevron-down arrow-icon"></i></div>
      <div class="accordion-content" id="accordion-public">
        @include('partials.group-table', ['groups' => $generalGroups, 'type' => 'public'])
      </div>

      <div class="accordion-header" data-tab="specialty">گروه‌های تخصصی <i class="fas fa-chevron-down arrow-icon"></i></div>
      <div class="accordion-content" id="accordion-specialty">
        <h5 class="toggle-header" data-target="#job-groups">گروه‌های شغلی و صنفی شما <i class="fas fa-chevron-down arrow-icon"></i></h5>
        <div id="job-groups" class="collapsible-group" style="display: none;">
          @include('partials.group-table', ['groups' => $specialityGroups, 'type' => 'specialty'])
        </div>

        <h5 class="toggle-header" data-target="#experience-groups">گروه‌های تخصصی و تجربی شما <i class="fas fa-chevron-down arrow-icon"></i></h5>
        <div id="experience-groups" class="collapsible-group" style="display: none;">
          @include('partials.group-table', ['groups' => $experienceGroups, 'type' => 'specialty'])
        </div>
      </div>

      <div class="accordion-header" data-tab="exclusive">گروه‌های اختصاصی <i class="fas fa-chevron-down arrow-icon"></i></div>
      <div class="accordion-content" id="accordion-exclusive">
        <h5 class="toggle-header" data-target="#age-groups">گروه‌های سنی شما <i class="fas fa-chevron-down arrow-icon"></i></h5>
        <div id="age-groups" class="collapsible-group" style="display: none;">
          @include('partials.group-table', ['groups' => $ageGroups, 'type' => 'exclusive'])
        </div>

        <h5 class="toggle-header" data-target="#gender-groups">گروه‌های جنسیتی شما <i class="fas fa-chevron-down arrow-icon"></i></h5>
        <div id="gender-groups" class="collapsible-group" style="display: none;">
          @include('partials.group-table', ['groups' => $genderGroups, 'type' => 'exclusive'])
        </div>
      </div>

      <div class="accordion-header" data-tab="managed">گفتگوهای خصوصی <i class="fas fa-chevron-down arrow-icon"></i></div>
      <div class="accordion-content" id="accordion-managed">
        @include('partials.group-table', ['groups' => $managedGroups, 'type' => 'managed'])
      </div>
    </div>

    {{-- Desktop Tab Contents --}}
    <div class="tab-content active" id="public">
      @include('partials.group-table', ['groups' => $generalGroups, 'type' => 'public'])
    </div>

    <div class="tab-content" id="specialty">
      <h5 class="toggle-header" data-target="#job-groups-desktop">گروه‌های شغلی و صنفی شما <i class="fas fa-chevron-down arrow-icon"></i></h5>
      <div id="job-groups-desktop" class="collapsible-group" style="display: none;">
        @include('partials.group-table', ['groups' => $specialityGroups, 'type' => 'specialty'])
      </div>

      <h5 class="toggle-header" data-target="#experience-groups-desktop">گروه‌های تخصصی و تجربی شما <i class="fas fa-chevron-down arrow-icon"></i></h5>
      <div id="experience-groups-desktop" class="collapsible-group" style="display: none;">
        @include('partials.group-table', ['groups' => $experienceGroups, 'type' => 'specialty'])
      </div>
    </div>

    <div class="tab-content" id="exclusive">
      <h5 class="toggle-header" data-target="#age-groups-desktop">گروه‌های سنی شما <i class="fas fa-chevron-down arrow-icon"></i></h5>
      <div id="age-groups-desktop" class="collapsible-group" style="display: none;">
        @include('partials.group-table', ['groups' => $ageGroups, 'type' => 'exclusive'])
      </div>

      <h5 class="toggle-header" data-target="#gender-groups-desktop">گروه‌های جنسیتی شما <i class="fas fa-chevron-down arrow-icon"></i></h5>
      <div id="gender-groups-desktop" class="collapsible-group" style="display: none;">
        @include('partials.group-table', ['groups' => $genderGroups, 'type' => 'exclusive'])
      </div>
    </div>

    <div class="tab-content" id="managed">
      @include('partials.group-table', ['groups' => $managedGroups, 'type' => 'managed'])
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Tabs (Desktop)
    const tabs = document.querySelectorAll('.tab');
    const contents = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        contents.forEach(c => c.classList.remove('active'));
        tab.classList.add('active');
        document.getElementById(tab.dataset.tab).classList.add('active');
      });
    });

    // Accordion (Mobile)
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    accordionHeaders.forEach(header => {
      header.addEventListener('click', () => {
        const content = document.getElementById('accordion-' + header.dataset.tab);
        const icon = header.querySelector('.arrow-icon');
        const isActive = content.classList.contains('active');
        content.classList.toggle('active');
        icon.classList.toggle('rotate');
      });
    });

    // Inner collapsibles (both desktop and mobile)
    document.querySelectorAll('.toggle-header').forEach(header => {
      header.addEventListener('click', () => {
        const target = document.querySelector(header.dataset.target);
        const icon = header.querySelector('.arrow-icon');
        const isHidden = getComputedStyle(target).display === 'none';
        target.style.display = isHidden ? 'flex' : 'none';
        icon.classList.toggle('rotate', isHidden);
      });
    });
  });
</script>
@endsection
