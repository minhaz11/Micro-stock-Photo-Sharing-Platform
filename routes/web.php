<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');

})->name('clear');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::namespace('Gateway')->prefix('ipn')->name('ipn.')->group(function () {
    Route::post('paypal', 'paypal\ProcessController@ipn')->name('paypal');
    Route::get('paypal_sdk', 'paypal_sdk\ProcessController@ipn')->name('paypal_sdk');
    Route::post('perfect_money', 'perfect_money\ProcessController@ipn')->name('perfect_money');
    Route::post('stripe', 'stripe\ProcessController@ipn')->name('stripe');
    Route::post('stripe_js', 'stripe_js\ProcessController@ipn')->name('stripe_js');
    Route::post('stripe_v3', 'stripe_v3\ProcessController@ipn')->name('stripe_v3');
    Route::post('skrill', 'skrill\ProcessController@ipn')->name('skrill');
    Route::post('paytm', 'paytm\ProcessController@ipn')->name('paytm');
    Route::post('payeer', 'payeer\ProcessController@ipn')->name('payeer');
    Route::post('paystack', 'paystack\ProcessController@ipn')->name('paystack');
    Route::post('voguepay', 'voguepay\ProcessController@ipn')->name('voguepay');
    Route::get('flutterwave/{trx}/{type}', 'flutterwave\ProcessController@ipn')->name('flutterwave');
    Route::post('razorpay', 'razorpay\ProcessController@ipn')->name('razorpay');
    Route::post('instamojo', 'instamojo\ProcessController@ipn')->name('instamojo');
    Route::get('blockchain', 'blockchain\ProcessController@ipn')->name('blockchain');
    Route::get('blockio', 'blockio\ProcessController@ipn')->name('blockio');
    Route::post('coinpayments', 'coinpayments\ProcessController@ipn')->name('coinpayments');
    Route::post('coinpayments_fiat', 'coinpayments_fiat\ProcessController@ipn')->name('coinpayments_fiat');
    Route::post('coingate', 'coingate\ProcessController@ipn')->name('coingate');
    Route::post('coinbase_commerce', 'coinbase_commerce\ProcessController@ipn')->name('coinbase_commerce');
    Route::get('mollie', 'mollie\ProcessController@ipn')->name('mollie');
    Route::post('cashmaal', 'cashmaal\ProcessController@ipn')->name('cashmaal');
});

// User Support Ticket
Route::prefix('ticket')->group(function () {
    Route::get('/', 'TicketController@supportTicket')->name('ticket');
    Route::get('/new', 'TicketController@openSupportTicket')->name('ticket.open');
    Route::post('/create', 'TicketController@storeSupportTicket')->name('ticket.store');
    Route::get('/view/{ticket}', 'TicketController@viewTicket')->name('ticket.view');
    Route::post('/reply/{ticket}', 'TicketController@replyTicket')->name('ticket.reply');
    Route::get('/download/{ticket}', 'TicketController@ticketDownload')->name('ticket.download');
});


/*
|--------------------------------------------------------------------------
| Start Admin Area
|--------------------------------------------------------------------------
*/

Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');
        // Admin Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });

    Route::middleware(['admin'])->group(function () {
        Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');
        Route::get('profile', 'AdminController@profile')->name('profile');
        Route::post('profile', 'AdminController@profileUpdate')->name('profile.update');
        Route::get('password', 'AdminController@password')->name('password');
        Route::post('password', 'AdminController@passwordUpdate')->name('password.update');

        // Users Manager
        Route::get('users', 'ManageUsersController@allUsers')->name('users.all');
        Route::get('contributors', 'ManageUsersController@allContributors')->name('users.contributor.all');
        Route::get('users/active', 'ManageUsersController@activeUsers')->name('users.active');
        Route::get('users/banned', 'ManageUsersController@bannedUsers')->name('users.banned');
        Route::get('users/email-verified', 'ManageUsersController@emailVerifiedUsers')->name('users.emailVerified');
        Route::get('users/email-unverified', 'ManageUsersController@emailUnverifiedUsers')->name('users.emailUnverified');
        Route::get('users/sms-unverified', 'ManageUsersController@smsUnverifiedUsers')->name('users.smsUnverified');
        Route::get('users/sms-verified', 'ManageUsersController@smsVerifiedUsers')->name('users.smsVerified');

        Route::get('users/{scope}/search', 'ManageUsersController@search')->name('users.search');
        Route::get('user/detail/{id}', 'ManageUsersController@detail')->name('users.detail');
        Route::post('user/update/{id}', 'ManageUsersController@update')->name('users.update');
        Route::post('user/add-sub-balance/{id}', 'ManageUsersController@addSubBalance')->name('users.addSubBalance');
        Route::get('user/send-email/{id}', 'ManageUsersController@showEmailSingleForm')->name('users.email.single');
        Route::post('user/send-email/{id}', 'ManageUsersController@sendEmailSingle')->name('users.email.single');
        Route::get('user/transactions/{id}', 'ManageUsersController@transactions')->name('users.transactions');
        Route::get('user/deposits/{id}', 'ManageUsersController@deposits')->name('users.deposits');
        Route::get('user/deposits/via/{method}/{type?}/{userId}', 'ManageUsersController@depViaMethod')->name('users.deposits.method');
        Route::get('user/withdrawals/{id}', 'ManageUsersController@withdrawals')->name('users.withdrawals');
        Route::get('user/withdrawals/via/{method}/{type?}/{userId}', 'ManageUsersController@withdrawalsViaMethod')->name('users.withdrawals.method');
        // Login History
        Route::get('users/login/history/{id}', 'ManageUsersController@userLoginHistory')->name('users.login.history.single');

        Route::get('users/send-email', 'ManageUsersController@showEmailAllForm')->name('users.email.all');
        Route::post('users/send-email', 'ManageUsersController@sendEmailAll')->name('users.email.send');

        //ftp
        Route::get('/storage/settings', 'GeneralSettingController@ftpSettings')->name('ftp.setting');
        Route::post('/storage/settings', 'GeneralSettingController@ftpSettingsUpdate')->name('ftp.setting.update');

        //category manage
        Route::get('/category/all', 'CategoryController@all')->name('category.all');
        Route::post('/category/store', 'CategoryController@store')->name('category.store');
        Route::post('/category/update/{id}', 'CategoryController@update')->name('category.update');
        Route::get('/search', 'CategoryController@search')->name('category.search');

        //subscriptions
        Route::get('/subscriptions/all', 'SubscriptionController@all')->name('subscription.all');
        Route::post('/subscriptions/create', 'SubscriptionController@create')->name('subscription.create');
        Route::post('/subscriptions/update/', 'SubscriptionController@update')->name('subscription.update');


        //badges
        Route::get('/badges/all', 'BadgeController@all')->name('badges.all');
        Route::post('/badges/create', 'BadgeController@create')->name('badge.create');
        Route::post('/badges/upadte', 'BadgeController@update')->name('badge.update');

        Route::get('review/download/file/{id}', 'ImageController@downloadFile')->name('file.download');

        //reviewers
        Route::get('reviewers', 'ReviewerController@allReviewers')->name('reviewer.all');
        Route::get('reviewer/detail/{id}', 'ReviewerController@reviewerDetail')->name('reviewer.detail');
        Route::post('reviewer/update/{id}', 'ReviewerController@reviewerUpdate')->name('reviewer.update');
        Route::get('reviewer/login/history/{id}', 'ReviewerController@reviewerLoginHistory')->name('reviewer.login.history.single');
        Route::get('reviewer/{scope}/search', 'ReviewerController@searchReviewer')->name('reviewer.search');

        Route::get('reviewer/active', 'ReviewerController@activeReviewers')->name('reviewer.active');
        Route::get('reviewer/banned', 'ReviewerController@bannedReviewers')->name('reviewer.banned');
        Route::get('reviewer/pending', 'ReviewerController@pendingReviewers')->name('reviewer.pending');
        Route::get('reviewer/send-email', 'ReviewerController@reviewerShowEmailAllForm')->name('reviewer.email.all');
        Route::post('reviewer/send-email', 'ReviewerController@reviewerSendEmailAll')->name('reviewer.email.send');

        Route::get('reviewer/approved/photos/{id}', 'ReviewerController@approvedBy')->name('reviewer.approved');
        Route::get('reviewer/rejected/photos/{id}', 'ReviewerController@rejectedBy')->name('reviewer.rejected');
        Route::get('reviewer/reviewed/photos/{id}', 'ReviewerController@reviewedBy')->name('reviewer.reviewed');

        Route::get('reviewer/send-email/{id}', 'ReviewerController@showEmailSingleForm')->name('reviewer.email.single');
        Route::post('reviewer/send-email/{id}', 'ReviewerController@sendEmailSingle')->name('reviewer.email.single');

        //image managing
        Route::get('photos/all', 'ImageController@all')->name('image.all');
        Route::get('photos/premium/all', 'ImageController@premiumAll')->name('image.premium.all');
        Route::get('photos/free/all', 'ImageController@freeAll')->name('image.free.all');
        Route::get('photos/approved', 'ImageController@approved')->name('image.approved');
        Route::get('photos/rejected', 'ImageController@rejected')->name('image.rejected');
        Route::get('photos/review/{id}', 'ImageController@review')->name('image.details');
        Route::get('photos/details/{id}', 'ImageController@details')->name('image.details.all');
        Route::get('photos/pendings/', 'ImageController@pending')->name('image.pending');
        Route::get('/pending/reviews', 'ImageController@pendingReviews')->name('image.pending.reviews');
        Route::post('/pending/photo/reject/', 'ImageController@rejectPhoto')->name('image.reject');
        Route::post('/approve/photo/{id}', 'ImageController@approvePhoto')->name('image.approve');
        Route::get('/downloads', 'ImageController@downloads')->name('image.downloads');
        Route::get('/downloads/ip/{ip}', 'ImageController@downloadsByIp')->name('ip.downloads');

        Route::get('review/reported/photos', 'ImageController@pendingReport')->name('reported.pending');
        Route::get('review/reported/photo/action/{id}', 'ImageController@reportAction')->name('reported.action');
        Route::get('/pending/report/reviews', 'ImageController@pendingReportReviews')->name('reported.pending.review');

        Route::post('review/report/all/remove/{id}', 'ImageController@reportRemove')->name('report.remove');
        Route::post('review/report/remove/{id}', 'ImageController@removeReport')->name('reported.remove');
        Route::post('review/ban/photo/{id}', 'ImageController@banned')->name('report.banned');


        // Subscriber
        Route::get('subscriber', 'SubscriberController@index')->name('subscriber.index');
        Route::get('subscriber/send-email', 'SubscriberController@sendEmailForm')->name('subscriber.sendEmail');
        Route::post('subscriber/remove', 'SubscriberController@remove')->name('subscriber.remove');
        Route::post('subscriber/send-email', 'SubscriberController@sendEmail')->name('subscriber.sendEmail');


        // Deposit Gateway
        Route::name('gateway.')->prefix('gateway')->group(function () {
            // Automatic Gateway
            Route::get('automatic', 'GatewayController@index')->name('automatic.index');
            Route::get('automatic/edit/{alias}', 'GatewayController@edit')->name('automatic.edit');
            Route::post('automatic/update/{code}', 'GatewayController@update')->name('automatic.update');
            Route::post('automatic/remove/{code}', 'GatewayController@remove')->name('automatic.remove');
            Route::post('automatic/activate', 'GatewayController@activate')->name('automatic.activate');
            Route::post('automatic/deactivate', 'GatewayController@deactivate')->name('automatic.deactivate');


            // Manual Methods
            Route::get('manual', 'ManualGatewayController@index')->name('manual.index');
            Route::get('manual/new', 'ManualGatewayController@create')->name('manual.create');
            Route::post('manual/new', 'ManualGatewayController@store')->name('manual.store');
            Route::get('manual/edit/{alias}', 'ManualGatewayController@edit')->name('manual.edit');
            Route::post('manual/update/{id}', 'ManualGatewayController@update')->name('manual.update');
            Route::post('manual/activate', 'ManualGatewayController@activate')->name('manual.activate');
            Route::post('manual/deactivate', 'ManualGatewayController@deactivate')->name('manual.deactivate');
        });


        // DEPOSIT SYSTEM
        Route::name('deposit.')->prefix('deposit')->group(function () {
            Route::get('/', 'DepositController@deposit')->name('list');
            Route::get('pending', 'DepositController@pending')->name('pending');
            Route::get('rejected', 'DepositController@rejected')->name('rejected');
            Route::get('approved', 'DepositController@approved')->name('approved');
            Route::get('successful', 'DepositController@successful')->name('successful');
            Route::get('details/{id}', 'DepositController@details')->name('details');

            Route::post('reject', 'DepositController@reject')->name('reject');
            Route::post('approve', 'DepositController@approve')->name('approve');
            Route::get('via/{method}/{type?}', 'DepositController@depViaMethod')->name('method');
            Route::get('/{scope}/search', 'DepositController@search')->name('search');
            Route::get('date-search/{scope}', 'DepositController@dateSearch')->name('dateSearch');

        });

        // WITHDRAW SYSTEM
        Route::name('withdraw.')->prefix('withdraw')->group(function () {
            Route::get('pending', 'WithdrawalController@pending')->name('pending');
            Route::get('approved', 'WithdrawalController@approved')->name('approved');
            Route::get('rejected', 'WithdrawalController@rejected')->name('rejected');
            Route::get('log', 'WithdrawalController@log')->name('log');
            Route::get('via/{method_id}/{type?}', 'WithdrawalController@logViaMethod')->name('method');
            Route::get('{scope}/search', 'WithdrawalController@search')->name('search');
            Route::get('date-search/{scope}', 'WithdrawalController@dateSearch')->name('dateSearch');
            Route::get('details/{id}', 'WithdrawalController@details')->name('details');
            Route::post('approve', 'WithdrawalController@approve')->name('approve');
            Route::post('reject', 'WithdrawalController@reject')->name('reject');


            // Withdraw Method
            Route::get('method/', 'WithdrawMethodController@methods')->name('method.index');
            Route::get('method/create', 'WithdrawMethodController@create')->name('method.create');
            Route::post('method/create', 'WithdrawMethodController@store')->name('method.store');
            Route::get('method/edit/{id}', 'WithdrawMethodController@edit')->name('method.edit');
            Route::post('method/edit/{id}', 'WithdrawMethodController@update')->name('method.update');
            Route::post('method/activate', 'WithdrawMethodController@activate')->name('method.activate');
            Route::post('method/deactivate', 'WithdrawMethodController@deactivate')->name('method.deactivate');
        });

        // Report
        Route::get('report/transaction', 'ReportController@transaction')->name('report.transaction');
        Route::get('report/transaction/search', 'ReportController@transactionSearch')->name('report.transaction.search');
        Route::get('report/login/history', 'ReportController@loginHistory')->name('report.login.history');
        Route::get('report/login/ipHistory/{ip}', 'ReportController@loginIpHistory')->name('report.login.ipHistory');


        // Admin Support
        Route::get('tickets', 'SupportTicketController@tickets')->name('ticket');
        Route::get('tickets/pending', 'SupportTicketController@pendingTicket')->name('ticket.pending');
        Route::get('tickets/closed', 'SupportTicketController@closedTicket')->name('ticket.closed');
        Route::get('tickets/answered', 'SupportTicketController@answeredTicket')->name('ticket.answered');
        Route::get('tickets/view/{id}', 'SupportTicketController@ticketReply')->name('ticket.view');
        Route::post('ticket/reply/{id}', 'SupportTicketController@ticketReplySend')->name('ticket.reply');
        Route::get('ticket/download/{ticket}', 'SupportTicketController@ticketDownload')->name('ticket.download');
        Route::post('ticket/delete', 'SupportTicketController@ticketDelete')->name('ticket.delete');


        // Language Manager
        Route::get('/language', 'LanguageController@langManage')->name('language.manage');
        Route::post('/language', 'LanguageController@langStore')->name('language.manage.store');
        Route::post('/language/delete/{id}', 'LanguageController@langDel')->name('language.manage.del');
        Route::post('/language/update/{id}', 'LanguageController@langUpdatepp')->name('language.manage.update');
        Route::get('/language/edit/{id}', 'LanguageController@langEdit')->name('language.key');
        Route::post('/language/import', 'LanguageController@langImport')->name('language.import_lang');


        Route::post('language/store/key/{id}', 'LanguageController@storeLanguageJson')->name('language.store.key');
        Route::post('language/delete/key/{id}', 'LanguageController@deleteLanguageJson')->name('language.delete.key');
        Route::post('language/update/key/{id}', 'LanguageController@updateLanguageJson')->name('language.update.key');


        // General Setting
        Route::get('general-setting', 'GeneralSettingController@index')->name('setting.index');
        Route::post('general-setting', 'GeneralSettingController@update')->name('setting.update');
        Route::post('uplaod/instruction', 'GeneralSettingController@uploadSetting')->name('setting.upload');

        // Logo-Icon
        Route::get('setting/logo-icon', 'GeneralSettingController@logoIcon')->name('setting.logo_icon');
        Route::post('setting/logo-icon', 'GeneralSettingController@logoIconUpdate')->name('setting.logo_icon');
        Route::post('watermark', 'GeneralSettingController@watermarkUpdate')->name('watermark');

        // Plugin
        Route::get('extensions', 'ExtensionController@index')->name('extensions.index');
        Route::post('extensions/update/{id}', 'ExtensionController@update')->name('extensions.update');
        Route::post('extensions/activate', 'ExtensionController@activate')->name('extensions.activate');
        Route::post('extensions/deactivate', 'ExtensionController@deactivate')->name('extensions.deactivate');


        // Email Setting
        Route::get('email-template/global', 'EmailTemplateController@emailTemplate')->name('email.template.global');
        Route::post('email-template/global', 'EmailTemplateController@emailTemplateUpdate')->name('email.template.global');
        Route::get('email-template/setting', 'EmailTemplateController@emailSetting')->name('email.template.setting');
        Route::post('email-template/setting', 'EmailTemplateController@emailSettingUpdate')->name('email.template.setting');
        Route::get('email-template/index', 'EmailTemplateController@index')->name('email.template.index');
        Route::get('email-template/{id}/edit', 'EmailTemplateController@edit')->name('email.template.edit');
        Route::post('email-template/{id}/update', 'EmailTemplateController@update')->name('email.template.update');
        Route::post('email-template/send-test-mail', 'EmailTemplateController@sendTestMail')->name('email.template.sendTestMail');


        // SMS Setting
        Route::get('sms-template/global', 'SmsTemplateController@smsSetting')->name('sms.template.global');
        Route::post('sms-template/global', 'SmsTemplateController@smsSettingUpdate')->name('sms.template.global');
        Route::get('sms-template/index', 'SmsTemplateController@index')->name('sms.template.index');
        Route::get('sms-template/edit/{id}', 'SmsTemplateController@edit')->name('sms.template.edit');
        Route::post('sms-template/update/{id}', 'SmsTemplateController@update')->name('sms.template.update');
        Route::post('email-template/send-test-sms', 'SmsTemplateController@sendTestSMS')->name('sms.template.sendTestSMS');

        // SEO
        Route::get('seo', 'FrontendController@seoEdit')->name('seo');


        // Frontend
        Route::name('frontend.')->prefix('frontend')->group(function () {


            Route::get('templates', 'FrontendController@templates')->name('templates');
            Route::post('templates', 'FrontendController@templatesActive')->name('templates.active');


            Route::get('frontend-sections/{key}', 'FrontendController@frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'FrontendController@frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'FrontendController@frontendElement')->name('sections.element');
            Route::post('remove', 'FrontendController@remove')->name('remove');

            // Page Builder
            Route::get('manage-pages', 'PageBuilderController@managePages')->name('manage.pages');
            Route::post('manage-pages', 'PageBuilderController@managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'PageBuilderController@managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete', 'PageBuilderController@managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'PageBuilderController@manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'PageBuilderController@manageSectionUpdate')->name('manage.section.update');
        });
    });
});


//reviewer
Route::namespace('Reviewer')->prefix('reviewer')->name('reviewer.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');

        //register
        Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'RegisterController@register')->name('signup')->middleware('regStatus');

        // Reviewer Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');

    });
    Route::middleware('reviewer')->group(function () {
        Route::get('authorization', 'Auth\AuthorizeController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'Auth\AuthorizeController@sendVerifyCode')->name('send_verify_code');
        Route::post('verify-email', 'Auth\AuthorizeController@emailVerification')->name('verify_email');
        Route::post('verify-sms', 'Auth\AuthorizeController@smsVerification')->name('verify_sms');
        Route::post('verify-g2fa', 'Auth\AuthorizeController@g2faVerification')->name('go2fa.verify');

        Route::middleware('checkReviewer')->group(function () {
            Route::get('dashboard', 'ReviewerController@dashboard')->name('dashboard');
            Route::get('profile', 'ReviewerController@profile')->name('profile');
            Route::post('profile', 'ReviewerController@profileUpdate')->name('profile.update');
            Route::get('password', 'ReviewerController@password')->name('password');
            Route::post('password', 'ReviewerController@passwordUpdate')->name('password.update');

            Route::get('/pending/reviews', 'PhotoReviewController@pendingReviews')->name('pending.review');
            Route::get('/pending/report/reviews', 'ReviewReportPhotoController@pendingReportReviews')->name('pending.report.review');
            //review photo
            Route::get('review/pending/photos', 'PhotoReviewController@pending')->name('photo.pending');
            Route::get('review/pending/photos/action/{id}', 'PhotoReviewController@action')->name('photo.action');
            Route::post('review/pending/photo/reject/', 'PhotoReviewController@rejectPhoto')->name('photo.reject');
            Route::post('review/approve/photo/{id}', 'PhotoReviewController@approvePhoto')->name('photo.approve');

            Route::get('review/approved/photos', 'PhotoReviewController@approved')->name('photo.approved');
            Route::get('review/approved-by/photos', 'PhotoReviewController@approvedBy')->name('photo.approved.me');
            Route::get('review/rejected-by/photos', 'PhotoReviewController@rejectedBy')->name('photo.rejected.me');

            Route::get('review/download/file/{id}', 'PhotoReviewController@downloadFile')->name('file.download');


            //reported photo
            Route::get('review/reported/photos', 'ReviewReportPhotoController@pending')->name('reported.pending');
            Route::get('review/reported/photos/action/{id}', 'ReviewReportPhotoController@action')->name('reported.action');

            Route::post('review/report/all/remove/{id}', 'ReviewReportPhotoController@reportRemove')->name('report.remove');
            Route::post('review/report/remove/{id}', 'ReviewReportPhotoController@removeReport')->name('reported.remove');
            Route::post('review/ban/photo/{id}', 'ReviewReportPhotoController@banned')->name('report.banned');

            Route::get('review/banned-by/', 'ReviewReportPhotoController@bannedBy')->name('reported.bannedby');
            Route::get('review/reviewed-by/', 'ReviewReportPhotoController@reviewedBy')->name('reported.reviewedby');

            //predefined reasons
            Route::get('predefined/reasons', 'ReviewerController@reasonAll')->name('predefined.reasons');
            Route::post('predefined/reasons/add', 'ReviewerController@reasonAdd')->name('predefined.reasons.add');
            Route::post('predefined/reasons/update/{id}', 'ReviewerController@reasonUpdate')->name('predefined.reasons.update');
            Route::post('predefined/reasons/remove/{id}', 'ReviewerController@reasonRemove')->name('predefined.reasons.remove');
            Route::get('predefined/individual/reasons', 'ReviewerController@reasonBy')->name('predefined.reasons.me');
        });


    });
});


//contributor
Route::namespace('Contributor')->prefix('user')->name('contributor.')->group(function () {

    Route::middleware(['checkStatus', 'auth'])->group(function () {

        Route::get('/follower/all', 'ContributorController@followers')->name('follower.all');
        Route::get('/follower/search', 'ContributorController@followersSearch')->name('follower.search');
        Route::get('/follower/remove/{follower}/{followed}', 'ContributorController@followerRemove')->name('follower.remove');

        //image uplaod and handling
        Route::get('/image/pending/photos', 'ImageController@pending')->name('image.all');
        Route::get('/image/approved/photos', 'ImageController@approvedPhotos')->name('image.approved');
        Route::get('/image/rejected/photos', 'ImageController@rejectedPhotos')->name('image.rejected');
        Route::get('/image/banned/photos', 'ImageController@bannedPhotos')->name('image.banned');
        Route::get('/image/upload', 'ImageController@add')->name('image.upload');
        Route::post('/image/store', 'ImageController@store')->name('image.store');
        Route::get('/image/edit/{id}', 'ImageController@edit')->name('image.edit');
        Route::post('/image/update/{id}', 'ImageController@update')->name('image.update');
        Route::post('/image/remove/{id}', 'ImageController@remove')->name('image.remove');

        //image search
        Route::get('/image/search/{scope}', 'ImageController@searchImage')->name('image.search');


    });

});


/*
|--------------------------------------------------------------------------
| Start User Area
|--------------------------------------------------------------------------
*/
Route::name('user.')->group(function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login')->name('logging');
    Route::get('logout', 'Auth\LoginController@logoutGet')->name('logout');


    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register')->middleware('regStatus');

    Route::group(['middleware' => ['guest']], function () {
        Route::get('register/{reference}', 'Auth\RegisterController@referralRegister')->name('refer.register');
    });
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/code-verify', 'Auth\ForgotPasswordController@codeVerify')->name('password.code_verify');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/verify-code', 'Auth\ForgotPasswordController@verifyCode')->name('password.verify-code');
});

Route::name('user.')->prefix('user')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('authorization', 'AuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'AuthorizationController@sendVerifyCode')->name('send_verify_code');
        Route::post('verify-email', 'AuthorizationController@emailVerification')->name('verify_email');
        Route::post('verify-sms', 'AuthorizationController@smsVerification')->name('verify_sms');
        Route::post('verify-g2fa', 'AuthorizationController@g2faVerification')->name('go2fa.verify');

        Route::middleware(['checkStatus'])->group(function () {
            Route::post('image/report', 'ImageController@report')->name('report');

            Route::namespace('User')->group(function () {
                Route::get('/migrate', 'DashboardController@migrate')->name('migrate');
                Route::get('/purchase/subscription/{id}', 'DashboardController@purchaseSubscription')->name('purchase.subscription');
                Route::post('/purchase/subscription', 'DashboardController@purchaseSubscriptionConfirm')->name('purchase.subscription.confirm');

                Route::get('/feed', 'DashboardController@feed')->name('feed');
                Route::get('/transaction/logs', 'DashboardController@trxLogs')->name('trx');

                Route::get('dashboard', 'DashboardController@home')->name('home');
                Route::get('/collection/all', 'CollectionController@all')->name('collection.all');
                Route::get('/collection/image/{id}', 'CollectionController@collectionImages')->name('collection.images');
                Route::post('/collection/update/{id}', 'CollectionController@collectionUpdate')->name('collection.update');
                Route::post('/collection/remove/{id}', 'CollectionController@collectionRemove')->name('collection.remove');
                Route::post('/collection/image/remove/', 'CollectionController@collectionImageRemove')->name('collection.image.remove');
                Route::get('/collection/search/', 'CollectionController@collectionSearch')->name('collection.search');


                Route::get('/downloads/all', 'DashboardController@downloads')->name('download.all');
                Route::get('/following/all', 'DashboardController@following')->name('following.all');
                Route::get('/following/search', 'DashboardController@followingSearch')->name('follwing.search');
                Route::get('/liked/photo/all', 'DashboardController@likedPhotos')->name('liked.all');

                Route::get('/profile', 'DashboardController@profile')->name('profile');
                Route::post('/profile', 'DashboardController@profileUpdate')->name('profile.update');

                Route::get('/subscriptions', 'DashboardController@subscriptions')->name('subscriptions');
                Route::get('/referred/users', 'DashboardController@referredUsers')->name('referred.users');
                Route::get('/referral/commissions', 'DashboardController@referralCommissions')->name('referral.commissions');

                Route::get('/earning-logs', 'DashboardController@earningLogs')->name('earning.logs');
            });


            Route::get('change-password', 'UserController@changePassword')->name('password');
            Route::post('change-password', 'UserController@submitPassword')->name('change.pass');

            //2FA
            Route::get('twofactor', 'UserController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'UserController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'UserController@disable2fa')->name('twofactor.disable');


            // Deposit
            Route::any('/deposit', 'Gateway\PaymentController@deposit')->name('deposit');
            Route::post('deposit/insert', 'Gateway\PaymentController@depositInsert')->name('deposit.insert');
            Route::get('deposit/preview', 'Gateway\PaymentController@depositPreview')->name('deposit.preview');
            Route::get('deposit/confirm', 'Gateway\PaymentController@depositConfirm')->name('deposit.confirm');
            Route::get('deposit/manual', 'Gateway\PaymentController@manualDepositConfirm')->name('deposit.manual.confirm');
            Route::post('deposit/manual', 'Gateway\PaymentController@manualDepositUpdate')->name('deposit.manual.update');
            Route::get('deposit/history', 'UserController@depositHistory')->name('deposit.history');

            //payment
            Route::any('/payment/{id?}', 'Gateway\PaymentController@deposit')->name('payment');
            Route::post('deposit/insert', 'Gateway\PaymentController@depositInsert')->name('deposit.insert');
            Route::get('subscription/payment/preview', 'Gateway\PaymentController@depositPreview')->name('payment.preview');
            Route::get('deposit/confirm', 'Gateway\PaymentController@depositConfirm')->name('deposit.confirm');
            Route::get('deposit/manual', 'Gateway\PaymentController@manualDepositConfirm')->name('deposit.manual.confirm');
            Route::post('deposit/manual', 'Gateway\PaymentController@manualDepositUpdate')->name('deposit.manual.update');
            Route::get('deposit/history', 'UserController@depositHistory')->name('deposit.history');

            // Withdraw
            Route::get('/withdraw', 'UserController@withdrawMoney')->name('withdraw');
            Route::post('/withdraw', 'UserController@withdrawStore')->name('withdraw.money');
            Route::get('/withdraw/preview', 'UserController@withdrawPreview')->name('withdraw.preview');
            Route::post('/withdraw/preview', 'UserController@withdrawSubmit')->name('withdraw.submit');
            Route::get('/withdraw/history', 'UserController@withdrawLog')->name('withdraw.history');

        });
    });
});

Route::post('/collection/store', 'CollectionController@store')->name('collection.store');


//download
Route::get('/download', 'ImageController@getDownload')->name('download');


//like
Route::post('/image/like/', 'ImageController@like')->name('like');


//follow
Route::post('follow/', 'ImageController@follow')->name('follow');
Route::get('/image/unlike/{id}/{user}', 'ImageController@unlike')->name('unlike');


//collect
Route::get('/image/collect/', 'ImageController@collect')->name('collect');
Route::post('/image/collect/', 'ImageController@collectStore')->name('collect.store');

//author profile
Route::get('/contributor/profile/{id}', 'ImageController@contributorProfile')->name('con.profile');


//view all photos
Route::get('/view/all/photos', 'SiteController@viewAllPhotos')->name('view.all');
Route::get('/search/photos', 'SiteController@searchPhotos')->name('search.photos');


//image details
Route::get('/image/details/{id}/{slug}', 'ImageController@imageDetails')->name('image.details');
Route::get('/category/{id}/{slug}', 'ImageController@categoryImages')->name('image.category');
Route::post('/comment/store/', 'SiteController@commentStore')->name('comment');
Route::get('/txt/download', 'SiteController@getDownload')->name('txt.download');

Route::get('/frequently-asked-question', 'SiteController@faq')->name('faq');
Route::get('links/{slug}/{id}', 'SiteController@policyAndTerms')->name('links');

Route::post('/subscribe', 'SiteController@subscribe')->name('subscribe');
Route::get('/contact', 'SiteController@contact')->name('contact');
Route::post('/contact', 'SiteController@contactSubmit')->name('contact.send');
Route::get('/change/{lang?}', 'SiteController@changeLanguage')->name('lang');


Route::get('placeholder-image/{size}', 'SiteController@placeholderImage')->name('placeholderImage');

Route::get('/{slug}', 'SiteController@pages')->name('pages');
Route::get('/', 'SiteController@index')->name('home');
