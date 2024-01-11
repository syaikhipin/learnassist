<?php

use App\Http\Controllers\GoalStatusController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

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


if(file_exists(storage_path('app/installed.php')))
{

    Route::get('/', [AppController::class, 'index'])->name('app.index');
    Route::get('/s/{hare}', [AppController::class, 'share'])->name('app.share'); # Quick share short link
    Route::get('/privacy-policy', [AppController::class, 'privacyPolicy'])->name('app.privacy-policy');
    Route::get('/terms-of-service', [AppController::class, 'termsOfService'])->name('app.terms-of-service');
    Route::prefix('app')->group(function () {
        Route::get('/view/{item}/{uuid}', [AppController::class, 'viewItem'])->name('app.view-item');

        Route::get('/login', [AppController::class, 'login'])->name('app.login');

        Route::post('/login', [AppController::class, 'loginPost'])->name('app.login-post');
        Route::get('/forgot-password', [AppController::class, 'forgotPassword'])->name('app.forgot-password');
        Route::post('/forgot-password', [AppController::class, 'forgotPasswordPost'])->name('app.forgot-password-post');
        Route::get('/password-reset', [AppController::class, 'passwordReset'])->name('app.password-reset');
        Route::post('/password-reset', [AppController::class, 'passwordResetPost'])->name('app.password-reset-post');
        Route::post('/login', [AppController::class, 'loginPost'])->name('app.login-post');
        Route::get('/logout', [AppController::class, 'logout'])->name('app.logout');
        Route::get('/automatic-login/{type}', [AppController::class, 'automaticLogin'])->name('app.automatic-login');
        Route::get('/dashboard', [AppController::class, 'dashboard'])->name('app.dashboard');
        Route::get('/app-modal/{type}', [AppController::class, 'appModal'])->name('app.app-modal');
        Route::get('/documents', [AppController::class, 'documents'])->name('app.documents');
        Route::get('/document', [AppController::class, 'document'])->name('app.document');
        Route::get('/load-document', [AppController::class, 'loadDocument'])->name('app.load-document');
        Route::post('/ask-ai-to-write', [AppController::class, 'askAiToWrite'])->name('app.ask-ai-to-write');
        Route::get('/download-document', [AppController::class, 'downloadDocument'])->name('app.download-document');
        Route::post('/save-document', [AppController::class, 'saveDocument'])->name('app.save-document');
        Route::post('/upload-document-image', [AppController::class, 'uploadDocumentImage'])->name('app.upload-document-image');
        Route::get('/spreadsheets', [AppController::class, 'spreadsheets'])->name('app.spreadsheets');
        Route::get('/presentations', [AppController::class, 'presentations'])->name('app.presentations');
        Route::get('/calendar', [AppController::class, 'calendar'])->name('app.calendar');
        Route::get('/calendar-events', [AppController::class, 'calendarEvents'])->name('app.calendar-events');
        Route::post('/save-event', [AppController::class, 'saveEvent'])->name('app.save-event');
        Route::get('/address-book', [AppController::class, 'addressBook'])->name('app.address-book');
        Route::get('/contact', [AppController::class, 'contact'])->name('app.contact');
        Route::post('/contact', [AppController::class, 'saveContact'])->name('app.save-contact');
        Route::get('/digital-assets', [AppController::class, 'digitalAssets'])->name('app.digital-assets');
        Route::get('/view-file/{uuid}', [AppController::class, 'viewFile'])->name('app.view-file');
        Route::get('/download-media-file/{uuid}', [AppController::class, 'downloadMediaFile'])->name('app.download-media-file');
        Route::get('/download-shared-file/{uuid}', [AppController::class, 'downloadSharedFile'])->name('app.download-shared-file');
        Route::post('/save-upload', [AppController::class, 'saveUpload'])->name('app.save-upload');
        Route::get('/images', [AppController::class, 'images'])->name('app.images');
        Route::get('/image-editor/{uuid}', [AppController::class, 'imageEditor'])->name('app.image-editor');
        Route::get('/quick-share', [AppController::class, 'quickShare'])->name('app.quick-share');
        Route::post('/save-quick-share', [AppController::class, 'saveQuickShare'])->name('app.save-quick-share');
        Route::post('/create-quick-share', [AppController::class, 'createQuickShare'])->name('app.create-quick-share');
        Route::get('/view-share/{uuid}', [AppController::class, 'viewShare'])->name('app.view-share');
        Route::get('/settings', [AppController::class, 'settings'])->name('app.settings');
        Route::post('/settings', [AppController::class, 'saveSettings'])->name('app.save-settings');
        Route::get('/delete/{item}/{uuid}', [AppController::class, 'deleteItem'])->name('app.delete-item');
        Route::get('/logout', [AppController::class, 'logout'])->name('app.logout');
        Route::get('/user/{user}', [AppController::class, 'user'])->name('app.user');
        Route::post('/user', [AppController::class, 'saveUser'])->name('app.save-user');
        Route::get('/manage-api/{uuid}', [AppController::class, 'manageApi'])->name('app.manage-api');
        Route::get('/ckeditor-cloud-token', [AppController::class, 'ckeditorCloudToken'])->name('app.ckeditor-cloud-token');
        Route::get('/flashcards/{action?}', [AppController::class, 'flashcards'])->name('app.flashcards');
        Route::post('/flashcards/{action}', [AppController::class, 'saveFlashcard'])->name('app.save-flashcard');
        Route::get('/ai-prompts/{action?}', [AppController::class, 'aiPrompts'])->name('app.ai-prompts');
        Route::post('/ai-prompts/{action}', [AppController::class, 'saveAiPrompts'])->name('app.save-ai-prompts');
        Route::get('/document', [AppController::class, 'document'])->name('app.document');
        Route::get('/billing', [AppController::class, 'billing'])->name('app.billing');
        Route::any('/payment-stripe', [AppController::class, 'paymentStripe'])->name('app.payment-stripe');
        Route::any('/stripe-create-payment-intent', [AppController::class, 'stripeCreatePaymentIntent'])->name('app.stripe-create-payment-intent');
        Route::get('/subscribe/{uuid}', [AppController::class, 'subscribe'])->name('app.subscribe');
        Route::get('/validate-paypal-subscription', [AppController::class, 'validatePaypalSubscription'])->name('app.validate-paypal-subscription');

        Route::get('/todos', [AppController::class,'todos'])->name('app.todos');
        Route::get('/view-todo', [AppController::class,'viewTodo']);
        Route::get('/add-task', [AppController::class,'addTask']);
        Route::post('/save-todos', [AppController::class,'todoPost']);

        Route::get("/assignments", [AppController::class, "projects"]);
        Route::get("/assignment-list", [AppController::class, "projectList"]);
        Route::get("/create-assignment", [AppController::class, "createProject"]);
        Route::get("/view-assignment", [AppController::class, "projectView"]);
        Route::get("/view-assignment-tasks", [AppController::class, "projectTasksView"]);
        Route::get("/view-assignment-discussions", [AppController::class, "projectDiscussionsView"]);
        Route::get("/view-assignment-resources", [AppController::class, "projectResourcesView"]);
        Route::post("/save-project", [AppController::class, "projectPost"]);
        Route::post("/save-project-message", [
            AppController::class,
            "projectMessagePost",]);

        Route::post("/assignments/{action}", [
            AppController::class,
            "assignmentsAction",]);

        Route::get('/view-contact', [AppController::class,'viewContact']);

        Route::get('/ai-chat', [AppController::class,'aiChat']);
        Route::post('/chat', [AppController::class, 'chat']);
        Route::post('/save-project-task', [AppController::class,'ProjectTaskPost']);

        Route::get('/goal-categories', [AppController::class,'goalCategories']);

        Route::get('/new-studygoal-category', [AppController::class,'newStudygoalCategory']);
        Route::post('/save-studygoal-category', [AppController::class, 'saveStudygoalCategory']);
        Route::get('/studygoals', [AppController::class,'studygoals']);
        Route::get('/category-edit', [AppController::class,'categoryEdit']);
        Route::get('/get-study-goal-category', [AppController::class,'getStudyGoalCategory']);

        Route::get('/view-studygoal', [AppController::class,'viewStudygoal']);
        Route::get('/new-studygoal', [AppController::class,'newStudygoal']);
        Route::post('/save-studygoal', [AppController::class, 'saveStudygoal']);

        Route::post('/todos/{action}',[TodoController::class,'store']);
        Route::post('/goals/{action}',[GoalStatusController::class,'store']);
        Route::post('/ai-generate-image', [AppController::class,'aiGenerateImage']);

        Route::get('/handle-timer', [AppController::class,'handleTimer']);

    });

    Route::get('/system/{action}', [SystemController::class, 'actions']);
    Route::post('/system/{action}', [SystemController::class, 'actionsStore']);


    Route::get('/signup', [AppController::class, 'signup'])->name('signup');
    Route::post('/signup', [AppController::class, 'signupPost'])->name('signup-post');
    Route::prefix('super-admin')->group(function () {
        Route::get('/', [SuperAdminController::class, 'index'])->name('super-admin.index');
        Route::get('/login', [SuperAdminController::class, 'login'])->name('super-admin.login');
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('super-admin.dashboard');
        Route::get('/workspaces', [SuperAdminController::class, 'workspaces'])->name('super-admin.workspaces');
        Route::get('/users', [SuperAdminController::class, 'users'])->name('super-admin.users');
        Route::get('/payments', [SuperAdminController::class, 'payments'])->name('super-admin.payments');
        Route::get('/files', [SuperAdminController::class, 'files'])->name('super-admin.files');
        Route::get('/landing-page', [SuperAdminController::class, 'landingPage'])->name('super-admin.landing-page');
        Route::get('/post-editor/{uuid}', [SuperAdminController::class, 'postEditor'])->name('super-admin.post-editor');
        Route::get('/go-to/{where}', [SuperAdminController::class, 'goTo'])->name('super-admin.go-to');
        Route::get('/subscription-plans', [SuperAdminController::class, 'subscriptionPlans'])->name('super-admin.subscription-plans');
        Route::get('/subscription-plan', [SuperAdminController::class, 'subscriptionPlan'])->name('super-admin.subscription-plan');
        Route::post('/subscription-plan', [SuperAdminController::class, 'saveSubscriptionPlan'])->name('super-admin.save-subscription-plan');
        Route::post('/payment-gateway', [SuperAdminController::class, 'savePaymentGateway'])->name('super-admin.save-payment-gateway');
        Route::post('/save-post', [SuperAdminController::class, 'savePost'])->name('super-admin.save-post');
        Route::post('/save-user', [SuperAdminController::class, 'saveUser'])->name('super-admin.save-user');
        Route::get('/reports/{type}', [SuperAdminController::class, 'reports'])->name('super-admin.reports');
        Route::get('/settings', [SuperAdminController::class, 'settings'])->name('super-admin.settings');
        Route::get('/delete/{item}/{uuid}', [SuperAdminController::class, 'deleteItem'])->name('super-admin.delete-item');
        Route::get('/view-user/{uuid}', [SuperAdminController::class, 'viewUser'])->name('super-admin.view-user');
        Route::get('/view-workspace/{uuid}', [SuperAdminController::class, 'viewWorkspace'])->name('super-admin.view-workspace');
        Route::post('/save-post-content', [SuperAdminController::class, 'savePostContent'])->name('super-admin.save-post-content');
        Route::get('/system-api/{action}', [SuperAdminController::class, 'systemApi']);
    });

}
else
{
    //set app debug to true
    config(['app.debug' => true]);
    Route::get('/', [InstallController::class, 'install']);
    Route::post('/save-database-info', [InstallController::class, 'saveDatabaseInfo']);
    Route::post('/create-database-tables', [InstallController::class, 'createDatabaseTables']);
    Route::post('/save-primary-data', [InstallController::class, 'savePrimaryData']);
}

Route::get('/app-refresh',[InstallController::class,'appRefresh']);


