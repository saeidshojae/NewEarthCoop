<div class="toggle-box">
    <div class="toggle-header" onclick="toggleBox(this)">
        <span>ویرایش موقعیت مکانی</span>
        <i class="fas fa-chevron-down toggle-icon"></i>
    </div>
    <div class="toggle-content">
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>توجه:</strong> تغییر موقعیت مکانی شما به منزله خروج از گروه‌های فعلی شما می‌باشد.
        </div>

        <form action="{{ route('profile.update.address') }}" method="POST" class="location-form">
            @csrf
            @method('PUT')

            <div class="location-container">
                <div class="location-path mb-3" id="location_path_display">
                    <span class="text-muted">مسیر انتخاب نشده</span>
                </div>

                <div id="location-selects">
                    <div class="mb-3">
                        <label class="form-label">انتخاب قاره</label>
                        <select class="form-select location-select" name="continent_id" data-level="1" id="continent-select">
                            <option value="">انتخاب کنید</option>
                            @foreach($continents as $continent)
                                <option value="{{ $continent->id }}" {{ $continent->id == $user->address->continent_id ? 'selected' : '' }}>
                                    {{ $continent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-submit">
                        <i class="fas fa-save"></i>
                        ذخیره تغییرات
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@include('partials.add_location_modal', ['type' => 'region', 'label' => 'منطقه'])
@include('partials.add_location_modal', ['type' => 'neighborhood', 'label' => 'محله'])
@include('partials.add_location_modal', ['type' => 'street', 'label' => 'خیابان'])
@include('partials.add_location_modal', ['type' => 'alley', 'label' => 'کوچه'])

<!-- Add jQuery and Select2 dependencies -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .location-container {
        direction: rtl;
        max-width: 800px;
        margin: 0 auto;
    }

    .location-path {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
    }

    .location-path span {
        display: inline-block;
        margin: 0 0.5rem;
        color: #0d6efd;
        cursor: pointer;
    }

    .location-path span:hover {
        text-decoration: underline;
    }

    .location-select {
        margin-bottom: 1rem;
    }

    .select2-container {
        width: 100% !important;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .btn-submit {
        padding: 0.5rem 2rem;
        font-size: 1.1rem;
    }

    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeeba;
        color: #856404;
    }
</style>
<script>
    window.userAddress = @json($user->address);
</script>

<script>
function parseTypedId(val){
  // ورودی مثل: "city_123" یا "rural_45" یا "78"
  if (!val) return { type: null, id: null };
  const m = String(val).match(/^(city|rural)_(\d+)$/);
  if (m) return { type: m[1], id: m[2] };
  return { type: null, id: String(val) };
}

let isPrefilling = false;           // NEW
const levelReqToken = {};           // NEW

const levelLabels = ['قاره', 'کشور', 'استان', 'شهرستان', 'بخش', 'شهر / دهستان', 'منطقه', 'محله', 'خیابان', 'کوچه'];
const levelKeys = ['continent', 'country', 'province', 'county', 'section', 'city', 'region', 'neighborhood', 'street', 'alley'];
const nameKeys = ['continent_id', 'country_id', 'province_id', 'county_id', 'section_id', 'city_id', 'region_id', 'neighborhood_id', 'street_id', 'alley_id'];
const allowAddModal = ['region', 'neighborhood', 'street', 'alley'];

let pathParts = [];
let pathValues = [];

$(document).ready(function() {
    // تنظیمات Select2
    $('.location-select').select2({
        width: '100%',
        placeholder: 'انتخاب کنید',
        allowClear: true
    });

    
    $(document).on('change', '.location-select', function() {
        if (isPrefilling) return;
      const level = $(this).data('level');
      const value = $(this).val();
    
      if (value === '__add_new__') {
        const type = levelKeys[level - 1];
        // ← والد را از سلکتِ یک سطح قبل می‌خوانیم؛ اگر نبود، از pathValues
        const prevValFromDom = $(`select[data-level="${level - 1}"]`).val();
        const parentId = prevValFromDom || pathValues[level - 2];
    
        if (!parentId) {
          showWarningAlert('ابتدا سطح والد را انتخاب کنید.');
          $(this).val('').trigger('change.select2');
          return;
        }
        

        openAddModal(type, parentId, level);
        return;
      }
    
      if (value) {
            pathParts[level - 1] = $(this).find('option:selected').text();
            pathValues[level - 1] = value;
            
            // حذف سطوح بعدی
            for (let i = level; i < pathParts.length; i++) {
                pathParts[i] = null;
                pathValues[i] = null;
            }
            
            // بارگذاری سطح بعدی
            if (level < levelKeys.length) {
                loadNextLevel(value, level + 1);
            }
        } else {
            // حذف سطوح بعدی در صورت خالی شدن انتخاب
            for (let i = level - 1; i < pathParts.length; i++) {
                pathParts[i] = null;
                pathValues[i] = null;
            }
        }
        
        updatePathDisplay();
    });


    // مدیریت کلیک روی مسیر
    $(document).on('click', '#location_path_display span', function() {
        const level = $(this).data('level');
        const value = $(this).data('value');
        if (level === 0) {
        // ریست کامل
        pathParts = [];
        pathValues = [];

        // حذف همه‌ی selectها
        $('#location-selects').html('');

        // بازسازی select قاره
        const continentSelect = `
            <div class="mb-3">
                <label class="form-label">انتخاب قاره</label>
                <select class="form-select location-select" name="continent_id" data-level="1" id="continent-select">
                    <option value="">انتخاب کنید</option>
                    @foreach($continents as $continent)
                        <option value="{{ $continent->id }}">{{ $continent->name }}</option>
                    @endforeach
                </select>
            </div>
        `;
        $('#location-selects').append(continentSelect);

        // فعال کردن select2 برای select قاره
        $('#continent-select').select2({
            width: '100%',
            placeholder: 'انتخاب کنید',
            allowClear: true
        });

        updatePathDisplay();
        return;
    }

        // حذف سطوح بعدی
        for (let i = level; i < pathParts.length; i++) {
            pathParts[i] = null;
            pathValues[i] = null;
        }
    
        // حذف select‌های بعد از سطح کلیک‌شده
        for (let i = level + 1; i <= levelKeys.length; i++) {
            $(`select[data-level="${i}"]`).closest('.mb-3').remove();
        }
    
        // نمایش مجدد selectهای قبلی
        for (let i = 1; i <= level; i++) {
            $(`select[data-level="${i}"]`).closest('.mb-3').show();
        }
    
        // بارگذاری مجدد سطح بعدی
        
        loadNextLevel(value, level + 1);
        updatePathDisplay();
    });


    // بارگذاری آدرس فعلی کاربر
    if (window.userAddress) {
        loadUserAddressChain();
    }
});

function updatePathDisplay() {
    const display = [
        `<span data-level="0" data-value="world">🌍 زمین</span>`,
        ...pathParts
            .filter(part => part !== null)
            .map((part, index) => `<span data-level="${index + 1}" data-value="${pathValues[index]}">${part}</span>`)
    ].join(' / ');

    $('#location_path_display').html(display || '<span class="text-muted">مسیر انتخاب نشده</span>');
}


async function loadNextLevel(parentValue, level) {
  const key   = levelKeys[level - 1];
  const label = levelLabels[level - 1];
  const name  = nameKeys[level - 1];
  console.log(name)
  if (!key || level === 1 || !parentValue) return;

  // توکن برای لغو پاسخ‌های قدیمی
  const token = `${Date.now()}_${Math.random()}`;
  levelReqToken[level] = token;

  // parentValue ممکنه prefixed باشه؛ برای فراخوانی API، id خام را بده
  const { id: parentIdRaw } = parseTypedId(parentValue);

  // تابع کمکى برای رندر یک select
  const renderSelect = (items, theKey, theLabel, theName, theLevel) => {
    const hasAdd   = allowAddModal.includes(theKey);
    const showLabel = ['خیابان','کوچه'].includes(theLabel) ? `${theLabel} (اختیاری)` : theLabel;

    let optionsHtml = `<option value="">انتخاب ${showLabel}</option>`;
    items.forEach(item => {
      const value = item.type ? `${item.type}_${item.id}` : String(item.id);
      optionsHtml += `<option value="${value}">${item.name}</option>`;
    });
    if (hasAdd) optionsHtml += `<option value="__add_new__">+ افزودن ${theLabel}</option>`;

    // پاکسازی هم‌سطح و سطوح پایین‌تر
    const $old = $(`select[data-level="${theLevel}"]`);
    if ($old.length) { $old.select2('destroy'); $old.closest('.mb-3').remove(); }
    for (let i = theLevel + 1; i <= levelKeys.length; i++) {
      const $s = $(`select[data-level="${i}"]`);
      if ($s.length) { $s.select2('destroy'); $s.closest('.mb-3').remove(); }
    }

    const $wrap = $(`
      <div class="mb-3">
        <label class="form-label">${showLabel}</label>
        <select class="form-select location-select" name="${theName}" data-level="${theLevel}">
          ${optionsHtml}
        </select>
      </div>
    `);
    $('#location-selects').append($wrap);
    $wrap.find('select').select2({ width:'100%', placeholder:'انتخاب کنید', allowClear:true });
  };

  // فراخوانى API
  try {
    const data = await $.get(`/api/locations`, { level: key, parent_id: parentIdRaw });

    // اگر پاسخ قدیمی است، رهایش کن
    if (levelReqToken[level] !== token) return;

    // اگر این لِوِل داده ندارد، «پرش هوشمند» به لِوِل‌های بعدی
    if (!Array.isArray(data) || data.length === 0) {
      // جدول پرش‌ها: اگر region خالی بود برو neighborhood؛ اگر neighborhood خالی بود برو street؛ ...
      const jumpOrder = ['region','neighborhood','street','alley'];
      const idx = jumpOrder.indexOf(key);

      if (idx !== -1) {
        // تلاش کن لِوِل بعدی را با همان parentValue بسازی
        const nextIdx = idx + 1;
        if (nextIdx < jumpOrder.length) {
          // تا پیدا شدن اولین لِوِل دارای داده، می‌پریم جلو
          for (let j = nextIdx; j < jumpOrder.length; j++) {

            const tryKey   = jumpOrder[j];
            const tryLevel = levelKeys.indexOf(tryKey) + 1;
            if (!tryLevel) continue;

            const tryData = await $.get(`/api/locations`, { level: tryKey, parent_id: parentIdRaw })
              .catch(() => null);

            if (levelReqToken[level] !== token) return; // همچنان از توکن پیروی کن

            if (tryData && Array.isArray(tryData) && tryData.length) {
              renderSelect(tryData, tryKey, levelLabels[tryLevel - 1], nameKeys[tryLevel - 1], tryLevel);
              return;
            }
          }
        }
      }
        
      // اگر هیچ‌کدام داده ندارند، یک سلکت خالی ولی فعال با دکمه «افزودن» بساز (اگر قابل افزودن است)
      renderSelect([], key, label, name, level);
      return;
    }

    // داده داریم: رندر معمولی
    renderSelect(data, key, label, name, level);

  } catch (err) {
    console.error('Error loading locations:', err?.statusText || err);
    showErrorAlert('خطا در بارگذاری اطلاعات. لطفاً دوباره تلاش کنید.');
  }
}


function openAddModal(type, parentId, currentLevel) {
  const modalId = `#add${capitalize(type)}Modal`;
  if (parentId == null) { showWarningAlert('والد نامعتبر است.'); return; }

  const $modal = $(modalId);

  $modal.one('shown.bs.modal', () => {
    const $parentField = $modal.find('input[name="parent_id"].parent_id');
    if ($parentField.length) $parentField.val(String(parentId)).trigger('change');
    $modal.find('.parent-type').val(levelKeys[currentLevel - 2] || '');
    $modal.find('.current-level').val(currentLevel);
  });

  $modal.modal('show');
}




function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

async function loadUserAddressChain() {
    isPrefilling = true; 
    const address = window.userAddress;
    if (!address) return;

$('#continent-select').val(address.continent_id);  // بدون trigger    


    const levels = [
        'continent_id', 'country_id', 'province_id', 'county_id', 'section_id',
        'city_id', 'region_id', 'neighborhood_id', 'street_id', 'alley_id'
    ];

    let parentId = address.continent_id;
    pathParts[0] = $('#continent-select option:selected').text();
    pathValues[0] = parentId;

    for (let i = 1; i < levels.length; i++) {

        const level = i + 1;
        let value = address[levels[i]];
        if (!value) {
            break;
        };
        
const normalizeId = v => String(v ?? '')
  .trim()
  .split(/\s+/)
  .pop()
  .replace(/^[a-z_]+_/i, ''); // هر چیزی تا اولین "_" حذف میشه


        await new Promise(resolve => {
            const key = levelKeys[level - 1];
            console.log(key)
                if (key === 'region') {

                  if (address.city_id) {
                    if (address.city_id.startsWith('rural_')) {
                      parentId = 'rural_rural_' + normalizeId(address.city_id);
                    } else if(address.city_id.startsWith('city_') ){
                      parentId = 'city_city_' + normalizeId(address.city_id);
                    } else {
                      parentId = address.city_id;
                    }
                  }
                }


            if(key == 'neighborhood'){
              parentId = address.region_id || address.village_id;
            }

            if(key == 'street'){
              parentId = address.neighborhood_id || address.village_id;
            }

            if(key == 'alley'){
              parentId = address.street_id || address.village_id;
            }
            
            $.get(`/api/locations?level=${key}&parent_id=${parentId}`, function(data) {
                
                const name = nameKeys[level - 1];
                let matchedId = null;
                let optionsHtml = `<option value="">انتخاب ${levelLabels[level - 1]}</option>`;

                data.forEach(item => {
                  const val = item.type ? `${item.type}_${item.id}` : String(item.id);
                  let isSelected = false;
                
                
                const addrId = normalizeId(address?.[levels[i]]);
                  const valId  = normalizeId(val);
                  isSelected = !!(addrId && valId && addrId == valId);

                
                
                  optionsHtml += `<option value="${val}" ${isSelected ? 'selected' : ''}>${item.name}</option>`;
                  if (isSelected) {
                    pathParts[level - 1] = item.name;
                    pathValues[level - 1] = val;
                    matchedId = item.id; // عدد خام برای parentId مرحله بعد
                  }
                });


                // حذف select قبلی اگر وجود دارد
                $(`select[data-level="${level}"]`).remove();

                const $select = $(`
                    <div class="mb-3">
                        <label class="form-label">${levelLabels[level - 1]}</label>
                        <select class="form-select location-select" name="${name}" data-level="${level}">
                            ${optionsHtml}
                        </select>
                    </div>
                `);

                $('#location-selects').append($select);
                $select.find('select').select2(); // بدون trigger

                // تنظیم parentId برای مرحله بعد
                if (key === 'city') {
                    parentId = address.city_id;
                 } else if (key === 'region') {
                    if (address.city_id) {
                      if (address.city_id.startsWith('city_') || address.city_id.startsWith('rural_')) {
                        parentId = address.city_id;            // همون مقدار prefixed را بگیر
                      } else {
                        parentId = 'city_' + address.city_id;  // اگر عدد خالصه، city_ اضافه کن
                      }
                    } else {
                      parentId = address.region_id || address.village_id;
                    }
                }
                else if (key === 'neighborhood') {
                    // برای سطوح بعد از region، از region_id استفاده می‌کنیم
                    parentId = address.region_id || address.village_id;
                } else if(key == 'street'){
                  parentId = address.neighborhood_id || address.village_id;
                } else if(key == 'alley'){
                  parentId = address.street_id || address.village_id;
                } else {
                    parentId = matchedId || value;
                }

                updatePathDisplay();
                resolve();
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Error loading address chain:', textStatus, errorThrown);
                showErrorAlert('خطا در بارگذاری اطلاعات آدرس. لطفاً دوباره تلاش کنید.');
                resolve();
            });
        });
    }
    
        isPrefilling = false;
}
// جایگزین همین نسخه کن
window.loadLocation = function(level, parentId, targetSelectId, callback) {

  // اگر targetSelectId خالی بود، بر اساس نام level پیدا کن
  let $target;
  if (targetSelectId) {
    $target = $(`select[name="${targetSelectId}"]`);
  } else {
    const name = `${level}_id`; // e.g. neighborhood_id
    $target = $(`select[name="${name}"]`);
  }
  

  $.ajax({
    url: '/api/locations',
    type: 'GET',
    dataType: 'json',
    data: { level, parent_id: parentId },
    success: function(data) {
      $target.empty().append('<option value="">انتخاب کنید</option>');
      if (Array.isArray(data) && data.length) {
        data.forEach(item => {
          const val = item.type ? `${item.type}_${item.id}` : item.id; // با ساختار loadNextLevel یکی باشه
          $target.append(`<option value="${val}">${item.name}</option>`);
        });
        $target.prop('disabled', false);
      } else {
        $target.prop('disabled', true);
      }
      if (typeof callback === 'function') callback();
    },
    error: function(xhr) {
      console.error('loadLocation error:', xhr.responseText);
      if (typeof callback === 'function') callback();
    }
  });
};


const selectIdMap = {
  continent:   'continent_id',
  country:     'country_id',
  province:    'province_id',
  county:      'county_id',
  section:     'section_id',
  city:        'city_id',
  region:      'region_id',
  neighborhood:'neighborhood_id',
  street:      'street_id',
  alley:       'alley_id'
};

function $selectOf(level){ // level مثل: 'region' | 'neighborhood' | ...
  const key = `${level}_id`;
  // اول id، اگر نبود name
  return $(`#${key}, select[name="${key}"]`);
}
$(document).on('submit', '.add-location-form2', function(e){
  e.preventDefault();
  const $form = $(this);
  const type = $form.data('type');               // region | neighborhood | street | alley
  const name = ($form.find('input[name="name"]').val() || '').trim();
  const parentId = $form.find('input[name="parent_id"]').val();
  const csrf = $('meta[name="csrf-token"]').attr('content');

  if (!name || !parentId) { showWarningAlert('نام/والد ناقص است.'); return; }

  $.ajax({
    url: `/profile/add-${type}`,
    type: 'POST',
    dataType: 'json',
    data: { _token: csrf, name, parent_id: parentId },
    success: function(resp){
      const newId   = resp.id   ?? resp.data?.id;
      const newName = resp.name ?? resp.data?.name ?? name;
      if (!newId) { showErrorAlert('پاسخ سرور معتبر نیست.'); return; }

      // 1) سلکتِ همان سطح را از روی name پیدا کن
      const $sel = $(`select[name="${type}_id"]`);
      if ($sel.length === 0) { showErrorAlert(`select ${type}_id پیدا نشد`); return; }

      // 2) اضافهٔ مستقیم به DOM + انتخاب (با پشتیبانی Select2)
      const optionValue = String(newId); // برای region/neighborhood/street/alley معمولاً بدون prefix
      if ($sel.hasClass('select2-hidden-accessible')) {
        const opt = new Option(newName, optionValue, true, true);
        $sel.append(opt).trigger('change'); // هم اضافه، هم انتخاب
      } else {
        $sel.append(`<option value="${optionValue}">${newName}</option>`)
            .val(optionValue)
            .trigger('change');
      }
      $sel.prop('disabled', false);

      // 3) بروزرسانی مسیر نمایش (اختیاری)
      const levelIndex = levelKeys.indexOf(type); // 0-based
      if (levelIndex >= 0) {
        pathParts[levelIndex]  = newName;
        pathValues[levelIndex] = optionValue;
        updatePathDisplay();
      }

      // 4) اگر می‌خواهی سطح بعدی هم فوراً قابل انتخاب شود، لود کن (اختیاری)
      const nextLevel = levelIndex + 2; // چون levelها 1-based هستند
      const nextKey = levelKeys[nextLevel - 1];
      if (nextKey) {
        loadNextLevel(optionValue, nextLevel);
      }

      // 5) بستن مودال و ریست
      showSuccessAlert(`${newName} افزوده شد.`);
      $form[0].reset();
      $form.closest('.modal').modal('hide');
    },
    error: function(xhr){
      console.warn('[ADD][FAIL]', xhr?.status, xhr?.responseText);
      let msg = 'خطا در ثبت.';
      try { const j = xhr.responseJSON; if (j?.message) msg = j.message; } catch(_){}
      showErrorAlert(msg);
    }
  });
});




</script>