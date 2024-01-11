<?php
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OfficeController::class, 'index'])->name('office.index');
Route::get('/s/{hare}', [OfficeController::class, 'share'])->name('office.share'); # Quick share short link
Route::get('/privacy-policy', [OfficeController::class, 'privacyPolicy'])->name('office.privacy-policy');
Route::get('/terms-of-service', [OfficeController::class, 'termsOfService'])->name('office.terms-of-service');
Route::prefix('office')->group(function () {
    Route::get('/view/{item}/{uuid}', [OfficeController::class, 'viewItem'])->name('office.view-item');

    Route::get('/login', [OfficeController::class, 'login'])->name('office.login');

    Route::post('/login', [OfficeController::class, 'loginPost'])->name('office.login-post');
    Route::get('/forgot-password', [OfficeController::class, 'forgotPassword'])->name('office.forgot-password');
    Route::post('/forgot-password', [OfficeController::class, 'forgotPasswordPost'])->name('office.forgot-password-post');
    Route::get('/password-reset', [OfficeController::class, 'passwordReset'])->name('office.password-reset');
    Route::post('/password-reset', [OfficeController::class, 'passwordResetPost'])->name('office.password-reset-post');
    Route::post('/login', [OfficeController::class, 'loginPost'])->name('office.login-post');
    Route::get('/logout', [OfficeController::class, 'logout'])->name('office.logout');
    Route::get('/automatic-login/{type}', [OfficeController::class, 'automaticLogin'])->name('office.automatic-login');
    Route::get('/dashboard', [OfficeController::class, 'dashboard'])->name('office.dashboard');
    Route::get('/app-modal/{type}', [OfficeController::class, 'appModal'])->name('office.app-modal');
    Route::get('/documents', [OfficeController::class, 'documents'])->name('office.documents');
    Route::get('/document', [OfficeController::class, 'document'])->name('office.document');
    Route::get('/load-document', [OfficeController::class, 'loadDocument'])->name('office.load-document');
    Route::post('/ask-ai-to-write', [OfficeController::class, 'askAiToWrite'])->name('office.ask-ai-to-write');
    Route::get('/download-document', [OfficeController::class, 'downloadDocument'])->name('office.download-document');
    Route::post('/save-document', [OfficeController::class, 'saveDocument'])->name('office.save-document');
    Route::post('/upload-document-image', [OfficeController::class, 'uploadDocumentImage'])->name('office.upload-document-image');
    Route::get('/spreadsheets', [OfficeController::class, 'spreadsheets'])->name('office.spreadsheets');
    Route::get('/presentations', [OfficeController::class, 'presentations'])->name('office.presentations');
    Route::get('/calendar', [OfficeController::class, 'calendar'])->name('office.calendar');
    Route::get('/calendar-events', [OfficeController::class, 'calendarEvents'])->name('office.calendar-events');
    Route::post('/save-event', [OfficeController::class, 'saveEvent'])->name('office.save-event');
    Route::get('/address-book', [OfficeController::class, 'addressBook'])->name('office.address-book');
    Route::get('/contact', [OfficeController::class, 'contact'])->name('office.contact');
    Route::post('/contact', [OfficeController::class, 'saveContact'])->name('office.save-contact');
    Route::get('/digital-assets', [OfficeController::class, 'digitalAssets'])->name('office.digital-assets');
    Route::get('/view-file/{uuid}', [OfficeController::class, 'viewFile'])->name('office.view-file');
    Route::get('/download-media-file/{uuid}', [OfficeController::class, 'downloadMediaFile'])->name('office.download-media-file');
    Route::get('/download-shared-file/{uuid}', [OfficeController::class, 'downloadSharedFile'])->name('office.download-shared-file');
    Route::post('/save-upload', [OfficeController::class, 'saveUpload'])->name('office.save-upload');
    Route::get('/images', [OfficeController::class, 'images'])->name('office.images');
    Route::get('/image-editor/{uuid}', [OfficeController::class, 'imageEditor'])->name('office.image-editor');
    Route::get('/quick-share', [OfficeController::class, 'quickShare'])->name('office.quick-share');
    Route::post('/save-quick-share', [OfficeController::class, 'saveQuickShare'])->name('office.save-quick-share');
    Route::post('/create-quick-share', [OfficeController::class, 'createQuickShare'])->name('office.create-quick-share');
    Route::get('/view-share/{uuid}', [OfficeController::class, 'viewShare'])->name('office.view-share');
    Route::get('/settings', [OfficeController::class, 'settings'])->name('office.settings');
    Route::post('/settings', [OfficeController::class, 'saveSettings'])->name('office.save-settings');
    Route::get('/delete/{item}/{uuid}', [OfficeController::class, 'deleteItem'])->name('office.delete-item');
    Route::get('/logout', [OfficeController::class, 'logout'])->name('office.logout');
    Route::get('/user/{user}', [OfficeController::class, 'user'])->name('office.user');
    Route::post('/user', [OfficeController::class, 'saveUser'])->name('office.save-user');
    Route::get('/manage-api/{uuid}', [OfficeController::class, 'manageApi'])->name('office.manage-api');
    Route::get('/ckeditor-cloud-token', [OfficeController::class, 'ckeditorCloudToken'])->name('office.ckeditor-cloud-token');
    Route::get('/flashcards/{action?}', [OfficeController::class, 'flashcards'])->name('office.flashcards');
    Route::post('/flashcards/{action}', [OfficeController::class, 'saveFlashcard'])->name('office.save-flashcard');
    Route::get('/ai-prompts/{action?}', [OfficeController::class, 'aiPrompts'])->name('office.ai-prompts');
    Route::post('/ai-prompts/{action}', [OfficeController::class, 'saveAiPrompts'])->name('office.save-ai-prompts');
    Route::get('/document', [OfficeController::class, 'document'])->name('office.document');
    Route::get('/billing', [OfficeController::class, 'billing'])->name('office.billing');
    Route::post('/payment-stripe', [OfficeController::class, 'paymentStripe'])->name('office.payment-stripe');
    Route::get('/subscribe/{uuid}', [OfficeController::class, 'subscribe'])->name('office.subscribe');
    Route::get('/validate-paypal-subscription', [OfficeController::class, 'validatePaypalSubscription'])->name('office.validate-paypal-subscription');

    Route::get('/todos', [OfficeController::class,'todos'])->name('office.todos');
    Route::get('/view-todo', [OfficeController::class,'viewTodo']);
    Route::get('/add-task', [OfficeController::class,'addTask']);
    Route::post('/save-todos', [OfficeController::class,'todoPost']);


    Route::get("/projects", [OfficeController::class, "projects"]);
    Route::get("/project-list", [OfficeController::class, "projectList"]);
    Route::get("/create-project", [OfficeController::class, "createProject"]);
    Route::get("/view-project", [OfficeController::class, "projectView"]);
    Route::post("/save-project", [OfficeController::class, "projectPost"]);

    Route::get('/view-contact', [OfficeController::class,'viewContact']);

    Route::get('/ai-chat', [OfficeController::class,'aiChat']);
    Route::post('/chat', [OfficeController::class, 'chat']);
    Route::get('/ai-image', [OfficeController::class,'aiImage']);
    Route::post('/save-project-task', [OfficeController::class,'ProjectTaskPost']);

    Route::post('/todos/{action}',[TodoController::class,'store']);
    Route::post('/ai-generate-image', [OfficeController::class,'aiGenerateImage']);

});


if(lightExists('saas/routes.php'))
{
    require lightPath('saas/routes.php');
}

Route::prefix('api')->group(function () {
    Route::post('/contact', [OfficeController::class, 'apiSaveContact'])->name('api.save-contact');
});
