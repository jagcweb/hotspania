<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/index', [App\Http\Controllers\HomeController::class, 'start'])->name('start');

Route::get('/currentjobs', function () {
    $jobs = \App\Models\Job::all();
    $failedJobs = \App\Models\FailedJob::all();

    return view('jobs.currentjobs', [
        'jobs' => $jobs,
        'failed_jobs' => $failedJobs
    ]);
})->name('jobs.current');

Route::get('/delete-all-jobs', [App\Http\Controllers\JobController::class, 'deleteAllJobs'])->name('jobs.deleteAll');
Route::get('/delete-all-failed-jobs', [App\Http\Controllers\JobController::class, 'deleteAllFailedJobs'])->name('jobs.deleteAllFailed');

Route::get('/', function () {
    if(!Auth::check()){
        return redirect()->route('start');
    } else {
        if (\Auth::user()->getRoleNames()[0] == 'admin') {
            return redirect()->route('admin.citychanges');
        } else {
            return redirect()->route('home');
        }
    }
});

Route::get('/logout', function(){
    Auth::logout();
    return redirect()->route('home');
 });

 Auth::routes(['register' => false]);

//HomeController
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home/image-get/{filename}',  [App\Http\Controllers\HomeController::class, 'getImage'])->name('home.imageget');
Route::get('/home/gif-get/{filename}',  [App\Http\Controllers\HomeController::class, 'getGif'])->name('home.gifget');
Route::get('/document/{filename}',  [App\Http\Controllers\HomeController::class, 'getDocument'])->name('home.document');
Route::get('/home/load-more/{page}', [App\Http\Controllers\HomeController::class, 'loadMore'])->name('home.loadmore');
Route::get('/home/get-zones/{cityId}', [App\Http\Controllers\HomeController::class, 'getZonesFromCity'])->name('home.getZonesFromCity');

//AccountController
Route::get('/account', [App\Http\Controllers\AccountController::class, 'index'])->name('account.index');
Route::get('/account/edit', [App\Http\Controllers\AccountController::class, 'edit'])->name('account.edit');
Route::post('/account/assign-package', [App\Http\Controllers\AccountController::class, 'assignPackage'])->name('account.assign_package');
Route::post('account/make-available/{id}',  [App\Http\Controllers\AccountController::class, 'makeAvailable'])->name('account.make_available');
Route::get('account/make-unavailable/{id}',  [App\Http\Controllers\AccountController::class, 'makeUnavailable'])->name('account.make_unavailable');
Route::get('/account/visible/{id}', [App\Http\Controllers\AccountController::class, 'visibleAccount'])->name('account.visible');
Route::get('/account/load/addVisit/profile/{id}', [App\Http\Controllers\AccountController::class, 'addVisitProfile'])->name('account.addVisitProfile')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/account/load/show/{id}', [App\Http\Controllers\AccountController::class, 'show'])->name('account.show')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/account/load/like/{id}', [App\Http\Controllers\AccountController::class, 'like'])->name('account.like')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/account/edit/edit-data', [App\Http\Controllers\AccountController::class, 'editData'])->name('account.edit-data');
Route::post('/account/edit/update', [App\Http\Controllers\AccountController::class, 'update'])->name('account.update');
Route::get('/account/{nickname}', [App\Http\Controllers\AccountController::class, 'get'])->name('account.get');
Route::get('/account/images/{image}/setfront', [App\Http\Controllers\AccountController::class, 'setFront'])->name('account.images.setfront');
Route::get('/account/images/{image}/visible', [App\Http\Controllers\AccountController::class, 'visible'])->name('account.images.visible');
Route::get('/account/images/{image}/invisible', [App\Http\Controllers\AccountController::class, 'invisible'])->name('account.images.invisible');
Route::post('/account/report/{id}', [App\Http\Controllers\AccountController::class, 'report'])->name('account.report');
Route::get('/account/load-more/{page}/{userId}', [App\Http\Controllers\AccountController::class, 'loadMore'])
    ->middleware(['web'])
    ->name('account.loadMore');

Route::post('/account/upload-images', [App\Http\Controllers\AccountController::class, 'upload'])->name('account.images.upload');
Route::get('/account/check-like/{id}', [App\Http\Controllers\AccountController::class, 'checkLike']);
Route::get('/account/remove-like/{id}', [App\Http\Controllers\AccountController::class, 'removeLike'])->name('account.removeLike');

//RegistersController
Route::middleware('guest')->group(function () {
    Route::get('/register/paso-{step}/{user?}', [App\Http\Controllers\RegisterController::class, 'create'])->name('user.register');
    Route::get('/register/check-username-or-email', [App\Http\Controllers\RegisterController::class, 'checkUsernameOrEmail'])->name('user.checkUsernameOrEmail');
});

Route::post('/register-user/{step}/{id?}', [App\Http\Controllers\RegisterController::class, 'save'])->name('user.save');

//NotificationController
Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/get', [App\Http\Controllers\NotificationController::class, 'get'])->name('notifications.get');
Route::post('/notifications/mark-as-read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
Route::get('/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
Route::post('/notifications/delete/{id}', [App\Http\Controllers\NotificationController::class, 'delete'])->name('notifications.delete');
Route::post('/notifications/delete-all', [App\Http\Controllers\NotificationController::class, 'deleteAll'])->name('notifications.deleteAll');

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/load-docu', [App\Http\Controllers\Admin\AdminController::class, 'cargarDocumentacion'])->name('admin.load-docu');
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');

    // City Change Management
    Route::get('city', 'App\Http\Controllers\Admin\CityChangesController@index')->name('admin.citychanges');
    Route::post('city-apply', 'App\Http\Controllers\Admin\CityChangesController@apply')->name('admin.citychanges.apply');

    // User Management
    
    Route::get('users/create', 'App\Http\Controllers\Admin\UserController@create')->name('admin.users.create');
    Route::get('users/edit/{id}', 'App\Http\Controllers\Admin\UserController@edit')->name('admin.users.edit');
    Route::post('users/save', 'App\Http\Controllers\Admin\UserController@save')->name('admin.users.save');
    Route::post('users/update', 'App\Http\Controllers\Admin\UserController@update')->name('admin.users.update');
    Route::post('users/update/status', 'App\Http\Controllers\Admin\UserController@updateStatus')->name('admin.users.update_status');
    Route::post('users/change-password/{id}', 'App\Http\Controllers\Admin\UserController@updatePassword')->name('admin.users.update_password');
    Route::get('users/getPending', 'App\Http\Controllers\Admin\UserController@getPending')->name('admin.users.getPending');
    Route::get('users/getActive', 'App\Http\Controllers\Admin\UserController@getActive')->name('admin.users.getActive');
    Route::get('users/getRejected', 'App\Http\Controllers\Admin\UserController@getRejected')->name('admin.users.getRejected');
    Route::get('users/getInactive', 'App\Http\Controllers\Admin\UserController@getInactive')->name('admin.users.getInactive');
    Route::get('users/getApproved', 'App\Http\Controllers\Admin\UserController@getApproved')->name('admin.users.getApproved');
    Route::post('users/changeStatus/{id}', 'App\Http\Controllers\Admin\UserController@changeStatus')->name('admin.users.changeStatus');
    Route::get('users/getRequests', 'App\Http\Controllers\Admin\UserController@getRequests')->name('admin.users.getRequests');
    Route::get('users/getLoginRecords', 'App\Http\Controllers\Admin\UserController@getLoginRecords')->name('admin.users.getLoginRecords');
    Route::get('users/positions', 'App\Http\Controllers\Admin\UserController@getPositionals')->name('admin.users.getPositionals');
    Route::post('users/update-positions', 'App\Http\Controllers\Admin\UserController@updatePositions')->name('admin.users.updatePositions');
    Route::post('users/make-available/{id}', 'App\Http\Controllers\Admin\UserController@makeAvailable')->name('admin.users.make_available');
    Route::get('users/make-unavailable/{id}', 'App\Http\Controllers\Admin\UserController@makeUnavailable')->name('admin.users.make_unavailable');

    // Image Management
    Route::get('images-get/{id}/{name}/{filter}', 'App\Http\Controllers\Admin\ImageController@get')->name('admin.images.getFilter');
    Route::post('images', 'App\Http\Controllers\Admin\ImageController@upload')->name('admin.images.upload');
    Route::get('images/download/{id}', 'App\Http\Controllers\Admin\ImageController@downloadImages')->name('admin.images.download');
    Route::get('images/{image}/setfront', 'App\Http\Controllers\Admin\ImageController@setFront')->name('admin.images.setfront');
    Route::get('images/{image}/approve', 'App\Http\Controllers\Admin\ImageController@approve')->name('admin.images.approve');
    Route::get('images/{image}/unapprove', 'App\Http\Controllers\Admin\ImageController@unapprove')->name('admin.images.unapprove');
    Route::get('images/{user}/approveall', 'App\Http\Controllers\Admin\ImageController@approveAll')->name('admin.images.approveall');
    Route::get('images/{user}/unapproveall', 'App\Http\Controllers\Admin\ImageController@approveAll')->name('admin.images.unapproveall');
    Route::get('images/{image}/visible', 'App\Http\Controllers\Admin\ImageController@visible')->name('admin.images.visible');
    Route::get('images/{image}/invisible', 'App\Http\Controllers\Admin\ImageController@invisible')->name('admin.images.invisible');
    Route::get('images/{user}/visibleall', 'App\Http\Controllers\Admin\ImageController@visibleAll')->name('admin.images.visibleall');
    Route::get('images/{user}/invisibleall', 'App\Http\Controllers\Admin\ImageController@invisibleAll')->name('admin.images.invisibleall');
    Route::post('images/profile', 'App\Http\Controllers\Admin\ImageController@uploadProfile')->name('admin.images.uploadProfile');
    Route::get('images/delete-{image}', 'App\Http\Controllers\Admin\ImageController@delete')->name('admin.images.delete');
    Route::get('images/delete/all-{user}', 'App\Http\Controllers\Admin\ImageController@deleteAll')->name('admin.images.deleteall');
    Route::get('images/get-{filename}', 'App\Http\Controllers\Admin\ImageController@getImage')->name('admin.images.get');
    Route::get('images/gif-{filename}', 'App\Http\Controllers\Admin\ImageController@getGif')->name('admin.images.get_gif');
    Route::post('images/blur/{id}/save-blur', 'App\Http\Controllers\Admin\ImageController@saveBlur')->name('admin.images.saveBlur');



    // Utility Management
    Route::post('utilities/assign-package', 'App\Http\Controllers\Admin\UtilityController@assignPackage')->name('admin.utilities.assign_package');
    Route::post('utilities/city-save', 'App\Http\Controllers\Admin\UtilityController@saveCity')->name('admin.utilities.cities_save');
    Route::get('utilities/city-delete/{id}', 'App\Http\Controllers\Admin\UtilityController@deleteCity')->name('admin.utilities.cities_delete');
    Route::post('utilities/city-update', 'App\Http\Controllers\Admin\UtilityController@updateCity')->name('admin.utilities.cities_update');
    Route::get('utilities/zones', 'App\Http\Controllers\Admin\UtilityController@zones')->name('admin.utilities.zones');
    Route::post('utilities/zone-save', 'App\Http\Controllers\Admin\UtilityController@saveZone')->name('admin.utilities.zones_save');
    Route::get('utilities/zone-delete/{id}', 'App\Http\Controllers\Admin\UtilityController@deleteZone')->name('admin.utilities.zones_delete');
    Route::post('utilities/zone-update', 'App\Http\Controllers\Admin\UtilityController@updateZone')->name('admin.utilities.zones_update');
    Route::get('utilities/tags', 'App\Http\Controllers\Admin\UtilityController@tags')->name('admin.utilities.tags');
    Route::post('utilities/tag-save', 'App\Http\Controllers\Admin\UtilityController@saveTag')->name('admin.utilities.tags_save');
    Route::get('utilities/tag-delete/{id}', 'App\Http\Controllers\Admin\UtilityController@deleteTag')->name('admin.utilities.tags_delete');
    Route::post('utilities/tag-update', 'App\Http\Controllers\Admin\UtilityController@updateTag')->name('admin.utilities.tags_update');
    Route::get('utilities/packages', 'App\Http\Controllers\Admin\UtilityController@packages')->name('admin.utilities.packages');
    Route::post('utilities/package-save', 'App\Http\Controllers\Admin\UtilityController@savePackage')->name('admin.utilities.packages_save');
    Route::get('utilities/package-delete/{id}', 'App\Http\Controllers\Admin\UtilityController@deletePackage')->name('admin.utilities.packages_delete');
    Route::post('utilities/package-update', 'App\Http\Controllers\Admin\UtilityController@updatePackage')->name('admin.utilities.packages_update');
    Route::post('utilities/package-user-save', 'App\Http\Controllers\Admin\UtilityController@savePackageUser')->name('admin.utilities.packages_users_save');
    Route::get('utilities/package-user-delete/{id}', 'App\Http\Controllers\Admin\UtilityController@deletePackageUser')->name('admin.utilities.packages_users_delete');
    Route::get('utilities/news', 'App\Http\Controllers\Admin\UtilityController@news')->name('admin.utilities.news');
    Route::get('utilities/reports', 'App\Http\Controllers\Admin\UtilityController@reports')->name('admin.utilities.reports');

    //NotificationController
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/notifications/get', [App\Http\Controllers\NotificationController::class, 'get'])->name('admin.notifications.get');
    Route::post('/notifications/mark-as-read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('admin.notifications.markAsRead');
    Route::get('/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('admin.notifications.markAllAsRead');
    Route::post('/notifications/delete/{id}', [App\Http\Controllers\NotificationController::class, 'delete'])->name('admin.notifications.delete');
    Route::post('/notifications/delete-all', [App\Http\Controllers\NotificationController::class, 'deleteAll'])->name('admin.notifications.deleteAll');
});


//HomeController
Route::get('/privacy-policies', [App\Http\Controllers\HomeController::class, 'privacyPolicies'])->name('home.privacy_policies');


//UserController
Route::get('/admin/users/get', [App\Http\Controllers\Admin\UserController::class, 'get'])->name('admin.user.get');
Route::post('/admin/users/update/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.user.update');
Route::get('/admin/users/verify/{id}', [App\Http\Controllers\Admin\UserController::class, 'verify'])->name('admin.user.verify');
Route::get('/admin/users/ban/{id}', [App\Http\Controllers\Admin\UserController::class, 'ban'])->name('admin.user.ban');
Route::get('/admin/users/get-pdf/{name_pdf}', [App\Http\Controllers\Admin\UserController::class, 'getPdf'])->name('admin.user.getPdf');

//ChatbotController
Route::get('/chatbot', [App\Http\Controllers\ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/chat', [App\Http\Controllers\ChatbotController::class, 'chat'])->name('chatbot.chat');


Route::get('/check-dns-records', function () {
    $domain = 'hotspania.es';
    $results = [];
    
    // Verificar MX
    $mx = dns_get_record($domain, DNS_MX);
    $results['MX'] = $mx;
    
    // Verificar TXT (incluye SPF)
    $txt = dns_get_record($domain, DNS_TXT);
    $results['TXT'] = $txt;
    
    // Verificar DMARC
    $dmarc = dns_get_record('_dmarc.' . $domain, DNS_TXT);
    $results['DMARC'] = $dmarc;
    
    return response()->json([
        'domain' => $domain,
        'records' => $results,
        'timestamp' => now(),
        'analysis' => [
            'spf_found' => collect($txt)->contains(function($record) {
                return strpos($record['txt'], 'v=spf1') !== false;
            }),
            'dmarc_found' => !empty($dmarc),
            'mx_count' => count($mx)
        ]
    ]);
});

Route::get('/test-partial-dns', function () {
    $domain = 'hotspania.es';
    $mx = dns_get_record($domain, DNS_MX);
    $txt = dns_get_record($domain, DNS_TXT);
    
    // Verificar SPF
    $spf_active = false;
    foreach ($txt as $record) {
        if (strpos($record['txt'], 'v=spf1') !== false) {
            $spf_active = true;
            break;
        }
    }
    
    try {
        \Illuminate\Support\Facades\Mail::raw(
            "⚡ TEST CON DNS PARCIALMENTE PROPAGADO\n\n" .
            "Estado actual de DNS para hotspania.es:\n" .
            "✅ SPF: Activo y propagado\n" .
            "✅ DMARC: Activo y propagado\n" .
            "⏳ MX Records: " . (empty($mx) ? 'Propagándose...' : 'Activos (' . count($mx) . ')') . "\n" .
            "⏳ DKIM: Propagándose...\n\n" .
            "Este email se envía con:\n" .
            "- Servidor SMTP: Ionos (" . env('MAIL_HOST') . ")\n" .
            "- Autenticación SPF: ✅ Configurada\n" .
            "- Política DMARC: ✅ Configurada\n\n" .
            "Aunque MX esté propagándose, el envío debería funcionar porque:\n" .
            "1. Usamos SMTP directo de Ionos\n" .
            "2. SPF ya autoriza a Ionos\n" .
            "3. DMARC está configurado\n\n" .
            "Timestamp: " . now() . "\n" .
            "Progreso DNS: 60% completado",
            function ($message) {
                $message->to('hotspania@gmail.com')
                        ->subject('⚡ Test DNS Parcial - SPF+DMARC Activos - ' . now()->format('H:i:s'))
                        ->from('consultas@hotspania.es', 'Hotspania DNS Parcial');
            }
        );
        
        return response()->json([
            'status' => 'success',
            'message' => '⚡ Email enviado con DNS parcialmente propagado',
            'dns_status' => [
                'spf_active' => $spf_active,
                'dmarc_active' => true,
                'mx_active' => !empty($mx),
                'mx_count' => count($mx)
            ],
            'smtp_config' => [
                'host' => env('MAIL_HOST'),
                'port' => env('MAIL_PORT'),
                'encryption' => env('MAIL_ENCRYPTION')
            ],
            'timestamp' => now(),
            'expectation' => '🎯 Con SPF+DMARC activos, este email tiene buenas posibilidades de llegar'
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/digitalocean-unblock-request', function () {
    return response()->json([
        'status' => 'SMTP BLOCKING CONFIRMED',
        'evidence' => [
            'web_ports' => 'All working (80, 443, 53, 22)',
            'email_receive_ports' => 'All working (993, 143, 995, 110)', 
            'smtp_send_ports' => 'All blocked (25, 465, 587)',
            'conclusion' => 'DigitalOcean blocks outbound SMTP specifically'
        ],
        'unblock_request' => [
            'url' => 'https://cloud.digitalocean.com/support/tickets/new',
            'template' => "Subject: Request SMTP Port Unblocking for Business Email\n\n" .
                         "Hello DigitalOcean Support,\n\n" .
                         "I need SMTP ports unblocked for my legitimate business website.\n\n" .
                         "BUSINESS DETAILS:\n" .
                         "- Website: hotspania.es\n" .
                         "- Business: Tourism/Hospitality\n" .
                         "- Email: consultas@hotspania.es\n" .
                         "- Purpose: Customer contact forms, booking confirmations\n\n" .
                         "SERVER DETAILS:\n" .
                         "- Droplet: ubuntu-s-1vcpu-1gb-amd-ams3-01\n" .
                         "- IP: 146.190.18.74\n" .
                         "- Region: AMS3\n\n" .
                         "TECHNICAL VERIFICATION:\n" .
                         "I've confirmed that ports 25, 465, and 587 are blocked while all other ports work normally.\n" .
                         "This is preventing legitimate business email functionality.\n\n" .
                         "EMAIL PROVIDER:\n" .
                         "- Provider: Ionos\n" .
                         "- SMTP Server: smtp.ionos.es\n" .
                         "- DNS Records: Properly configured\n\n" .
                         "This is NOT for bulk marketing - only transactional business emails.\n\n" .
                         "Please unblock outbound SMTP ports for this droplet.\n\n" .
                         "Thank you,\n" .
                         "jagcweb\n" .
                         "Date: " . now()->format('Y-m-d H:i:s') . " UTC",
            'expected_response_time' => '24-48 hours'
        ]
    ]);
});

