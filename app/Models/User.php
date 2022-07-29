<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Heloufir\FilamentWorkflowManager\Core\WorkflowPermissions;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasRoles;
    use HasApiTokens, HasFactory, Notifiable, WorkflowPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canAccessFilament(): bool
    {
        if (config('app.env') === 'production') {
            return str_ends_with($this->email, env('FILAMENTADMIN_EMAIL')) && $this->hasVerifiedEmail();
        }

        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {

        return $this->avatar ? Storage::url($this->avatar) : null;
    }
}
