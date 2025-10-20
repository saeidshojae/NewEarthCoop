<?php

use App\Http\Controllers\Admin\ActivateController;
use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Profile\InvitationController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\GroupController as AdminGroupController;
use App\Http\Controllers\Admin\GroupManagementController;
use App\Http\Controllers\Admin\GroupSettingController;
use App\Http\Controllers\Admin\InvitationCodeController;
use App\Http\Controllers\Admin\RuleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Group\ElectionController;
use App\Http\Controllers\Group\GroupController;
use App\Http\Controllers\Group\ChatController;
use App\Http\Controllers\Group\MessageController;
use App\Http\Controllers\Group\CommentController;
use App\Http\Controllers\Group\BlogController;
use App\Http\Controllers\Group\PollController;
use App\Http\Controllers\Group\ReactionController;
use App\Modules\Stock\Controllers\StockController;
use App\Modules\Stock\Controllers\AuctionController;
use App\Modules\Stock\Controllers\BidController;

use App\Http\Controllers\Auth\Register\StartController;
use App\Http\Controllers\Auth\Register\Step1Controller;
use App\Http\Controllers\Auth\Register\Step2Controller;
use App\Http\Controllers\Auth\Register\Step3Controller;
use App\Http\Controllers\Profile\HistoryController;
use App\Http\Controllers\Profile\TermController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\EnsureEmailIsVerified;
use App\Http\Controllers\ChatRequestController;
use App\Http\Middleware\UpdateLastSeenOnLogout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\ReportedMessageController;
use App\Http\Controllers\Admin\PageController;
use App\Models\Blog;
use App\Models\Region;
use App\Models\Rural;
use App\Models\Street;
use App\Models\Village;
use App\Models\Abadi;
use App\Models\Alley;
use App\Models\City;
use App\Models\County;
use App\Models\District;
use App\Models\ExperienceField;
use App\Models\Group;
use App\Models\Message;
use App\Models\Neighborhood;
use App\Models\OccupationalField;
use App\Models\Province;
use App\Models\Continent;

/*
|--------------------------------------------------------------------------
| Static pages
|--------------------------------------------------------------------------
*/
date_default_timezone_set("Asia/tehran");

Route::view('/terms', 'terms')->name('terms');
Route::post('/terms', [TermController::class, 'store'])->name('terms.store');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware(UpdateLastSeenOnLogout::class);

// درخواست ارسال OTP
Route::get('/forgot-password', [LoginController::class, 'forgotView'])->name('password.reset.view');
Route::post('/forgot-password', [LoginController::class, 'forgot'])->name('password.reset.send');

// ریست پسورد با OTP
Route::get('/reset-password', [LoginController::class, 'resetView'])->name('password.reset.viewForm');;
Route::post('/reset-password', [LoginController::class, 'reset'])->name('password.reset');


/*
|--------------------------------------------------------------------------
| Registration - Multi-Step
|--------------------------------------------------------------------------
*/

// نمایش خوش‌آمدگویی و شروع ثبت‌نام
Route::get('/', [StartController::class, 'showWelcome'])->name('welcome');
Route::post('/register/accept', [StartController::class, 'processAgreement'])->name('register.accept');
Route::get('/register', [StartController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [StartController::class, 'processRegister'])->name('register.process');

// مراحل ثبت‌نام که نیاز به احراز هویت و تایید ایمیل دارند
Route::middleware(EnsureEmailIsVerified::class)->group(function () {
    // مرحله ۱: اطلاعات هویتی
    Route::get('/register/step1', [Step1Controller::class, 'show'])->name('register.step1');
    Route::post('/register/step1', [Step1Controller::class, 'process'])->name('register.step1.process');

    // مرحله ۲: انتخاب تخصص‌هاpa
    Route::get('/register/step2', [Step2Controller::class, 'show'])->name('register.step2');
    Route::post('/register/step2', [Step2Controller::class, 'process'])->name('register.step2.process');

    // مرحله ۳: انتخاب مکان
    Route::get('/register/step3', [Step3Controller::class, 'show'])->name('register.step3');
    Route::post('/register/step3', [Step3Controller::class, 'process'])->name('register.step3.process');
});

// AJAX helpers (برای فیلدهای مکان و تخصص)
Route::post('/get-children', [Step2Controller::class, 'getChildren'])->name('get.children');
Route::post('/get-field-info', [Step2Controller::class, 'getFieldInfo']);
Route::post('/add-field', [Step2Controller::class, 'addField']);

//get invation code
Route::get('/invation-code', [InvitationController::class, 'get'])->name('invite');
Route::post('/invation-code', [InvitationController::class, 'store'])->name('invite.store');

//google login
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(Authenticate::class)->group(function () {

    // Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread');
    Route::get('/notifications/latest', [NotificationController::class, 'latest'])->name('notifications.latest');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.delete');
    Route::delete('/notifications/clear-read', [NotificationController::class, 'deleteAllRead'])->name('notifications.clearRead');

    // Profile
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'editModifiable'])->name('profile.edit');
    Route::delete('/profile/document/{index}', [ProfileController::class, 'deleteDocument'])->name('profile.document.delete');

    Route::get('/profile/edit-oc', function(){
        
        // دریافت لیست‌های اولیه جهت انتخاب‌های چندگانه
        $occupationalFields = \App\Models\OccupationalField::whereNull(columns: 'parent_id')->get();
        $experienceFields   = \App\Models\ExperienceField::whereNull('parent_id')->get();
                $user = auth()->user();

        return view('profile.edit-oc', compact('occupationalFields', 'experienceFields', 'user'));
        
    })->name('profile.edit-oc');
    Route::get('/profile/invation-code-generate', [ProfileController::class, 'generateInvationCode'])->name('profile.generate-code');

    Route::put('/profile/update/general', [ProfileController::class, 'updateGeneral'])->name('profile.update.general');
    Route::put('/profile/update/password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
    Route::put('/profile/update/social-network', [ProfileController::class, 'updateSocialNetworks'])->name('profile.update.social-network');
    Route::put('/profile/update/experience', [ProfileController::class, 'updateExperience'])->name('profile.update.experience');
    Route::put('/profile/update/address', [ProfileController::class, 'updateAddress'])->name('profile.update.address');


    Route::post('/profile/send-invitation', [ProfileController::class, 'sendInvitation'])->name('profile.send.invitation');
    Route::get('/profile/show-info', [ProfileController::class, 'showInfo'])->name(name: 'profile.show.info');

    Route::get('/profile/accept-candidate/{type}', [ProfileController::class, 'acceptCandidate'])->name('profile.accept.candidate');

    Route::get('/profile/join-group/{type}', [ProfileController::class, 'profileJoinGroup'])->name('profile.join.group');
    
    
    /*
    |--------------------------------------------------------------------------
    | Group & Chat Routes
    |--------------------------------------------------------------------------
    */
    

    // لیست گروه‌ها و نمایش جزئیات
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::get('/groups/{group}/logout', [GroupController::class, 'logout'])->name('groups.logout');
    Route::get('/groups/{group}/relogout', [GroupController::class, 'relogout'])->name('groups.relogout');
    Route::get('/groups/{group}/open', [GroupController::class, 'open'])->name('groups.open');
    Route::put('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');

    // چت گروهی
    Route::get('/groups/chat/{group}', [ChatController::class, 'chat'])->name('groups.chat');
    Route::get('/api/groups/{group}/messages', [ChatController::class, 'chatAPI']);
    
    Route::get('/search-users', [GroupController::class, 'searchUsers']);
    Route::post('/add-users-to-group', [GroupController::class, 'addUsersToGroup']);
    

    //delegation
    Route::get('/groups/delegation/{poll}/{expert}', [ChatController::class, 'delegation'])->name('groups.delegation');

    //show profile
    Route::get('/profile-member/{user}', [ProfileController::class, 'showProfileMember'])->name('profile.member.show');

    // ارسال پیام در گروه
    Route::post('/messages/send', [MessageController::class, 'store'])->name('groups.messages.store');
    Route::post('/messages/{message}/edit', [MessageController::class, 'edit'])->name('groups.messages.edit');
    Route::get('/messages/{message}/delete', [MessageController::class, 'delete'])->name('groups.messages.delete');
    Route::post('/groups/messages/{message}/pin', [MessageController::class, 'pin'])->name('messages.pin');
    Route::post('/groups/messages/{message}/unpin', [MessageController::class, 'unpin'])->name('messages.unpin');
    Route::post('/groups/messages/{message}/report', [MessageController::class, 'report'])->name('messages.report');
    
        Route::get('/groups/{group}/search', [MessageController::class, 'search'])
             ->name('groups.search');

    Route::get('/change-user-role/{user_id}/{group_id}', function($user_id, $group_id){
        $user = \App\Models\User::find($user_id);

        $groupUser = \App\Models\GroupUser::where('user_id', $user_id)->where('group_id', $group_id)->first();

        if($groupUser->role == 0){
            $groupUser->role = 5;
            $groupUser->save();
            return back()->with('success', 'نقش کاربر ' . $user->fullName() . ' از ناظر به فعال ۲ تغییر پیدا کرد');
        }elseif($groupUser->role == 5){
            $groupUser->role = 0;
            $groupUser->save();
            return back()->with('success', 'نقش کاربر ' . $user->fullName() . ' از فعال ۲ به ناظر تغییر پیدا کرد');
        }else{
            return back()->with('success', 'نقش غیرمجاز است');
        }
    })->name('change-user-role');

    // کامنت‌ها
    Route::post('/comment/send', [CommentController::class, 'store'])->name('groups.comment.store');
        Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    Route::get('/groups/comment/{blog}', [CommentController::class, 'comment'])->name('groups.comment');
    Route::get('/api/comments/{blog}/messages', [CommentController::class, 'commentAPI']);
    // ارسال پست
    Route::post('/blog/send/{group}', [BlogController::class, 'store'])->name('groups.blog.store');
    Route::delete('/blog/{blog}', [BlogController::class, 'destroy'])->name('groups.blog.destroy');
    Route::put('/blog/{blog}', [BlogController::class, 'update'])->name('groups.blog.update');

    // نظرسنجی و رأی‌گیری
    Route::post('/poll/send/{group}', [PollController::class, 'store'])->name('groups.poll.store');
    Route::put('/poll/{group}/poll/{poll}', [PollController::class, 'update'])->name('groups.poll.update');
    Route::get('/poll/{group}/delete/{poll}', [PollController::class, 'delete'])->name('groups.poll.delete');

    Route::post('/polls/{poll}/vote', [PollController::class, 'vote'])->name('poll.vote');

    // ری‌اکت‌ها
    Route::post('/blogs/{blog}/react', [ReactionController::class, 'blogReact'])->name('blogs.react');
    Route::post('/comments/{comment}/react', [ReactionController::class, 'commentReact'])->name('comments.react');
    Route::post('/election/{group}/vote', [ElectionController::class, 'submitVote'])->name('vote');
    Route::post('/finish-election/{election}', [ElectionController::class, 'finishElection'])->name('finish.election');

        /*
    |--------------------------------------------------------------------------
    | History Routes
    |--------------------------------------------------------------------------
    */
    Route::get('history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('history/election', [HistoryController::class, 'election'])->name('history.election');
    Route::get('history/poll', [HistoryController::class, 'poll'])->name('history.poll');

    
    Route::get('my-invation-code', function(){
        return view('my-invation-code');
    
    })->name('my-invation-code');
    
    // سهام و حراج - کاربر
    Route::get('auctions', [\App\Modules\Stock\Controllers\AuctionController::class, 'index'])->name('auction.index');
    Route::get('auctions/{auction}', [\App\Modules\Stock\Controllers\AuctionController::class, 'show'])->name('auction.show');
    Route::post('auctions/{auction}/bid', [\App\Modules\Stock\Controllers\AuctionController::class, 'placeBid'])->name('auction.bid');
    
    Route::get('wallet', [\App\Modules\Stock\Controllers\WalletController::class, 'index'])->name('wallet.index');
    Route::get('holdings', [\App\Modules\Stock\Controllers\HoldingController::class, 'index'])->name('holding.index');
    Route::get('holdings/{stock}', [\App\Modules\Stock\Controllers\HoldingController::class, 'show'])->name('holding.show');
    
    Route::get('spring-accounts', function(){
        $spring = \App\Models\Spring::where('user_id', auth()->user()->id)->where('status', '0')->first();

        // if($spring == null){
        //     return view('spring-accounts');
        // }else{
        //     return view('terms-spring');
        // }
        
                    return view('terms-spring');


    })->name('spring-accounts');
    
    Route::post('/najm/confirm', function(){
        $spring = \App\Models\Spring::where('user_id', auth()->user()->id)->where('status', '0')->first();
        $spring->status =1;
        $spring->save();
        
        return redirect()->route('spring-accounts');
    
    })->name('najm.confirm');

});

/*
|--------------------------------------------------------------------------
| Admin Panel
|--------------------------------------------------------------------------
*/

Route::middleware(AdminMiddleware::class)->prefix('admin')->name('admin.')->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // مدیریت گروه‌ها
    Route::get('groups/{group}/manage', [GroupManagementController::class, 'manage'])->name('groups.manage');
    
    Route::put('groups/manage/chage-roles/{group}', function(Request $request, \App\Models\Group $group){
        $validated = $request->validate([
            'users' => 'required|array|min:1',
            'users.*' => 'exists:users,id',
            'main_role' => 'required|in:0,1,2,3', // بر اساس نقش‌های موجود
        ]);

        foreach ($validated['users'] as $userId) {
            $groupUser = \App\Models\GroupUser::where('group_id', $group->id)->where('user_id', $userId)->first();
            $groupUser->role = $validated['main_role'];
            $groupUser->save();
            
        }
    
        return back()->with('success', 'نقش کاربران با موفقیت تغییر یافت.');    
    })->name('groups.change-roles');
    
    Route::put('groups/{group}/users/{user}/role', [GroupManagementController::class, 'updateRole'])->name('groups.updateRole');
    Route::put('groups/{group}', [AdminGroupController::class, 'update'])->name('groups.update');
    Route::get('groups', [AdminGroupController::class, 'index'])->name('group.index');
    Route::get('group-delete/{group}', [AdminGroupController::class, 'delete'])->name('group.delete');

    Route::put('group-post-update/{blog}', [AdminGroupController::class, 'postUpdate'])->name('group-post.update');
    Route::get('group-post-delete/{blog}', [AdminGroupController::class, 'postDelete'])->name('group.post.delete');

    // کدهای دعوت
    Route::get('invitation-codes', [InvitationCodeController::class, 'index'])->name('invitation_codes.index');
    Route::post('invitation-codes', [InvitationCodeController::class, 'store'])->name('invitation_codes.store');

    Route::get('/activate', [ActivateController::class, 'index'])->name('activate.index'); 
    Route::put('/activate', [ActivateController::class, 'update'])->name('activate.update');

    
    Route::get('/category', function(){
        return view('admin.category.index');
    })->name('category.index');
    
    Route::post('/category', function(Request $request){
        $inputs = $request->validate([
            'name' => 'required|max:255',
            'groups' => 'required|array'
        ]);
        
        $category = \App\Models\Category::create($inputs);
        foreach($inputs['groups'] as $groupSettingId){
            \App\Models\CategoryGroupSetting::create(['category_id' => $category->id, 'group_setting_id' => $groupSettingId]);
        }
        
        return back();
        
    })->name('category.store');
     
    Route::get('/category-delete/{category}', function(\App\Models\Category $category){
        $category->delete();
                return back();

    })->name('category.delete');
    
    Route::put('/category/{category}', function(\App\Models\Category $category, Request $request){
        $inputs = $request->validate([
            'name' => 'required|max:255',
            'groups' => 'required|array'
        ]);
        
        $category->update($inputs);
        foreach(\App\Models\CategoryGroupSetting::where('category_id', $category->id)->get() as $gs){
            $gs->delete();
        }
        
        foreach($inputs['groups'] as $groupSettingId){
            \App\Models\CategoryGroupSetting::create(['category_id' => $category->id, 'group_setting_id' => $groupSettingId]);
        }
        
        return back();
        
    })->name('category.update');
    //اساسنامه
    Route::get('rules', [RuleController::class, 'index'])->name('rule.index');
    Route::post('rules', [RuleController::class, 'store'])->name('rule.store');
    Route::get('rules/{term}', [RuleController::class, 'destroy'])->name('rule.destroy');
    Route::put('rules/{term}', [RuleController::class, 'update'])->name('rule.update');
    
    Route::get('/najm-page', function(){
        return view('admin.najm.index'); 
    })->name('najm-page');
    
    Route::put('/najm-page', function(Request $request){
        $inputs = $request->validate([
            'najm_summary' => 'nullable', 
        ]);
        
        $setting = \App\Models\Setting::find(1);
        if(isset($inputs['najm_summary'])){
            $setting->najm_summary = $inputs['najm_summary'];
        }

        $setting->save();
        
        return back();
    })->name('update.najm');
    
    Route::get('/welcome-page', function(){
        return view('admin.welcome.index'); 
    })->name('welcome-page');
    
    Route::put('/welcome-page', function(Request $request){
        $inputs = $request->validate([
            'welcome_titre' => 'nullable|max:255', 
            'welcome_content' => 'nullable', 
            'home_titre' => 'nullable|max:255', 
            'home_content' => 'nullable', 
        ]);
        
        $setting = \App\Models\Setting::find(1);
        if(isset($inputs['welcome_titre'])){
            $setting->welcome_titre = $inputs['welcome_titre'];
        }
        if(isset($inputs['welcome_content'])){
            $setting->welcome_content = $inputs['welcome_content'];
        }
        if(isset($inputs['home_titre'])){
            $setting->home_titre = $inputs['home_titre'];
        }
                if(isset($inputs['home_content'])){
            $setting->home_content = $inputs['home_content'];
        }
        $setting->save();
        
        return back();
    })->name('update.welcome');
    
    
    Route::post('/slider', function(Request $request){
        $inputs = $request->validate([
            'src' => 'required|file|mimes:png,jpg,jpeg,webp|max:2048',
            'position' => 'required|numeric|in:0,1'
        ]);
        
        if ($request->hasFile('src') && $request->file('src')->isValid()) {
            $file = $request->file('src');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $inputs['file_type'] = $file->getMimeType();
            $file->move(public_path('images/sliders'), $name);
            $inputs['src'] = $name;
        }
        
        \App\Models\Slider::create($inputs);
        return back();
    })->name('slider.store');
    
        
    Route::get('/slider-delete/{slider}', function(\App\Models\Slider $slider){
        $slider->delete();
        return back();
    })->name('slider.delete');
    
    //اساسنامه
    Route::resource('users', UserController::class);
    Route::get('users/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    Route::get('active-address', [AddressController::class, 'index'])->name('active.address');
    Route::get('active-address/edit/{id}', [AddressController::class, 'edit'])->name('active.address.edit');
    Route::put('active-address/{id}', [AddressController::class, 'update'])->name('active.address.update');
    Route::get('active-address/delete/{id}', [AddressController::class, 'delete'])->name('active.address.delete');
    Route::post('active-address/bulk-approve', [AddressController::class, 'bulkApprove'])->name('active.address.bulk.approve');
    Route::post('active-address/bulk-delete', [AddressController::class, 'bulkDelete'])->name('active.address.bulk.delete');


    Route::get('active-experience', [ExperienceController::class, 'index'])->name('active.experience');
    Route::put('active-experience/{id}', [ExperienceController::class, 'update'])->name('active.experience.update');
    Route::get('active-experience/edit/{id}', [ExperienceController::class, 'edit'])->name('active.experience.edit');
    Route::get('active-experience/delete/{id}', [ExperienceController::class, 'delete'])->name('active.experience.delete');
    Route::post('active-experience/bulk-approve', [ExperienceController::class, 'bulkApprove'])->name('active.experience.bulk.approve');
    Route::post('active-experience/bulk-delete', [ExperienceController::class, 'bulkDelete'])->name('active.experience.bulk.delete');

    Route::get('group-setting', [GroupSettingController::class, 'index'])->name('group.setting.index');
    Route::get('group-setting/change-status/{setting}', [GroupSettingController::class, 'edit'])->name('group.setting.edit');
    Route::put('group-setting/{setting}', [GroupSettingController::class, 'update'])->name('group.setting.update');

    Route::resource('announcement', AnnouncementController::class);
    Route::get('announcement/delete/{id}', [AnnouncementController::class, 'delete'])->name('announcement.delete');

    // گزارش پیام‌ها
    Route::post('/messages/{message}/report', [MessageController::class, 'report'])->name('messages.report');

    Route::resource('pages', PageController::class);
    Route::post('pages/upload', [PageController::class, 'upload'])->name('pages.upload');
    
    // Public page route
});
Route::get('pages/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('pages.show');

// پنل ادمین - گزارش‌ها
Route::middleware(AdminMiddleware::class)->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reports', [ReportedMessageController::class, 'index'])->name('reports.index');
    Route::post('/reports/{report}', [ReportedMessageController::class, 'update'])->name('reports.update');
    Route::post('/reports/{report}', [ReportedMessageController::class, 'destroy'])->name('reports.destroy');
});

// مدیریت سهام و حراج - ادمین
Route::middleware(AdminMiddleware::class)->prefix('admin')->name('admin.')->group(function () {
    Route::get('stock', [\App\Modules\Stock\Controllers\StockController::class, 'adminIndex'])->name('stock.index');
    Route::get('stock/create', [\App\Modules\Stock\Controllers\StockController::class, 'adminCreate'])->name('stock.create');
    Route::post('stock', [\App\Modules\Stock\Controllers\StockController::class, 'adminStore'])->name('stock.store');

    Route::get('auctions', [\App\Modules\Stock\Controllers\AuctionController::class, 'adminIndex'])->name('auction.index');
    Route::get('auctions/create', [\App\Modules\Stock\Controllers\AuctionController::class, 'adminCreate'])->name('auction.create');
    Route::post('auctions', [\App\Modules\Stock\Controllers\AuctionController::class, 'adminStore'])->name('auction.store');
    Route::post('auctions/{auction}/start', [\App\Modules\Stock\Controllers\AuctionController::class, 'startAuction'])->name('auction.start');
    Route::post('auctions/{auction}/close', [\App\Modules\Stock\Controllers\AuctionController::class, 'closeAuction'])->name('auction.close');
    Route::post('wallet/credit', [\App\Modules\Stock\Controllers\WalletController::class, 'adminCredit'])->name('wallet.credit');
});


Route::get('/fields/children/{id}', function ($id) {
    return \App\Models\OccupationalField::where('parent_id', $id)->get(['id', 'name']);
});


Route::get('/fields/children-ex/{id}', function ($id) {
    return \App\Models\ExperienceField::where('parent_id', $id)->get(['id', 'name']);
});

// Email Verification Routes
Route::post('/email/verify/send', [EmailVerificationController::class, 'sendVerificationCode'])->name('email.verification.send');
Route::get('/email/verify', [EmailVerificationController::class, 'showVerificationForm'])->name('email.verify.form');
Route::post('/email/verify', [EmailVerificationController::class, 'verify'])->name('email.verify');
Route::post('/email/verify/resend', [EmailVerificationController::class, 'resend'])->name('email.verification.resend');


Route::post('/chat-requests/{user}', [ChatRequestController::class, 'send'])->name('chat-requests.send');
Route::post('/chat-requests/{chatRequest}/accept', [ChatRequestController::class, 'accept'])->name('chat-requests.accept');
Route::post('/chat-requests/{chatRequest}/reject', [ChatRequestController::class, 'reject'])->name('chat-requests.reject');
Route::get('/chat/{group}', [ChatController::class, 'show'])->name('chat.show');



Route::get('/api/groups/search', function (Request $request) {
    if (!auth()->check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $searchText = $request->query('q');
    $searchType = $request->query('type', 'name');
    
    if (empty($searchText)) {
        return response()->json(['groups' => []]);
    }
    
    $query = \App\Models\Group::query()
        ->whereHas('users', function ($query) {
            $query->where('user_id', auth()->id())
                  ; 
        })
        ->with(['users' => function ($query) {
            $query->where('user_id', auth()->id());
        }]);
    
    if ($searchType === 'content') {
        $query->where(function ($q) use ($searchText) {
            $q->whereHas('messages', function ($q) use ($searchText) {
                $q->where('message', 'like', "%{$searchText}%");
            });
        })->orWhere(function ($q) use ($searchText) {
            $q->whereHas('blogs', function ($q) use ($searchText) {
                $q->where('title', 'like', "%{$searchText}%");
            });
        })->orWhere(function ($q) use ($searchText) {
            $q->whereHas('polls', function ($q) use ($searchText) {
                $q->where('question', 'like', "%{$searchText}%");
            });
        });
    } else {
        $query->where('name', 'like', "%{$searchText}%");
    }
    
    $groups = $query->get()->map(function ($group) use ($searchText) {
        $pivot = $group->users->first()->pivot;
        
        // Get all matching messages for this group
        $matchingMessages = $group->messages()
            ->where('message', 'like', "%{$searchText}%")
            ->with('user')
            ->get();
        
        return [
            'id' => $group->id,
            'name' => $group->name,
            'avatar' => $group->avatar ? asset('images/groups/' . $group->avatar) : null,
            'members_count' => $group->users->count(),
            'location_level' => $group->location_level,
            'is_approved' => 1,
            'status' => 1,
            'role' => match($pivot->role) {
                0 => 'ناظر',
                1 => 'فعال',
                2 => 'بازرس',
                3 => 'مدیر',
            },
            'matching_messages' => $matchingMessages->map(function($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'created_at' => $message->created_at,
                    'user' => [
                        'id' => $message->user->id,
                        'name' => $message->user->name
                    ]
                ];
            })
        ];
    });
    
    return response()->json(['groups' => $groups]);
});

Route::prefix('api')->group(function () {
    // Chat features routes
    Route::post('/groups/{group}/clear-history', [ChatController::class, 'clearHistory']);
    Route::post('/groups/{group}/delete', [ChatController::class, 'deleteChat']);
    Route::post('/groups/{group}/report', [ChatController::class, 'reportUser']);
});


Route::get('/api/groups/{group}/content', function (App\Models\Group $group) {
    $content = [];
    
    // Get latest blog posts
    $blogs = $group->blogs()->latest()->take(5)->get();
    foreach ($blogs as $blog) {
        $content[] = $blog->title;
        $content[] = $blog->content;
    }
    
    // Get latest comments
    $comments = $group->blogs()->with('comments')->get()->pluck('comments')->flatten()->take(5);
    foreach ($comments as $comment) {
        $content[] = $comment->message;
    }
    
    // Get latest polls
    $polls = $group->polls()->latest()->take(5)->get();
    foreach ($polls as $poll) {
        $content[] = $poll->title;
        $content[] = $poll->description;
    }
    
    return response()->json([
        'content' => implode(' ', $content)
    ]);
});


Route::get('/users/search', function(Request $request){
    $query = $request->get('q');

    $users = \App\Models\User::where('first_name', 'like', "%{$query}%")
        ->orWhere('last_name', 'like', "%{$query}%")
        ->orWhere('email', 'like', "%{$query}%")
        ->orWhere('phone', 'like', "%{$query}%")
        ->get(['id', 'first_name','last_name', 'email']);

    return response()->json($users);
});

Route::get('/categories/{category}/blogs', function(\App\Models\Category $category, Request $request){
    $query = Blog::query()
            ->where('category_id', $category->id)
            ->latest();

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->integer('group_id'));
        }

        $blogs = $query->select(['id','title','created_at'])->get();

        // این Route رو با چیزی که تو پروژه‌ات برای نمایش صفحه‌ی بلاگ داری هماهنگ کن
        // طبق regex موجود، ظاهراً مسیر نمایش بلاگ: /groups/comment/{id}
        $items = $blogs->map(function ($b) {
            return [
                'id' => $b->id,
                'title' => $b->title,
                'date' => verta($b->created_at)->format('Y/m/d H:i'),
                'url' => route('groups.comment', $b->id), // اگر اسمت فرق دارد، همین‌جا عوض کن
            ];
        });

        return response()->json([
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
            'count' => $blogs->count(),
            'items' => $items,
        ]);
})
    ->name('categories.blogs');
    
    
    
    
    
    Route::post('profile/add-{type}', function(Request $request, $type) {
    $allowed = ['region','neighborhood','street','alley'];
    if (!in_array($type, $allowed, true)) {
        return response()->json(['message' => 'نوع نامعتبر است'], 422);
    }

    // trim name
    $request->merge(['name' => trim((string)$request->input('name'))]);

$request->validate([
  'name' => 'required|string|max:255',
  'parent_id' => ['required'],
], [], [
  'name' => 'نام',
  'parent_id' => 'شناسه والد',
]);


$rawParent = (string) $request->input('parent_id');

$kind = null;
$pid  = null;
$allowedKinds = ['city','rural','region','neighborhood','street','village'];

$raw = trim(mb_strtolower($rawParent));

// حالت فقط عدد
if ($raw !== '' && ctype_digit($raw)) {
    $pid = (int) $raw;
} else {
    // شکستن بر اساس _
    $parts = array_values(array_filter(explode('_', $raw), fn($p) => $p !== ''));

    // مثال‌ها:
    // city_city_52 → ['city','city','52']
    // region_77    → ['region','77']
    // neighborhood___15 → ['neighborhood','15']
    if (count($parts) >= 2) {
        $first = $parts[0];                          // نوع را از اولین بخش می‌گیریم
        $last  = $parts[count($parts) - 1];          // شناسه را از آخرین بخش می‌گیریم

        if (in_array($first, $allowedKinds, true) && ctype_digit($last)) {
            $kind = $first;
            $pid  = (int) $last;
        }
    }
}

if ($pid === null) {
    return response()->json(['message' => 'قالب parent_id نامعتبر است'], 422);
}

    $name = $request->input('name');

    // قوانین سازگاری نوع والد با نوع درج‌شونده
    $allowedParentsByType = [
        'region'       => ['city','rural'],        // شهر یا دهستان
        'neighborhood' => ['region','village'],    // منطقه یا روستا
        'street'       => ['neighborhood','village'],
        'alley'        => ['street','village'],
    ];

    if ($kind !== null && !in_array($kind, $allowedParentsByType[$type], true) && !ctype_digit($rawParent)) {
        // اگر parent پیشوند داشت ولی با نوع فعلی سازگار نبود
        return response()->json(['message' => 'نوع والد با موجودیت سازگار نیست'], 422);
    }
    try {
        $location = DB::transaction(function () use ($type, $kind, $pid, $name) {
            switch ($type) {
                case 'region':
                    if ($kind === 'rural') {
                        // ✅ اگر والد دهستان بود، Village بساز (طبق منطق قبلی)
                        // (در صورت نیاز، parent ستون را با اسکیمای خودت تنظیم کن)
                        return Village::create([
                            'name'     => $name,
                            'rural_id' => $pid,   // ⬅ در اسکیمای تو ممکن است 'parent_id' یا 'rural_id' باشد
                            'status'   => 0,
                        ]);
                    } else {
                        // والد شهر است (city_* یا عدد خالص city_id)
                        // ⬅ اگر Region ستون city_id دارد، از آن استفاده کن
                        return Region::create([
                            'name'     => $name,
                            'city_id'  => $pid,   // ⬅ اگر در اسکیمای تو 'parent_id' است، این خط را به 'parent_id' تغییر بده
                            'status'   => 0,
                        ]);
                    }

                case 'neighborhood':

                    return Neighborhood::create([
                        'name'      => $name,
                        'parent_id' => $pid,   // ⬅ اگر ستونت 'region_id' است، همین‌جا تغییر بده
                        'status'    => 0,
                    ]);

                case 'street':

                    return Street::create([
                        'name'      => $name,
                        'parent_id' => $pid,   // ⬅ در صورت تفاوت اسکیمای جدول streets اینجا را تغییر بده
                        'status'    => 0,
                    ]);

                case 'alley':

                    return Alley::create([
                        'name'      => $name,
                        'parent_id' => $pid,   // ⬅ در صورت تفاوت اسکیمای جدول alleys اینجا را تغییر بده
                        'status'    => 0,
                    ]);
            }
        });

        return response()->json(
            ['id' => $location->id, 'name' => $location->name],
            201
        );
    } catch (\Throwable $e) {
        $msg = $e->getMessage() ?: 'خطا در ثبت مکان.';
        return response()->json(['message' => $msg], 422);
    }
});

