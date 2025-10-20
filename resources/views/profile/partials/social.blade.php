
<div class="toggle-box">
    <div class="toggle-header" onclick="toggleBox(this)">
      <span>شبکه های اجتماعی</span>
      <i class="fas fa-chevron-down toggle-icon" id="toggleIcon"></i>
    </div>
    <div class="toggle-content" id="toggleContent">
        <hr>
      <form action="{{ route('profile.update.social-network') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
          <div class="form-group col-12">
            <label>لینک شبکه اجتماعی</label>
            <div id="dynamic-inputs">
                @php
                    $storedLinks = $user->social_networks ?? [];
                    if (is_string($storedLinks)) {
                        $storedLinks = json_decode($storedLinks, true);
                    }
                    $socialLinks = old('options', $storedLinks);
                @endphp
    
                @forelse($socialLinks as $index => $link)
                    <div class="input-group mb-2">
                        <input type="url" name="options[]" value="{{ old("options.$index", $link) }}"
                               class="form-control" placeholder="لینک را وارد کنید">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger" onclick="removeInput(this)">✖</button>
                        </div>
                    </div>
                @empty
                    <div class="input-group mb-2">
                        <input type="url" name="options[]" class="form-control" placeholder="لینک را وارد کنید">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-danger" onclick="removeInput(this)">✖</button>
                        </div>
                    </div>
                @endforelse
            </div>
    
            <button type="button" onclick="addInput()" class="btn btn-sm btn-success">➕ افزودن گزینه جدید</button>
    
            @error('options.*')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div><br>

        <input type="submit" value="ذخیره تغییرات" class="btn btn-primary" style="background-color: #518dbdcc !important;">
      </form>
      
    </div>


    <script>
      function addInput() {
          const container = document.getElementById('dynamic-inputs');
          const inputGroup = document.createElement('div');
          inputGroup.className = 'input-group mb-2';
  
          inputGroup.innerHTML = `
              <input type="url" name="options[]" class="form-control" placeholder="لینک را وارد کنید">
              <div class="input-group-append">
                  <button type="button" class="btn btn-danger" onclick="removeInput(this)">✖</button>
              </div>
          `;
  
          container.appendChild(inputGroup);
      }
  
      function removeInput(button) {
          button.closest('.input-group').remove();
      }
  </script>
      
</div>
