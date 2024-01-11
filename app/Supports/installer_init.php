<?php
if (!defined("LARAVEL_START")) {
    exit();
}

// Check if PHP version is below 8.1
if (version_compare(PHP_VERSION, '8.1.0', '<')) {
    exit("PHP version must be 8.1 or higher");
}

if (!is_writable(getcwd())) {
    exit("Unable to create .env file on- " . getcwd());
}

function envFileContent()
{
    $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';

    //Check if Cloudflare is enabled
    if (isset($_SERVER['HTTP_CF_VISITOR'])) {
        $scheme = 'https';
    }

    if($scheme !== 'https')
    {
        exit("Note secure! Please load the installer using https:// to install this app.");
    }

    try{

        $app_url = $scheme.'://' . $_SERVER['HTTP_HOST'];

    }catch (\Exception $e){
        $app_url = 'http://localhost';
    }

    return 'APP_NAME=CloudOnex
APP_ENV=production
APP_KEY=base64:xFdMvSpKeDGnmW75lMNZqvOBDBvvUZ5lcGPLanB0MTw=
APP_DEBUG=false
APP_URL=\'' . $app_url . '\'

LOCALE=en
DATE_FORMAT=\'M d Y\'
DATE_TIME_FORMAT=\'M d Y h:i A\'

CURRENCY=USD

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=\'localhost\'
DB_PORT=3306
DB_DATABASE=\'empweru\'
DB_USERNAME=\'root\'
DB_PASSWORD=\'root\'

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120


MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

UPLOADS_DRIVER=\'local\'
UPLOADS_ACCESS_KEY_ID=\'\'
UPLOADS_SECRET_ACCESS_KEY=\'\'
UPLOADS_DEFAULT_REGION=\'\'
UPLOADS_BUCKET=\'\'
UPLOADS_USE_PATH_STYLE_ENDPOINT=false
UPLOADS_URL=\'\'
UPLOADS_ENDPOINT=\'\'

';
}

function htaccessContent()
{
    return '<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On
    RewriteBase /

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ /index.php [L]
</IfModule>

# Disable index view
Options -Indexes

# Hide .env file
<Files .env>
    Order allow,deny
    Deny from all
</Files>
';
}

if(!is_file('.env')){
    file_put_contents('.env', envFileContent());
}

if(!is_file('.htaccess')){
    file_put_contents('.htaccess', htaccessContent());
}
else{
    $htaccess = file_get_contents('.htaccess');
    if(!str_contains($htaccess, 'RewriteEngine On')){
        file_put_contents('.htaccess', htaccessContent(), FILE_APPEND);
    }
}
