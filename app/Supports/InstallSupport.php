<?php
namespace App\Supports;

use App\Models\AiChat;
use App\Models\AiChatSession;
use App\Models\AiPrompt;
use App\Models\CalendarEvent;
use App\Models\Contact;
use App\Models\Document;
use App\Models\Flashcard;
use App\Models\FlashcardCollection;
use App\Models\GoalCategory;
use App\Models\MediaFile;
use App\Models\Post;
use App\Models\ProjectReply;
use App\Models\Projects;
use App\Models\ProjectTask;
use App\Models\Setting;
use App\Models\StudyGoal;
use App\Models\SubscriptionPlan;
use App\Models\TimeLog;
use App\Models\Todo;
use App\Models\TokenUse;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Workspace;
use Faker\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class InstallSupport{

    public static function createDatabaseTables()
    {
        if(!Schema::hasTable('users'))
        {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->uuid();
                $table->unsignedInteger('role_id')->default(0);
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('email');
                $table->string('password');
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('country')->nullable();
                $table->string('zip')->nullable();
                $table->string('avatar')->nullable();
                $table->string('timezone')->nullable();
                $table->string('language')->nullable();
                $table->boolean('is_super_admin')->default(false);
                $table->string('access_key', 36)->nullable();
                $table->string('password_reset_token', 36)->nullable();
                $table->boolean('is_email_verified')->default(false);
                $table->uuid('email_verification_token')->nullable();
                $table->rememberToken();
                $table->dateTime('last_login_at')->nullable();
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('settings'))
        {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->string('key');
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }


        if(!Schema::hasTable('assignment_media_relations'))
        {
            Schema::create('assignment_media_relations', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->unsignedInteger('assignment_id')->default(0);
                $table->unsignedInteger('media_id')->default(0);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('time_logs'))
        {
            Schema::create('time_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->unsignedInteger('user_id')->default(0);
                $table->unsignedInteger('project_id')->default(0);
                $table->unsignedInteger('task_id')->default(0);
                $table->unsignedInteger('timer_id')->default(0);
                $table->unsignedInteger('timer_duration')->default(0);
                $table->timestamp('timer_started_at')->nullable();
                $table->timestamp('timer_stopped_at')->nullable();
                $table->boolean('is_running')->default(false);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('activities'))
        {
            Schema::create('activities', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->unsignedInteger('user_id')->default(0);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }



        if(!Schema::hasTable('documents'))
        {
            Schema::create('documents', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->unsignedInteger('category_id')->default(0);
                $table->uuid();
                $table->unsignedInteger('user_id')->default(0);
                $table->string('type')->nullable();
                $table->string('title')->nullable();
                $table->longText('content')->nullable();
                $table->boolean('is_published')->default(false);
                $table->string('slug')->nullable();
                $table->string('access_key', 36)->nullable();
                $table->text('attachment')->nullable();
                $table->unsignedInteger('last_opened_by')->default(0);
                $table->timestamp('last_opened_at')->nullable();
                $table->timestamps();
            });
        }
        if(!Schema::hasTable('document_categories'))
        {
            Schema::create('document_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->uuid();
                $table->unsignedInteger('user_id')->default(0);
                $table->string('type')->nullable();
                $table->string('title')->nullable();
                $table->string('name');

                $table->string('slug')->nullable();
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->text('description')->nullable();
                $table->unsignedInteger('last_opened_by')->default(0);
                $table->timestamp('last_opened_at')->nullable();
                $table->timestamps();
            });
        }
        if(!Schema::hasTable('todos'))
        {
            Schema::create('todos', function (Blueprint $table) {
                $table->id();
                $table->uuid();
                $table->unsignedInteger('workspace_id');
                $table->string('title');
                $table->text('description')->nullable();
                $table->unsignedInteger('admin_id')->default(0);
                $table->date('date')->nullable();
                $table->string('related_to')->nullable();
                $table->enum('status',[
                    'High',
                    'Medium',
                    'Low',
                ])->default('Low');
                $table->unsignedInteger('related_id')->default(0);
                $table->boolean('completed')->default(0);
                $table->timestamps();
            });
        }
        if(!Schema::hasTable('project_tasks'))
        {
            Schema::create('project_tasks', function (Blueprint $table) {
                $table->id();
                $table->uuid();
                $table->unsignedInteger('workspace_id');
                $table->unsignedInteger('project_id')->default(0);
                $table->string('title');
                $table->text('description')->nullable();
                $table->unsignedInteger('admin_id')->default(0);

                $table->date('date')->nullable();
                $table->string('related_to')->nullable();
                $table->unsignedInteger('related_id')->default(0);
                $table->boolean('completed')->default(0);
                $table->timestamps();
            });
        }
        if(!Schema::hasTable('projects'))
        {
            Schema::create('projects', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id');
                $table->uuid('uuid');
                $table->unsignedInteger('admin_id')->default(0);
                $table->unsignedInteger('goal_id')->default(0);
                $table->string('title')->nullable();
                $table->string('budget')->nullable();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->enum('status',[
                    'Pending',
                    'Started',
                    'Finished',
                ])->default('Pending');
                $table->text('description')->nullable();
                $table->text('summary')->nullable();
                $table->longText('message')->nullable();
                $table->text('members')->nullable();
                $table->timestamps();
            });
        }


        if(!Schema::hasTable('media_files'))
        {
            Schema::create('media_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->uuid();
                $table->unsignedInteger('user_id')->default(0);
                $table->unsignedInteger('directory_id')->default(0);
                $table->unsignedInteger('size')->default(0);
                $table->unsignedSmallInteger('width')->default(0);
                $table->unsignedSmallInteger('height')->default(0);
                $table->string('folder')->nullable();
                $table->string('title')->nullable();
                $table->string('path');
                $table->string('mime_type',)->nullable();
                $table->string('extension', 10)->nullable();
                $table->text('description')->nullable();
                $table->string('access_key', 36)->nullable();
                $table->boolean('is_ai_generated')->default(0);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('calendar_events'))
        {
            Schema::create('calendar_events', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id');
                $table->uuid();
                $table->unsignedInteger('user_id')->default(0);
                $table->unsignedInteger('contact_id')->default(0);
                $table->unsignedInteger('admin_id')->default(0);
                $table->unsignedInteger('manager_id')->default(0);
                $table->unsignedInteger('address_id')->default(0);
                $table->string('title');
                $table->dateTime('start')->nullable();
                $table->dateTime('end')->nullable();
                $table->boolean('all_day')->default(false);
                $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->enum('type', ['leave', 'work','system', 'personal', 'holiday', 'other'])->default('other');
                $table->string('access_key')->nullable();
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('contacts'))
        {
            Schema::create('contacts', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->uuid();
                $table->unsignedInteger('user_id')->default(0);
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('title')->nullable();
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('country')->nullable();
                $table->string('zip')->nullable();
                $table->string('avatar')->nullable();
                $table->string('access_key', 36)->nullable();
                $table->text('notes')->nullable();
                $table->date('birthday')->nullable();
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('quick_shares'))
        {
            Schema::create('quick_shares', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->uuid();
                $table->unsignedInteger('user_id')->default(0);
                $table->unsignedInteger('contact_id')->default(0);
                $table->string('type')->nullable();
                $table->string('sub_type')->nullable();
                $table->string('title')->nullable();
                $table->string('url')->nullable();
                $table->string('path')->nullable();
                $table->string('mime_type',)->nullable();
                $table->string('extension', 10)->nullable();
                $table->unsignedInteger('size')->default(0);
                $table->longText('content')->nullable();
                $table->unsignedInteger('view_count')->default(0);
                $table->unsignedInteger('download_count')->default(0);
                $table->string('access_key')->nullable();
                $table->string('short_url_key')->nullable();
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('quick_share_access_logs'))
        {
            Schema::create('quick_share_access_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->unsignedInteger('quick_share_id')->default(0);
                $table->uuid();
                $table->unsignedInteger('user_id')->default(0);
                $table->unsignedInteger('contact_id')->default(0);
                $table->string('ip')->nullable();
                $table->string('user_agent')->nullable();
                $table->string('country')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('browser')->nullable();
                $table->string('os')->nullable();
                $table->string('device')->nullable();
                $table->string('brand')->nullable();
                $table->string('model')->nullable();
                $table->boolean('is_bot')->default(false);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('api_keys'))
        {
            Schema::create('api_keys', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->uuid();
                $table->unsignedInteger('user_id')->default(0);
                $table->string('name')->nullable();
                $table->string('key')->nullable();
                $table->string('secret')->nullable();
                $table->timestamps();
            });
        }


        if(!Schema::hasTable('workspaces'))
        {
            Schema::create('workspaces', function (Blueprint $table) {
                $table->id();
                $table->uuid();
                $table->unsignedInteger('owner_id')->default(0);
                $table->string('name')->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('is_subscribed')->default(false);
                $table->boolean('is_on_free_trial')->default(false);
                $table->unsignedInteger('free_trial_days')->default(0);
                $table->date('free_trial_ends_at')->nullable();
                $table->unsignedInteger('plan_id')->default(0);
                $table->string('plan_name')->nullable();
                $table->string('plan_type')->nullable();
                $table->string('term')->nullable();
                $table->date('subscription_start_date')->nullable();
                $table->date('next_renewal_date')->nullable();
                $table->decimal('plan_amount', 10, 2)->default(0);
                $table->string('plan_currency')->nullable();
                $table->string('plan_interval')->nullable();
                $table->unsignedInteger('plan_interval_count')->default(0);
                $table->unsignedInteger('plan_storage_space')->default(0);
                $table->unsignedInteger('plan_users')->default(0);
                $table->unsignedInteger('plan_contacts')->default(0);
                $table->boolean('is_on_grace_period')->default(false);
                $table->unsignedInteger('grace_period_days')->default(0);
                $table->date('grace_period_ends_at')->nullable();
                $table->text('modules')->nullable();
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('plans'))
        {
            Schema::create('plans', function (Blueprint $table) {
                $table->id();
                $table->uuid();
                $table->string('name')->nullable();
                $table->string('type')->nullable();
                $table->decimal('amount', 10, 2)->default(0);
                $table->string('currency')->nullable();
                $table->string('interval')->nullable();
                $table->unsignedInteger('interval_count')->default(0);
                $table->unsignedInteger('storage_space')->default(0);
                $table->unsignedInteger('users')->default(0);
                $table->unsignedInteger('contacts')->default(0);
                $table->text('modules')->nullable();
                $table->text('features')->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('is_default')->default(false);
                $table->boolean('is_free')->default(false);
                $table->boolean('is_featured')->default(false);
                $table->string('paypal_plan_id')->nullable();
                $table->string('stripe_plan_id')->nullable();
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('payment_gateways'))
        {
            Schema::create('payment_gateways', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->uuid();
                $table->string('name')->nullable();
                $table->string('api_name')->nullable();
                $table->string('type')->nullable();
                $table->string('slug')->nullable();
                $table->string('username')->nullable();
                $table->string('password')->nullable();
                $table->string('api_key')->nullable();
                $table->string('api_secret')->nullable();
                $table->string('account')->nullable();
                $table->text('description')->nullable();
                $table->text('settings')->nullable();
                $table->boolean('is_active')->default(true);
                $table->boolean('is_default')->default(false);
                $table->boolean('is_test_mode')->default(false);
                $table->unsignedInteger('order')->default(0);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->uuid();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->unsignedInteger('user_id')->default(0);
                $table->unsignedInteger('gateway_id')->default(0);
                $table->unsignedInteger('plan_id')->default(0);
                $table->date('date')->nullable();
                $table->string('transaction_id')->nullable();
                $table->string('description')->nullable();
                $table->decimal('amount', 10, 2)->default(0);
                $table->decimal('fee', 10, 2)->default(0);
                $table->decimal('tax', 10, 2)->default(0);
                $table->decimal('total', 10, 2)->default(0);
                $table->string('currency')->nullable();
                $table->string('status')->nullable();
                $table->string('type')->nullable();
                $table->string('payment_method')->nullable();
                $table->text('response')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->uuid();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->unsignedInteger('user_id')->default(0);
                $table->unsignedInteger('parent_id')->default(0);
                $table->unsignedInteger('collection_id')->default(0);
                $table->unsignedInteger('single_category_id')->default(0);
                $table->string('type', 100);
                $table->string('template', 50)->nullable();
                $table->string('header_type', 50)->nullable();
                $table->string('api_name')->nullable();
                $table->string('slug');
                $table->string('name')->nullable();
                $table->string('title');
                $table->string('seo_title')->nullable();
                $table->text('excerpt')->nullable();
                $table->text('lead_text')->nullable();
                $table->text('keywords')->nullable();
                $table->text('meta_tag')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->longText('markdown')->nullable();
                $table->longText('content')->nullable();
                $table->longText('head')->nullable();
                $table->longText('js')->nullable();
                $table->string('featured_image')->nullable();
                $table->string('featured_video')->nullable();
                $table->string('youtube_video_id')->nullable();
                $table->string('vimeo_video_id')->nullable();
                $table->string('canonical_url')->nullable();
                $table->unsignedInteger('reading_time')->default(0);
                $table->boolean('is_published')->default(0);
                $table->boolean('is_home_page')->default(0);
                $table->boolean('is_system_page')->default(0);
                $table->boolean('is_pinned')->default(0);
                $table->boolean('show_date')->default(1);
                $table->boolean('allow_comment')->default(0);
                $table->boolean('is_page')->default(0);
                $table->unsignedInteger('author_id')->default(0);
                $table->unsignedInteger('sort_order')->default(0);
                $table->unsignedInteger('item_id')->default(0);
                $table->boolean('is_cached')->default(0);
                $table->boolean('seo_index')->default(1);
                $table->json('settings')->nullable();
                $table->string('og_title')->nullable();
                $table->string('og_description')->nullable();
                $table->string('og_image')->nullable();
                $table->string('twitter_card')->nullable();
                $table->string('twitter_title')->nullable();
                $table->string('twitter_description')->nullable();
                $table->string('twitter_image')->nullable();
                $table->timestamps();

            });
        }

        if(!Schema::hasTable('subscription_plans')) {
            Schema::create('subscription_plans', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->uuid();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->unsignedDecimal('price_monthly')->nullable();
                $table->unsignedDecimal('price_yearly')->nullable();
                $table->unsignedDecimal('price_two_years')->nullable();
                $table->unsignedDecimal('price_three_years')->nullable();
                $table->string('paypal_plan_id_monthly')->nullable();
                $table->string('paypal_plan_id_yearly')->nullable();
                $table->string('stripe_plan_id_monthly')->nullable();
                $table->string('stripe_plan_id_yearly')->nullable();
                $table->string('paddle_plan_id_monthly')->nullable();
                $table->string('paddle_plan_id_yearly')->nullable();
                $table->string('softhash_item_id')->nullable();
                $table->text('description')->nullable();
                $table->text('features')->nullable();
                $table->text('modules')->nullable();
                $table->unsignedInteger('maximum_allowed_users')->default(0);
                $table->boolean('has_modules')->default(0);
                $table->boolean('is_free')->default(0);
                $table->boolean('is_active')->default(1);
                $table->boolean('is_featured')->default(0);
                $table->boolean('is_default')->default(0);
                $table->boolean('per_user_pricing')->default(0);
                $table->unsignedInteger('users_limit')->default(0);
                $table->unsignedInteger('max_file_upload_size')->default(0);
                $table->unsignedInteger('file_space_limit')->default(0);
                $table->unsignedInteger('text_token_limit')->default(0);
                $table->unsignedInteger('image_token_limit')->default(0);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('flashcards')) {
            Schema::create('flashcards', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->uuid();
                $table->unsignedInteger('user_id')->default(0);
                $table->unsignedInteger('collection_id')->default(0);
                $table->string('title')->nullable();
                $table->string('image')->nullable();
                $table->text('description')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->unsignedInteger('viewed')->default(0);
                $table->boolean('is_active')->default(1);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('flashcard_collections')) {
            Schema::create('flashcard_collections', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->uuid();
                $table->unsignedInteger('user_id')->default(0);
                $table->string('title')->nullable();
                $table->string('image')->nullable();
                $table->text('description')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(1);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('goal_categories')) {
            Schema::create('goal_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->uuid();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('ai_prompts')) {
            Schema::create('ai_prompts', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->uuid();
                $table->unsignedInteger('user_id')->default(0);
                $table->unsignedInteger('collection_id')->default(0);
                $table->text('prompt')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('ai_chats')) {

            Schema::create('ai_chats', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->unsignedInteger('session_id')->default(0);
                $table->unsignedInteger('token_count')->default(0);
                $table->uuid();
                $table->unsignedInteger('user_id')->default(0);
                $table->enum('type', ['user', 'system'])->default('user');
                $table->text('message')->nullable();
                $table->timestamps();
            });

        }

        if(!Schema::hasTable('ai_chat_sessions')) {

            Schema::create('ai_chat_sessions', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->uuid();
                $table->unsignedInteger('user_id')->default(0);
                $table->string('title')->nullable();
                $table->timestamps();
            });

        }


        if(!Schema::hasTable('token_uses')) {
            Schema::create('token_uses', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->string('type');
                $table->unsignedInteger('token_count')->default(0);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('study_goals')) {

            Schema::create('study_goals', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id');
                $table->uuid('uuid');
                $table->unsignedInteger('admin_id')->default(0);
                $table->unsignedInteger('category_id')->default(0);
                $table->string('title')->nullable();
                $table->string('subject')->nullable();
                $table->string('reason')->nullable();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->text('description')->nullable();
                $table->boolean('completed')->default(0);
                $table->timestamps();
            });

        }
        if(!Schema::hasTable('project_replies')) {

            Schema::create('project_replies', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid');
                $table->unsignedInteger('workspace_id');
                $table->unsignedInteger('visitor_id')->default(0);
                $table->unsignedInteger('admin_id')->default(0);
                $table->unsignedInteger('agent_id')->default(0);
                $table->unsignedInteger('project_id')->default(0);
                $table->text('message')->nullable();
                $table->boolean('is_private')->default(0);
                $table->boolean('agent_can_view')->default(1);
                $table->timestamps();
            });

        }


    }

    public static function createPrimaryData($user)
    {


        $workspace_id = $user->workspace_id;
        $main_user = $user;
        $main_workspace_id = $workspace_id;

        $default_settings_exist = Setting::where('workspace_id', $workspace_id)->first();

        if($default_settings_exist)
        {
            return;
        }

        add_activity($workspace_id, $user->first_name.' '.__('installed app'), $user->id);

        # This is sample data, does not need to be translated

        $documents = [
            [
                'title' => 'Getting Started',
                'content' => '<h2>Getting Started</h2><p>Hey <strong>'.$user->first_name.'!</strong> </p><p>You have just installed APP. </p><p>It will help you to create, organize, and share your business and personal documents. </p><p>Make word documents and spreadsheets, edit images, quickly share files and screenshots, and more. </p><p>During your installation, the system created some sample documents to get to know your CloudOffice and its modules. This document and other documents generated during installation are sample documents. You may delete them.</p>',
            ],
            [
                'title' => 'Creating and Sharing Documents',
                'content' => 'You can create documents with rich editors and share them securely with auto-generated unique URLs. To get started, go to "Documents" and click "New Document" from the top right corner. After creating, click "Share," and you will get a unique URL secured by a randomly generated access key.',
            ],
            [
                'title' => 'Address Book',
                'content' => 'Keep all your business contacts in your address books. You can also create contacts via API. Go to "API" under Settings to generate API keys and guide.',
            ],
            [
                'title' => 'Quick Share',
                'content' => 'Quick share allows you to share zip files, images, and videos without expensive subscriptions, all with your brands. For example, you can instantly share a screenshot, video, or zip file without using a third-party service. It also automatically shows a preview of images and videos. ',
            ],
            [
                'title' => 'Editing an Image',
                'content' => 'Upload your images, and your master images will remain the same on your CloudOffice. Then you can edit them and download your changes.',
            ],
            [
                'title' => 'Sample Spreadsheet',
                'content' => '',
                'type' => 'spreadsheet',
            ]
        ];


        foreach ($documents as $create_document)
        {
            $document = new Document();
            $document->workspace_id = $workspace_id;
            $document->user_id = $user->id;
            $document->uuid = Str::uuid();
            $document->type = $create_document['type'] ?? 'word';
            $document->title = $create_document['title'];
            $document->content = $create_document['content'];
            $document->last_opened_by = $user->id;
            $document->last_opened_at = now();
            $document->access_key = Str::random(32);
            $document->save();
        }

        $workspace_name = config('app.name') ?? 'StudyBuddy';
        $logo = 'system/logo.png';

        if(config('app.name') == 'StudyBuddy')
        {
            $logo = 'system/logo.png';
        }

        $default_settings = [
            'workspace_name' => $workspace_name,
            'logo' => $logo,
            'backend_logo' => 'system/backend-logo.png',
        ];

        foreach ($default_settings as $key => $value)
        {
            $setting = new Setting();
            $setting->workspace_id = $workspace_id;
            $setting->key = $key;
            $setting->value = $value;
            $setting->save();
        }

        $sample_contacts = [
            [
                'first_name' => 'Demo',
                'last_name' => 'Example',
                'title' => 'Sample Contact',
                'email' => 'demo@example.com',
                'notes' => 'This is a sample contact. You can delete it.',
            ],
        ];

        foreach ($sample_contacts as $create_contact)
        {
            $contact = new Contact();
            $contact->workspace_id = $workspace_id;
            $contact->uuid = Str::uuid();
            $contact->user_id = $user->id;
            $contact->first_name = $create_contact['first_name'];
            $contact->last_name = $create_contact['last_name'];
            $contact->title = $create_contact['title'];
            $contact->email = $create_contact['email'];
            $contact->notes = $create_contact['notes'];
            $contact->save();
        }



          $faker = Factory::create();

          $created_users_ids = [];

        if (app()->environment('local', 'demo')) {
            //Create additional users for demo

            for($i=0; $i < 50 ; $i++){

                $user = new User();
                $user->uuid = Str::uuid();
                $user->workspace_id = 1;
                $user->first_name = $faker->firstName;
                $user->last_name = $faker->lastName;
                $user->email = $faker->email;
                $user->password = Hash::make(Str::random());
                $user->is_super_admin = 0;
                $user->save();

                $created_users_ids[] = $user->id;

            }

            //Generate Token usages for last 30 days

            for($i=0; $i < 30 ; $i++){

                $token_usage = new TokenUse();
                $token_usage->workspace_id = 1;
                $token_usage->type = 'text';
                $token_usage->token_count = rand(1000, 7000);
                $token_usage->created_at = Carbon::now()->subDays($i)->format('Y-m-d H:i:s');
                $token_usage->save();

                $token_usage = new TokenUse();
                $token_usage->workspace_id = 1;
                $token_usage->type = 'image';
                $token_usage->token_count = rand(5, 15);
                $token_usage->created_at = Carbon::now()->subDays($i)->format('Y-m-d H:i:s');
                $token_usage->save();

            }

            $sample_todos = [
                [
                    'title' => 'Write 5 blog posts for Techhub',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-12-31',
                    'status' => 'High',


                ],
                [
                    'title' => 'Create a detailed project plan outlining tasks and timelines.',
                    'description' => 'Create a detailed project plan outlining tasks and timelines.',
                    'date' => '2023-04-09',
                    'complated'=>1,
                    'status' => 'Medium',

                ],[
                    'title' => 'Gather requirements from stakeholders and end-users.',
                    'description' => 'Gather requirements from stakeholders and end-users.',
                    'date' => '2023-09-23',
                    'complated'=>1,
                    'status' => 'High',

                ],[
                    'title' => 'Implement data collection and storage mechanisms.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-12-31',
                    'complated'=>1,
                    'status' => 'Low',

                ],[
                    'title' => 'Build and train the AI model using appropriate algorithms',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-04-09',
                    'status' => 'Medium',

                ],[
                    'title' => 'Test the AI model for accuracy, performance, and scalability',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-12-31',
                    'status' => 'High',

                ],[
                    'title' => 'Optimize the AI model for efficiency and resource utilization',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-02-03',
                    'status' => 'Low',

                ],[
                    'title' => 'Develop a robust backend infrastructure to support the AI product',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-32-01',
                    'completed'=>1,
                    'status' => 'Medium',


                ],[
                    'title' => 'Implement real-time monitoring and logging for system health.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-04-09',
                    'completed'=>1,
                    'status' => 'High',


                ],[
                    'title' => 'Develop a marketing strategy to promote the AI product.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-10-31',
                    'completed'=>1,
                    'status' => 'Low',


                ],[
                    'title' => 'Establish partnerships with industry influencers or collaborators.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2024-10-31',
                    'completed'=>1,
                    'status' => 'Medium',


                ],[
                    'title' => 'Explore opportunities for expanding the AI product into new markets.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-8-13',
                    'completed'=>1,
                    'status' => 'High',


                ],[
                    'title' => 'Conduct regular security audits and implement necessary updates.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-2-23',
                    'completed'=>1,
                    'status' => 'Low',


                ],[
                    'title' => 'Conduct A/B testing to optimize user interfaces and features.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-12-11',
                    'completed'=>1,
                    'status' => 'Medium',


                ],[
                    'title' => 'Plan for regular updates and feature enhancements based on user needs.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-10-12',
                    'completed'=>1,
                    'status' => 'High',

                ],[
                    'title' => 'Implement a feedback loop for continuous learning and improvement.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-7-21',
                    'complated'=>1,
                    'status' => 'Low',


                ],[
                    'title' => 'Write 5 blog posts for Techhub',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-2-31',
                    'completed'=>1,
                    'status' => 'Medium',

                ],[
                    'title' => 'Continuously evaluate and enhance the user experience.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2024-1-31',
                    'completed'=>1,
                    'status' => 'High',

                ],[
                    'title' => 'Collect and analyze user feedback for continuous improvement.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-12-31',
                    'status' => 'Low',

                ],
            ];

            foreach ($sample_todos as $sample_todo)
            {
                $todo = new Todo();
                $todo->workspace_id = $workspace_id;
                $todo->uuid = Str::uuid();
                $todo->admin_id = $user->id;
                $todo->completed= $sample_todo['complated']??0;
                $todo->title = $sample_todo['title'];
                $todo->status = $sample_todo['status'];
                $todo->description = $sample_todo['description'];
                $todo->date = $sample_todo['date'];
                $todo->save();
            }

            $sample_goals = [
                [
                    'title' => 'Master AI frameworks and tools',
                    'description' => 'Gain proficiency in popular AI frameworks like TensorFlow, PyTorch, and scikit-learn. Learn how to leverage these tools to build and deploy AI models effectively.',
                    'reason' => 'Gain proficiency in popular AI frameworks like TensorFlow, PyTorch, and scikit-learn. Learn how to leverage these tools to build and deploy AI models effectively.',
                    'start_date' => '2023-08-31',
                    'end_date' => '2023-12-31',
                    'completed'=>0,
                    'category_id'=>4,


                ],
                [
                    'title' => 'Stay updated with research and advancements',
                    'description' => 'Keep up with the latest research papers, publications, and conferences in the field of AI. Stay informed about cutting-edge techniques, algorithms, and breakthroughs in AI technology.',
                    'start_date' => '2023-08-31',
                    'end_date' => '2023-04-09',
                    'reason'=>'Keep up with the latest research papers, publications, and conferences in the field of AI. Stay informed about cutting-edge techniques, algorithms, and breakthroughs in AI technology.',
                    'completed'=>1,
                    'category_id'=>1,

                ],[
                    'title' => 'Apply AI in real-world projects',
                    'description' => 'pply your theoretical knowledge by working on practical AI projects. Engage in hands-on experiences, participate in Kaggle competitions, and collaborate on AI projects to gain valuable industry experience and showcase your skills.',
                    'reason'=>'pply your theoretical knowledge by working on practical AI projects. Engage in hands-on experiences, participate in Kaggle competitions, and collaborate on AI projects to gain valuable industry experience and showcase your skills.',
                    'start_date' => '2023-08-31',
                    'end_date' => '2023-09-23',
                    'completed'=>1,
                    'category_id'=>2,

                ],[
                    'title' => 'Understand computer vision and image processing',
                    'description' => 'Explore the domain of computer vision and image processing, covering topics like object detection, image segmentation, and image recognition. Utilize libraries such as OpenCV and TensorFlow for practical applications.',
                    'reason'=>'Explore the domain of computer vision and image processing, covering topics like object detection, image segmentation, and image recognition. Utilize libraries such as OpenCV and TensorFlow for practical applications.',
                    'start_date' => '2023-08-31',
                    'end_date' => '2023-12-31',
                    'completed'=>1,
                    'category_id'=>1,

                ],



            ];

            foreach ($sample_goals as $sample_goal)
            {
                $goal = new StudyGoal();
                $goal->workspace_id = $workspace_id;
                $goal->uuid = Str::uuid();
                $goal->admin_id = $user->id;
                $goal->completed= $sample_goal['completed']??0;
                $goal->title = $sample_goal['title'];
                $goal->category_id = $sample_goal['category_id'];
                $goal->description = $sample_goal['description'];
                $goal->reason = $sample_goal['reason'];
                $goal->end_date = $sample_goal['end_date'];
                $goal->save();
            }

            $sample_categories = [
                [
                    'name' => 'Computer Science',

                ],
                [
                    'name' => 'Mathematics',


                ],[
                    'name' => 'Engineering',

                ],[
                    'name' => 'Business',

                ],

            ];
            foreach ($sample_categories as $sample_category)
            {
                $category = new GoalCategory();
                $category->workspace_id = $workspace_id;
                $category->uuid = Str::uuid();
                $category->name = $sample_category['name'];
                $category->save();
            }
            $sample_events = [
                [
                    'title' => 'Video Call with John',
                    'start' => '2023-08-31',
                    'end' => '2023-12-31',

                ],
                [
                    'title' => 'Tutorial on AI',
                    'start' => '2023-08-31',
                    'end' => '2023-04-09',

                ],[
                    'title' => 'Seminar on Computer Vision',
                    'start' => '2023-08-31',
                    'end' => '2023-04-09',

                ],[
                    'title' => 'Online Course on IP',
                    'start' => '2023-08-31',
                    'end' => '2023-04-09',
                ],

            ];

            foreach ($sample_events as $sample_event)

            {
                $event = new CalendarEvent();
                $event->workspace_id = $workspace_id;
                $event->uuid = Str::uuid();
                $event->admin_id = $user->id;
                $event->title = $sample_event['title'];
                $event->start = $sample_event['start'];
                $event->end = $sample_event['end'];
                $event->save();
            }



            $sample_projects = [
                [
                    'title' => 'Computer Vision CS230',
                    'goal_id' => '3',
                    'budget' => '10000',
                    'start_date' => '2023-12-31',
                    'end_date' => '2023-12-31',
                    'status' => 'Pending',
                    'members' => ["1"],
                    'summary'=>'In this computer vision assignment, you will delve into the fascinating field of image analysis and understanding using advanced algorithms and techniques.',
                    'description'=>'In this computer vision assignment, you will delve into the fascinating field of image analysis and understanding using advanced algorithms and techniques. You will explore various tasks and challenges in computer vision, such as object detection, image segmentation, and image classification.'


                ],
                [
                    'title' => 'Financial Management 202',
                    'goal_id' => '2',
                    'budget'=>'1300',
                    'start_date' => '2023-12-31',
                    'end_date' => '2023-12-31',
                    'status' => 'Started',
                    'summary'=>'This project focuses on developing AI products that leverage cutting-edge technologies to enhance user experiences and automate complex tasks.',
                    'description'=>'By incorporating computer vision algorithms, we seek to revolutionize the way people interact with their surroundings, enabling AI-powered image recognition and object detection functionalities. Furthermore, our AI products prioritize user privacy and data security, implementing robust encryption and anonymization techniques to ensure confidentiality. Through continuous research and development, we strive to deliver innovative AI solutions that streamline processes, improve decision-making, and empower users in various domains.'


                ],
                [
                    'title' => 'Spanish Language 201',
                    'goal_id' => '1',
                    'budget'=>'3200',
                    'start_date' => '2023-12-31',
                    'end_date' => '2023-12-31',
                    'status' => 'Finished',
                    'summary'=>'By harnessing the power of AI, we aim to enhance productivity, efficiency, and accuracy in fields such as healthcare, finance, manufacturing.',
                    'description'=>'Leveraging cutting-edge machine learning techniques, our products will provide advanced solutions for automation, decision-making, and predictive analysis. By harnessing the power of AI, we aim to enhance productivity, efficiency, and accuracy in fields such as healthcare, finance, manufacturing, and customer service. Our products will enable businesses to streamline their operations, optimize resource allocation, and deliver personalized experiences to their customers. Through continuous research and innovation, we strive to push the boundaries of AI technology and create transformative products that drive growth and success in the digital era..'


                ],
                [
                    'title' => 'Biology Lab 102: Genetics',
                    'budget'=>'5600',
                    'start_date' => '2023-12-31',
                    'goal_id' => '1',
                    'end_date' => '2023-12-31',
                    'status' => 'Started',
                    'summary'=>'By harnessing the power of AI, we aim to enhance productivity, efficiency, and accuracy in fields such as healthcare, finance, manufacturing.',
                    'description'=>'By harnessing the power of AI, we aim to enhance productivity, efficiency, and accuracy in fields such as healthcare, finance, manufacturing, and customer service. Our products will enable businesses to streamline their operations, optimize resource allocation, and deliver personalized experiences to their customers. Through continuous research and innovation, we strive to push the boundaries of AI technology and create transformative products that drive growth and success in the digital era..'


                ],
                [
                    'title' => 'Investments and Portfolio Management (FIN 201):',
                    'goal_id' => '1',
                    'budget'=>'32000',
                    'start_date' => '2023-12-31',
                    'end_date' => '2023-12-31',
                    'status' => 'Finished',
                    'summary'=>'Dive into the world of investments and learn strategies for building and managing investment portfolios. Study topics such as asset allocation, risk management etc.',
                    'description'=>'This project aims to develop and implement AI products that revolutionize various industries. Leveraging cutting-edge machine learning techniques, our products will provide advanced solutions for automation, decision-making, and predictive analysis. By harnessing the power of AI, we aim to enhance productivity, efficiency, and accuracy in fields such as healthcare, finance, manufacturing, and customer service. Our products will enable businesses to streamline their operations, optimize resource allocation, and deliver personalized experiences to their customers. Through continuous research and innovation, we strive to push the boundaries of AI technology and create transformative products that drive growth and success in the digital era..'


                ],
                [
                    'title' => 'Python Programming (CSCI101)',
                    'goal_id' => '1',
                    'budget'=>'8200',
                    'start_date' => '2023-12-31',
                    'end_date' => '2023-12-31',
                    'status' => 'Pending',
                    'summary'=>'By harnessing the power of AI, we aim to enhance productivity, efficiency, and accuracy in fields such as healthcare, finance, manufacturing.',
                    'description'=>'This project aims to develop and implement AI products that revolutionize various industries. Leveraging cutting-edge machine learning techniques, our products will provide advanced solutions for automation, decision-making, and predictive analysis. By harnessing the power of AI, we aim to enhance productivity, efficiency, and accuracy in fields such as healthcare, finance, manufacturing, and customer service. Our products will enable businesses to streamline their operations, optimize resource allocation, and deliver personalized experiences to their customers. Through continuous research and innovation, we strive to push the boundaries of AI technology and create transformative products that drive growth and success in the digital era..'


                ],
                [
                    'title' => 'Artificial Intelligence (COMP300)',
                    'goal_id' => '1',
                    'budget'=>'3800',
                    'start_date' => '2023-12-31',
                    'end_date' => '2023-12-31',
                    'status' => 'Started',
                    'summary'=>'By harnessing the power of AI, we aim to enhance productivity, efficiency, and accuracy in fields such as healthcare, finance, manufacturing.',
                    'description'=>'Enter the world of algorithms, programming, and problem-solving as you embark on a journey through computer science. Learn the foundations of computing, software development, data structures, and algorithms, while exploring emerging technologies like artificial intelligence and machine learning.'


                ],
                [
                    'title' => 'Quantum Mechanics (PHYS 301)',
                    'goal_id' => '1',
                    'budget'=>'45600',
                    'start_date' => '2023-12-31',
                    'end_date' => '2023-12-31',
                    'status' => 'Started',
                    'summary'=>'Enter the world of algorithms, programming, and problem-solving as you embark on a journey through computer science.',
                    'description'=>'This project aims to develop and implement AI products that revolutionize various industries. Leveraging cutting-edge machine learning techniques, our products will provide advanced solutions for automation, decision-making, and predictive analysis. By harnessing the power of AI, we aim to enhance productivity, efficiency, and accuracy in fields such as healthcare, finance, manufacturing, and customer service. Our products will enable businesses to streamline their operations, optimize resource allocation, and deliver personalized experiences to their customers. Through continuous research and innovation, we strive to push the boundaries of AI technology and create transformative products that drive growth and success in the digital era..'


                ],
                [
                    'title' => 'Macroeconomics (ECON201)',
                    'goal_id' => '1',
                    'budget'=>'763200',
                    'start_date' => '2023-12-31',
                    'end_date' => '2023-12-31',
                    'status' => 'Pending',
                    'summary'=>'Analyze and apply the principles of financial management, including capital budgeting, financial analysis, risk assessment. ',
                    'description'=>'This project aims to develop and implement AI products that revolutionize various industries. Leveraging cutting-edge machine learning techniques, our products will provide advanced solutions for automation, decision-making, and predictive analysis. By harnessing the power of AI, we aim to enhance productivity, efficiency, and accuracy in fields such as healthcare, finance, manufacturing, and customer service. Our products will enable businesses to streamline their operations, optimize resource allocation, and deliver personalized experiences to their customers. Through continuous research and innovation, we strive to push the boundaries of AI technology and create transformative products that drive growth and success in the digital era..'


                ],
            ];

            foreach ($sample_projects as $sample_project)
            {

                //Random 2 or 3 user ids
                $random_users_ids = Arr::random($created_users_ids, rand(2, 3));

                $project = new Projects();
                $project->workspace_id = $workspace_id;
                $project->uuid = Str::uuid();
                $project->admin_id = $user->id;

                $project->goal_id = $sample_project['goal_id'];
                $project->title = $sample_project['title'];
                $project->budget = $sample_project['budget'];
                $project->description = $sample_project['description'];
                $project->start_date = $sample_project['start_date'];
                $project->end_date = $sample_project['end_date'];
                $project->status = $sample_project['status'];

                $project->members = json_encode($random_users_ids);

                $project->summary = $sample_project['summary'];
                $project->description = $sample_project['description'];
                $project->save();
            }
            $sample_project_todos = [
                [
                    'title' => 'Build and train the AI model using appropriate algorithms',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-04-09',
                    'project_id'=>1,
                    'completed'=>1,

                ],[
                    'title' => 'Test the AI model for accuracy, performance, and scalability',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-12-31',
                    'project_id'=>1,
                    'admin_id'=>1,
                    'completed'=>1,

                ],[
                    'title' => 'Optimize the AI model for efficiency and resource utilization',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-02-03',
                    'project_id'=>1,
                    'admin_id'=>2,
                    'completed'=>1,

                ],[
                    'title' => 'Develop a robust backend infrastructure to support the AI product',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-32-01',
                    'admin_id'=>3,
                    'completed'=>1,
                    'project_id'=>1,


                ],[
                    'title' => 'Implement real-time monitoring and logging for system health.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-04-09',
                    'completed'=>1,
                    'admin_id'=>2,
                    'project_id'=>1,


                ],[
                    'title' => 'Develop a marketing strategy to promote the AI product.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-10-31',
                    'completed'=>1,
                    'admin_id'=>6,
                    'project_id'=>2,


                ],[
                    'title' => 'Establish partnerships with industry influencers or collaborators.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2024-10-31',
                    'completed'=>1,
                    'admin_id'=>5,
                    'project_id'=>2,


                ],[
                    'title' => 'Explore opportunities for expanding the AI product into new markets.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-8-13',
                    'completed'=>1,
                    'admin_id'=>4,
                    'project_id'=>2,



                ],[
                    'title' => 'Conduct regular security audits and implement necessary updates.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-2-23',
                    'completed'=>1,
                    'admin_id'=>4,
                    'project_id'=>2,



                ],[
                    'title' => 'Conduct A/B testing to optimize user interfaces and features.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-12-11',
                    'completed'=>1,
                    'admin_id'=>4,
                    'project_id'=>3,



                ],[
                    'title' => 'Plan for regular updates and feature enhancements based on user needs.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-10-12',
                    'completed'=>1,
                    'project_id'=>1,
                    'admin_id'=>1,


                ],[
                    'title' => 'Implement a feedback loop for continuous learning and improvement.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-7-21',
                    'completed'=>1,
                    'project_id'=>1,
                    'admin_id'=>1,



                ],[
                    'title' => 'Write 5 blog posts for Techhub',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-2-31',
                    'project_id'=>1,
                    'completed'=>0,
                    'admin_id'=>1,

                ],[
                    'title' => 'Continuously evaluate and enhance the user experience.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2024-1-31',
                    'project_id'=>4,
                    'completed'=>0,
                    'admin_id'=>1,

                ],[
                    'title' => 'Collect and analyze user feedback for continuous improvement.',
                    'description' => 'Write 5 blog posts for Techhub about entrepreneurship.',
                    'date' => '2023-12-31',
                    'project_id'=>4,
                    'admin_id'=>1,
                    'completed'=>0,


                ],
            ];

            foreach ($sample_project_todos as $sample_todo)
            {
                $todo = new ProjectTask();
                $todo->workspace_id = $workspace_id;
                $todo->uuid = Str::uuid();
                $todo->admin_id = $user->id;
                $todo->project_id =  $sample_todo['project_id'];
                $todo->completed= $sample_todo['completed']??0;
                $todo->title = $sample_todo['title'];
                $todo->description = $sample_todo['description'];
                $todo->date = $sample_todo['date'];
                $todo->save();
            }



            $sample_project_discussion=[
                [
                    'message' => 'Artificial intelligence is the simulation of human intelligence processes by machines, especially computer systems. These processes include learning (the acquisition of information and rules for using the information), reasoning (using rules to reach approximate or definite conclusions) and self-correction.',
                    'created_at' => '2023-04-09',
                    'admin_id'=>4,
                    'project_id'=>1,

                ],[
                    'message' => 'Medical image analysis is definitely groundbreaking. It can be used to detect diseases like cancer and tuberculosis. It can also be used to analyze X-rays, CT scans, and MRI scans.',
                    'created_at' => '2023-04-09',
                    'admin_id'=>1,
                    'project_id'=>1,

                ],[
                    'message' => 'It has potential in areas like mental health and human-computer interaction. For example, it can be used to detect depression or anxiety in patients. It can also be used to improve the user experience of computer systems.',
                    'created_at' => '2023-04-09',
                    'admin_id'=>3,
                    'project_id'=>1,
                ],[
                    'message' => 'Yes, facial recognition is quite powerful. On a related note, emotion detection from facial expressions is also an interesting application. Computers can analyze facial cues to determine emotions like happiness, sadness, or anger. ',
                    'created_at' => '2023-04-09',
                    'admin_id'=>1,
                    'project_id'=>1,

                ],[
                    'message' => 'One application that I find fascinating is object recognition. Its impressive how algorithms can identify and classify objects in images or videos. It has practical uses in fields like self-driving cars and surveillance systems.',
                    'created_at' => '2023-04-09',
                    'admin_id'=>2,
                    'project_id'=>1,

                ],[
                    'message' => 'Absolutely! Another application I find intriguing is facial recognition. Its incredible how computers can analyze facial features and match them to existing data. It has uses in security systems, authentication, and even social media tagging.',
                    'created_at' => '2023-04-09',
                    'admin_id'=>1,
                    'project_id'=>1,


                ],[
                    'message' => 'Exactly! Its amazing how computers can understand and extract information from visual data. What are some specific applications of computer vision that you find fascinating?',
                    'created_at' => '2023-04-09',
                    'admin_id'=>1,
                    'project_id'=>1,

                ],[
                    'message' => 'Hey, have you heard about computer vision? Its such an interesting field!',
                    'created_at' => '2023-04-09',
                    'admin_id'=>2,
                    'project_id'=>1,


                ],[
                    'message' => 'Artificial intelligence is the simulation of human intelligence processes by machines, especially computer systems. These processes include learning (the acquisition of information and rules for using the information), reasoning (using rules to reach approximate or definite conclusions) and self-correction.',
                    'created_at' => '2023-04-09',
                    'admin_id'=>3,
                    'project_id'=>1,


                ],[
                    'message' => 'These processes include learning (the acquisition of information and rules for using the information), reasoning (using rules to reach approximate or definite conclusions) and self-correction.',
                    'created_at' => '2023-04-09',
                    'admin_id'=>2,
                    'project_id'=>1,
                ],[
                    'message' => 'Exactly! Its amazing how computers can understand and extract information from visual data. What are some specific applications of computer vision that you find fascinating?',
                    'created_at' => '2023-04-09',
                    'admin_id'=>1,
                    'project_id'=>1,

                ]

            ];

            foreach ($sample_project_discussion as $sample_discussion)
            {
                $discussion = new ProjectReply();
                $discussion->workspace_id = $workspace_id;
                $discussion->uuid = Str::uuid();
                $discussion->admin_id = $main_user->id;
                $discussion->project_id =  $sample_discussion['project_id'];
                $discussion->message = $sample_discussion['message'];
                $discussion->created_at = $sample_discussion['created_at'];
                $discussion->save();
            }


            $sample_chat_sessions = [
                [
                    'title' => 'train the AI model',
                    'created_at' => '2023-04-09',

                ],[
                    'title' => 'chat with LiamP',
                    'created_at' => '2023-04-09',

                ],[
                    'title' => 'Define a project scope',
                    'created_at' => '2023-04-09',
                ],[
                    'title' => 'Project statement',
                    'created_at' => '2023-04-09',

                ],[
                    'title' => 'Best way to propmt',
                    'created_at' => '2023-04-09',

                ],[
                    'title' => 'Entrepreneurship.',
                    'created_at' => '2023-04-09',


                ],[
                    'title' => 'Project summary.',
                    'created_at' => '2023-04-09',

                ],[
                    'title' => 'Youtube script.',
                    'created_at' => '2023-04-09',


                ],[
                    'title' => 'Business mindset.',
                    'created_at' => '2023-04-09',


                ],[
                    'title' => 'AI fundamentals.',
                    'created_at' => '2023-04-09',

                ],[
                    'title' => 'Designing.',
                    'created_at' => '2023-04-09',

                ]

            ];

            foreach ( $sample_chat_sessions as $sample_chat_session)
            {
                $chat_session = new AiChatSession();
                $chat_session->workspace_id = $workspace_id;
                $chat_session->uuid = Str::uuid();
                $chat_session->user_id = $user->id;
                $chat_session->title = $sample_chat_session['title'];
                $chat_session->created_at = $sample_chat_session['created_at'];
                $chat_session->save();
            }



            $sample_chats = [
                [
                    'message' => 'Hey there! Welcome to our chat app demo. Enjoy your experience!',
                    'created_at' => '2023-04-09',
                    'user_id' => 1,
                    'type' => 'user',

                ],
                [
                    'message' => 'Our smart assistant is equipped with vast knowledge and can engage in meaningful discussions on a wide range of topics.',
                    'created_at' => '2023-04-09',
                    'user_id' => 1,
                    'type' => 'system',

                ]
                ,[
                    'message' => 'Connect with friends, share photos and videos, and express yourself with emojis and stickers. Have a great time chatting!',
                    'created_at' => '2023-04-09',
                    'user_id' => 2,

                    'type' => 'system',

                ],[
                    'message' => 'Welcome to our chat app demo. Discover a world of instant communication and stay connected with your favorite people. Try out our voice and video call features too. Enjoy the chat!',
                    'created_at' => '2023-04-09',
                    'user_id' => 1,
                    'session_id' => '1',
                    'type' => 'system',

                ],[
                    'message' => 'Hey! Excited to have you on board for our chat app demo. Chat with friends, create group conversations, and customize your profile. Letus make chatting fun and easy together',
                    'created_at' => '2023-04-09',
                    'user_id' => 1,
                    'session_id' => '1',


                ],[
                    'message' => 'Hello! Welcome to our AI-powered chat app demo. Prepare to be amazed as you engage in conversations with our intelligent virtual assistant. Ask questions, seek advice, or simply have a friendly chat. Letus dive into the world of AI',
                    'created_at' => '2023-04-09',
                    'user_id' => 1,
                    'session_id' => '1',
                    'type' => 'system',


                ],[
                    'message' => 'Get ready to have conversations like never before with our AI chat app demo. ',
                    'created_at' => '2023-04-09',
                    'user_id' => 1,




                ],[
                    'message' => 'Chatting with our virtual assistant is like having a knowledgeable friend at your fingertips. Ask questions, discuss current events, or even get recommendations based on your preferences. Enjoy the wonders of AI-powered conversation.',
                    'created_at' => '2023-04-09',
                    'user_id' => 1,

                    'type' => 'system',


                ],[
                    'message' => 'Prepare to be impressed by our AI chat app demo. Our virtual assistant is trained to understand and respond to your queries, offering insightful answers and engaging discussions. Experience the future of chat with our intelligent AI companion',
                    'created_at' => '2023-04-09',
                    'user_id' => 1,

                    'type' => 'system',




                ],[
                    'message' => 'From general knowledge to personal assistance, our AI is here to make your chat experience intelligent and enjoyable.',
                    'created_at' => '2023-04-09',
                    'user_id' => 1,


                ],[
                    'message' => 'AI chat app demo, where you can experience the brilliance of artificial intelligence.',
                    'created_at' => '2023-04-09',
                    'user_id' => 1,
                    'type' => 'system',

                ],[
                    'message' => 'Discuss current events, or even get recommendations based on your preferences. Enjoy the wonders of AI-powered conversation',
                    'created_at' => '2023-04-09',
                    'user_id' => 1,
                    'type' => 'system',



                ]

            ];

            $chat_sessions = AiChatSession::all();

            foreach ($chat_sessions as $chat_session)
            {
                foreach ( $sample_chats as $sample_chat)
                {
                    $chat = new AiChat();
                    $chat->workspace_id = $workspace_id;
                    $chat->uuid = Str::uuid();
                    $chat->user_id = $user->id;
                    $chat->message = $sample_chat['message'];
                    $chat->session_id = $chat_session->id;
                    $chat->type = $sample_chat['type'] ?? 'user';
                    $chat->created_at = $sample_chat['created_at'];
                    $chat->save();
                }
            }

            // Create sample time logs for last 30 days

            $start_date = Carbon::now()->subDays(30);
            $end_date = Carbon::now();

            $time_logs = [];

            $random_hours = [1.4,2.3,3.7,4.2,5.9,6.7,7.1,8.9];

            while ($start_date->lte($end_date))
            {

                $time_log = new TimeLog();
                $time_log->workspace_id = $workspace_id;
                $time_log->user_id = 1;
                // A random start time
                $time_log->timer_started_at = $start_date->format('Y-m-d') . ' 09:00:00';
                $duration_minutes = Arr::random($random_hours) * 60;
                $timer_stopped_at = $start_date->copy()->addMinutes($duration_minutes);
                $time_log->timer_stopped_at = $timer_stopped_at->format('Y-m-d H:i:s');
                $time_log->timer_duration = ($duration_minutes * 60);
                $time_log->save();



                $start_date->addDay();
            }


            $workspace_id = $main_workspace_id;
            $user_id = $main_user->id;
            $workspace_names = [
                'Opera Inc.',
                'Alex Tutorial Home',
                'Mark Next Inc.',
                'High Space',
                'Orion Tech',
                'One Language School',
                'Save the Climate',
                'We Garden',
            ];

            $workspaces = [];

            foreach ($workspace_names as $name) {
                $workspace = new Workspace();
                $workspace->uuid = Str::uuid();
                $workspace->name = $name;
                $workspace->save();

                $workspaces[] = $workspace;

            }

            $faker = Factory::create();

            $users = [];

            foreach ($workspaces as $workspace)
            {

                $create_user = new User();
                $create_user->first_name = $faker->firstName;
                $create_user->last_name = $faker->lastName;
                $create_user->uuid = Str::uuid();
                $create_user->email = $faker->email;
                $create_user->phone = $faker->phoneNumber;
                $create_user->password = Hash::make('123456');
                $create_user->is_super_admin = 0;
                $create_user->workspace_id = $workspace->id;

                // created at random date in 30 days

                $create_user->created_at = $faker->dateTimeBetween('-30 days', 'now');

                $create_user->last_login_at = $faker->dateTimeBetween($create_user->created_at, 'now');

                $create_user->save();

                $workspace->owner_id = $create_user->id;
                $workspace->save();

                $users[] = $create_user;

            }

            foreach ($users as $create_user)
            {
                for($i = 0; $i < 2; $i++)
                {
                    $transaction = new Transaction();
                    $transaction->uuid = Str::uuid();
                    $transaction->user_id = $workspace_id;
                    $transaction->workspace_id = $create_user->workspace_id;
                    $transaction->amount = $faker->randomFloat(2, 0, 1000);
                    $transaction->created_at = $faker->dateTimeBetween('-30 days', 'now');
                    $transaction->save();
                }
            }

            //Create 20 contacts
            for($i = 0; $i < 20; $i++)
            {
                $contact = new Contact();
                $contact->uuid = Str::uuid();
                $contact->workspace_id = $workspace_id;
                $contact->user_id = $user_id;
                $contact->first_name = $faker->firstName;
                $contact->last_name = $faker->lastName;
                $contact->email = $faker->email;
                $contact->phone = $faker->phoneNumber;
                $contact->title = $faker->jobTitle;
                $contact->save();
            }

            $flashcard_collection = new FlashcardCollection();
            $flashcard_collection->workspace_id = $workspace_id;
            $flashcard_collection->uuid = Str::uuid();
            $flashcard_collection->title = 'Business English';
            $flashcard_collection->save();

            $items = [
                [
                    'title' => 'Stakeholder',
                    'description' => 'An individual, group, or organization that has interest or concern in an organization. They can affect or be affected by the organization\'s actions, objectives, and policies.',
                ],
                [
                    'title' => 'Supply Chain',
                    'description' => 'The network of all the individuals, organizations, resources, activities, and technology involved in the creation and sale of a product, from the delivery of source materials from the supplier to the manufacturer, through to its eventual delivery to the end user.',
                ],
                [
                    'title' => 'ROI (Return on Investment)',
                    'description' => 'A performance measure used to evaluate the efficiency or profitability of an investment. ROI = (Net Profit / Cost of Investment) x 100%',
                ],
                [
                    'title' => 'B2B (Business-to-Business)',
                    'description' => 'This term refers to businesses that sell products or provide services to other businesses.',
                ],
                [
                    'title' => 'B2C (Business-to-Consumer)',
                    'description' => 'This term refers to businesses that sell products or provide services directly to consumers.',
                ],
                [
                    'title' => 'SWOT Analysis',
                    'description' => 'A strategic planning technique used to help a person or organization identify Strengths, Weaknesses, Opportunities, and Threats related to business competition or project planning.',
                ],
                [
                    'title' => 'Net Profit Margin',
                    'description' => 'A profitability ratio calculated by taking the company\'s net income and dividing it by total sales. It represents the percentage of each dollar of a company\'s revenue that results in net profit.',
                ],
                [
                    'title' => 'EBITDA (Earnings Before Interest, Taxes, Depreciation, and Amortization)',
                    'description' => 'An indicator of a company\'s financial performance, EBITDA is used to analyze and compare profitability among companies and industries because it eliminates the effects of financing and accounting decisions.',
                ],
                [
                    'title' => 'Due Diligence',
                    'description' => 'The process of investigation, performed by investors, into the details of a potential investment, such as an examination of operations and management and the verification of material facts.',
                ],
                [
                    'title' => 'Scalability',
                    'description' => 'The ability of a system, network, or process to handle a growing amount of work or its potential to be enlarged to accommodate that growth.',
                ],
            ];

            foreach ($items as $item)
            {
                $flashcard = new Flashcard();
                $flashcard->workspace_id = $workspace_id;
                $flashcard->uuid = Str::uuid();
                $flashcard->collection_id = $flashcard_collection->id;
                $flashcard->title = $item['title'];
                $flashcard->description = $item['description'];
                $flashcard->save();
            }

            $prompts = [
                'Top 10 travel destinations for post-pandemic world and why.',
                'How to create and maintain a successful home garden.',
                'The impact of artificial intelligence on modern job market.',
                'Sustainable living: Practical ways to reduce your carbon footprint.',
                'Exploring the health benefits and culinary uses of uncommon vegetables.',
                'The rise of e-sports: A look into its history and future prospects.',
                'Navigating the challenges of remote work: A comprehensive guide.',
                'Mindfulness and mental health: Techniques for daily stress management.',
                'Book review: Discuss a book that has greatly influenced your life.',
                'The influence of social media on modern politics.',
                'Recipe post: Share a family recipe and the story behind it.',
                'Breaking down the latest fashion trends: What’s in and what’s out.',
                'Tips and tricks for efficient home organization.',
                'The role of community involvement in personal development.',
                'Exploring the benefits of yoga and meditation for physical and mental health.',
                'The impact of music on productivity: A scientific perspective.',
                'Traveling on a budget: How to see the world without breaking the bank.',
                'An insider\'s guide to your city: Hidden gems and local favorites.',
                'How adopting a pet can improve your life.',
            ];

            foreach ($prompts as $prompt)
            {
                $create_prompt = new AiPrompt();
                $create_prompt->workspace_id = $workspace_id;
                $create_prompt->uuid = Str::uuid();
                $create_prompt->prompt = $prompt;
                $create_prompt->save();
            }

        }

        $sample_media_files = [
            [
                'title' => 'Sample Image',
                'path' => 'media/sample-image.png',
                'mime_type' => 'image/png',
                'extension' => 'jpg',
                'size' => 386925,
            ]
        ];

        foreach ($sample_media_files as $create_media_file)
        {
            $media_file = new MediaFile();
            $media_file->workspace_id = $workspace_id;
            $media_file->uuid = Str::uuid();
            $media_file->user_id = $user->id;
            $media_file->title = $create_media_file['title'];
            $media_file->path = $create_media_file['path'];
            $media_file->mime_type = $create_media_file['mime_type'];
            $media_file->extension = $create_media_file['extension'];
            $media_file->size = $create_media_file['size'];
            $media_file->save();
        }

        ## No translation needed

        $workspace = new Workspace();
        $workspace->uuid = Str::uuid();
        $workspace->name = 'Admin Workspace';
        $workspace->is_on_free_trial = false;
        $workspace->is_active = true;
        $workspace->owner_id = $main_user->id;
        $workspace->is_subscribed = 1;
        $workspace->plan_id = 1;
        $workspace->save();

        $post = new Post();
        $post->uuid = Str::uuid();
        $post->workspace_id = 1;
        $post->type = 'page';
        $post->title = __('Welcome to StudyBuddy');
        $post->slug = 'home';
        $post->is_home_page = true;

        $post_settings = [
            'hero_section_name' => __('Hero Section'),
            'hero_title' => __('"The Ultimate Productivity App for Students!"'),
            'hero_subtitle' => __('A revolutionary collaborative productivity tool specifically designed for students.'),
           // 'hero_image' => 'media/sample-image.png',
            'hero_video' => 'media/video.mp4',
            'signup_image' => 'media/sample-demo.png',
            'feature_section_name' => __('Explore tools and Features'),

            'logo_title' => 'Trusted by over 1000+ companies over the world',
            'logo_1' => 'media/logo_1.png',
            'logo_2' => 'media/open-ai-logo.png',
            'logo_3' => 'media/logo-3.png',
            'logo_4' => 'media/logo_2.png',
            'logo_5' => 'media/logi-5.png',
            'hero_button_text' => __('Get Started for free'),
            'hero_button_url' => '#signup',
            'signup_section_name' => __('Sign Up'),
            'signup_title' => __('Get Started with StudyBuddy for free. No credit card required. No commitment. Cancel anytime.'),
            'signup_subtitle' => __('Increase your productivity with StudyBuddy!'),
            'signup_reasons' => [
                __('Works everywhere, whether on a PC, tablet, or mobile device.'),

                __('No software to install. No updates to install. No hassle.'),
                __('No credit card required. No commitment. Cancel anytime.'),
            ],
            'facebook' => __('https://www.facebook.com/'),
            'twitter' => __('https://www.twitter.com/'),
            'github' => __('https://www.github.com/'),
            'slack' => __('https://www.slack.com/'),

            'feature_title' => __('Revolutionize the way you study with Cutting-Edge AI integrated Productivity Software!'),
            'feature_subtitle' => __('Works everywhere, whether on a PC, tablet, or mobile device.'),
            'feature_1_title' => __('Ask AI Tutor'),
            'feature_1_image' => 'media/feature-chat.png',

            'feature_1_subtitle' => __('Dont know answer of some questions? No problem your AI tutor will answer questions on any suubject. Chat with Ai and increase your knowledge.'),
            'feature_1_icon' => '<svg class="flex-shrink-0 mt-2 h-6 w-6 md:w-7 md:h-7 hs-tab-active:text-blue-600 text-gray-800 dark:hs-tab-active:text-blue-500"
                   xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M5.5 2A3.5 3.5 0 0 0 2 5.5v5A3.5 3.5 0 0 0 5.5 14h5a3.5 3.5 0 0 0 3.5-3.5V8a.5.5 0 0 1 1 0v2.5a4.5 4.5 0 0 1-4.5 4.5h-5A4.5 4.5 0 0 1 1 10.5v-5A4.5 4.5 0 0 1 5.5 1H8a.5.5 0 0 1 0 1H5.5z"/>
                <path d="M16 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
              </svg>

',
            'feature_2_title' => __('Assignment Management'),
            'feature_2_image' => 'media/feature-project.png',
            'feature_2_subtitle' => __('Plan for your study discuss with your friends and get the best out of your study time.'),
            'feature_2_icon' => '<svg class="flex-shrink-0 mt-2 h-6 w-6 md:w-7 md:h-7 hs-tab-active:text-blue-600 text-gray-800 dark:hs-tab-active:text-blue-500"
                   xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M0 0h1v15h15v1H0V0Zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07Z"/>
              </svg>

',
            'feature_3_title' => __('Create Notes'),
            'feature_3_image' => 'media/feature-ai-image.png',
            'feature_3_subtitle' => __('Create amazing notes, highlight important ideas.You can also take help from AI to create notes.'),
            'feature_3_icon' => '<svg class="flex-shrink-0 mt-2 h-6 w-6 md:w-7 md:h-7 hs-tab-active:text-blue-600 text-gray-800 dark:hs-tab-active:text-blue-500 "
                   xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M5.52.359A.5.5 0 0 1 6 0h4a.5.5 0 0 1 .474.658L8.694 6H12.5a.5.5 0 0 1 .395.807l-7 9a.5.5 0 0 1-.873-.454L6.823 9.5H3.5a.5.5 0 0 1-.48-.641l2.5-8.5zM6.374 1 4.168 8.5H7.5a.5.5 0 0 1 .478.647L6.78 13.04 11.478 7H8a.5.5 0 0 1-.474-.658L9.306 1H6.374z"/>
              </svg>


',
            'feature_highlight_section_name' => __('Why StudyBuddy?'),
            'feature_highlight_title' => __('StudyBuddy is made to make you more efficient and productive.'),
            'feature_highlight_subtitle' => __('Works everywhere, whether on a PC, tablet, or mobile device.'),
            'feature_highlight_feature_1_title' => __('Study Goals'),
            'feature_highlight_feature_1_subtitle' => __('Set your study goals with this app and track your progress efficiently. '),
            'feature_highlight_feature_1_icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
</svg>
',
            'feature_highlight_feature_2_title' => __('Assignment Management'),
            'feature_highlight_feature_2_subtitle' => __('Manage assignments Create documents. Collaborate with others. '),
            'feature_highlight_feature_2_icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
</svg>
',
            'feature_highlight_feature_3_title' => __('Task Management'),
            'feature_highlight_feature_3_subtitle' => __('Manage your tasks and get things done with amazing to-do lists. '),
            'feature_highlight_feature_3_icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
</svg>
',
            'feature_highlight_feature_4_title' => __('Study Notes'),
            'feature_highlight_feature_4_subtitle' => __('Take amazing study notes. Share with classmates. Export as PDF.'),
            'feature_highlight_feature_4_icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
</svg>
',
            'about_section_name' => __('About'),
            'about_section_title' => __('Revolutionary Suite of productivity tools and apps for learners'),
            'about_section_subtitle' => __('StudyBuddy is a revolutionary AI tools on the Cloud. AI writting tool, assignment management tool to manage the projects you create with AI and many more.'),
           // 'about_section_image' => 'media/dashboard.png',
            'pricing_section_name' => __('Plans & Pricing'),
            'pricing_section_title' => __('Pricing'),
            'pricing_section_subtitle' => __('Choose which suite is right for you'),
            'faq_section_name' => __('FAQ'),
            'faq_section_title' => __('Frequently Asked Questions'),
            'faq_section_subtitle' => __('Your questions answered'),
            'faq_questions' => [
                __('What is the difference between the monthly and yearly plans?'),
                __('How do I cancel my subscription?'),
                __('What happens if I cancel my subscription?'),
                __('How do I start a trial?'),
            ],
            'faq_answers' => [
                __('The monthly plan is billed monthly and the yearly plan is billed yearly. The yearly plan is 10% off the monthly price.'),
                __('You can cancel your subscription at any time. If you cancel your subscription, you will continue to have access to your account until the end of your current billing period.'),
                __('If you cancel your subscription, you will continue to have access to your account until the end of your current billing period. You will not be billed again after your current billing period ends.'),
                __('You can start a trial by clicking the "Get Started" or the "Sign Up" button on the pricing page. You do not need a credit card to start a trial.'),
            ],
            'testimonials_section_name' => __('Testimonials'),
            'testimonials_section_title' => __('Testimonials'),
            'testimonials_section_subtitle' => __('What our customers say'),
            'testimonials' => [
                __('It works well and has all the functions I need. I would recommend it to anyone who needs a simple and easy to use document editor.'),
                __('I love this product! This is efficient and productive. I can create documents and share them with my colleagues. I can also export them.'),
                __('I use this product to share assignments with my students. It is very easy to use and I can see the logs who accessed them.'),
            ],
            'testimonials_author' => [
                __('Sarah Malik'),
                __('James Larsson'),
                __('Alice Holmes'),
            ],
            'testimonials_author_title' => [
                __('Student, UT'),
                __('Content Writer, Ray Media'),
                __('Teacher, UT'),
            ],
            'footer_business_short_description' => 'StudyBuddy improves student productivity. It lets students set goals and achieve those efficiently through powerful set of features and tools.',
        ];

        $post_settings['hero_image'] = 'media/hero.png';
        $post_settings['about_section_image'] = 'media/dashboard.png';

        $post->settings = $post_settings;

        $post->save();

        $post = new Post();
        $post->uuid = Str::uuid();
        $post->workspace_id = 1;
        $post->type = 'page';
        $post->title = __('Privacy Policy');
        $post->slug = 'privacy-policy';
        $post->api_name = 'privacy_policy';
        $post->content = '<h2 style="text-align:center; font-weight:bold;">PRIVACY NOTICE</h2>
<p>
  <br>
  <strong>Last updated February 11, 2023</strong>
</p>
<p>This privacy notice for CloudOnex ("Company," "we," "us," or "our"), describes how and why we might collect, store, use, and/or share ("process") your information when you use our services ("Services"), such as when you: <br>Visit our website at <a href="https://www.cloudonex.com">https://www.cloudonex.com</a>, or any website of ours that links to this privacy notice </p>
<p>Engage with us in other related ways, including any sales, marketing, or events <br>Questions or concerns?&nbsp;Reading this privacy notice will help you understand your privacy rights and choices. If you do not agree with our policies and practices, please do not use our Services. If you still have any questions or concerns, please contact us at __________. </p>
<p>&nbsp;</p>
<p>
  <br>SUMMARY OF KEY POINTS
</p>
<p>
  <br>This summary provides key points from our privacy notice, but you can find out more details about any of these topics by clicking the link following each key point or by using our table of contents below to find the section you are looking for. You can also click&nbsp;here&nbsp;to go directly to our table of contents.
</p>
<p>
  <br>What personal information do we process? When you visit, use, or navigate our Services, we may process personal information depending on how you interact with CloudOnex and the Services, the choices you make, and the products and features you use. Click&nbsp;here&nbsp;to learn more.
</p>
<p>
  <br>Do we process any sensitive personal information? We do not process sensitive personal information.
</p>
<p>
  <br>Do we receive any information from third parties? We do not receive any information from third parties.
</p>
<p>
  <br>How do we process your information? We process your information to provide, improve, and administer our Services, communicate with you, for security and fraud prevention, and to comply with law. We may also process your information for other purposes with your consent. We process your information only when we have a valid legal reason to do so. Click&nbsp;here&nbsp;to learn more.
</p>
<p>
  <br>In what situations and with which parties do we share personal information? We may share information in specific situations and with specific third parties. Click&nbsp;here&nbsp;to learn more.
</p>
<p>
  <br>What are your rights? Depending on where you are located geographically, the applicable privacy law may mean you have certain rights regarding your personal information. Click&nbsp;here&nbsp;to learn more.
</p>
<p>
  <br>How do you exercise your rights? The easiest way to exercise your rights is by filling out our data subject request form available here, or by contacting us. We will consider and act upon any request in accordance with applicable data protection laws.
</p>
<p>
  <br>Want to learn more about what CloudOnex does with any information we collect? Click&nbsp;here&nbsp;to review the notice in full.
</p>
<p>
  <br>1. WHAT INFORMATION DO WE COLLECT?
</p>
<p>
  <br>Personal information you disclose to us
</p>
<p>
  <br>In Short:&nbsp;We collect personal information that you provide to us.
</p>
<p>
  <br>We collect personal information that you voluntarily provide to us when you register on the Services,&nbsp;express an interest in obtaining information about us or our products and Services, when you participate in activities on the Services, or otherwise when you contact us.
</p>
<p>&nbsp;</p>
<p>Personal Information Provided by You. The personal information that we collect depends on the context of your interactions with us and the Services, the choices you make, and the products and features you use. The personal information we collect may include the following: <br>names </p>
<p>phone numbers</p>
<p>email addresses</p>
<p>usernames</p>
<p>passwords</p>
<p>Sensitive Information. We do not process sensitive information.</p>
<p>&nbsp;</p>
<p>Payment Data. We may collect data necessary to process your payment if you make purchases, such as your payment instrument number, and the security code associated with your payment instrument. All payment data is stored by __________. You may find their privacy notice link(s) here: __________.</p>
<p>&nbsp;</p>
<p>All personal information that you provide to us must be true, complete, and accurate, and you must notify us of any changes to such personal information.</p>
<p>&nbsp;</p>
<p>2. HOW DO WE PROCESS YOUR INFORMATION?</p>
<p>
  <br>In Short:&nbsp;We process your information to provide, improve, and administer our Services, communicate with you, for security and fraud prevention, and to comply with law. We may also process your information for other purposes with your consent.
</p>
<p>
  <br>We process your personal information for a variety of reasons, depending on how you interact with our Services, including: <br>To facilitate account creation and authentication and otherwise manage user accounts.&nbsp;We may process your information so you can create and log in to your account, as well as keep your account in working order.
</p>
<p>
  <br>To save or protect an individual\'s vital interest. We may process your information when necessary to save or protect an individual’s vital interest, such as to prevent harm.
</p>
<p>&nbsp;</p>
<p>3. WHAT LEGAL BASES DO WE RELY ON TO PROCESS YOUR INFORMATION?</p>
<p>
  <br>In Short:&nbsp;We only process your personal information when we believe it is necessary and we have a valid legal reason (i.e., legal basis) to do so under applicable law, like with your consent, to comply with laws, to provide you with services to enter into or fulfill our contractual obligations, to protect your rights, or to fulfill our legitimate business interests.
</p>
<p>
  <br>If you are located in the EU or UK, this section applies to you.
</p>
<p>
  <br>The General Data Protection Regulation (GDPR) and UK GDPR require us to explain the valid legal bases we rely on in order to process your personal information. As such, we may rely on the following legal bases to process your personal information: <br>Consent.&nbsp;We may process your information if you have given us permission (i.e., consent) to use your personal information for a specific purpose. You can withdraw your consent at any time. Click&nbsp;here&nbsp;to learn more.
</p>
<p>Legal Obligations. We may process your information where we believe it is necessary for compliance with our legal obligations, such as to cooperate with a law enforcement body or regulatory agency, exercise or defend our legal rights, or disclose your information as evidence in litigation in which we are involved.</p>
<p>
  <br>Vital Interests. We may process your information where we believe it is necessary to protect your vital interests or the vital interests of a third party, such as situations involving potential threats to the safety of any person.
</p>
<p>&nbsp;</p>
<p>If you are located in Canada, this section applies to you.</p>
<p>
  <br>We may process your information if you have given us specific permission (i.e., express consent) to use your personal information for a specific purpose, or in situations where your permission can be inferred (i.e., implied consent). You can withdraw your consent at any time. Click&nbsp;here&nbsp;to learn more.
</p>
<p>
  <br>In some exceptional cases, we may be legally permitted under applicable law to process your information without your consent, including, for example: <br>If collection is clearly in the interests of an individual and consent cannot be obtained in a timely way
</p>
<p>For investigations and fraud detection and prevention</p>
<p>For business transactions provided certain conditions are met</p>
<p>If it is contained in a witness statement and the collection is necessary to assess, process, or settle an insurance claim</p>
<p>For identifying injured, ill, or deceased persons and communicating with next of kin</p>
<p>If we have reasonable grounds to believe an individual has been, is, or may be victim of financial abuse</p>
<p>If it is reasonable to expect collection and use with consent would compromise the availability or the accuracy of the information and the collection is reasonable for purposes related to investigating a breach of an agreement or a contravention of the laws of Canada or a province</p>
<p>If disclosure is required to comply with a subpoena, warrant, court order, or rules of the court relating to the production of records</p>
<p>If it was produced by an individual in the course of their employment, business, or profession and the collection is consistent with the purposes for which the information was produced</p>
<p>If the collection is solely for journalistic, artistic, or literary purposes</p>
<p>If the information is publicly available and is specified by the regulations</p>
<p>&nbsp;</p>
<p>4. WHEN AND WITH WHOM DO WE SHARE YOUR PERSONAL INFORMATION?</p>
<p>
  <br>In Short:&nbsp;We may share information in specific situations described in this section and/or with the following third parties.
</p>
<p>&nbsp;</p>
<p>We may need to share your personal information in the following situations: <br>Business Transfers. We may share or transfer your information in connection with, or during negotiations of, any merger, sale of company assets, financing, or acquisition of all or a portion of our business to another company. </p>
<p>&nbsp;</p>
<p>5. DO WE USE COOKIES AND OTHER TRACKING TECHNOLOGIES?</p>
<p>
  <br>In Short:&nbsp;We may use cookies and other tracking technologies to collect and store your information.
</p>
<p>
  <br>We may use cookies and similar tracking technologies (like web beacons and pixels) to access or store information. Specific information about how we use such technologies and how you can refuse certain cookies is set out in our Cookie Notice.
</p>
<p>
  <br>6. IS YOUR INFORMATION TRANSFERRED INTERNATIONALLY?
</p>
<p>
  <br>In Short:&nbsp;We may transfer, store, and process your information in countries other than your own.
</p>
<p>
  <br>Our servers are located in. If you are accessing our Services from outside, please be aware that your information may be transferred to, stored, and processed by us in our facilities and by those third parties with whom we may share your personal information (see "WHEN AND WITH WHOM DO WE SHARE YOUR PERSONAL INFORMATION?" above), in &nbsp;and other countries.
</p>
<p>
  <br>If you are a resident in the European Economic Area (EEA) or United Kingdom (UK), then these countries may not necessarily have data protection laws or other similar laws as comprehensive as those in your country. However, we will take all necessary measures to protect your personal information in accordance with this privacy notice and applicable law.
</p>
<p>
  <br>7. HOW LONG DO WE KEEP YOUR INFORMATION?
</p>
<p>
  <br>In Short:&nbsp;We keep your information for as long as necessary to fulfill the purposes outlined in this privacy notice unless otherwise required by law.
</p>
<p>
  <br>We will only keep your personal information for as long as it is necessary for the purposes set out in this privacy notice, unless a longer retention period is required or permitted by law (such as tax, accounting, or other legal requirements). No purpose in this notice will require us keeping your personal information for longer than the period of time in which users have an account with us.
</p>
<p>
  <br>When we have no ongoing legitimate business need to process your personal information, we will either delete or anonymize such information, or, if this is not possible (for example, because your personal information has been stored in backup archives), then we will securely store your personal information and isolate it from any further processing until deletion is possible.
</p>
<p>
  <br>8. WHAT ARE YOUR PRIVACY RIGHTS?
</p>
<p>
  <br>In Short:&nbsp;In some regions, such as the European Economic Area (EEA), United Kingdom (UK), and Canada, you have rights that allow you greater access to and control over your personal information.&nbsp;You may review, change, or terminate your account at any time.
</p>
<p>
  <br>In some regions (like the EEA, UK, and Canada), you have certain rights under applicable data protection laws. These may include the right (i) to request access and obtain a copy of your personal information, (ii) to request rectification or erasure; (iii) to restrict the processing of your personal information; and (iv) if applicable, to data portability. In certain circumstances, you may also have the right to object to the processing of your personal information. You can make such a request by contacting us by using the contact details provided in the section "HOW CAN YOU CONTACT US ABOUT THIS NOTICE?" below.
</p>
<p>
  <br>We will consider and act upon any request in accordance with applicable data protection laws. <br>&nbsp; <br>If you are located in the EEA or UK and you believe we are unlawfully processing your personal information, you also have the right to complain to your local data protection supervisory authority. You can find their contact details here: https://ec.europa.eu/justice/data-protection/bodies/authorities/index_en.htm.
</p>
<p>
  <br>If you are located in Switzerland, the contact details for the data protection authorities are available here: https://www.edoeb.admin.ch/edoeb/en/home.html.
</p>
<p>
  <br>Withdrawing your consent: If we are relying on your consent to process your personal information, which may be express and/or implied consent depending on the applicable law, you have the right to withdraw your consent at any time. You can withdraw your consent at any time by contacting us by using the contact details provided in the section "HOW CAN YOU CONTACT US ABOUT THIS NOTICE?" below.
</p>
<p>
  <br>However, please note that this will not affect the lawfulness of the processing before its withdrawal nor, when applicable law allows, will it affect the processing of your personal information conducted in reliance on lawful processing grounds other than consent.
</p>
<p>
  <br>Opting out of marketing and promotional communications:&nbsp;You can unsubscribe from our marketing and promotional communications at any time by clicking on the unsubscribe link in the emails that we send, or by contacting us using the details provided in the section "HOW CAN YOU CONTACT US ABOUT THIS NOTICE?" below. You will then be removed from the marketing lists. However, we may still communicate with you — for example, to send you service-related messages that are necessary for the administration and use of your account, to respond to service requests, or for other non-marketing purposes.
</p>
<p>
  <br>Account Information
</p>
<p>
  <br>If you would at any time like to review or change the information in your account or terminate your account, you can: <br>Log in to your account settings and update your user account.
</p>
<p>Upon your request to terminate your account, we will deactivate or delete your account and information from our active databases. However, we may retain some information in our files to prevent fraud, troubleshoot problems, assist with any investigations, enforce our legal terms and/or comply with applicable legal requirements.</p>
<p>
  <br>9. CONTROLS FOR DO-NOT-TRACK FEATURES
</p>
<p>
  <br>Most web browsers and some mobile operating systems and mobile applications include a Do-Not-Track ("DNT") feature or setting you can activate to signal your privacy preference not to have data about your online browsing activities monitored and collected. At this stage no uniform technology standard for recognizing and implementing DNT signals has been finalized. As such, we do not currently respond to DNT browser signals or any other mechanism that automatically communicates your choice not to be tracked online. If a standard for online tracking is adopted that we must follow in the future, we will inform you about that practice in a revised version of this privacy notice.
</p>
<p>
  <br>10. DO CALIFORNIA RESIDENTS HAVE SPECIFIC PRIVACY RIGHTS?
</p>
<p>
  <br>In Short:&nbsp;Yes, if you are a resident of California, you are granted specific rights regarding access to your personal information.
</p>
<p>
  <br>California Civil Code Section 1798.83, also known as the "Shine The Light" law, permits our users who are California residents to request and obtain from us, once a year and free of charge, information about categories of personal information (if any) we disclosed to third parties for direct marketing purposes and the names and addresses of all third parties with which we shared personal information in the immediately preceding calendar year. If you are a California resident and would like to make such a request, please submit your request in writing to us using the contact information provided below.
</p>
<p>
  <br>If you are under 18 years of age, reside in California, and have a registered account with Services, you have the right to request removal of unwanted data that you publicly post on the Services. To request removal of such data, please contact us using the contact information provided below and include the email address associated with your account and a statement that you reside in California. We will make sure the data is not publicly displayed on the Services, but please be aware that the data may not be completely or comprehensively removed from all our systems (e.g., backups, etc.).
</p>
<p>
  <br>CCPA Privacy Notice
</p>
<p>
  <br>The California Code of Regulations defines a "resident" as:
</p>
<p>
  <br>(1) every individual who is in the State of California for other than a temporary or transitory purpose and <br>(2) every individual who is domiciled in the State of California who is outside the State of California for a temporary or transitory purpose
</p>
<p>
  <br>All other individuals are defined as "non-residents."
</p>
<p>
  <br>If this definition of "resident" applies to you, we must adhere to certain rights and obligations regarding your personal information.
</p>
<p>
  <br>What categories of personal information do we collect?
</p>
<p>
  <br>We have collected the following categories of personal information in the past twelve (12) months:
</p>
<p>&nbsp;</p>
<p>We may also collect other personal information outside of these categories through instances where you interact with us in person, online, or by phone or mail in the context of: <br>Receiving help through our customer support channels; </p>
<p>Participation in customer surveys or contests; and</p>
<p>Facilitation in the delivery of our Services and to respond to your inquiries. <br>How do we use and share your personal information? </p>
<p>
  <br>More information about our data collection and sharing practices can be found in this privacy notice.
</p>
<p>
  <br>You may contact us or by referring to the contact details at the bottom of this document.
</p>
<p>
  <br>If you are using an authorized agent to exercise your right to opt out we may deny a request if the authorized agent does not submit proof that they have been validly authorized to act on your behalf.
</p>
<p>
  <br>Will your information be shared with anyone else?
</p>
<p>
  <br>We may disclose your personal information with our service providers pursuant to a written contract between us and each service provider. Each service provider is a for-profit entity that processes the information on our behalf, following the same strict privacy protection obligations mandated by the CCPA.
</p>
<p>
  <br>We may use your personal information for our own business purposes, such as for undertaking internal research for technological development and demonstration. This is not considered to be "selling" of your personal information.
</p>
<p>
  <br>Your rights with respect to your personal data
</p>
<p>
  <br>Right to request deletion of the data — Request to delete
</p>
<p>
  <br>You can ask for the deletion of your personal information. If you ask us to delete your personal information, we will respect your request and delete your personal information, subject to certain exceptions provided by law, such as (but not limited to) the exercise by another consumer of his or her right to free speech, our compliance requirements resulting from a legal obligation, or any processing that may be required to protect against illegal activities.
</p>
<p>
  <br>Right to be informed — Request to know
</p>
<p>
  <br>Depending on the circumstances, you have a right to know: <br>whether we collect and use your personal information;
</p>
<p>the categories of personal information that we collect;</p>
<p>the purposes for which the collected personal information is used;</p>
<p>whether we sell or share personal information to third parties;</p>
<p>the categories of personal information that we sold, shared, or disclosed for a business purpose;</p>
<p>the categories of third parties to whom the personal information was sold, shared, or disclosed for a business purpose;</p>
<p>the business or commercial purpose for collecting, selling, or sharing personal information; and</p>
<p>the specific pieces of personal information we collected about you. <br>In accordance with applicable law, we are not obligated to provide or delete consumer information that is de-identified in response to a consumer request or to re-identify individual data to verify a consumer request. </p>
<p>
  <br>Right to Non-Discrimination for the Exercise of a Consumer’s Privacy Rights
</p>
<p>
  <br>We will not discriminate against you if you exercise your privacy rights.
</p>
<p>
  <br>Right to Limit Use and Disclosure of Sensitive Personal Information
</p>
<p>&nbsp;</p>
<p>We do not process consumer\'s sensitive personal information.</p>
<p>&nbsp;</p>
<p>Verification process</p>
<p>
  <br>Upon receiving your request, we will need to verify your identity to determine you are the same person about whom we have the information in our system. These verification efforts require us to ask you to provide information so that we can match it with information you have previously provided us. For instance, depending on the type of request you submit, we may ask you to provide certain information so that we can match the information you provide with the information we already have on file, or we may contact you through a communication method (e.g., phone or email) that you have previously provided to us. We may also use other verification methods as the circumstances dictate.
</p>
<p>
  <br>We will only use personal information provided in your request to verify your identity or authority to make the request. To the extent possible, we will avoid requesting additional information from you for the purposes of verification. However, if we cannot verify your identity from the information already maintained by us, we may request that you provide additional information for the purposes of verifying your identity and for security or fraud-prevention purposes. We will delete such additionally provided information as soon as we finish verifying you.
</p>
<p>
  <br>Other privacy rights
</p>
<p>You may object to the processing of your personal information.</p>
<p>You may request correction of your personal data if it is incorrect or no longer relevant, or ask to restrict the processing of the information.</p>
<p>You can designate an authorized agent to make a request under the CCPA on your behalf. We may deny a request from an authorized agent that does not submit proof that they have been validly authorized to act on your behalf in accordance with the CCPA. <br>To exercise these rights, you can contact us&nbsp;or by referring to the contact details at the bottom of this document. If you have a complaint about how we handle your data, we would like to hear from you. </p>';
        $post->save();

        $post = new Post();
        $post->uuid = Str::uuid();
        $post->workspace_id = 1;
        $post->type = 'page';
        $post->api_name = 'terms_of_service';
        $post->title = __('Terms of Service');
        $post->slug = 'terms-of-service';
        $post->content = '<h2><strong>Terms and Conditions</strong></h2>
<p>Welcome to&nbsp;CloudOnex!</p>
<p>These terms and conditions outline the rules and regulations for the use of&nbsp;CloudOnex\'s Website, located at <a href="https://www.cloudonex.com" target="_blank" rel="noopener">www.cloudonex.com</a>.</p>
<p>By accessing this website we assume you accept these terms and conditions. Do not continue to use&nbsp;CloudOnex&nbsp;if you do not agree to take all of the terms and conditions stated on this page.</p>
<p>The following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice and all Agreements: &ldquo;Client&rdquo;, &ldquo;You&rdquo; and &ldquo;Your&rdquo; refers to you, the person log on this website and compliant to the Company\'s terms and conditions. &ldquo;The Company&rdquo;, &ldquo;Ourselves&rdquo;, &ldquo;We&rdquo;, &ldquo;Our&rdquo; and &ldquo;Us&rdquo;, refers to our Company. &ldquo;Party&rdquo;, &ldquo;Parties&rdquo;, or &ldquo;Us&rdquo;, refers to both the Client and ourselves. All terms refer to the offer, acceptance and consideration of payment necessary to undertake the process of our assistance to the Client in the most appropriate manner for the express purpose of meeting the Client\'s needs in respect of provision of the Company\'s stated services, in accordance with and subject to, prevailing law of Netherlands. Any use of the above terminology or other words in the singular, plural, capitalization and/or he/she or they, are taken as interchangeable and therefore as referring to same.</p>
<h3><strong>Cookies</strong></h3>
<p>We employ the use of cookies. By accessing&nbsp;CloudOnex, you agreed to use cookies in agreement with the&nbsp;CloudOnex\'s Privacy Policy.</p>
<p>Most interactive websites use cookies to let us retrieve the user\'s details for each visit. Cookies are used by our website to enable the functionality of certain areas to make it easier for people visiting our website. Some of our affiliate/advertising partners may also use cookies.</p>
<h3><strong>License</strong></h3>
<p>Unless otherwise stated,&nbsp;CloudOnex&nbsp;and/or its licensors own the intellectual property rights for all material on&nbsp;CloudOnex. All intellectual property rights are reserved. You may access this from&nbsp;CloudOnex&nbsp;for your own personal use subjected to restrictions set in these terms and conditions.</p>
<p>You must not:</p>
<ul>
<li>Republish material from&nbsp;CloudOnex</li>
<li>Sell, rent or sub-license material from&nbsp;CloudOnex</li>
<li>Reproduce, duplicate or copy material from&nbsp;CloudOnex</li>
<li>Redistribute content from&nbsp;CloudOnex</li>
</ul>
<p>This Agreement shall begin on the date hereof.</p>
<p>Parts of this website offer an opportunity for users to post and exchange opinions and information in certain areas of the website.&nbsp;CloudOnex&nbsp;does not filter, edit, publish or review Comments prior to their presence on the website. Comments do not reflect the views and opinions of&nbsp;CloudOnex,its agents and/or affiliates. Comments reflect the views and opinions of the person who post their views and opinions. To the extent permitted by applicable laws,&nbsp;CloudOnex&nbsp;shall not be liable for the Comments or for any liability, damages or expenses caused and/or suffered as a result of any use of and/or posting of and/or appearance of the Comments on this website.</p>
<p>CloudOnex&nbsp;reserves the right to monitor all Comments and to remove any Comments which can be considered inappropriate, offensive or causes breach of these Terms and Conditions.</p>
<p>You warrant and represent that:</p>
<ul>
<li>You are entitled to post the Comments on our website and have all necessary licenses and consents to do so;</li>
<li>The Comments do not invade any intellectual property right, including without limitation copyright, patent or trademark of any third party;</li>
<li>The Comments do not contain any defamatory, libelous, offensive, indecent or otherwise unlawful material which is an invasion of privacy</li>
<li>The Comments will not be used to solicit or promote business or custom or present commercial activities or unlawful activity.</li>
</ul>
<p>You hereby grant&nbsp;CloudOnex&nbsp;a non-exclusive license to use, reproduce, edit and authorize others to use, reproduce and edit any of your Comments in any and all forms, formats or media.</p>
<h3><strong>Hyperlinking to our Content</strong></h3>
<p>The following organizations may link to our Website without prior written approval:</p>
<ul>
<li>Government agencies;</li>
<li>Search engines;</li>
<li>News organizations;</li>
<li>Online directory distributors may link to our Website in the same manner as they hyperlink to the Websites of other listed businesses; and</li>
<li>System wide Accredited Businesses except soliciting non-profit organizations, charity shopping malls, and charity fundraising groups which may not hyperlink to our Web site.</li>
</ul>
<p>These organizations may link to our home page, to publications or to other Website information so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products and/or services; and (c) fits within the context of the linking party\'s site.</p>
<p>We may consider and approve other link requests from the following types of organizations:</p>
<ul>
<li>commonly-known consumer and/or business information sources;</li>
<li>dot.com community sites;</li>
<li>associations or other groups representing charities;</li>
<li>online directory distributors;</li>
<li>internet portals;</li>
<li>accounting, law and consulting firms; and</li>
<li>educational institutions and trade associations.</li>
</ul>
<p>We will approve link requests from these organizations if we decide that: (a) the link would not make us look unfavorably to ourselves or to our accredited businesses; (b) the organization does not have any negative records with us; (c) the benefit to us from the visibility of the hyperlink compensates the absence of&nbsp;CloudOnex; and (d) the link is in the context of general resource information.</p>
<p>These organizations may link to our home page so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products or services; and (c) fits within the context of the linking party\'s site.</p>
<p>If you are one of the organizations listed in paragraph 2 above and are interested in linking to our website, you must inform us by sending an e-mail to&nbsp;CloudOnex. Please include your name, your organization name, contact information as well as the URL of your site, a list of any URLs from which you intend to link to our Website, and a list of the URLs on our site to which you would like to link. Wait 2-3 weeks for a response.</p>
<p>Approved organizations may hyperlink to our Website as follows:</p>
<ul>
<li>By use of our corporate name; or</li>
<li>By use of the uniform resource locator being linked to; or</li>
<li>By use of any other description of our Website being linked to that makes sense within the context and format of content on the linking party\'s site.</li>
</ul>
<p>No use of&nbsp;CloudOnex\'s logo or other artwork will be allowed for linking absent a trademark license agreement.</p>
<h3><strong>iFrames</strong></h3>
<p>Without prior approval and written permission, you may not create frames around our Webpages that alter in any way the visual presentation or appearance of our Website.</p>
<h3><strong>Content Liability</strong></h3>
<p>We shall not be hold responsible for any content that appears on your Website. You agree to protect and defend us against all claims that is rising on your Website. No link(s) should appear on any Website that may be interpreted as libelous, obscene or criminal, or which infringes, otherwise violates, or advocates the infringement or other violation of, any third party rights.</p>
<h3><strong>Reservation of Rights</strong></h3>
<p>We reserve the right to request that you remove all links or any particular link to our Website. You approve to immediately remove all links to our Website upon request. We also reserve the right to amen these terms and conditions and it\'s linking policy at any time. By continuously linking to our Website, you agree to be bound to and follow these linking terms and conditions.</p>
<h3><strong>Removal of links from our website</strong></h3>
<p>If you find any link on our Website that is offensive for any reason, you are free to contact and inform us any moment. We will consider requests to remove links but we are not obligated to or so or to respond to you directly.</p>
<p>We do not ensure that the information on this website is correct, we do not warrant its completeness or accuracy; nor do we promise to ensure that the website remains available or that the material on the website is kept up to date.</p>
<h3><strong>Disclaimer</strong></h3>
<p>To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website. Nothing in this disclaimer will:</p>
<ul>
<li>limit or exclude our or your liability for death or personal injury;</li>
<li>limit or exclude our or your liability for fraud or fraudulent misrepresentation;</li>
<li>limit any of our or your liabilities in any way that is not permitted under applicable law; or</li>
<li>exclude any of our or your liabilities that may not be excluded under applicable law.</li>
</ul>
<p>The limitations and prohibitions of liability set in this Section and elsewhere in this disclaimer: (a) are subject to the preceding paragraph; and (b) govern all liabilities arising under the disclaimer, including liabilities arising in contract, in tort and for breach of statutory duty.</p>
<p>As long as the website and the information and services on the website are provided free of charge, we will not be liable for any loss or damage of any nature.</p>';
        $post->save();




        $available_modules = require lightPath('modules.php');
        $modules = [];
        foreach ($available_modules as $key => $value) {
            $modules[$key] = true;
        }

        $subscription_plans = [
            [
                'name' => __('Basic'),
                'price_monthly' => 4.99,
                'price_yearly' => 49.99,
                'is_featured' => false,
                'text_token_limit' => '500000',
                'image_token_limit' => '500',
                'features' => [
                    __('Single User'),
                    __('1GB Storage'),
                    __('Create & Share Documents'),
                    __('Create & Share Spreadsheets'),
                    __('Quick Share'),
                    __('Image Editor'),
                    __('Digital Asset Management'),
                    __('Calendar'),
                    __('Address Book'),
                    __('Basic Support'),
                ],
                'modules' => $modules,
            ],
            [
                'name' => __('Standard'),
                'price_monthly' => 9.99,
                'price_yearly' => 99.99,
                'is_featured' => true,
                'text_token_limit' => '900000',
                'image_token_limit' => '1000',
                'features' => [
                    __('2 Users'),
                    __('5GB Storage'),
                    __('Create & Share Documents'),
                    __('Create & Share Spreadsheets'),
                    __('Quick Share'),
                    __('Image Editor'),
                    __('Digital Asset Management'),
                    __('Calendar'),
                    __('Address Book'),
                    __('Standard Support'),
                ],
                'modules' => $modules,
            ],
            [
                'name' => __('Premium'),
                'price_monthly' => 19.99,
                'price_yearly' => 199.99,
                'is_featured' => false,
                'text_token_limit' => '1500000',
                'image_token_limit' => '2000',
                'features' => [
                    __('Unlimited Users'),
                    __('10GB Storage'),
                    __('Create & Share Documents'),
                    __('Create & Share Spreadsheets'),
                    __('Quick Share'),
                    __('Image Editor'),
                    __('Digital Asset Management'),
                    __('Calendar'),
                    __('Address Book'),
                    __('Premium Support'),
                ],
                'modules' => $modules,
            ]
        ];

        foreach ($subscription_plans as $plan) {
            $subscription_plan = new SubscriptionPlan();
            $subscription_plan->workspace_id = $workspace->id;
            $subscription_plan->uuid = Str::uuid();
            $subscription_plan->name = $plan['name'];
            $subscription_plan->price_monthly = $plan['price_monthly'];
            $subscription_plan->price_yearly = $plan['price_yearly'];
            $subscription_plan->is_featured = $plan['is_featured'] ?? false;
            $subscription_plan->features = $plan['features'];
            $subscription_plan->modules = $plan['modules'];
            $subscription_plan->text_token_limit = $plan['text_token_limit'];
            $subscription_plan->image_token_limit = $plan['image_token_limit'];
            $subscription_plan->save();
        }

    }

    public static function updateSchema()
    {
        update_settings(1, [
            'database_version' => config('app.version'),
        ],true);

        if (!Schema::hasColumn('users', 'language')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('language')
                    ->nullable()
                    ->after('timezone');
            });
        }
        if (!Schema::hasColumn('study_goals', 'category_id')) {
            Schema::table('study_goals', function (Blueprint $table) {
                $table->unsignedInteger('category_id')
                    ->default(0)
                    ->after('admin_id');
            });
        }

        if(!Schema::hasTable('goal_categories')) {
            Schema::create('goal_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->uuid();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }


        //Check if paypal_plan_id_monthly column in the subscription_plans table exists
        if (!Schema::hasColumn('subscription_plans', 'paypal_plan_id_monthly')) {
            Schema::table('subscription_plans', function (Blueprint $table) {

                $table->string('paypal_plan_id_monthly')
                    ->nullable()
                    ->after('slug');
                $table->string('paypal_plan_id_yearly')
                    ->nullable()
                    ->after('paypal_plan_id_monthly');

                $table->string('stripe_plan_id_monthly')
                    ->nullable()
                    ->after('paypal_plan_id_yearly');
                $table->string('stripe_plan_id_yearly')
                    ->nullable()
                    ->after('stripe_plan_id_monthly');

            });
        }

        if (!Schema::hasColumn('subscription_plans', 'paddle_plan_id_monthly')) {

            Schema::table('subscription_plans', function (Blueprint $table) {

                $table->string('paddle_plan_id_monthly')
                    ->nullable()
                    ->after('stripe_plan_id_yearly');
                $table->string('paddle_plan_id_yearly')
                    ->nullable()
                    ->after('paddle_plan_id_monthly');

                $table->string('softhash_item_id')
                    ->nullable()
                    ->after('paddle_plan_id_yearly');

            });

        }

        //system api table

        if(!Schema::hasTable('system_apis')){
            Schema::create('system_apis', function (Blueprint $table) {
                $table->id();
                $table->uuid();
                $table->string('label')->nullable();
                $table->string('api_key')->nullable();
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('credit_cards')){
            Schema::create('credit_cards', function (Blueprint $table) {
                $table->id();
                $table->uuid();
                $table->unsignedInteger('workspace_id')->default(0);
                $table->unsignedInteger('gateway_id')->default(0);
                $table->unsignedInteger('user_id')->default(0);
                $table->string('token')->nullable();
                $table->timestamps();
            });
        }


        if (!Schema::hasColumn('users', 'is_email_verified')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_email_verified')
                    ->default(false)
                    ->after('password_reset_token');
                $table->uuid('email_verification_token')
                    ->nullable()
                    ->after('is_email_verified');
            });
        }

        if (!Schema::hasColumn('media_files', 'parent_id')) {
            Schema::table('media_files', function (Blueprint $table) {
                $table->string('related_to')
                    ->nullable()
                    ->after('directory_id');
                $table->unsignedInteger('related_id')
                    ->default(0)
                    ->after('related_to');
                $table->unsignedInteger('parent_id')
                    ->default(0)
                    ->after('related_id');
                $table->unsignedInteger('ai_model_id')
                    ->default(0)
                    ->after('parent_id');
            });
        }

        if (!Schema::hasColumn('subscription_plans', 'is_default')) {

            Schema::table('subscription_plans', function (Blueprint $table) {

                $table->boolean('is_default')
                    ->default(false)
                    ->after('is_featured');

            });

        }


    }

}
