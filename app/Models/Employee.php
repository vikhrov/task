<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Employee extends Model
{
    protected $table = 'employees';
    protected $fillable = [
        'name',
        'position_id',
        'date_of_employment',
        'phone_number',
        'email',
        'salary',
        'photo',
        'admin_created_id',
        'admin_updated_id',
        'level',
        'parent_id',
    ];

    protected $attributes = [
        'photo' => '0', // Установка photo по умолчанию в '0'
        'level' => '5',
    ];

    use HasFactory;

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'parent_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            $employee->admin_created_id = Auth::id(); // Айди админа, который создает запись
        });

        static::updating(function ($employee) {
            $employee->admin_updated_id = Auth::id(); // Айди админа, который обновляет запись
        });
    }
}

