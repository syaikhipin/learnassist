<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\SystemApi;
use App\Models\User;
use App\Supports\InstallSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SystemController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function validateSystemApi($request)
    {
        // To enable this feature, you need to generate a system api key from Super Admin -> Settings -> About
        $system_api = SystemApi::first();

        if(!$system_api)
        {
            abort(403);
        }

        $system_api_key = $system_api->api_key;
        $provided_api_key = $request->input('api_key');

        if(empty($provided_api_key))
        {
            abort(403);
        }

        if($system_api_key !== $provided_api_key)
        {
            abort(403);
        }
    }

    public function actionsStore(Request $request, $action)
    {
        switch ($action)
        {
            case 'lc-check':

                $license_key = $this->super_settings['l_key'] ?? null;

                if(!$license_key)
                {
                    return response([
                        'success' => false,
                        'action' => 'require-license-key',
                    ]);
                }

                if(app_l_validate($this->base_url,$license_key))
                {
                    return response([
                        'success' => true,
                    ]);
                }

                return response([
                    'success' => true,
                ]);

                break;

            case 'download-update':

                $this->validateSystemApi($request);

                $validator = Validator::make($request->all(), [
                    'download_url' => 'required|url',
                ]);

                if($validator->fails())
                {
                    return response([
                        'success' => false,
                        'errors' => $validator->errors(),
                    ]);
                }

                $download_url = $request->download_url;

                try {

                    $file_name = 'update-'.Str::random(10).'.zip';
                    update_settings(1,[
                        'update_file_name' => $file_name,
                    ],true);

                    $response = Http::get($download_url);

                    if ($response->successful()) {
                        Storage::put($file_name, $response->body());
                    }

                    return response([
                        'success' => true,
                    ]);

                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }

                break;

            case 'unzip-update':

                $this->validateSystemApi($request);

                $file_name = $this->super_settings['update_file_name'] ?? null;

                $file_path = storage_path('app/'.$file_name);

                if(!empty($file_name) && file_exists($file_path))
                {
                    try {

                        $zip = new \ZipArchive();
                        $res = $zip->open(storage_path('app/'.$file_name));
                        if ($res === TRUE) {
                            $zip->extractTo(base_path());
                            $zip->close();
                        }

                        return response([
                            'success' => true,
                        ]);

                    } catch (\Exception $e) {
                        Log::error($e->getMessage());
                    }
                }

                break;

                case 'cleanup-update':

                    $this->validateSystemApi($request);

                    $file_name = $this->super_settings['update_file_name'] ?? null;

                    $file_path = storage_path('app/'.$file_name);

                    if(!empty($file_name) && file_exists($file_path))
                    {
                        try {

                            unlink($file_path);

                            remove_setting(1,'update_file_name');

                            return response([
                                'success' => true,
                            ]);

                        } catch (\Exception $e) {
                            Log::error($e->getMessage());
                        }
                    }

                    break;

        }
    }

    public function actions(Request $request, $action)
    {

        switch ($action)
        {
            case 'activate':

                $this->authCheck();

                $response = Http::withOptions([
                    'verify' => false,
                ])->post('https://www.cloudonex.com/api/app-registration-flow',[
                    'item_id' => config('app.item_id'),
                    'uid' => config('app.uid'),
                    'first_name' => $this->user->first_name,
                    'last_name' => $this->user->last_name,
                    'ip' => get_client_ip(),
                    'email' => $this->user->email,
                    'url' => $this->base_url,
                ])->json();

                if(!empty($response['url']))
                {
                    return redirect($response['url']);
                }

                return redirect()->back()->with('error',__('Something went wrong! Please try again later!'));

                break;

            case 'set-license-key':

                $this->authCheck();

                $request->validate([
                    'license_key' => 'required',
                ]);

                $license_key = $request->license_key;

                update_settings(1,[
                    'l_key' => $license_key,
                ],true);

                return redirect($this->base_url.'/super-admin/dashboard')->with('success',__('License key updated successfully!'));


                break;

            case 'update-schema':

                InstallSupport::updateSchema();

                break;

            case 'login-as-system-admin':

                $this->validateSystemApi($request);

                $user = User::where('is_super_admin',true)->first();

                if(!$user)
                {
                    abort(403);
                }

                auth()->login($user);

                break;
        }

    }

}
