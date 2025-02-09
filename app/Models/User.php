<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Constant;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'account',
        'name',
        'email',
        'tenant_id',
        'head_account_flg',
        'password',
        'job_position',
        'status',
        'avatar',
    ];


    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user');
    }

    public function jobPosition()
    {
        return $this->hasOne(Constant::class, 'key', 'job_position')
            ->where('group', 'job_position');
    }

    public function userStatus()
    {
        return $this->hasOne(Constant::class, 'key', 'status')
            ->where('group', 'user_status');
    }

    public static function createUser($userData)
    {
        $newUser = self::create([
            'email' => $userData['bi_email'],
            'account' => $userData['bi_account'],
            'name' => $userData['bi_full_name'],
            'password' => bcrypt($userData['bi_password']),
            'head_account_flg' => '0',
            'status' => '1'
        ]);
    }
}
