<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static $available_languages = [
        'en' => 'English',
        'it' => 'Italian',
        'fr' => 'French',
        'zh_cn' => 'Chinese',
        'es' => 'Spanish',
        'pt_br' => 'Portuguese(Brazil)',
        'tr' => 'Turkish',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public static function getByUuid($workspace_id, $uuid)
    {
        return self::where('workspace_id', $workspace_id)->where('uuid', $uuid)->first();
    }

    public static function getForWorkspace($workspace_id)
    {
        return self::where('workspace_id', $workspace_id)
            ->get()
            ->keyBy('id')
            ->all();
    }

    public static function listForSuperAdmin()
    {
        return self::all();
    }

    public static function createUser($data)
    {
        $user = new User();
        $user->uuid = Str::uuid();
        $user->workspace_id = 1;
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->is_super_admin = $data['is_super_admin'] ?? 0;
        $user->save();
        return $user;
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
