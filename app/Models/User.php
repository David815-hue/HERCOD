<?php

namespace App\Models;

use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Yebor974\Filament\RenewPassword\Contracts\RenewPasswordContract;
use Yebor974\Filament\RenewPassword\RenewPasswordPlugin;
use Yebor974\Filament\RenewPassword\Traits\RenewPassword;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements RenewPasswordContract, FilamentUser, MustVerifyEmail
{
    use HasFactory, Notifiable, RenewPassword, HasRoles, HasPanelShield;

    public $timestamps = false;

    protected $table = 'TBL_Usuarios';

    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'username',
        'email',
        'password',
        'inicio_primera_vez',
        'remember_token',
        'creado_por',
        'fecha_creacion',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        //'email_verified_at' => 'datetime',
        //'password' => 'hashed',
        //'Inicio_Primera_Vez' => 'boolean',
    ];

    public function needRenewPassword(): bool
    {
        $plugin = RenewPasswordPlugin::get();
        return is_null($this->{$plugin->getTimestampColumn()});
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->creado_por = Auth::check() ? Auth::user()->username : 'usuario_no_autenticado';
            $model->fecha_creacion = now();
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
