<?php

namespace App\Providers;

use App\GeneralSetting;
use App\Language;
use App\Page;
use App\Extension;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        $activeTemplate = activeTemplate();

        $viewShare['general'] = GeneralSetting::first();
        $viewShare['activeTemplate'] = $activeTemplate;
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        $viewShare['language'] = Language::all();
        $viewShare['pages'] = Page::where('tempname',$activeTemplate)->where('slug','!=','home')->get();
        view()->share($viewShare);
        

        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'banned_users_count'           => \App\User::banned()->count(),
                'banned_reviewer_count'           => \App\Reviewer::where('status',0)->count(),
                'pending_reviewer_count'           => \App\Reviewer::where('status',1)->count(),
                'email_unverified_users_count' => \App\User::emailUnverified()->count(),
                'sms_unverified_users_count'   => \App\User::smsUnverified()->count(),
                'pending_ticket_count'         => \App\SupportTicket::whereIN('status', [0,2])->count(),
                'pending_deposits_count'    => \App\Deposit::pending()->count(),
                'pending_withdraw_count'    => \App\Withdrawal::pending()->count(),
                'pendingPhoto'                 => \App\Image::whereStatus(0)->count(),
                'pendingReport'                 => \App\Report::whereStatus(0)->count(),
                'pendingReviews'                 =>  \App\Image::whereStatus(0)->whereNotNull('reviewing_status')->where('reviewing_status->admin_id',auth()->guard('admin')->id())->count(),
                'pendingReportReviews'                 =>  \App\Report::where('admin_id',auth()->guard('admin')->id())->where('status',0)->count()
            ]);
        });

        view()->composer('reviewer.partials.sidenav', function ($view) {
            $view->with([
                'pendingPhoto'                 => \App\Image::whereStatus(0)->count(),
                'pendingReport'                 => \App\Report::whereStatus(0)->count(),
                'pendingReviews'                 =>  \App\Image::whereStatus(0)->whereNotNull('reviewing_status')->where('reviewing_status->reviewer_id',auth()->guard('reviewer')->id())->count(),
                'pendingReportReviews'                 =>  \App\Report::whereReviewerId(auth()->guard('reviewer')->id())->where('status',0)->count()
              
            ]);
    
        });

        view()->composer(activeTemplate().'sections.counter', function ($view) {
            $view->with([
                'totalPhoto'                 => \App\Image::whereStatus(1)->count(),
                'totalUser'                 => \App\User::whereStatus(1)->count(),
                'totalDownload'             => \App\Download::count(),
                'totalTrx'                 => \App\Transaction::sum('amount'),
              
            ]);
        });
        view()->composer($activeTemplate.'home', function ($view) {
            $view->with([
                'categories'           => \App\Category::whereStatus(1)->latest()->inRandomOrder()->with('images')->get(),
                'images'               => \App\Image::whereStatus(1)->latest()->take(8)->with(['user','likes','downloads'])->get()
            ]);
        });
        
        view()->composer([$activeTemplate.'frontend.categoryPhotos',], function ($view) {
            $view->with([
                'categories'           => \App\Category::whereStatus(1)->latest()->inRandomOrder()->with('images')->get(),
               
            ]);
        });
        view()->composer($activeTemplate.'sections.pricing', function ($view) {
            $view->with([
                'subscriptions'  => \App\Subscription::where('status',1)->get(),

            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = \App\Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

    }
}
