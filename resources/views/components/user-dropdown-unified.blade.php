{{-- User Dropdown Component - بر اساس طراحی Home --}}
<div class="relative" x-data="{ userDropdownOpen: false }">
    <button @click="userDropdownOpen = !userDropdownOpen" 
            class="px-4 py-2 rounded-full shadow-md transition duration-300 font-medium transform hover:scale-105 flex items-center ripple" 
            style="background-color: var(--color-earth-green); color: var(--color-pure-white);">
        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-white text-lg ml-2">
            <i class="fas fa-user"></i>
        </div>
        <span class="hidden sm:inline">{{ Auth::user()->fullName() }}</span>
        <i class="fas fa-chevron-down mr-2 text-sm transition-transform duration-300" 
           :class="{ 'rotate-180': userDropdownOpen }"></i>
    </button>
    
    <div x-show="userDropdownOpen" 
         @click.away="userDropdownOpen = false" 
         x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0 scale-95" 
         x-transition:enter-end="opacity-100 scale-100" 
         x-transition:leave="transition ease-in duration-150" 
         x-transition:leave-start="opacity-100 scale-100" 
         x-transition:leave-end="opacity-0 scale-95" 
         class="absolute right-0 md:left-0 md:right-auto mt-2 w-64 bg-white rounded-lg shadow-xl py-2 z-[9999] text-right origin-top-right md:origin-top-left max-h-[calc(100vh-120px)] md:max-h-[80vh] overflow-y-auto"
         x-cloak
         style="display: none; background-color: var(--color-pure-white); scrollbar-width: thin; scrollbar-color: #10b981 #f3f4f6;"
         @click="userDropdownOpen = false">
        
        <a href="{{ route('profile.show') }}" 
           class="block px-4 py-2 hover:bg-gray-50 transition duration-200 flex items-center justify-start" 
           style="color: var(--color-earth-green);">
            {{ __('navigation.profile') }} <i class="fas fa-user-circle mr-3"></i>
        </a>
        
        <hr class="my-1 border-gray-200">
        
        <h6 class="px-4 py-2 text-sm font-bold" style="color: var(--color-ocean-blue);">{{ __('navigation.stock_office_section') }}</h6>
        
        <a href="{{ route('auction.index') }}" 
           class="block px-4 py-2 hover:bg-gray-50 transition duration-200 flex items-center justify-start" 
           style="color: var(--color-gentle-black);">
            {{ __('navigation.auctions') }} <i class="fas fa-gavel mr-3"></i>
        </a>
        
        <a href="{{ route('wallet.index') }}" 
           class="block px-4 py-2 hover:bg-gray-50 transition duration-200 flex items-center justify-start" 
           style="color: var(--color-gentle-black);">
            {{ __('navigation.wallet') }} <i class="fas fa-wallet mr-3"></i>
        </a>
        
        <a href="{{ route('holding.index') }}" 
           class="block px-4 py-2 hover:bg-gray-50 transition duration-200 flex items-center justify-start" 
           style="color: var(--color-gentle-black);">
            {{ __('navigation.holdings') }} <i class="fas fa-chart-line mr-3"></i>
        </a>
        
        @if (auth()->check() && auth()->user()->is_admin == 1)
            <hr class="my-1 border-gray-200">
            <h6 class="px-4 py-2 text-sm font-bold" style="color: var(--color-ocean-blue);">{{ __('navigation.admin_section') }}</h6>
            <a href="{{ route('admin.dashboard') }}" 
               class="block px-4 py-2 hover:bg-gray-50 transition duration-200 flex items-center justify-start" 
               style="color: var(--color-gentle-black);">
                {{ __('navigation.admin_dashboard') }} <i class="fas fa-cog mr-3"></i>
            </a>
            <a href="{{ route('admin.blog.dashboard') }}" 
               class="block px-4 py-2 hover:bg-gray-50 transition duration-200 flex items-center justify-start" 
               style="color: var(--color-gentle-black);">
                {{ __('navigation.admin_blog') }} <i class="fas fa-blog mr-3"></i>
            </a>
        @endif
        
        <hr class="my-1 border-gray-200">
        
        <a href="{{ route('terms') }}" 
           class="block px-4 py-2 hover:bg-gray-50 transition duration-200 flex items-center justify-start" 
           style="color: var(--color-gentle-black);">
            {{ __('navigation.charter') }} <i class="fas fa-file-alt mr-3"></i>
        </a>
        
        <a href="{{ route('spring-accounts.agreement') }}" 
           class="block px-4 py-2 hover:bg-gray-50 transition duration-200 flex items-center justify-start" 
           style="color: var(--color-gentle-black);">
            {{ __('navigation.financial_agreement') }} <i class="fas fa-file-contract mr-3"></i>
        </a>
        
        <hr class="my-1 border-gray-200">
        
        <a href="{{ route('logout') }}" 
           class="block px-4 py-2 hover:bg-red-50 transition duration-200 flex items-center justify-start" 
           style="color: var(--color-red-tomato);"
           onclick="event.preventDefault(); document.getElementById('logout-form-unified').submit();">
            {{ __('navigation.logout') }} <i class="fas fa-sign-out-alt mr-3"></i>
        </a>
        
        <form id="logout-form-unified" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</div>


