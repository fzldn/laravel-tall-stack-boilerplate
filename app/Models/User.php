<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Traits\HasSuperAdmin;
use App\Models\Traits\LogsModel;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use Notifiable;
    use HasRoles {
        roles as protected originalRoles;
    }
    use HasSuperAdmin;
    use LogsModel;

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

    /**
     * Determine if the user can access the Filament panel.
     *
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function logExcept(): array
    {
        return ['remember_token'];
    }

    public function logIncludes(Builder $query): Builder
    {
        return $query
            ->where('subject_type', Relation::getMorphAlias(RoleUser::class))
            ->where(function ($q) {
                $modelMorphKey = config('permission.column_names.model_morph_key');

                $q
                    ->where("properties->attributes->{$modelMorphKey}", $this->getKey())
                    ->orWhere("properties->old->{$modelMorphKey}", $this->getKey());
            });
    }

    /**
     * The roles that belong to the user.
     *
     * @return BelongsToMany<Role>
     */
    public function roles(): BelongsToMany
    {
        return $this->originalRoles()->using(RoleUser::class);
    }
}
