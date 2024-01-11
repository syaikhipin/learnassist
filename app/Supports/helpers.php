<?php

use App\Models\Activity;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Akaunting\Money\Money;
function formatCurrency($amount, $isoCode, $remove_trailing_zeros = false)
{
    if (!$amount) {
        return $amount;
    }
    $decimalPoint = currency($isoCode)->getDecimalMark();

    if ($decimalPoint == ",") {
        $amount = str_replace(".", ",", $amount);
    }

    $result = Money::$isoCode($amount, true)->format();

    if ($remove_trailing_zeros) {
        $result = rtrim($result, '0');
        $result = rtrim($result, $decimalPoint);
    }

    return $result;

}
function getWorkspaceCurrency($settings)
{
    return $settings['currency'] ?? config('app.currency') ?? 'USD';
}

function check_db_connection($database_host, $database_name, $database_username, $database_password): bool|array
{
    try {
        new pdo(
            "mysql:host=$database_host;dbname=$database_name",
            "$database_username",
            "$database_password",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        return true;
    } catch (PDOException $e) {
        return [
            'error' => $e->getMessage(),
        ];
    }
}

function simple_template($content, array $data = []): string
{
    foreach ($data as $key => $value) {
        $content = str_replace('{{' . $key . '}}', $value, $content);
    }
    return $content;
}

function edit_env_file($values): void
{
    $path = base_path(".env");
    if (file_exists($path)) {
        $content = file_get_contents($path);

        foreach ($values as $key => $value) {
            $value = '\'' . trim($value) . '\'';
            $content = str_replace(
                $key . '=\'' . env($key) . '\'',
                $key . '=' . $value,
                $content
            );
        }

        file_put_contents($path, $content);

    }
}

function create_model($model)
{
    $table = Str::snake(Str::pluralStudly($model));
    $class = new class() extends Illuminate\Database\Eloquent\Model {};
    $class->setTable($table);
    return $class;
}

function app_clean_html_content($content)
{
    //remove script tags
    $content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content);
    //remove style tags
    $content = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $content);

    return clean($content);


}

function settings_loader($workspace_id)
{
    $settings = DB::table('settings')
        ->where('workspace_id', $workspace_id)
        ->get();

    $s = [];
    foreach ($settings as $setting) {
        $s[$setting->key] = $setting->value;
    }

    return $s;
}

function update_settings($workspace_id, $settings, $create = false)
{
    foreach ($settings as $k => $v) {
        if (!$v) {
            $v = '';
        }
        $s = Setting::where('workspace_id', $workspace_id)
            ->where('key', $k)
            ->first();
        if ($s) {
            $s->value = $v;
            $s->save();
        } else {
            if ($create) {
                $s = new Setting();
                $s->workspace_id = $workspace_id;
                $s->key = $k;
                $s->value = $v;
                $s->save();
            }
        }
    }
}

function remove_setting($workspace_id, $key)
{
    $s = Setting::where('workspace_id', $workspace_id)
        ->where('key', $key)
        ->first();
    if ($s) {
        $s->delete();
    }
}

function api_response($data = [], $status = 200)
{
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}

function add_activity($workspace_id, $description, $user_id = 0)
{
    $activity = new Activity();
    $activity->workspace_id = $workspace_id;
    $activity->user_id = $user_id;
    $activity->description = $description;
    $activity->save();
}

function get_client_ip()
{
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $client_ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

        return $client_ips[0];
    }

    if (isset($_SERVER['HTTP_X_FORWARDED'])) {
        return $_SERVER['HTTP_X_FORWARDED'];
    }

    if (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    }

    if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_FORWARDED_FOR'];
    }

    if (isset($_SERVER['HTTP_FORWARDED'])) {
        return $_SERVER['HTTP_FORWARDED'];
    }

    if (isset($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];
    }

    return '0.0.0.0';
}


function createFromCurrency($number, $currency_iso_code)
{
    $currency = currency($currency_iso_code);
    $dec_point = $currency->getDecimalMark();
    $currency_symbol = $currency->getSymbol();
    $number = str_replace($currency_symbol, '', $number);
    $number = str_replace(' ', '', $number);
    if ($dec_point == ',') {
        $number = str_replace('.', '', $number);
        $number = str_replace(',', '.', $number);
    } else {
        $number = str_replace(',', '', $number);
    }

    return (float) $number;
}

function onLight($name)
{
    $path = base_path('/lights/'.config('app.uid').'/'.$name.'.php');
    if (file_exists($path)) {
        return include $path;
    }
    return [];
}

function lightExists($path)
{
    $path = base_path('lights/'.config('app.uid').'/'.$path);
    if (file_exists($path)) {
        return true;
    }
    return false;
}

function lightPath($path)
{
    return base_path('/lights/'.config('app.uid').'/'.$path);
}

function app_l_validate($base_url,$l)
{
    return (md5(config('app.item_id').$base_url) == $l);
}

function app_html_to_text($html, $length = 0) {
    // Strip HTML tags
    $text = strip_tags($html);

    // Convert HTML entities to their corresponding characters
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

    // Replace multiple spaces with single space
    $text = preg_replace('/\s+/', ' ', $text);

    // Trim the text
    $text = trim($text);

    if($length > 0)
    {
        $text = substr($text, 0, $length);
    }

    return $text;
}

function app_clean_input($input){
    $input = clean($input);
    return app_html_to_text($input);
}

function setEnvironmentValue($envKey, $envValue)
{
    $path = base_path('.env');

    if(is_bool(env($envKey)))
    {
        $envValue = env($envKey) ? 'true' : 'false';
    }

    if(file_exists($path))
    {
        // Make sure we are dealing with string to escape any special characters
        $envValue = is_string($envValue) ? "'$envValue'" : $envValue;

        // Get the current .env content
        $envContent = file_get_contents($path);

        // If the key exists already, replace the existing value
        if(preg_match("/^{$envKey}=.*$/m", $envContent))
        {
            file_put_contents($path, preg_replace(
                "/^{$envKey}=.*$/m",
                "{$envKey}={$envValue}",
                $envContent
            ));
        }
        // If the key does not exist, add it to the end of the file
        else
        {
            file_put_contents($path, $envContent . PHP_EOL . "{$envKey}={$envValue}");
        }
    }
}


function setEnvironmentValues($values)
{
    foreach ($values as $key => $value) {
        setEnvironmentValue($key, $value);
    }
}

function getUploadsUrl()
{
    if(config('filesystems.disks.uploads.driver') == 'local')
    {
        return config('app.url').'/uploads';
    }
    return config('filesystems.disks.uploads.endpoint').'/'.config('filesystems.disks.uploads.bucket');
}

function hasPlanModule($workspace, $active_plan_modules, $module)
{
    if($workspace->id == 1)
    {
        return true;
    }
    if(in_array($module, $active_plan_modules))
    {
        return true;
    }
    return false;
}

