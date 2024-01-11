<?php

namespace App\Http\Controllers;

use App\Mail\TestEmail;
use App\Mail\UserPasswordReset;
use App\Models\Activity;
use App\Models\AiChat;
use App\Models\AiChatSession;
use App\Models\AiPrompt;
use App\Models\ApiKey;
use App\Models\AssignmentMediaRelation;
use App\Models\CalendarEvent;
use App\Models\Contact;
use App\Models\CreditCard;
use App\Models\Document;
use App\Models\Flashcard;
use App\Models\FlashcardCollection;

use App\Models\GoalCategory;
use App\Models\MediaFile;
use App\Models\PaymentGateway;
use App\Models\Post;

use App\Models\ProjectReply;
use App\Models\Projects;
use App\Models\ProjectTask;
use App\Models\QuickShare;
use App\Models\QuickShareAccessLog;
use App\Models\StudyGoal;
use App\Models\SubscriptionPlan;
use App\Models\TimeLog;
use App\Models\Todo;
use App\Models\TokenUse;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Workspace;
use App\Supports\DataHandler;
use App\Supports\InstallSupport;
use DeviceDetector\DeviceDetector;
use Dompdf\Dompdf;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use OpenAI;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Stripe;
use Stripe\Subscription;

class AppController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $post = Post::where('is_home_page', 1)
            ->first();

        $main_post = $post;
        $subscription_plans = SubscriptionPlan::listForSuperAdmin();

        $language = $this->settings['frontend_language'] ?? 'en';
        //Set runtime language
        Config::set('app.locale', $language);

        return \view('website.home', [
            'post' => $post,
            'subscription_plans' => $subscription_plans,
            'main_post' => $main_post,
        ]);
    }

    public function login()
    {
        return view('app.auth', [
            'type' => 'login',
            'page_title' => __('Login'),
        ]);
    }


    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            add_activity($this->workspace_id, __('Failed to login with email') . ':' . $request->email);
            return response([
                'errors' => [
                    'user' => __('Invalid email or password'),
                ],
            ], 422);
        }

        if (!$this->isDemo()) {
            if (!Hash::check($request->password, $user->password)) {
                return response([
                    'errors' => [
                        'password' => __('Invalid email or password'),
                    ],
                ], 422);
            }
        }

        //Check if workspace is active
        $workspace = Workspace::where('id', $user->workspace_id)->first();
        if (!$workspace->is_active) {
            return response([
                'errors' => [
                    'user' => __('Workspace is not active'),
                ],
            ], 422);
        }

        $remember_me = $request->remember_me ? true : false;
        Auth::login($user,$remember_me);

        $user->last_login_at = now();
        $user->save();

        add_activity($this->workspace_id, __('Logged in with email') . ':' . $request->email, $user->id);

        return response([
            'success' => true,
        ]);

    }


    public function viewItem(Request $request, $item, $uuid)
    {
        switch ($item) {
            case 'document':

                $request->validate([
                    'access_key' => 'required|string|max:36',
                ]);

                $document = Document::where('uuid', $uuid)->first();

                abort_unless($document, 404);

                if ($document->access_key != $request->access_key) {
                    abort(404);
                }

                return view('app.view.document', [
                    'document' => $document,
                    'page_title' => $document->title,
                ]);

                break;

            case 'file':

                $request->validate([
                    'access_key' => 'required|string|max:36',
                ]);

                $media_file = MediaFile::where('uuid', $uuid)->first();

                abort_unless($media_file, 404);

                abort_unless($media_file->access_key, 404);

                if ($media_file->access_key != $request->access_key) {
                    abort(404);
                }

                if($request->action == 'download'){

                    $file_path = base_path('uploads/' . $media_file->path);

                    $title = $media_file->title;
                    $extension = $media_file->extension;

                    //Remove extension from title
                    $title = str_replace('.' . $extension, '', $title);

                    $file_name = $title . '.' . $extension;

                    return response()->download($file_path, $file_name);
                }

                return view('app.view.shared-file', [
                    'media_file' => $media_file,
                    'page_title' => $media_file->title,
                ]);

                break;
        }
    }

    public function dashboard()
    {
        $this->authCheck();

        $recent_documents = Document::getRecentDocuments($this->workspace_id, 'word');

        // Recent Activities

        $activities = Activity::where('workspace_id', $this->workspace_id)
            ->orderBy('id', 'desc')
            ->limit(100)
            ->get();

        $activities_stats = [];

        foreach ($activities as $activity) {
            $date = Carbon::parse($activity->created_at)->format('Y-m-d');
            if (!isset($activities_stats[$date])) {
                $activities_stats[$date] = 0;
            }
            $activities_stats[$date]++;
        }

        $recent_contacts = Contact::where('workspace_id', $this->workspace_id)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        $recent_users = User::where('workspace_id', $this->workspace_id)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        $recent_ai_prompts = AiPrompt::where('workspace_id', $this->workspace_id)
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get();

        $ai_prompt_count = AiPrompt::where('workspace_id', $this->workspace_id)
            ->count();

        $total_projects = Projects::where('workspace_id', $this->workspace_id)
            ->count();

        $word_documents_count = Document::where('workspace_id', $this->workspace_id)
            ->where('type', 'word')
            ->count();
        $total_goals= StudyGoal::where('workspace_id', $this->workspace_id)
            ->count();

        $total_goals_completed = StudyGoal::where('workspace_id', $this->workspace_id)
            ->where('completed', '1')
            ->count();

        $total_goals_completed_percentage = 0;

        if($total_goals_completed > 0){
            $total_goals_completed_percentage = round(($total_goals_completed / $total_goals) * 100);
        }


        $total_todos= Todo::where('workspace_id', $this->workspace_id)
            ->count();

        $total_todos_completed = Todo::where('workspace_id', $this->workspace_id)
            ->where('completed', '1')
            ->count();
        $total_todos_completed_percentage = 0;

        if($total_todos_completed > 0){
            $total_todos_completed_percentage = round(($total_todos_completed / $total_todos) * 100);
        }

        $total_assignment_todos= ProjectTask::where('workspace_id', $this->workspace_id)
            ->count();

        $total_assignment_todos_completed = ProjectTask::where('workspace_id', $this->workspace_id)
            ->where('completed', '1')
            ->count();

        $total_assignment_todos_completed_percentage = 0;

        if($total_assignment_todos_completed > 0){
            $total_assignment_todos_completed_percentage = round(($total_assignment_todos_completed / $total_todos) * 100);
        }

        $spreadsheet_documents_count = Document::where('workspace_id', $this->workspace_id)
            ->where('type', 'spreadsheet')
            ->count();

        $recent_todos = Todo::where('workspace_id', $this->workspace_id)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        $recent_projects = Projects::where('workspace_id', $this->workspace_id)
            ->orderBy('id', 'desc')
            ->limit(2)
            ->get();
        $recent_goals = StudyGoal::where('workspace_id', $this->workspace_id)
            ->orderBy('id', 'desc')
            ->limit(2)
            ->get();
        $recent_events = CalendarEvent::where('workspace_id', $this->workspace_id)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        $total_token_usage_this_period_text = TokenUse::getUsageLastThirtyDays($this->workspace_id, 'text');
        $total_token_usage_this_period_image = TokenUse::getUsageLastThirtyDays($this->workspace_id, 'image');

        $total_usage = TokenUse::getAllTimeTotal($this->workspace_id);

        $text_token_limit = $this->active_plan->text_token_limit ?? 0;
        $image_token_limit = $this->active_plan->image_token_limit ?? 0;

        $usage_percentage_text = 0;
        $usage_percentage_image = 0;

        if($text_token_limit > 0){
            $usage_percentage_text = ($total_token_usage_this_period_text / $text_token_limit) * 100;
            $usage_percentage_text = round($usage_percentage_text);
        }

        if($image_token_limit > 0){
            $usage_percentage_image = ($total_token_usage_this_period_image / $image_token_limit) * 100;
            $usage_percentage_image = round($usage_percentage_image);
        }

        $usage_counts_each_day_this_period = TokenUse::getUsageCountsEachDayThisPeriod($this->workspace_id);

        $total_studied_today = TimeLog::where('workspace_id', $this->workspace_id)
            ->where('user_id', $this->user->id)
            ->whereDate('timer_started_at', Carbon::today())
            ->where('timer_stopped_at', '!=', null)
            ->sum('timer_duration');

        $total_studied_today_minutes = floor($total_studied_today / 60);

        $study_trends_last_7_days = TimeLog::getStudyTrendsLast7Days($this->workspace_id, $this->user->id);
        $categories= GoalCategory::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();

        return view('app.dashboard', [
            'navigation' => 'dashboard',
            'page_title' => __('Dashboard'),
            'recent_documents' => $recent_documents,
            'activities_stats' => $activities_stats,
            'recent_contacts' => $recent_contacts,
            'word_documents_count' => $word_documents_count,
            'spreadsheet_documents_count' => $spreadsheet_documents_count,
            'recent_ai_prompts' => $recent_ai_prompts,
            'ai_prompt_count' => $ai_prompt_count,
            'recent_todos' => $recent_todos,
            'recent_projects' => $recent_projects,
            'total_token_usage_this_period_text' => $total_token_usage_this_period_text,
            'total_token_usage_this_period_image' => $total_token_usage_this_period_image,
            'total_usage' => $total_usage,
            'total_projects' => $total_projects,
            'usage_percentage_text' => $usage_percentage_text,
            'usage_percentage_image' => $usage_percentage_image,
            'text_token_limit' => $text_token_limit,
            'image_token_limit' => $image_token_limit,
            'activities'=> $activities,
            'recent_events' => $recent_events,
            'recent_goals' => $recent_goals,
            'recent_users' => $recent_users,
            'total_goals' => $total_goals,
            'total_todos' => $total_todos,
            'usage_counts_each_day_this_period' => $usage_counts_each_day_this_period,
            'total_goals_completed_percentage' => $total_goals_completed_percentage,
            'total_goals_completed'=>  $total_goals_completed,
            'total_todos_completed_percentage' => $total_todos_completed_percentage,
            'total_todos_completed'=>  $total_todos_completed,
            'study_trends_last_7_days' => $study_trends_last_7_days,
            'total_studied_today_minutes' => $total_studied_today_minutes,
            'total_assignment_todos_completed_percentage' => $total_assignment_todos_completed_percentage,
            'total_assignment_todos_completed' => $total_assignment_todos_completed,
            'total_assignment_todos' => $total_assignment_todos,
            'categories' => $categories,


        ]);
    }

    public function goalCategories()
    {

        $categories = GoalCategory::orderBy('id', 'DESC')
            ->where('workspace_id',$this->user->workspace_id)
            ->get();

        return \view('app.study-goal-categories', [
            'navigation' => 'study-goals',
            'page_title' => __('Study Goal Categories'),
            'page_subtitle' => __('Manage goal categories'),
            'categories' => $categories,

        ]);

    }

    public function saveStudygoalCategory(Request $request)
    {
        $request->validate([
            'name'=>'required|max:150',
            'category_id' => 'nullable|integer',
        ]);

        $category = false;

        if($request->category_id)
        {
            $category = GoalCategory::where('workspace_id',$this->user->workspace_id)
                ->where('id',$request->category_id)
                ->first();
        }

        if(!$category)
        {
            $category = new GoalCategory();

        }

        $category->name = $request->name;
        $category->uuid = Str::uuid();
        $category->workspace_id = $this->user->workspace_id;
        $category->save();
        return redirect()->back();

    }
    public function categoryEdit(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $category = GoalCategory::where('workspace_id',$this->user->workspace_id)
            ->where('id',$request->id)
            ->first();

        if($category)
        {
            return response($category);
        }


    }


    public function documents()
    {
        $this->authCheck();
        $this->moduleCheck('documents');

        $documents = Document::getForWorkspace($this->workspace_id, 'word');

        $recent_documents = Document::getRecentDocuments($this->workspace_id, 'word');

        return view('app.documents', [
            'navigation' => 'documents',
            'page_title' => __('Documents'),
            'page_subtitle' => __('Manage your documents'),
            'documents' => $documents,
            'recent_documents' => $recent_documents,
        ]);
    }

    public function document(Request $request)
    {
        $this->authCheck();
        $request->validate([
            'uuid' => 'required|uuid',
        ]);

        $document = Document::where('uuid', $request->uuid)
            ->where('workspace_id', $this->workspace_id)
            ->first();

        abort_unless($document, 404);

        $document->last_opened_at = now();
        $document->last_opened_by = $this->user_id;
        $document->save();

        $navigation = 'documents';

        if ($document->type == 'spreadsheet') {
            $navigation = 'spreadsheets';
        }
        elseif ($document->type == 'presentation') {
            $navigation = 'presentations';
        }

        return view('app.document', [
            'navigation' => $navigation,
            'page_title' => __('Document'),
            'page_subtitle' => __('Manage your documents'),
            'document' => $document,
        ]);
    }

    public function uploadDocumentImage(Request $request)
    {
        $this->authCheck();

        $request->validate([
            'upload' => 'required|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,mp4',
        ]);

        $file = $request->file('upload');

        $has_exceed_storage_limit = MediaFile::hasExceedStorageLimit($this->workspace, $file->getSize());

        if ($has_exceed_storage_limit) {
            return response([
                'error' => [
                    'message' => __('You have exceeded your storage limit.'),
                ],
            ], 400);
        }

        $path = $file->storePublicly('media', 'uploads');

        $media_file = new MediaFile();
        $media_file->workspace_id = $this->workspace_id;
        $media_file->uuid = Str::uuid();
        $media_file->user_id = $this->user_id;
        $media_file->title = $file->getClientOriginalName();
        $media_file->path = $path;
        $media_file->size = $file->getSize();
        $media_file->mime_type = $file->getMimeType();
        $media_file->extension = $file->getClientOriginalExtension();
        $media_file->save();

        return response([
            'url' => config('app.url') . '/uploads/' . $path,
        ]);

    }

    public function saveDocument(Request $request)
    {
        $this->authCheck();
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'uuid' => 'nullable|uuid',
        ]);

        $data = $this->purify($request->all());

        $document = null;

        if (!empty($data['uuid'])) {
            $document = Document::where('uuid', $data['uuid'])
                ->where('workspace_id', $this->workspace_id)
                ->first();
        }

        if (!$document) {
            $request->validate([
                'type' => 'required|string',
            ]);

            $document = new Document();
            $document->uuid = Str::uuid();
            $document->user_id = $this->user_id;
            $document->workspace_id = $this->workspace_id;
            $document->type = $data['type'];
            $document->access_key = Str::random(32);
        }

        $document->title = $data['title'];
        $document->content = app_clean_html_content($request->input('content') ?? '');
        $document->last_opened_at = now();
        $document->save();

        return response([
            'url' => $this->base_url . '/app/document?uuid=' . $document->uuid,
        ]);

    }

    public function loadDocument(Request $request)
    {
        $this->authCheck();
        $request->validate([
            'uuid' => 'required|uuid',
            'access_key' => 'required|string|max:36',
        ]);

        $document = Document::where('uuid', $request->uuid)
            ->where('workspace_id', $this->workspace_id)
            ->first();

        abort_unless($document, 404);

        if ($document->access_key != $request->access_key) {
            abort(404);
        }

        return response($document->content);

    }

    public function downloadDocument(Request $request)
    {
        $this->authCheck();
        $request->validate([
            'uuid' => 'required|uuid',
            'access_key' => 'required|string|max:36',
            'type' => 'required|string',
        ]);

        $document = Document::where('uuid', $request->uuid)
            ->where('workspace_id', $this->workspace_id)
            ->first();

        abort_unless($document, 404);

        if ($document->access_key != $request->access_key) {
            abort(404);
        }

        $type = $request->query('type');

        $file_name = Str::slug($document->title);

        if (empty($file_name)) {
            $file_name = $document->uuid;
        }

        switch ($type) {
            case 'pdf':

                // Generate PDF using dompdf
                $dompdf = new Dompdf();

                $html = view('app.document-pdf', [
                    'document' => $document,
                ])->render();

                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                // Download the generated PDF
                $dompdf->stream($file_name . '.pdf', [
                    'Attachment' => true
                ]);

                break;

            case 'docx':

                $phpWord = new PhpWord();

                $section = $phpWord->addSection();

                $content = clean($document->content, [
                    'HTML.Allowed' => 'p,br,b,strong,i,em,u,ul,ol,li,table,tr,td,th,thead,tbody,span,div,sub,sup,blockquote,hr,a[href|target|title],h1,h2,h3,h4,h5,h6',
                ]);

                Html::addHtml($section, $content);

                $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
                $objWriter->save($file_name . '.docx');

                // Download the generated DOCX
                return response()->download($file_name . '.docx')->deleteFileAfterSend(true);

                break;
            default:
                abort(404);
        }


    }

    public function calendarEvents(Request $request)
    {
        $this->authCheck();
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        $start = $request->start;
        $start = date('Y-m-d', strtotime($start));
        $end = $request->end;
        $end = date('Y-m-d', strtotime($end));

        $data = [];

        $events = CalendarEvent::where('workspace_id', $this->workspace_id)
            ->where('start', '>=', $start)
            ->where('start', '<=', $end)
            ->get();

        foreach ($events as $event) {
            $data[] = [
                'id' => $event->uuid,
                'title' => $event->title,
                'start' => $event->start,
                'end' => $event->end,
                'allDay' => $event->all_day,
            ];
        }


        return response($data);
    }

    public function saveUpload(Request $request)
    {
        $this->authCheck();

        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,mp4',
        ]);

        $file = $request->file('file');

        $has_exceed_storage_limit = MediaFile::hasExceedStorageLimit($this->workspace, $file->getSize());

        if ($has_exceed_storage_limit) {
            session()->flash('error', __('You have exceeded your storage limit.'));
            return response([
                'error' => [
                    'message' => __('You have exceeded your storage limit.'),
                ],
            ], 200);
        }


        try{
            $path = $request
                ->file('file')
                ->store('media', [
                    'visibility' => 'public',
                    'disk' => 'uploads',
                ]);
        }
        catch(\Exception $e){
            ray($e->getMessage());
        }


        $media_file = new MediaFile();
        $media_file->workspace_id = $this->workspace_id;
        $media_file->uuid = Str::uuid();
        $media_file->user_id = $this->user_id;
        $media_file->title = $file->getClientOriginalName();
        $media_file->path = $path;
        $media_file->size = $file->getSize();
        $media_file->mime_type = $file->getMimeType();
        $media_file->extension = $file->getClientOriginalExtension();
        $media_file->save();

    }

    public function spreadsheets()
    {
        $this->authCheck();
        $this->moduleCheck('spreadsheets');
        $documents = Document::getForWorkspace($this->workspace_id, 'spreadsheet');
        $recent_documents = Document::getRecentDocuments($this->workspace_id, 'spreadsheet');
        return view('app.spreadsheets', [
            'navigation' => 'spreadsheets',
            'page_title' => __('Spreadsheets'),
            'page_subtitle' => __('Manage your spreadsheets'),
            'documents' => $documents,
            'recent_documents' => $recent_documents,
        ]);
    }

    public function presentations()
    {
        $this->authCheck();
        $documents = Document::getForWorkspace($this->workspace_id, 'presentation');
        $recent_documents = Document::getRecentDocuments($this->workspace_id, 'presentation');
        return view('app.presentations', [
            'navigation' => 'presentations',
            'page_title' => __('Presentations'),
            'page_subtitle' => __('Manage your presentations'),
            'documents' => $documents,
            'recent_documents' => $recent_documents,
        ]);
    }

    public function calendar()
    {
        $this->authCheck();
        $this->moduleCheck('calendar');
        return view('app.calendar', [
            'navigation' => 'calendar',
            'page_title' => __('Calendar'),
            'page_subtitle' => __('Schedules and events'),
        ]);
    }

    public function addressBook()
    {
        $this->authCheck();
        $contacts = Contact::getForWorkspace($this->workspace_id);
        return view('app.address-book', [
            'navigation' => 'address-book',
            'page_title' => __('Address Book'),
            'page_subtitle' => __('Manage your contacts'),
            'contacts' => $contacts,
        ]);
    }

    public function viewContact(Request $request)
    {
        $this->authCheck();

        $request->validate([
            'uuid' => 'required',
        ]);


        if ($request->input('uuid')) {

            $contact = Contact::getByUuid($this->workspace_id, $request->input('uuid'));

            abort_unless($contact, 404);



            return \view('app.view-contact',[

                'navigation' => 'address-book',
                'contact' => $contact,
                'page_title' => __('Contacts'),
                'page_subtitle' => __('Contact Details'),

            ]);
        }


    }

    public function studygoals()
    {
        $this->authCheck();
        $this->moduleCheck('study_goal');

        $to_learns = StudyGoal::where('workspace_id',$this->user->workspace_id)->orderBy('id','desc')->get();

        $categories= GoalCategory::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();
        return \view('app.studygoals',[

            'navigation' => 'study-goals',
            'page_title' => __('Study Goals'),
            'page_subtitle' => __('Manage your Study Goals'),
            'to_learns'=> $to_learns,
            'categories'=> $categories
        ]);
    }
    public function viewStudygoal(Request $request)
    {

        $this->authCheck();

        $tolearn = false;


        if($request->id)
        {
            $tolearn = StudyGoal::where('workspace_id',$this->user->workspace_id)
                ->where('id',$request->id)
                ->first();
        }
        $categories= GoalCategory::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();

        return \view('app.view-studygoal',[

            'navigation' => 'study-goals',
            'page_title' => __('Study Goals'),
            'page_subtitle' => __('Manage your Study Goals'),
            'tolearn'=> $tolearn,
            'categories'=> $categories
        ]);
    }

    public function newStudygoal(Request $request)
    {
        $this->authCheck();

        $to_learn = false;


        if($request->id)
        {
            $to_learn = StudyGoal::where('workspace_id',$this->user->workspace_id)
                ->where('id',$request->id)
                ->first();
        }

        $categories= GoalCategory::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();


        return \view('app.new-studygoal',[

            'navigation' => 'studygoals',
            'page_title' => __('Study Goals'),
            'page_subtitle' => __('Manage your Study Goals'),
            'to_learn'=> $to_learn,
            'categories'=> $categories

        ]);
    }
    public function saveStudygoal(Request $request)
    {
        $this->authCheck();


        $request->validate([

            'title'=>'required|max:150',
            'id'=>'nullable|integer',
            'category_id'=>'nullable|integer',

        ]);

        $to_learn = false;

        if($request->object_id){

            $to_learn = StudyGoal::where('workspace_id',$this->user->workspace_id)
                ->where('id',$request->object_id)
                ->first();
        }

        if(!$to_learn){

            $to_learn = new StudyGoal();
            $to_learn->uuid = Str::uuid();
            $to_learn->workspace_id = $this->user->workspace_id;
        }

        $to_learn->title = $request->title;
        $to_learn->category_id = $request->category_id;
        $to_learn->reason = $request->reason;
        $to_learn->start_date = $request->start_date;
        $to_learn->end_date = $request->end_date;
        $to_learn->description = clean($request->description);
        $to_learn->save();

        return response([
            'url' => $this->base_url.'/app/studygoals',
        ]);


    }

    public function digitalAssets()
    {
        $this->authCheck();
        $files = MediaFile::getForWorkspace($this->workspace_id);
        return view('app.digital-assets', [
            'navigation' => 'digital-assets',
            'page_title' => __('Digital Assets'),
            'page_subtitle' => __('Manage your digital assets'),
            'files' => $files,
        ]);
    }

    public function images()
    {
        $this->authCheck();
        $files = MediaFile::getForWorkspace($this->workspace_id, 'image');
        return view('app.images', [
            'navigation' => 'images',
            'page_title' => __('Images'),
            'page_subtitle' => __('Manage and edit your images'),
            'files' => $files,
        ]);
    }

    public function imageEditor($uuid)
    {
        $this->authCheck();
        $file = MediaFile::getByUuid($this->workspace_id, $uuid);
        abort_unless($file, 404);
        return view('app.image-editor', [
            'navigation' => 'images',
            'page_title' => __('Image Editor'),
            'page_subtitle' => __('Edit your image'),
            'file' => $file,
        ]);
    }

    public function quickShare(Request $request)
    {
        $this->authCheck();

        $tab = $request->query('tab', 'new');

        $page_title = __('Quick Share');
        $page_subtitle = __('Share your files, docs and more');
        $sub_navigation = 'quick_share_new';
        $shares = [];
        $access_logs = [];

        switch ($tab) {
            case 'shares':
                $page_subtitle = __('Manage your shares');
                $shares = QuickShare::getForWorkspace($this->workspace_id);
                $sub_navigation = 'quick_share_shares';
                break;

            case 'access_logs':
                $page_subtitle = __('Access logs');
                $access_logs = QuickShareAccessLog::getForWorkspace($this->workspace_id);
                $sub_navigation = 'quick_share_access_logs';
                break;
        }

        return view('app.quick-share', [
            'navigation' => 'quick_share',
            'sub_navigation' => $sub_navigation,
            'page_title' => $page_title,
            'page_subtitle' => $page_subtitle,
            'shares' => $shares,
            'access_logs' => $access_logs,
            'tab' => $tab,
        ]);
    }

    public function saveQuickShare(Request $request)
    {
        $this->authCheck();

        $request->validate([
            'uuid' => 'nullable|uuid',
            'title' => 'required|string',
        ]);

        $uuid = $request->input('uuid');
        $type = $request->input('type');

        $quick_share = null;

        if ($uuid) {
            $quick_share = QuickShare::getByUuid($this->workspace_id, $uuid);
            abort_unless($quick_share, 404);
            $type = $quick_share->type;
        }

        if (!$quick_share) {
            $request->validate([
                'type' => 'required|string',
            ]);
            $quick_share = new QuickShare();
            $quick_share->workspace_id = $this->workspace_id;
            $quick_share->uuid = Str::uuid();
            $quick_share->user_id = $this->user_id;
            $quick_share->type = $type;
            $quick_share->short_url_key = Str::random(5);
        }

        if ($type === 'url') {
            $request->validate([
                'url' => 'required|url',
            ]);
            $quick_share->url = $request->input('url');
        } elseif ($type === 'text_snippet') {
            $request->validate([
                'content' => 'required|string',
            ]);

            $quick_share->content = $request->input('content');
        }

        $quick_share->title = $request->input('title');
        $quick_share->save();

        return response([
            'url' => $this->base_url . '/app/view-share/' . $quick_share->uuid,
        ]);

    }

    public function settings(Request $request)
    {
        $this->authCheck();
        $tab = $request->query('tab', 'general');
        $page_subtitle = '';
        $sub_navigation = '';
        $api_keys = [];
        switch ($tab) {
            case 'general':
                $page_subtitle = __('General settings');
                $sub_navigation = 'settings_general';
                break;
            case 'api':
                $page_subtitle = __('API settings');
                $sub_navigation = 'settings_api';
                $api_keys = ApiKey::getForWorkspace($this->workspace_id);
                break;
            case 'about':
                $page_subtitle = __('About');
                $sub_navigation = 'settings_about';
                break;
            case 'users':
                $page_subtitle = __('Users');
                $sub_navigation = 'settings_users';
                break;
        }
        return view('app.settings', [
            'navigation' => 'settings',
            'page_title' => __('Settings'),
            'page_subtitle' => $page_subtitle,
            'tab' => $tab,
            'sub_navigation' => $sub_navigation,
            'api_keys' => $api_keys,
        ]);
    }

    public function appModal(Request $request, $type)
    {
        $this->authCheck();
        switch ($type) {
            case 'share-document':

                $request->validate([
                    'uuid' => 'required|uuid',
                ]);

                $document = Document::getByUuid($this->workspace_id, $request->uuid);

                abort_unless($document, 404);

                return view('app.modals.share-document', [
                    'document' => $document,
                ]);

                break;

            case 'share-file':

                $request->validate([
                    'uuid' => 'required|uuid',
                ]);

                $media_file = MediaFile::getByUuid($this->workspace_id, $request->uuid);

                abort_unless($media_file, 404);

                if(empty($media_file->access_key))
                {
                    $media_file->access_key = Str::random(36);
                    $media_file->save();
                }

                return view('app.modals.share-file', [
                    'media_file' => $media_file,
                ]);

                break;
        }
    }

    public function deleteItem($type, $uuid)
    {
        $this->authCheck();
        switch ($type) {
            case 'document':

                $document = Document::getByUuid($this->workspace_id, $uuid);
                if ($document) {
                    $document->delete();
                }

                return redirect()->route('app.documents');

                break;

            case 'media-file':

                $media_file = MediaFile::getByUuid($this->workspace_id, $uuid);

                if ($media_file) {
                    if ($media_file->path) {
                        Storage::disk('uploads')->delete($media_file->path);
                    }
                    $media_file->delete();
                }

                break;

            case 'calendar-event':

                $event = CalendarEvent::getByUuid($this->workspace_id, $uuid);

                if ($event) {
                    $event->delete();
                }

                break;

            case 'user':

                $user = User::getByUuid($this->workspace_id, $uuid);

                if(!$user)
                {
                    if($this->user->is_super_admin)
                    {
                        $user = User::where('uuid', $uuid)->first();
                    }
                }

                if ($user) {
                    if ($user->id == $this->user_id) {
                        abort(403, __('You cannot delete yourself'));
                    }

                    if($user->id == 1)
                    {
                        abort(403, __('You cannot delete the first user'));
                    }
                    $user->delete();
                }

                break;

            case 'api-key':

                $api_key = ApiKey::getByUuid($this->workspace_id, $uuid);

                if ($api_key) {
                    $api_key->delete();
                }

                break;

            case 'contact':

                $contact = Contact::getByUuid($this->workspace_id, $uuid);

                if ($contact) {
                    $contact->delete();
                }

                break;
            case 'goal-category':

                $category = GoalCategory::getByUuid($this->workspace_id, $uuid);

                if ($category) {
                    $category->delete();
                }

                break;


            case 'quick-share':

                $quick_share = QuickShare::getByUuid($this->workspace_id, $uuid);

                if ($quick_share) {
                    if ($quick_share->path) {
                        Storage::disk('uploads')->delete($quick_share->path);
                    }
                    QuickShareAccessLog::where('workspace_id', $this->workspace_id)
                        ->where('quick_share_id', $quick_share->id)
                        ->delete();

                    $quick_share->delete();
                }

                break;

            case 'quick-share-access-log':

                $quick_share_access_log = QuickShareAccessLog::getByUuid($this->workspace_id, $uuid);

                if ($quick_share_access_log) {
                    $quick_share_access_log->delete();
                }

                break;

            case 'flashcard':

                $flashcard = Flashcard::getByUuid($this->workspace_id, $uuid);

                if ($flashcard) {
                    $flashcard->delete();
                }

                break;

            case 'flashcard-collection':

                $flashcard_collection = FlashcardCollection::getByUuid($this->workspace_id, $uuid);

                if ($flashcard_collection) {

                    $flashcards = Flashcard::where('workspace_id', $this->workspace_id)
                        ->where('collection_id', $flashcard_collection->id)
                        ->get();

                    foreach($flashcards as $flashcard)
                    {
                        $flashcard->delete();
                    }

                    $flashcard_collection->delete();
                }

                break;

            case 'ai-prompt':

                $ai_prompt = AiPrompt::getByUuid($this->workspace_id, $uuid);

                if ($ai_prompt) {
                    $ai_prompt->delete();
                }

                break;

            case 'project':

                $project = Projects::getByUuid($this->workspace_id, $uuid);

                if ($project) {
                    $project->delete();
                }

                break;

            case 'project-todo':

                $todo = ProjectTask::getByUuid($this->workspace_id, $uuid);

                if ($todo) {
                    $todo->delete();
                }

                break;

            case 'chat-session':

                $chat = AiChatSession::getByUuid($this->workspace_id, $uuid);

                if ($chat) {
                    $chat->delete();
                }

                break;
            case 'study-goal':

                $project = StudyGoal::getByUuid($this->workspace_id, $uuid);

                if ($project) {
                    $project->delete();
                }

                break;

            case 'study-goal':

                $project = StudyGoal::getByUuid($this->workspace_id, $uuid);

                if ($project) {
                    $project->delete();
                }

                break;


            case 'todo':

            $project = Todo::getByUuid($this->workspace_id, $uuid);

            if ($project) {
                $project->delete();
            }

            break;



        }

        session()->flash('success', __('Deleted successfully.'));

        return redirect()->back();

    }

    public function saveEvent(Request $request)
    {
        $this->authCheck();
        $request->validate([
            'id' => 'nullable|uuid',
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date',
            'all_day' => 'nullable|boolean',
        ]);


        $event = null;

        $start = $request->input('start');
        $start = date('Y-m-d H:i:s', strtotime($start));
        $end = $request->input('end');
        $end = date('Y-m-d H:i:s', strtotime($end));
        $all_day = 0;
        if ($request->input('all_day')) {
            $all_day = 1;
        }

        if ($request->input('id')) {
            $event = CalendarEvent::getByUuid($this->workspace_id, $request->input('id'));
        }

        if (!$event) {
            $event = new CalendarEvent();
            $event->workspace_id = $this->workspace_id;
            $event->uuid = Str::uuid();
            $event->user_id = $this->user_id;
        }

        $event->title = $request->input('title');
        $event->start = $start;
        $event->end = $end;
        $event->all_day = $all_day;
        $event->save();

    }

    public function contact(Request $request)
    {
        $this->authCheck();
        $request->validate([
            'uuid' => 'nullable|uuid',
        ]);

        $contact = null;
        $page_title = __('Address Book');
        $page_subtitle = __('Add a new contact');

        if ($request->query('uuid')) {
            $contact = Contact::getByUuid($this->workspace_id, $request->query('uuid'));
            if ($contact) {
                $page_subtitle = $contact->first_name . ' ' . $contact->last_name;
            }
        }

        return view('app.contact', [
            'navigation' => 'address-book',
            'page_title' => $page_title,
            'page_subtitle' => $page_subtitle,
            'contact' => $contact,
        ]);
    }

    private function createContact($request)
    {
        $contact = null;
        if ($request->input('uuid')) {
            $contact = Contact::getByUuid($this->workspace_id, $request->input('uuid'));
        }

        if (!$contact) {
            $contact = new Contact();
            $contact->workspace_id = $this->workspace_id;
            $contact->uuid = Str::uuid();
            $contact->user_id = $this->user_id;
        }

        $contact->first_name = $request->input('first_name');
        $contact->last_name = $request->input('last_name');
        $contact->title = $request->input('title');
        $contact->email = $request->input('email');
        $contact->phone = $request->input('phone');
        $contact->address = $request->input('address');
        $contact->city = $request->input('city');
        $contact->state = $request->input('state');
        $contact->zip = $request->input('zip');
        $contact->country = $request->input('country');
        $contact->notes = $request->input('notes');
        $contact->save();

        return $contact;
    }

    public function saveContact(Request $request)
    {
        $this->authCheck();
        $request->validate(Contact::defaultValidationRules());
        $contact = $this->createContact($request);
        return response([
            'success' => true,
            'url' => $this->base_url . '/app/contact?uuid=' . $contact->uuid,
        ]);
    }

    public function createQuickShare(Request $request)
    {
        $this->authCheck();

        if ($this->isDemo()) {
            session()->flash('error', __('This action is disabled in demo mode'));
            return response([
                'url' => $this->base_url . '/app/quick-share?tab=new',
            ]);
        }

        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,mp4',
        ]);

        $file = $request->file('file');
        $path = $file->storePublicly('shares', 'uploads');

        $type = null;

        $mime_type = $file->getMimeType();

        //Check if it is an image
        if (in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
            $type = 'image';
        } elseif ($mime_type == 'application/pdf') {
            $type = 'pdf';
        } elseif (in_array($mime_type, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ])) {
            $type = 'word';
        } elseif (in_array($mime_type, [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ])) {
            $type = 'excel';
        } elseif (in_array($mime_type, [
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ])) {
            $type = 'powerpoint';
        } elseif ($mime_type == 'application/zip') {
            $type = 'zip';
        } //Check if it is mp4 video
        elseif ($mime_type == 'video/mp4') {
            $type = 'video';
        }

        $quick_share = new QuickShare();
        $quick_share->workspace_id = $this->workspace_id;
        $quick_share->uuid = Str::uuid();
        $quick_share->user_id = $this->user_id;
        $quick_share->type = $type;
        $quick_share->path = $path;
        $quick_share->title = $file->getClientOriginalName();
        $quick_share->extension = $file->getClientOriginalExtension();
        $quick_share->mime_type = $file->getMimeType();
        $quick_share->size = $file->getSize();
        $quick_share->short_url_key = Str::random(5);
        $quick_share->save();

        return response([
            'url' => $this->base_url . '/app/view-share/' . $quick_share->uuid,
        ]);


    }



    public function viewShare($uuid)
    {
        $this->authCheck();
        $share = QuickShare::getByUuid($this->workspace_id, $uuid);
        if (!$share) {
            abort(404);
        }

        $access_logs = QuickShareAccessLog::where('quick_share_id', $share->id)->orderBy('id', 'desc')->get();

        return \view('app.view.share', [
            'share' => $share,
            'navigation' => 'quick_share',
            'page_title' => __('Quick Share'),
            'page_subtitle' => $share->title,
            'access_logs' => $access_logs,
        ]);
    }

    public function automaticLogin($type)
    {
        if (config('app.env') == 'production') {
            abort(404);
        }

        switch ($type) {
            case 'admin':

                $user = User::first();

                Auth::login($user);

                return redirect()->route('app.dashboard');

                break;
        }
    }

    public function saveSettings(Request $request)
    {
        $this->authCheck();
        $request->validate([
            'type' => 'required|string',
        ]);

        $type = $request->input('type');

        switch ($type) {
            case 'general':

                $request->validate([
                    'workspace_name' => 'required|string|max:100',
                ]);

                update_settings($this->workspace_id, [
                    'workspace_name' => $request->input('workspace_name'),
                ]);

                if($this->user && $this->user->is_super_admin)
                {
                    update_settings($this->workspace_id, [
                        'frontend_language' => $request->input('frontend_language'),
                    ],true);

                    update_settings($this->workspace_id, [
                        'language' => $request->input('language'),
                    ],true);

                    update_settings($this->workspace_id, [
                        'currency' => $request->input('currency'),
                    ],true);

                    update_settings($this->workspace_id, [
                        'user_requires_email_validation' => $request->input('user_requires_email_validation') ? 1 : 0,
                    ],true);

                }

                session()->flash('success', __('Settings updated successfully'));

                break;

                case 'email-settings':

                    $this->isSuperAdmin();

                    $request->validate([
                        'smtp_host' => 'nullable|string|max:200',
                        'smtp_username' => 'nullable|string|max:200',
                        'smtp_password' => 'nullable|string|max:200',
                        'smtp_port' => 'nullable|integer|max:65536',
                        'mail_encryption' => 'nullable|string|max:200',
                        'mail_from_address' => 'nullable|email|max:200',
                        'mail_from_name' => 'nullable|string|max:200',
                    ]);

                    setEnvironmentValues([
                        'MAIL_MAILER' => 'smtp',
                        'MAIL_HOST' => $request->smtp_host,
                        'MAIL_USERNAME' => $request->smtp_username,
                        'MAIL_PASSWORD' => $request->smtp_password,
                        'MAIL_PORT' => $request->smtp_port,
                        'MAIL_ENCRYPTION' => $request->mail_encryption,
                        'MAIL_FROM_ADDRESS' => $request->mail_from_address,
                        'MAIL_FROM_NAME' => $request->mail_from_name,
                    ]);


                    break;

            case 'test-email':

                $this->isSuperAdmin();

                $request->validate([
                    'email' => 'required|email|max:200',
                ]);

                $email = $request->email;

                //Prevent in demo mode
                if(config('app.env') == 'demo')
                {
                    return response([
                        'errors' => [
                            'email' => [
                                __('This feature is disabled in demo mode')
                            ]
                        ]
                    ],422);
                }

                try{
                    Mail::to($email)->send(new TestEmail($this->user));
                }
                catch (\Exception $e)
                {
                    return response([
                        'errors' => [
                            'email' => [
                                $e->getMessage()
                            ]
                        ]
                    ],422);
                }

                session()->flash('success', __('Email sent successfully'));

                return response([
                    'message' => __('Email sent successfully')
                ]);

                break;
            case 'storage':

                $this->isSuperAdmin();

                $request->validate([
                    'uploads_driver' => 'nullable|string|max:200',
                    'uploads_access_key_id' => 'nullable|string|max:200',
                    'uploads_secret_access_key' => 'nullable|string|max:200',
                    'uploads_default_region' => 'nullable|string|max:65536',
                    'uploads_bucket' => 'nullable|string|max:200',
                    'uploads_endpoint' => 'nullable|string|max:200',
                ]);

                $uploads_driver = $request->uploads_driver;

                if(config('app.env') == 'demo')
                {
                    return response([
                        'errors' => [
                            'email' => [
                                __('This feature is disabled in demo mode')
                            ]
                        ]
                    ],422);
                }


                if($uploads_driver == 'local')
                {
                    setEnvironmentValues([
                        'UPLOADS_DRIVER' => 'local',
                    ]);
                }
                elseif ($uploads_driver == 's3')
                {
                    setEnvironmentValues([
                        'UPLOADS_DRIVER' => 's3',
                        'UPLOADS_ACCESS_KEY_ID' => $request->uploads_access_key_id,
                        'UPLOADS_SECRET_ACCESS_KEY' => $request->uploads_secret_access_key,
                        'UPLOADS_DEFAULT_REGION' => $request->uploads_default_region,
                        'UPLOADS_BUCKET' => $request->uploads_bucket,
                        'UPLOADS_ENDPOINT' => $request->uploads_endpoint,
                    ]);
                }

                break;

            case 'api-key':

                $request->validate([
                    'uuid' => 'required|uuid',
                    'name' => 'required|string|max:100',
                ]);

                $api_key = ApiKey::getByUuid($this->workspace_id, $request->input('uuid'));
                if ($api_key) {
                    $api_key->name = $request->input('name');
                    $api_key->save();
                }

                break;


            case 'logo':

                $request->validate([
                    'logo' => 'required|file|mimes:jpeg,png,jpg,gif',
                ]);

                $file = $request->file('logo');

                $path = $file->storePublicly('logo', 'uploads');

                update_settings($this->workspace_id, [
                    'logo' => $path,

                ],true);

                return redirect()->back();
                break;

            case 'backend_logo':

                $request->validate([
                    'backend_logo' => 'required|file|mimes:jpeg,png,jpg,gif',
                ]);

                $file = $request->file('backend_logo');

                $path = $file->storePublicly('backend_logo', 'uploads');


                update_settings($this->workspace_id, [
                    'backend_logo' => $path,
                ],true);

                return redirect()->back();

                break;

            case 'favicon':

                $request->validate([
                    'favicon' => 'required|file|mimes:jpeg,png,jpg,gif',
                ]);

                $file = $request->file('favicon');

                $path = $file->storePublicly('favicon', 'uploads');

                update_settings($this->workspace_id, [
                    'favicon' => $path,

                ],true);

                return redirect()->back();

                break;

            case 'integrations_openai':

                $this->isSuperAdmin();


                $request->validate([
                    'openai_api_key' => 'nullable|string',
                ]);

                update_settings($this->workspace_id, [
                    'openai_api_key' => $request->input('openai_api_key'),
                ],true);

                break;
        }

        return response([
            'success' => true,
        ]);
    }

    public function user($selected_user)
    {
        $this->authCheck();

        $current_user = null;
        $page_title = __('Users');
        $page_subtitle = __('Add a new user');
        $available_languages = User::$available_languages;
        switch ($selected_user) {
            case 'me':
                $current_user = $this->user;
//                $data['available_languages'] = User::$available_languages;

                break;

            case 'new':
                break; # do nothing

            default:
                $current_user = User::getByUuid($this->workspace_id, $selected_user);
                break;

        }

        if ($current_user) {
            $page_subtitle = $current_user->first_name . ' ' . $current_user->last_name;
        }

        return \view('app.user', [
            'navigation' => 'settings',
            'page_title' => $page_title,
            'page_subtitle' => $page_subtitle,
            'current_user' => $current_user,
            'sub_navigation' => 'settings_users',
            'available_languages'=>$available_languages ?? [],
        ]);

    }

    public function saveUser(Request $request)
    {
        $this->authCheck();
        $request->validate([
            'current_user' => 'nullable|string|max:36',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:255',
            'password' => 'nullable|string|confirmed|min:6',
        ]);

        $user = null;
        $current_user = $request->input('current_user');

        if ($current_user) {
            //Check if user is editing himself
            if ($current_user == 'me') {
                $user = $this->user;
            } else {
                $user = User::getByUuid($this->workspace_id, $current_user);
            }
        }
        if (!$user) {
            //Check if email is already taken
            $user = User::where('email', $request->input('email'))->first();
            if ($user) {
                return response([
                    'success' => false,
                    'errors' => [
                        'email' => __('This email is already taken'),
                    ],
                ], 422);
            }

            $user = new User();
            $user->workspace_id = $this->workspace_id;
            $user->uuid = Str::uuid();
        }

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->language = $request->input('language');
        $user->password = $request->input('password') ? Hash::make($request->input('password')) : $user->password;
        $user->save();

        add_activity($this->workspace_id, __('User updated: ' . $user->first_name . ' ' . $user->last_name), $this->user_id);

        return response([
            'success' => true,
        ]);

    }

    public function manageApi($uuid, Request $request)
    {
        $this->authCheck();

        $api = null;

        if ($uuid == 'new') {
            $api = new ApiKey();
            $api->workspace_id = $this->workspace_id;
            $api->user_id = $this->user_id;
            $api->uuid = Str::uuid();
            $api->name = __('New API Key');
            $api->key = Str::random(32);
            $api->save();
        }

        if (!$api) {
            $api = ApiKey::getByUuid($this->workspace_id, $uuid);
            abort_unless($api, 404);
        }

        $action = $request->query('action', 'view');

        if ($action == 'regenerate') {
            $api->key = Str::random(32);
            $api->save();
            return redirect($this->base_url . '/app/manage-api/' . $api->uuid);
        }


        if ($action == 'view') {
            return \view('app.manage-api', [
                'navigation' => 'settings',
                'page_title' => __('API Keys'),
                'page_subtitle' => __('Edit API Key'),
                'api' => $api,
                'sub_navigation' => 'settings_api',
            ]);
        }

    }

    public function apiSaveContact(Request $request)
    {
        $this->apiCheck($request->input('api_key'));

        $validate = Validator::make($request->all(), Contact::defaultValidationRules());

        if ($validate->fails()) {
            return response([
                'success' => false,
                'errors' => $validate->errors(),
            ], 422);
        }

        $contact = $this->createContact($request);

        return response([
            'success' => true,
            'contact' => [
                'uuid' => $contact->uuid,
            ],
        ]);

    }

    public function logout()
    {
        session()->forget('user_id');
        Auth::logout();
        return redirect($this->base_url . '/app/login');
    }

    public function forgotPassword()
    {
        return view('app.auth', [
            'type' => 'forgot_password',
            'page_title' => __('Forgot Password'),
        ]);
    }

    public function passwordReset(Request $request)
    {
        $request->validate([
            'id' => 'required|uuid',
            'token' => 'required|string',
        ]);

        $uuid = $request->input('id');
        $token = $request->input('token');

        $user = User::where('uuid', $uuid)->where('password_reset_token', $token)->first();

        if (!$user) {
            return redirect($this->base_url . '/app/login');
        }

        return view('app.auth', [
            'type' => 'password_reset',
            'page_title' => __('Set New Password'),
            'user' => $user,
            'token' => $token,
            'uuid' => $uuid,
        ]);
    }

    public function passwordResetPost(Request $request)
    {
        $request->validate([
            'uuid' => 'required|uuid',
            'token' => 'required|string',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $uuid = $request->input('uuid');
        $token = $request->input('token');

        $user = User::where('uuid', $uuid)->where('password_reset_token', $token)->first();
        abort_unless($user, 404);

        $user->password = Hash::make($request->input('password'));
        $user->password_reset_token = null;
        $user->save();

        session()->flash('status', __('Password has been reset successfully'));

        return response([
            'success' => true,
        ]);

    }

    public function forgotPasswordPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response([
                'success' => false,
                'errors' => [
                    'email' => __('This email is not registered'),
                ],
            ], 422);
        }

        $user->password_reset_token = Str::random(32);
        $user->save();

        session()->flash('status', __('Password reset link has been sent to your email address'));

        try {
            Mail::to($user->email)->send(new UserPasswordReset($user));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return response([
            'success' => true,
        ]);
    }

    public function share(Request $request, $hare)
    {
        $share = QuickShare::where('short_url_key', $hare)->first();
        abort_unless($share, 404);

        //Get user agent
        $user_agent = $request->header('User-Agent');

        $os = null;
        $device = null;
        $brand = null;
        $model = null;
        $browser = null;
        $is_bot = false;

        try {
            $device_detector = new DeviceDetector($user_agent);

            $device_detector->parse();

            if ($device_detector->isBot()) {
                $is_bot = true;
            }

            $os = $device_detector->getOs();
            $device = $device_detector->getDeviceName();
            $brand = $device_detector->getBrandName();
            $model = $device_detector->getModel();
            $browser = $device_detector->getClient();
        } catch (\Exception $e) {
            Log::alert($e->getMessage());
        }

        //Add access log
        $share_log = new QuickShareAccessLog();
        $share_log->workspace_id = $share->workspace_id;
        $share_log->uuid = Str::uuid();
        $share_log->quick_share_id = $share->id;
        $share_log->ip = get_client_ip();
        $share_log->user_agent = $user_agent;
        $share_log->os = $os['name'] ?? null;
        $share_log->device = $device;
        $share_log->brand = $brand;
       // $share_log->model = $model;
        $share_log->browser = $browser['name'] ?? null;
        $share_log->is_bot = $is_bot;
        $share_log->save();

        $type = $share->type;

        if ($type == 'url') {
            return redirect($share->url);
        }

        return \view('app.share', [
            'share' => $share,
            'page_title' => $share->title,
        ]);

    }

    public function viewFile($uuid)
    {
        $this->authCheck();
        $media_file = MediaFile::getByUuid($this->workspace_id, $uuid);
        abort_unless($media_file, 404);

        return \view('app.view.file', [
            'media_file' => $media_file,
            'page_title' => $media_file->title,
        ]);

    }

    public function downloadMediaFile($uuid)
    {
        $media_file = MediaFile::getByUuid($this->workspace_id, $uuid);
        abort_unless($media_file, 404);

        $file_path = base_path('uploads/' . $media_file->path);

        if (!file_exists($file_path)) {
            abort(404);
        }

        $title = $media_file->title;
        $extension = $media_file->extension;

        //Remove extension from title
        $title = str_replace('.' . $extension, '', $title);

        $file_name = $title . '.' . $extension;

        return response()->download($file_path, $file_name);
    }

    public function downloadSharedFile($uuid)
    {
        $quick_share = QuickShare::where('uuid', $uuid)->first();

        abort_unless($quick_share, 404);
        abort_unless($quick_share->path, 404);

        $file_path = base_path('uploads/' . $quick_share->path);

        if (!file_exists($file_path)) {
            abort(404);
        }

        $title = $quick_share->title;
        $extension = $quick_share->extension;

        //Remove extension from title
        $title = str_replace('.' . $extension, '', $title);

        $file_name = $title . '.' . $extension;

        return response()->download($file_path, $file_name);

    }

    public function signup()
    {
        if (!$this->isSaaS()) {
            abort(404);
        }
        return view('app.auth', [
            'type' => 'signup',
            'page_title' => __('Sign Up'),
        ]);
    }

    public function signupPost(Request $request)
    {
        if (!$this->isSaaS()) {
            abort(404);
        }

        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        //Create workspace
        $workspace = new Workspace();
        $workspace->uuid = Str::uuid();
        $workspace->name = $request->input('first_name');
        $workspace->is_on_free_trial = true;
        $workspace->save();

        $user = new User();
        $user->uuid = Str::uuid();
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->workspace_id = $workspace->id;
        $user->save();
        $user->workspace_id = $workspace->id;
        $user->save();

        session()->put('user_id', $user->id);

        //Check if form is submitted via ajax
        if ($request->ajax()) {
            return response([
                'success' => true,
            ]);
        }

        return redirect()->route('app.dashboard');

    }

    public function ckeditorCloudToken(Request $request)
    {
        $this->authCheck();

        $accessKey = '';
        $environmentId = '';

        $payload = [
            'aud' => $environmentId,
            'iat' => time(),
            'sub' => 'user-' . $this->user->id,
            'user' => [
                'email' => $this->user->email,
                'name' => $this->user->first_name . ' ' . $this->user->last_name,
            ],
            'auth' => [
                'collaboration' => [
                    '*' => [
                        'role' => 'writer',
                    ],
                ],
            ],
        ];

        $jwt = JWT::encode($payload, $accessKey, 'HS256');

        return response([
            'token' => $jwt,
        ]);


    }

    public function billing(Request $request)
    {
        abort_unless($this->isSaaS(), 404);

        $this->authCheck();

        $show_trial_ended_message = $request->query('show_trial_ended_message', false);

        $subscription_plans = SubscriptionPlan::all();

        return view('app.billing', [
            'navigation' => 'settings',
            'page_title' => __('Settings'),
            'page_subtitle' => __('Billing'),
            'sub_navigation' => 'settings_billing',
            'subscription_plans' => $subscription_plans,
            'show_trial_ended_message' => $show_trial_ended_message,
        ]);

    }

    public function subscribe($uuid, Request $request)
    {
        $this->authCheck();

        $subscription_plan = SubscriptionPlan::where('uuid', $uuid)->first();

        if(!$subscription_plan)
        {
            abort(404);
        }

        $payment_gateways = PaymentGateway::where('workspace_id', 1)
            ->where('is_active', 1)
            ->get()
            ->keyBy('api_name')
            ->all();

        $term = $request->query('term', 'monthly');

        $price = $subscription_plan->price_monthly;

        if($term == 'yearly')
        {
            $price = $subscription_plan->price_yearly;
        }

        $price_smallest_unit = $price * 100;

        return view('app.subscribe', [
            'navigation' => 'settings',
            'page_title' => __('Settings'),
            'page_subtitle' => __('Billing'),
            'sub_navigation' => 'settings_billing',
            'subscription_plan' => $subscription_plan,
            'payment_gateways' => $payment_gateways,
            'term' => $term,
            'price' => $price,
            'price_smallest_unit' => $price_smallest_unit,
        ]);

    }

    public function stripeCreatePaymentIntent(Request $request)
    {
        $this->authCheck();

        $request->validate([
            'plan_id' => 'required|uuid',
            'term' => 'required|string',
        ]);

        $user = $this->user;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $user->country = $request->country;
        $user->save();

        $subscription_plan = SubscriptionPlan::getByUuid($request->plan_id);

        if (!$subscription_plan) {
            abort(401);
        }

        $amount = $subscription_plan->price_monthly;

        if ($request->term === 'yearly') {
            $amount = $subscription_plan->price_yearly;
        }

        $amount = $amount * 100;

        $gateway = PaymentGateway::where('api_name', 'stripe')->first();

        if (!$gateway) {
            abort(401);
        }

        Stripe::setApiKey($gateway->api_secret);

        $paymentIntent = PaymentIntent::create([
            'amount' => $amount, // ensure this is in the smallest currency unit (cents for USD)
            'currency' => $settings['currency'] ?? config('app.currency'),
            'description' => __('Payment for'). ' ' . $subscription_plan->name,
            'shipping' => [
                'name' => $this->user->first_name . ' ' . $this->user->last_name,
                'address' => [
                    'line1' => $this->user->address,
                    'city' => $this->user->city,
                    'state' => $this->user->state,
                    'postal_code' => $this->user->zip,
                    'country' => $this->user->country,
                ],
            ],
        ]);

        return response()->json(['client_secret' => $paymentIntent->client_secret]);

    }

    public function paymentStripe(Request $request)
    {
        $this->authCheck();
        ray($request->all());
        $request->validate([
            'plan_id' => 'required|uuid',
            'term' => 'required|string',
            'payment_intent_id' => 'required',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip' => 'nullable|string',
            'country' => 'nullable|string',
        ]);

        $plan = SubscriptionPlan::getByUuid($request->plan_id);

        $user = $this->user;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $user->country = $request->country;
        $user->save();

        if ($plan) {
            $next_renewal_date = date('Y-m-d');
            if ($request->term === 'monthly') {
                $amount = $plan->price_monthly;
                $stripe_plan = $plan->stripe_plan_id_monthly;
                $next_renewal_date = date('Y-m-d', strtotime('+1 month'));
            } elseif ($request->term === 'yearly') {
                $amount = $plan->price_yearly;
                $next_renewal_date = date('Y-m-d', strtotime('+1 year'));
                $stripe_plan = $plan->stripe_plan_id_yearly;
            } else {
                abort(401);
            }

            $gateway = PaymentGateway::where('api_name', 'stripe')->first();

            if (!$gateway) {
                abort(401);
            }

            $paymentIntentId = $request->input('payment_intent_id');

            try {
                // Set your secret key: remember to change this to your live secret key in production
                // See your keys here: https://dashboard.stripe.com/account/apikeys
                Stripe::setApiKey($gateway->api_secret);

                $intent = PaymentIntent::retrieve($paymentIntentId);

                if ($intent->status !== 'succeeded') {
                    throw new \Exception('Payment failed.');
                }

                // Create a Customer:

                $customer_data = [];


                $customer_data["email"] = $this->user->email;
                $customer_data["name"] =
                    $this->user->first_name . " " . $this->user->last_name;

                $customer_data["address"] = [
                    "line1" => $request->address,
                    "city" => $request->city,
                    "state" => $request->state,
                    "postal_code" => $request->zip,
                    "country" => $request->country,
                ];

                ray($customer_data);



                $customer = Customer::create($customer_data);

                // Attach the payment method to the customer
                $payment_method = \Stripe\PaymentMethod::retrieve($intent->payment_method);
                $payment_method->attach(['customer' => $customer->id]);

                // Set the payment method as default for the customer using the update method
                Customer::update($customer->id, [
                    'invoice_settings' => [
                        'default_payment_method' => $payment_method->id
                    ]
                ]);

                $card = new CreditCard();
                $card->workspace_id = 1;
                $card->uuid = Str::uuid();
                $card->gateway_id = $gateway->id;
                $card->user_id = $this->user->id;
                $card->token = $customer->id;
                $card->save();

                //Create stripe subscription
                Subscription::create([
                    'customer' => $customer->id,
                    'items' => [
                        [
                            'price' => $stripe_plan,
                        ],
                    ],
                ]);

                $workspace = Workspace::find($this->user->workspace_id);

                $workspace->is_subscribed = 1;
                $workspace->term = $request->term;
                $workspace->subscription_start_date = date('Y-m-d');
                $workspace->next_renewal_date = $next_renewal_date;
                $workspace->plan_amount = $amount;
                $workspace->is_on_free_trial = 0;
                $workspace->plan_id = $plan->id;
                $workspace->save();

                $transaction = new Transaction();
                $transaction->workspace_id = 1;
                $transaction->uuid = Str::uuid();
                $transaction->gateway_id = $gateway->id;
                $transaction->user_id = $this->user->id;
                $transaction->plan_id = $plan->id;
                $transaction->amount = $amount;
                $transaction->currency = getWorkspaceCurrency($this->super_settings);
                $transaction->payment_method = 'card';
                $transaction->transaction_id = $intent->id ?? '';
                $transaction->date = date('Y-m-d');
                $transaction->description = $workspace->name . ' - ' . $plan->name;
                $transaction->save();

                return redirect('/app/billing')->with('success', __('You have successfully subscribed to the plan!'));

            } catch (\Exception $e) {
                ray($e->getMessage());
                session()->flash('error', $e->getMessage());
                return redirect('/app/billing');
            }
        }

    }

    public function validatePaypalSubscription(Request $request)
    {

        $paypal_gateway = PaymentGateway::where('api_name', 'paypal')
            ->where('workspace_id', 1)
            ->first();

        if ($paypal_gateway) {

            $client_id = $paypal_gateway->api_key;
            $client_secret = $paypal_gateway->api_secret;

            if (!empty($client_id) && !empty($client_secret)) {

                // get access token

                $url = 'https://api.paypal.com/v1/oauth2/token';

                $response = Http::withBasicAuth($client_id, $client_secret)->post($url, [
                    'grant_type' => 'client_credentials',
                ]);

                $access_token = $response->json()['access_token'];

                $subscription_id = $request->input('subscription_id');
                $url = 'https://api.paypal.com/v1/billing/subscriptions/' . $subscription_id;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $access_token,
                ));
                $result = curl_exec($ch);
                curl_close($ch);
                $result = json_decode($result, true);
                if (!empty($result['status']) && $result['status'] == 'ACTIVE') {
                    $plan_id = $result['plan_id'];
                    $plan = SubscriptionPlan::where('paypal_plan_id', $plan_id)->first();
                    if (!empty($plan)) {
                        $this->workspace->plan_id = $plan->id;
                        $this->workspace->is_subscribed = 1;
                        $this->workspace->save();
                    }

                }
            }

            return redirect($this->base_url. '/dashboard')->with('status', 'You have successfully subscribed to the plan!');

        }
    }

    public function privacyPolicy()
    {
        $post = Post::where('api_name', 'privacy_policy')
            ->where('workspace_id', 1)
            ->first();

        $main_post = Post::where('is_home_page', 1)
            ->first();

        return view('website.message',[
            'post' => $post,
            'main_post' => $main_post,
        ]);
    }

    public function termsOfService()
    {
        $post = Post::where('api_name', 'terms_of_service')
            ->where('workspace_id', 1)
            ->first();

        $main_post = Post::where('is_home_page', 1)
            ->first();

        return view('website.message',[
            'post' => $post,
            'main_post' => $main_post,
        ]);
    }

    public function askAiToWrite(Request $request)
    {
        $this->authCheck();
        $this->validate($request, [
            'content' => 'required',
        ]);

        $content = $request->input('content');

        $result = '';

        if(!empty($this->super_settings['openai_api_key']) && $content)
        {
            if($this->isDemo() || $this->super_settings['openai_api_key'] == 'demo')
            {
                //For Demo, no translation is required
                $result = 'Hey there! Unfortunately, this feature is disabled in the Demo. To know how to enable it in the Live, visit- https://www.cloudonex.com/help/cloudoffice-saas/how-to-integrate-with-chatgpt';
            }
            else{
                //Set OpenAI API Key
                $client = OpenAI::client($this->super_settings['openai_api_key']);

                try {
                    $response = $client->chat()->create([
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => $content,
                            ],
                        ],
                    ]);

                    if(!empty($response->choices))
                    {
                        foreach ($response->choices as $data) {
                            $result .= $data->message->content;
                        }

                        TokenUse::addForWorkspace($this->user->workspace_id, $response->usage->totalTokens?? 0);
                    }
                }
                catch (\Exception $e)
                {
                    if($this->user->is_super_admin)
                    {
                        $result = 'Error: '.$e->getMessage();
                    }
                    else{
                        $result = __('Sorry, I am not able to write anything for you.');
                    }
                }
            }

        }

        if(empty($result))
        {
            $result = __('Sorry, I am not able to write anything for you.');
        }

        //Convert result markdown to html
        $result = Str::markdown($result);

        return response()->json([
            'success' => true,
            'result' => $result,
        ]);

    }

    public function flashcards(Request $request, $action = '')
    {
        $this->authCheck();
        $this->moduleCheck('flashcards');

        switch ($action)
        {
            case '':

                $collections = FlashcardCollection::where('workspace_id', $this->workspace->id)
                    ->orderBy('id', 'desc')
                    ->get();

                return \view('app.flashcards.collections', [
                    'collections' => $collections,
                    'navigation' => 'flashcards',
                    'page_title' => __('Flashcards'),
                    'page_subtitle' => __('Manage'),
                ]);

                break;

            case 'card':

                $request->validate([
                    'uuid' => 'required|uuid',
                ]);

                $card = Flashcard::where('uuid', $request->input('uuid'))
                    ->where('workspace_id', $this->workspace->id)
                    ->first();

                abort_unless($card, 404);

                return \view('app.flashcards.card', [
                    'card' => $card,
                    'navigation' => 'flashcards',
                    'page_title' => __('Flashcards'),
                    'page_subtitle' => $card->title,
                ]);

                break;

            case 'cards':
            case 'learn':
            case 'shuffle':

                $request->validate([
                    'uuid' => 'required|uuid',
                ]);

                $collection = FlashcardCollection::where('uuid', $request->query('uuid'))
                    ->where('workspace_id', $this->workspace->id)
                    ->first();

                abort_unless($collection, 404);

                $cards = Flashcard::where('collection_id', $collection->id)
                    ->where('workspace_id', $this->workspace->id)
                    ->orderBy('sort_order', 'asc')
                    ->get();

                if($action == 'shuffle')
                {
                    foreach ($cards as $card)
                    {
                        $card->sort_order = rand(1, 1000);
                        $card->save();
                    }

                    // Redirect to cards page
                    return redirect()->back();

                }

                $data = [
                    'collection' => $collection,
                    'cards' => $cards,
                    'navigation' => 'flashcards',
                    'page_title' => __('Flashcards'),
                    'page_subtitle' => $collection->title,
                ];

                if($action == 'cards')
                {
                    return \view('app.flashcards.cards', $data);
                }

                $data['layout_remove_footer'] = true;

                return \view('app.flashcards.learn', $data);

                break;




        }
    }
    public function saveFlashcard(Request $request, $action)
    {
        $this->authCheck();
        switch ($action)
        {
            case 'save-collection':

                $this->validate($request, [
                    'title' => 'required|string|max:255',
                    'uuid' => 'nullable|uuid',
                ]);

                $collection = null;

                if($request->input('uuid'))
                {
                    $collection = FlashcardCollection::where('uuid', $request->input('uuid'))
                        ->where('workspace_id', $this->workspace->id)
                        ->first();
                }

                if(!$collection)
                {
                    $collection = new FlashcardCollection();
                    $collection->workspace_id = $this->workspace->id;
                    $collection->uuid = Str::uuid();
                }

                $collection->title = $request->input('title');
                $collection->save();

                break;

            case 'save-card':

                $this->validate($request, [
                    'title' => 'required|string|max:255',
                    'object_id' => 'nullable|uuid',
                    'collection_id' => 'required|integer',
                    'description' => 'nullable|string',
                ]);

                $card = null;

                if($request->input('uuid'))
                {
                    $card = Flashcard::where('uuid', $request->input('uuid'))
                        ->where('workspace_id', $this->workspace->id)
                        ->first();
                }

                if(!$card)
                {
                    $card = new Flashcard();
                    $card->workspace_id = $this->workspace->id;
                    $card->uuid = Str::uuid();
                    $card->collection_id = $request->input('collection_id');
                }

                $card->title = $request->input('title');
                $card->description = $request->input('description');
                $card->save();

                return response([
                    'url' => $this->base_url.'/app/flashcards/card?uuid='.$card->uuid,
                ]);

                break;
        }
    }



    public function aiPrompts(Request $request, $action = '')
    {
        $this->authCheck();
        switch ($action)
        {
            case '':

                $prompts = AiPrompt::where('workspace_id', $this->workspace->id)
                    ->orderBy('id', 'desc')
                    ->get();

                return \view('app.ai-prompts', [
                    'prompts' => $prompts,
                    'navigation' => 'ai_prompts',
                    'page_title' => __('AI Prompts'),
                    'page_subtitle' => __('Manage'),
                ]);

                break;
        }

    }

    public function saveAiPrompts(Request $request, $action = '')
    {

        $this->authCheck();
        switch ($action)
        {
            case 'save-prompt':

                $this->validate($request, [
                    'uuid' => 'nullable|uuid',
                    'prompt' => 'nullable|string',
                ]);

                $prompt = null;

                if($request->input('uuid'))
                {
                    $prompt = AiPrompt::where('uuid', $request->input('uuid'))
                        ->where('workspace_id', $this->workspace->id)
                        ->first();
                }

                if(!$prompt)
                {
                    $prompt = new AiPrompt();
                    $prompt->workspace_id = $this->workspace->id;
                    $prompt->uuid = Str::uuid();
                }

                $prompt->prompt = $request->input('prompt');
                $prompt->save();

                return response([
                    'url' => $this->base_url.'/app/ai-prompts?uuid='.$prompt->uuid,
                ]);

                break;

            case 'create-document':

                $this->validate($request, [
                    'prompt' => 'required',
                ]);

                $prompt = $request->input('content');

                $result = '';

                if(!empty($this->super_settings['openai_api_key']) && $prompt)
                {
                    if($this->isDemo() || $this->super_settings['openai_api_key'] == 'demo')
                    {
                        //For Demo, no translation is required
                        $result = 'Hey there! Unfortunately, this feature is disabled in the Demo. To know how to enable it in the Live add you openai api in the settings';
                    }
                    else{
                        //Set OpenAI API Key
                        $client = OpenAI::client($this->super_settings['openai_api_key']);

                        try {
                            $response = $client->chat()->create([
                                'model' => 'gpt-3.5-turbo',
                                'messages' => [
                                    [
                                        'role' => 'user',
                                        'content' => $prompt,
                                    ],
                                ],
                            ]);

                            if(!empty($response->choices))
                            {
                                foreach ($response->choices as $data) {
                                    $result .= $data->message->content;
                                }
                            }
                        }
                        catch (\Exception $e)
                        {
                            if($this->user->is_super_admin)
                            {
                                $result = 'Error: '.$e->getMessage();
                            }
                            else{
                                $result = __('Sorry, I am not able to write anything for you.');
                            }
                        }
                    }

                }

                if(empty($result))
                {
                    $result = __('Sorry, I am not able to write anything for you.');
                }

                //Convert markdown result to html
                $result = Str::markdown($result);

                //Create a new document
                $document = new Document();
                $document->workspace_id = $this->workspace->id;
                $document->uuid = Str::uuid();
                $document->user_id = $this->user_id;
                $document->workspace_id = $this->workspace_id;
                $document->type = 'word';
                $document->access_key = Str::random(32);
                $document->title = __('Untitled');
                $document->content = $result;
                $document->last_opened_at = now();
                $document->save();

                TokenUse::addForWorkspace($this->user->workspace_id, $response->usage->totalTokens?? 0);

                return response([
                    'url' => $this->base_url.'/app/document?uuid='.$document->uuid,
                ]);

                break;
        }

    }

    public function viewTodo(Request $request)
    {
        $this->authCheck();

        $todo = false;

        if($request->id)
        {
            $todo = Todo::where('workspace_id',$this->user->workspace_id)
                ->where('id',$request->id)
                ->first();
        }

        return \view('app.view-todo',[

            'navigation' => 'todos',
            'todo' => $todo,
            'page_title' => __('To-dos'),
            'page_subtitle' => __('Manage your tasks'),
        ]);
    }


    public function todos(Request $request)
    {
        $this->authCheck();
        $todo = false;

        if($request->id)
        {
            $todo = Todo::where('workspace_id',$this->user->workspace_id)
                ->where('id',$request->id)
                ->first();
        }
        $todos = Todo::where('workspace_id',$this->user->workspace_id)
            ->orderBy('id', 'DESC')
            ->get();

        return view('app.todos', [
            'navigation' => 'todos',
            'todos' => $todos,
            'todo' => $todo,
            'page_title' => __('To-dos'),
            'page_subtitle' => __('Manage your tasks'),

        ]);
    }



    public function addTask(Request $request)
    {


//        if($this->modules && !in_array('to_dos',$this->modules))
//
//        {
//            abort(401);
//        }



        $todo = false;

        if($request->id)
        {
            $todo = Todo::where('workspace_id',$this->user->workspace_id)
                ->where('id',$request->id)
                ->first();
        }

        $todos= Todo::where('related_id', 0)
            ->where('workspace_id',$this->user->workspace_id)
            ->orderBy('id', 'DESC')
            ->get();


        return \view('app.add-task',[

            'navigation' => 'todos',
            'todo'=> $todo,
            'todos'=> $todos,
            'page_title' => __('To-dos'),



        ]);
    }

    public function todoPost(Request $request)
    {

        $request->validate([

            'title'=>'required|max:150',
            'object_id'=>'nullable|integer',


        ]);

        $todo = false;

        if($request->object_id){

            $todo  = Todo::where('workspace_id',$this->user->workspace_id)
                ->where('id',$request->object_id)
                ->first();
        }

        if(! $todo ){

            $todo  = new Todo();
            $todo->uuid = Str::uuid();
            $todo->workspace_id = $this->user->workspace_id;

        }

        $todo->title = $request->title;
        $todo->date = $request->date;
        $todo->status = $request->status;
        $todo->description= clean($request->description);


        $todo->save();


        return response([
            'url' => $this->base_url.'/app/todos',
        ]);



    }


    public function ProjectTaskPost(Request $request)
    {

        $request->validate([

            'title'=>'required|max:150',
            'admin_id'=>'nullable|integer',
            'object_id'=>'nullable|integer',


        ]);

        $todo = false;

        if($request->todo_id){

            $todo = ProjectTask::where('workspace_id',$this->user->workspace_id)
                ->where('id',$request->todo_id)
                ->first();
        }

        if(! $todo ){

            $todo  = new ProjectTask();
            $todo->uuid = Str::uuid();
            $todo->workspace_id = $this->user->workspace_id;

        }

        $todo->title = $request->title;
        $todo->date = $request->date;
        $todo->description= clean($request->description);
        $todo->admin_id = $request->admin_id;
        $todo->project_id = $request->project_id;

        $todo->save();


        return response([
            'url' => $this->base_url.'/app/view-assignment-tasks?id='.$request->project_id,
        ]);



    }




    public function aiChat(Request $request)
    {
        $this->authCheck();
        $this->moduleCheck('ai_chat');

        $today= Carbon::today()->format('Y-m-d');

        $recent_chat_sessions = AiChatSession::where('workspace_id',$this->user->workspace_id)
            ->orderBy('id','DESC')
            ->limit(20)
            ->get();

        $active_chat_session = null;
        $chats = [];

        if($request->query('session_id'))
        {
            if($request->query('session_id') == 'new')
            {
                $active_chat_session = new AiChatSession();
                $active_chat_session->workspace_id = $this->user->workspace_id;
                $active_chat_session->uuid = Str::uuid();
                $active_chat_session->save();
            }
            else{
                $active_chat_session = AiChatSession::where('workspace_id',$this->user->workspace_id)
                    ->where('uuid',$request->query('session_id'))
                    ->first();
            }
        }

        if($active_chat_session)
        {
            $chats = AiChat::where('workspace_id',$this->user->workspace_id)
                ->where('session_id',$active_chat_session->id)
                ->orderBy('id','ASC')
                ->limit(50)
                ->get();
        }

        return \view('app.ai-chat',[
            'navigation' => 'ai_chat',
            'page_title' => __('AI Tutor'),
            'page_subtitle' => __('Ask AI anything'),
            'recent_chat_sessions' => $recent_chat_sessions,
            'active_chat_session' => $active_chat_session,
            'chats' => $chats,
            'today' => $today,

        ]);
    }





    public function projects()
    {


        $projects = Projects::where("workspace_id", $this->user->workspace_id)->get();

        $users = User::all()
            ->keyBy("id")
            ->all();


        return \view("app.projects", [
            "navigation" => "projects",
            "projects" => $projects,
            "users" => $users,
            'page_title' => __('Assignments'),
            'page_subtitle' => __('Manage your assignments'),
        ]);
    }
    public function projectList()
    {


        $projects = Projects::where("workspace_id", $this->user->workspace_id)
            ->get();

        $users = User::all()
            ->keyBy("id")
            ->all();


        return \view("app.project-list", [
            "navigation" => "projects",
            "projects" => $projects,
            "users" => $users,
            'page_title' => __('Assignments'),
            'page_subtitle' => __('Manage your assignments'),
        ]);
    }
    public function createProject(Request $request)
    {
        $project = false;
        $members = [];

        if ($request->id) {
            $project = Projects::where(
                "workspace_id",
                $this->user->workspace_id
            )
                ->where("id", $request->id)
                ->first();
        }

        $other_users = User::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();

        if ($project && $project->members) {
            $members = json_decode($project->members, true);
        }
        $contacts = Contact::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();
        $studygoals = StudyGoal::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();
        $categories= GoalCategory::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();

        return \view("app.create-project", [
            "navigation" => "projects",
            "project" => $project,
            "other_users" => $other_users,
            "members" => $members,
            "contacts" => $contacts,
            'page_title' => __('projects'),
            'page_subtitle' => __('Create New project'),
            'studygoals' => $studygoals,
            'categories'=>$categories,
        ]);
    }

    public function projectView(Request $request)
    {
        $project = false;

        if ($request->id) {
            $project = Projects::where(
                "workspace_id",
                $this->user->workspace_id
            )
                ->where("id", $request->id)
                ->first();
        }

        $todo = false;

        if($request->todo_id)
        {
            $todo = ProjectTask::where('workspace_id',$this->user->workspace_id)
                ->where('id',$request->todo_id)
                ->first();
        }
        abort_unless($project, 401);

        $users = User::all()
            ->keyBy("id")
            ->all();
        $contacts = Contact::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();
        $goals = StudyGoal::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();
        $members = [];
        if ($project && $project->members) {
            $members = json_decode($project->members, true);
        }

        $todos = ProjectTask::where('workspace_id',$this->user->workspace_id)
            ->where('project_id',$project->id)
            ->orderBy('id', 'DESC')
            ->get();

        $total_todos = count($todos);

        $completed_todos = count($todos->where('completed',1));

        $progress = 0;
        if($total_todos > 0)
        {
            $progress = ($completed_todos/$total_todos)*100;
        }

        $progress = round($progress);

        return \view("app.view-project", [
            "navigation" => "Assignments",
            "selected_nav" => "details",
            "project" => $project,
            "todo" => $todo,
            "todos" => $todos,
            "goals" => $goals,
            "members" => $members,
            "users" => $users,
            "contacts" => $contacts,
            'page_title' => __('Assignments'),
            'page_subtitle' => __('Assignment Details'),
            'progress' => $progress,
        ]);
    }

    public function projectTasksView(Request $request)
    {
        $project = false;

        if ($request->id) {
            $project = Projects::where(
                "workspace_id",
                $this->user->workspace_id
            )
                ->where("id", $request->id)
                ->first();
        }

        $todo = false;

        if($request->todo_id)
        {
            $todo = ProjectTask::where('workspace_id',$this->user->workspace_id)
                ->where('id',$request->todo_id)
                ->first();
        }
        abort_unless($project, 401);

        $users = User::all()
            ->keyBy("id")
            ->all();
        $contacts = Contact::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();
        $goals = StudyGoal::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();
        $members = [];
        if ($project && $project->members) {
            $members = json_decode($project->members, true);
        }

        $todos = ProjectTask::where('workspace_id',$this->user->workspace_id)
            ->where('project_id',$project->id)
            ->orderBy('id', 'DESC')
            ->get();

        $total_todos = count($todos);

        $completed_todos = count($todos->where('completed',1));

        $progress = 0;
        if($total_todos > 0)
        {
            $progress = ($completed_todos/$total_todos)*100;
        }

        $progress = round($progress);

        return \view("app.view-project-tasks", [
            "navigation" => "projects",
            "selected_nav" => "project-tasks",
            "project" => $project,
            "todo" => $todo,
            "todos" => $todos,
            "goals" => $goals,
            "members" => $members,
            "users" => $users,
            "contacts" => $contacts,
            'page_title' => __('Assignments'),
            'page_subtitle' => __('Assignment Tasks'),
            'progress' => $progress,
        ]);
    }

    public function projectDiscussionsView(Request $request)
    {
        $project = false;

        if ($request->id) {
            $project = Projects::where(
                "workspace_id",
                $this->user->workspace_id
            )
                ->where("id", $request->id)
                ->first();
        }

        $todo = false;

        if($request->todo_id)
        {
            $todo = ProjectTask::where('workspace_id',$this->user->workspace_id)
                ->where('id',$request->todo_id)
                ->first();
        }
        abort_unless($project, 401);

        $users = User::all()
            ->keyBy("id")
            ->all();
        $contacts = Contact::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();
        $goals = StudyGoal::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();
        $replies = ProjectReply::where("project_id", $project->id)
            ->orderBy("id", "desc")
            ->get();
        $members = [];
        if ($project && $project->members) {
            $members = json_decode($project->members, true);
        }

        $todos = ProjectTask::where('workspace_id',$this->user->workspace_id)
            ->where('project_id',$project->id)
            ->orderBy('id', 'DESC')
            ->get();

        $recent_users = User::where('workspace_id', $this->workspace_id)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();


        $total_todos = count($todos);

        $completed_todos = count($todos->where('completed',1));

        $progress = 0;
        if($total_todos > 0)
        {
            $progress = ($completed_todos/$total_todos)*100;
        }

        $progress = round($progress);

        return \view("app.view-project-discussion", [
            "navigation" => "projects",
            "selected_nav" => "project-discussions",
            "project" => $project,
            "todo" => $todo,
            "todos" => $todos,
            "goals" => $goals,
            "members" => $members,
            "users" => $users,
            "contacts" => $contacts,
            'page_title' => __('Assignments'),
            'page_subtitle' => __('Assignment Discussions'),
            'progress' => $progress,
            'replies' => $replies,
            'recent_users' => $recent_users,
        ]);
    }



    public function projectResourcesView(Request $request)
    {
        $project = false;

        if ($request->id) {
            $project = Projects::where(
                "workspace_id",
                $this->user->workspace_id
            )
                ->where("id", $request->id)
                ->first();
        }

        $todo = false;

        if($request->todo_id)
        {
            $todo = ProjectTask::where('workspace_id',$this->user->workspace_id)
                ->where('id',$request->todo_id)
                ->first();
        }
        abort_unless($project, 401);

        $users = User::all()
            ->keyBy("id")
            ->all();
        $contacts = Contact::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();
        $goals = StudyGoal::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();
        $replies = ProjectReply::where("project_id", $project->id)
            ->orderBy("id", "desc")
            ->get();
        $members = [];
        if ($project && $project->members) {
            $members = json_decode($project->members, true);
        }

        $todos = ProjectTask::where('workspace_id',$this->user->workspace_id)
            ->where('project_id',$project->id)
            ->orderBy('id', 'DESC')
            ->get();

        $recent_users = User::where('workspace_id', $this->workspace_id)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();


        $total_todos = count($todos);

        $completed_todos = count($todos->where('completed',1));

        $progress = 0;
        if($total_todos > 0)
        {
            $progress = ($completed_todos/$total_todos)*100;
        }

        $progress = round($progress);
        $files = MediaFile::where('workspace_id',$this->user->workspace_id)
            ->orderBy('id', 'DESC')
            ->get();


        $attached_files = [];
        $attached_file_ids = AssignmentMediaRelation::where('workspace_id',$this->user->workspace_id)
            ->where('assignment_id',$project->id)
            ->get()
            ->pluck('media_id')
            ->all();

        if($attached_file_ids){
            $attached_files = MediaFile::where('workspace_id',$this->user->workspace_id)
                ->whereIn('id',$attached_file_ids)
                ->get();
        }


        return \view("app.view-project-resources", [
            "navigation" => "projects",
            "selected_nav" => "project-resources",
            "project" => $project,
            "todo" => $todo,
            "todos" => $todos,
            "goals" => $goals,
            "members" => $members,
            "users" => $users,
            'attached_files' => $attached_files,
            "contacts" => $contacts,
            'page_title' => __('Assignments'),
            'page_subtitle' => __('Assignment Resources'),
            'progress' => $progress,
            'replies' => $replies,
            'files' => $files,
            'recent_users' => $recent_users,
        ]);
    }




    public function projectPost(Request $request)
    {
        $request->validate([
            "title" => "required|max:150",
            "object_id" => "nullable|integer",

            "goal_id" => "nullable|integer",
            "members" => "nullable",

        ]);

        $project = false;
        $members = [];
        if ($request->members) {
            $members = $request->members;
        }

        $members = json_encode($members);

        if ($request->object_id) {
            $project = Projects::where(
                "workspace_id",
                $this->user->workspace_id
            )
                ->where("id", $request->object_id)
                ->first();
        }

        if (!$project) {
            $project = new Projects();
            $project->uuid = Str::uuid();
            $project->workspace_id = $this->user->workspace_id;
        }

        $project->title = $request->title;

        $project->goal_id = $request->goal_id;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->summary = $request->summary;
        $project->members = $members;
        $project->status = $request->status;
        $project->budget = $request->budget;
        $project->message = $request->message;
        $project->description = $request->description;
        $project->save();
        return response([
            'url' => $this->base_url.'/app/assignments',
        ]);

    }


    public function projectMessagePost(Request $request)
    {
        $request->validate([
            "message" => "required|string",
            "id" => "nullable|integer",
        ]);

        $project = false;

        if ($request->id) {
            $project = Projects::where(
                "workspace_id",
                $this->user->workspace_id
            )
                ->where("id", $request->id)
                ->first();
        }

        if (!$project) {
            $project = new ProjectReply();
            $project->uuid = Str::uuid();
            $project->workspace_id = $this->user->workspace_id;
        }

        $project->project_id = $request->project_id;
        $project->admin_id = $this->user->id;
        $project->message = $request->message;

        $project->save();

        return redirect()->back();
    }

    public function chat(Request $request)
    {
        $this->authCheck();

        $request->validate([
            'message' => 'required',
            'session_id' => 'nullable|uuid',
        ]);

        $ai_chat_session = null;

        if($request->input('session_id'))
        {
            $ai_chat_session = AiChatSession::where('workspace_id', $this->user->workspace_id)
                ->where('uuid', $request->input('session_id'))
                ->first();
        }

        if(!$ai_chat_session)
        {
            $ai_chat_session = new AiChatSession();
            $ai_chat_session->workspace_id = $this->user->workspace_id;
            $ai_chat_session->user_id = $this->user->id;
            $ai_chat_session->uuid = Str::uuid();
            $ai_chat_session->title = 'Chat with '.$this->user->first_name. ' '.$this->user->last_name;
            $ai_chat_session->save();
        }

        $content = app_clean_input($request->input('message'));


        $result = '';

        $ai_chat = new AiChat();
        $ai_chat->workspace_id = $this->user->workspace_id;
        $ai_chat->user_id = $this->user->id;
        $ai_chat->uuid = Str::uuid();
        $ai_chat->session_id = $ai_chat_session->id;
        $ai_chat->type = 'user';
        $ai_chat->message = $content;
        $ai_chat->save();

        if(!empty($this->super_settings['openai_api_key']) && $content)
        {
            if($this->isDemo() || $this->super_settings['openai_api_key'] == 'demo')
            {
                //For Demo, no translation is required
                $result = 'Hey there! Unfortunately, this feature is disabled in the Demo.';
            }
            else{
                //Set OpenAI API Key
                $client = OpenAI::client($this->super_settings['openai_api_key']);

                try {
                    $response = $client->chat()->create([
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => $content,
                            ],
                        ],
                    ]);

                    if(!empty($response->choices))
                    {
                        foreach ($response->choices as $data) {
                            $result .= $data->message->content;
                        }
                    }

                    TokenUse::addForWorkspace($this->user->workspace_id, $response->usage->totalTokens ?? 0);

                }
                catch (\Exception $e)
                {
                    if($this->user->is_super_admin)
                    {
                        $result = 'Error: '.$e->getMessage();
                    }
                    else{
                        $result = __('Sorry, I am not able to write anything for you.');
                    }
                }
            }

        }

        if(empty($result))
        {
            $result = __('Sorry, I am not able to write anything for you.');
        }

        //Convert result markdown to html
        $result = Str::markdown($result);

        $ai_chat = new AiChat();
        $ai_chat->workspace_id = $this->user->workspace_id;
        $ai_chat->user_id = $this->user->id;
        $ai_chat->session_id = $ai_chat_session->id;
        $ai_chat->uuid = Str::uuid();
        $ai_chat->type = 'system';
        $ai_chat->message = $result;
        $ai_chat->save();


        return response([
            'message' => $result,
            'session_id' => $ai_chat_session->uuid,
            'created_at' => $ai_chat->created_at,
        ]);


    }


    public function aiGenerateImage(Request $request)
    {

        $request->validate([
            'prompt' => 'required|string|max:1000',
        ]);



        if(!empty($this->super_settings['openai_api_key'])) {
            if ($this->isDemo() || $this->super_settings['openai_api_key'] == 'demo') {
                //For Demo, no translation is required
                $result = 'Hey there! Unfortunately, this feature is disabled in the Demo.';
            } else {
                //Set OpenAI API Key
                $client = OpenAI::client($this->super_settings['openai_api_key']);
                $response = $client->images()->create([
                    'prompt' => $request->input('prompt'),
                    'n' => 1,
                    'size' => '512x512',
                    'response_format' => 'url',
                ]);

                $image_url = '';
                foreach ($response->data as $data) {
                    $image_url = $data->url; // 'https://oaidalleapiprodscus.blob.core.windows.net/private/...'
                }

                //Save the image to uploads/media
                $path = 'uploads/media/'.Str::uuid().'.jpg';
                $file = file_get_contents($image_url);
                file_put_contents($path, $file);

                $name = basename($path);
                $size = filesize($path);
                $mime_type = 'image/jpeg';
                $extension = 'jpg';

                $path = str_replace('uploads/', '', $path);

                $media_file = new MediaFile();
                $media_file->workspace_id = $this->workspace_id;
                $media_file->uuid = Str::uuid();
                $media_file->user_id = $this->user_id;
                $media_file->title = $name;
                $media_file->path = $path;
                $media_file->size = $size;
                $media_file->mime_type = $mime_type;
                $media_file->extension = $extension;
                $media_file->is_ai_generated = 1;
                $media_file->save();

                TokenUse::addForWorkspace($this->user->workspace_id, $response->usage->totalTokens?? 0, 'image');


            }
        }

    }

    public function assignmentsAction($action, Request $request)
    {
        switch ($action)
        {
            case 'attach-media':

                $request->validate([
                    'assignment_id' => 'required|integer',
                    'media_id' => 'required|integer',
                ]);

                $relation = new AssignmentMediaRelation();
                $relation->workspace_id = $this->user->workspace_id;
                $relation->assignment_id = $request->input('assignment_id');
                $relation->media_id = $request->input('media_id');
                $relation->save();

                break;
        }
    }

    public function handleTimer()
    {
        $this->authCheck();
        if($this->timer)
        {
            $timer = $this->timer;
            if($timer->is_running)
            {
                $timer->is_running = 0;
                $timer->timer_stopped_at = Carbon::now();
                $timer->timer_duration = $timer->timer_started_at->diffInSeconds($timer->timer_stopped_at);
                $timer->save();
            }
        }
        else{
            $timer = new TimeLog();
            $timer->workspace_id = $this->user->workspace_id;
            $timer->user_id = $this->user->id;
            $timer->is_running = 1;
            $timer->timer_started_at = Carbon::now();
            $timer->save();
        }

        return redirect()->back();
    }

    public function getStudyGoalCategory(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer',
        ]);

        $category = GoalCategory::where('workspace_id', $this->user->workspace_id)->where('id', $request->input('category_id'))->first();

        if($category)
        {
            return response([
                'status' => 'success',
                'category' => $category,
            ]);
        }
        else{
            return response([
                'status' => 'error',
                'message' => __('Category not found.'),
            ]);
        }

    }



}
