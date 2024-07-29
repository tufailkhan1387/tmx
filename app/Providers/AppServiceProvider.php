<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // validation for alpha and space
        Validator::extend('alpha_space', function ($attribute, $value, $parameters, $validator) {
            // Check if the value contains only alphabets and spaces
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        Validator::replacer('alpha_space', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, ':attribute must contain only alphabets and spaces.');
        });

        // Use a view composer to share notifications with the 'layout.navbar' view
        View::composer('layout.navbar', function ($view) {
            if (Auth::check()) {
                $notifications = Notification::where('user_id', Auth::id())
                                             ->where('is_read', 0)
                                             ->orderBy('created_at', 'desc')
                                             ->get();
                                             
                $view->with('notifications', $notifications);
            }
        });
    }
}
