<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Yebor974\Filament\RenewPassword\Contracts\RenewPasswordContract;
use Yebor974\Filament\RenewPassword\RenewPasswordPlugin;
use Yebor974\Filament\RenewPassword\Traits\RenewPassword;

class User extends Authenticatable implements RenewPasswordContract
{
    use HasFactory, Notifiable, RenewPassword;

    protected $fillable = [
        'name',
        'email',
        'password',
        'inicio_primera_vez', // Añadir el campo `inicio_primera_vez` a los fillables
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'inicio_primera_vez' => 'datetime', // Añadir la conversión para `inicio_primera_vez`
    ];

    public function needRenewPassword(): bool
    {
        $plugin = RenewPasswordPlugin::get();
        return is_null($this->{$plugin->getTimestampColumn()});
    }
}