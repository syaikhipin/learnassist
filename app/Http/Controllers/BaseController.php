<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\SubscriptionPlan;
use App\Models\TimeLog;
use App\Models\User;
use App\Models\Workspace;
use App\Supports\DataHandler;
use App\Supports\InstallSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class BaseController extends Controller
{
    protected $base_url;
    protected $user;
    protected $user_id;
    protected $workspace_id = 0;
    protected $workspace = null;
    protected $users;
    protected $settings;
    protected $super_settings;
    protected $saas = false;
    protected $active_plan = null;
    protected $active_plan_modules = [];
    protected $timer = false;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $base_url = config('app.url') ?? $request->getSchemeAndHttpHost();
            $this->base_url = $base_url;

            $this->user = Auth::user();
            $this->super_settings = settings_loader(1);
            if ($this->user) {
                App::setLocale($this->user->language ?? $this->super_settings['language'] ?? 'en');
                $user_id = $this->user->id;
                $this->user_id = $user_id;
                $this->workspace_id = $this->user->workspace_id;
                View::share('user', $this->user);
                $this->users = User::getForWorkspace($this->workspace_id);
                View::share('users', $this->users);
                $this->settings = settings_loader($this->workspace_id);
                View::share('settings', $this->settings);
                $this->workspace = Workspace::find($this->workspace_id);

                if($this->workspace->plan_id)
                {
                    $this->active_plan = SubscriptionPlan::find($this->workspace->plan_id);
                }
                else
                {
                    $this->active_plan = SubscriptionPlan::where('is_default', 1)->first();
                }

                if($this->active_plan)
                {
                    $this->active_plan_modules = $this->active_plan->modules;
                }

                View::share('active_plan_modules', $this->active_plan_modules);

                if($this->workspace->is_on_free_trial && $this->workspace_id !== 1)
                {
                    $free_trial_days = $this->super_settings['free_trial_days'] ?? \config('app.free_trial_days') ?? 30;

                    $workspace_created_at = $this->workspace->created_at;

                    $free_trial_ends_at = Carbon::parse($workspace_created_at)->addDays($free_trial_days);

                    if(Carbon::now()->gt($free_trial_ends_at))
                    {
                        $excludedRoutes = [
                            'app.billing',
                            'app.payment-stripe',
                            'app.stripe-create-payment-intent',
                            'app.subscribe',
                            'app.validate-paypal-subscription',
                        ];

                        $routeName = Route::currentRouteName();
                        if(!in_array($routeName, $excludedRoutes))
                        {
                            // free trial ends, redirect to payment page
                            header('Location: ' . $this->base_url . '/app/billing?show_trial_ended_message=1');
                            exit;
                        }

                    }
                }

                View::share('active_plan', $this->active_plan);

                $this->timer = TimeLog::where('workspace_id', $this->workspace_id)
                    ->where('user_id', $user_id)
                    ->where('is_running', '1')
                    ->first();

                $duration = 0;
                if($this->timer)
                {
                    $timer_started_at = $this->timer->timer_started_at;
                    $duration = Carbon::parse($timer_started_at)->diffInSeconds(Carbon::now());
                }

                View::share('timer', $this->timer);
                View::share('duration', $duration);

            }
            else{
                App::setLocale($this->super_settings['language'] ?? 'en');
            }

            $app_version = config('app.version');
            $database_version = $this->super_settings['database_version'] ?? '1.0.0';

            if(version_compare($app_version, $database_version, '>'))
            {
                InstallSupport::updateSchema();
            }

            View::share('super_settings', $this->super_settings);
            View::share('base_url', $this->base_url);
            $this->saas = true;
            View::share('saas', $this->saas);



            View::share('workspace', $this->workspace);

            return $next($request);
        });
    }

    /**
     * Purify data
     * @param $data
     * @return mixed
     */
    protected function purify($data)
    {
        return (new DataHandler($data))->purify()
            ->all();
    }

    protected function authCheck()
    {
        if (!$this->user) {
            header('Location: ' . $this->base_url . '/app/login');
            exit;
        }
    }

    protected function moduleCheck($module)
    {
        if(!hasPlanModule($this->workspace,$this->active_plan_modules,$module))
        {
            session()->flash('error', __('You are not authorized to access this module.'));
            header('Location: ' . $this->base_url . '/app/dashboard');
            exit;
        }
    }

    protected function isSuperAdmin()
    {
        if($this->user && $this->user->is_super_admin)
        {
            return;
        }
        header('Location: ' . $this->base_url . '/super-admin/login');
        exit;
    }

    protected function apiCheck($api_key)
    {
        $api_key_object = ApiKey::where('key', $api_key)
            ->first();
        if ($api_key_object) {
            $this->user_id = $api_key_object->user_id;
            $this->user = User::find($this->user_id);
            $this->workspace_id = $this->user->workspace_id;
            $this->users = User::getForWorkspace($this->workspace_id);
            $this->settings = settings_loader($this->workspace_id);
        } else {
            api_response([
                'status' => 'error',
                'message' => 'Invalid API Key'
            ], 401);
        }
    }

    protected function isDemo()
    {
        if (config('app.env') === 'demo') {
            return true;
        }
        return false;
    }

    protected function isSaaS()
    {
        return true;
    }
}
