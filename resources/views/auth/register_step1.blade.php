<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>مرحله ۱ - اطلاعات هویتی</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Persian Datepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    
    <style>
        :root {
            --color-earth-green: #10b981;
            --color-ocean-blue: #3b82f6;
            --color-digital-gold: #f59e0b;
            --color-pure-white: #ffffff;
            --color-gentle-black: #1e293b;
            --color-dark-green: #047857;
            --color-dark-blue: #1d4ed8;
        }
        
        * { font-family: 'Vazirmatn', 'Poppins', sans-serif; }
        
        body { background-color: #e2e8f0; }
        
        @keyframes bounce-custom {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        
        .animate-bounce-custom { animation: bounce-custom 3s infinite ease-in-out; }
        
        .form-card-gradient {
            background: linear-gradient(145deg, var(--color-pure-white) 0%, #f0f4f7 100%);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
            border-radius: 18px;
            position: relative;
            border: 1px solid rgba(220, 220, 220, 0.3);
        }
        
        .form-card-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--color-earth-green), var(--color-ocean-blue), var(--color-digital-gold));
        }
    </style>
</head>
<body class="font-vazirmatn leading-relaxed flex items-center justify-center min-h-screen p-4">

    <!-- Main Form Content -->
    <div class="form-card-gradient w-full max-w-2xl mx-auto p-8 md:p-10">
        <!-- Logo -->
        <div class="flex items-center justify-center space-x-3 rtl:space-x-reverse mb-8">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="animate-bounce-custom">
                <path d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2Z" fill="#10b981" opacity="0.8"/>
                <path d="M12 2C10.5 4 8 6 8 9C8 12 12 14 12 14C12 14 16 12 16 9C16 6 13.5 4 12 2ZM12 14C12 14 10 16 10 18C10 20 12 22 12 22" fill="#047857"/>
            </svg>
            <span class="text-4xl font-extrabold text-gentle-black" style="color: var(--color-gentle-black);">EarthCoop</span>
        </div>
        
        <!-- Step Indicator -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center gap-4 mb-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white" style="background-color: var(--color-earth-green);">۱</div>
                    <span class="mr-2 font-bold" style="color: var(--color-earth-green);">هویتی</span>
                </div>
                <div class="w-8 h-1 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-gray-400 bg-gray-200">۲</div>
                    <span class="mr-2 text-gray-400">صنفی</span>
                </div>
                <div class="w-8 h-1 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-gray-400 bg-gray-200">۳</div>
                    <span class="mr-2 text-gray-400">مکانی</span>
                </div>
            </div>
        </div>
        
        <!-- Form -->
        <div class="text-right">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gentle-black mb-6" style="color: var(--color-gentle-black);">
                مرحله ۱: اطلاعات هویتی
            </h2>
            
            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <form action="{{ route('register.step1.process') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Name -->
                <div>
                    <label for="first_name" class="block text-lg font-bold text-gray-800 mb-3">نام:</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-ocean-blue focus:border-ocean-blue @error('first_name') border-red-500 @enderror"
                           placeholder="نام خود را وارد کنید">
                </div>
                
                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-lg font-bold text-gray-800 mb-3">نام خانوادگی:</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-ocean-blue focus:border-ocean-blue @error('last_name') border-red-500 @enderror"
                           placeholder="نام خانوادگی خود را وارد کنید">
                </div>
                
                <!-- Birth Date -->
                <div>
                    <label class="block text-lg font-bold text-gray-800 mb-3">تاریخ تولد:</label>
                    <div class="grid grid-cols-3 gap-4">
                        <select name="birth_date[]" required class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-blue">
                            <option value="">روز</option>
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}" {{ old('birth_date.0') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        
                        <select name="birth_date[]" required class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-blue">
                            <option value="">ماه</option>
                            @php $months = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند']; @endphp
                            @foreach($months as $index => $month)
                                <option value="{{ $index + 1 }}" {{ old('birth_date.1') == ($index + 1) ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                        
                        <select name="birth_date[]" required class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-blue">
                            <option value="">سال</option>
                            @php
                                use Morilog\Jalali\Jalalian;
                                $currentYear = Jalalian::now()->getYear() - 15;
                                $startYear = $currentYear - 135;
                            @endphp
                            @for ($i = $currentYear; $i >= $startYear; $i--)
                                <option value="{{ $i }}" {{ old('birth_date.2') == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                
                <!-- Gender -->
                <div>
                    <label class="block text-lg font-bold text-gray-800 mb-3">جنسیت:</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="gender" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} class="form-radio h-5 w-5" style="color: var(--color-earth-green);">
                            <span class="mr-2 text-gray-700">مرد</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }} class="form-radio h-5 w-5" style="color: var(--color-earth-green);">
                            <span class="mr-2 text-gray-700">زن</span>
                        </label>
                    </div>
                </div>
                
                <!-- Nationality -->
                <div>
                    <label for="nationality" class="block text-lg font-bold text-gray-800 mb-3">ملیت:</label>
                    <select name="nationality" id="nationality" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-blue">
                        <option value="ایرانی" {{ old('nationality') == 'ایرانی' ? 'selected' : '' }}>ایرانی</option>
                        <option value="مهاجر" {{ old('nationality') == 'مهاجر' ? 'selected' : '' }}>مهاجر</option>
                    </select>
                </div>
                
                <!-- National ID -->
                <div>
                    <label for="national_id" class="block text-lg font-bold text-gray-800 mb-3">کدملی:</label>
                    <input type="text" id="national_id" name="national_id" value="{{ old('national_id') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-blue font-poppins text-right @error('national_id') border-red-500 @enderror"
                           placeholder="کدملی 10 رقمی">
                </div>
                
                @include('partials.countries-list')
                
                <!-- Password (if not set) -->
                @if (auth()->user()->password == null)
                <div class="relative">
                    <label for="password" class="block text-lg font-bold text-gray-800 mb-3">رمز عبور (*)</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-blue pl-12 @error('password') border-red-500 @enderror">
                    <i class="fas fa-eye absolute left-4 top-14 cursor-pointer text-gray-500 toggle-password" data-target="password"></i>
                </div>
                
                <div class="relative">
                    <label for="password_confirmation" class="block text-lg font-bold text-gray-800 mb-3">تایید رمز عبور (*)</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ocean-blue pl-12">
                    <i class="fas fa-eye absolute left-4 top-14 cursor-pointer text-gray-500 toggle-password" data-target="password_confirmation"></i>
                </div>
                @endif
                
                <!-- Email (disabled) -->
                <div>
                    <label for="email" class="block text-lg font-bold text-gray-800 mb-3">ایمیل:</label>
                    <input type="email" id="email" value="{{ old('email', auth()->user()->email) }}" disabled
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                </div>
                
                <!-- Submit Button -->
                <button type="submit"
                        class="w-full px-6 py-4 rounded-full text-white font-bold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition duration-300"
                        style="background-color: var(--color-earth-green);">
                    <i class="fas fa-arrow-left ml-2"></i>
                    ادامه
                </button>
            </form>
        </div>
    </div>
    
    <script>
        // Toggle Password Visibility
        document.querySelectorAll('.toggle-password').forEach(toggle => {
            toggle.addEventListener('click', () => {
                const targetId = toggle.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (input.type === 'password') {
                    input.type = 'text';
                    toggle.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    toggle.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });
        
        // Enter key navigation
        const focusable = document.querySelectorAll('form input, form select, form textarea');
        focusable.forEach((field, index) => {
            field.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const next = focusable[index + 1];
                    if (next) next.focus();
                }
            });
        });
    </script>
    
    <!-- Congratulations Modal -->
    @if(session('congratulations'))
    <div id="congratulations-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md mx-auto p-8 text-center" style="animation: fadeIn 0.5s ease-out;">
            <!-- Success Icon -->
            <div class="mb-6">
                <div class="w-24 h-24 mx-auto rounded-full flex items-center justify-center" style="background-color: rgba(16, 185, 129, 0.1);">
                    <i class="fas fa-check-circle text-6xl" style="color: var(--color-earth-green);"></i>
                </div>
            </div>
            
            <!-- Message -->
            <h2 class="text-3xl font-extrabold text-gentle-black mb-4" style="color: var(--color-gentle-black);">
                تبریک می‌گوییم! 🎉
            </h2>
            
            <p class="text-lg text-gray-700 mb-6 leading-relaxed">
                سهامدار عزیز، شما با موفقیت به <span class="font-bold" style="color: var(--color-earth-green);">EarthCoop</span> پیوستید. 
                دنیای همکاری‌های بزرگ در انتظار شماست.
            </p>
            
            <p class="text-base text-gray-600 mb-6 leading-relaxed">
                لطفا دقایقی وقت بگذارید و در <span class="font-bold">سه مرحله</span> اطلاعات هویتی، صنفی و مکانی خود را وارد کنید.
            </p>
            
            <!-- Close Button -->
            <button onclick="document.getElementById('congratulations-modal').style.display='none'" 
                    class="w-full px-8 py-4 rounded-full text-white font-bold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition duration-300"
                    style="background-color: var(--color-ocean-blue);">
                <i class="fas fa-arrow-left ml-2"></i>
                باشه، بزن بریم!
            </button>
        </div>
    </div>
    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
    @endif

</body>
</html>
