<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin Eloquent
 */
class Position extends Model
{
    protected $table = 'positions';
    protected $fillable = [
        'name',
        'admin_created_id',
        'admin_updated_id',
    ];

    use HasFactory;


    public function employees()
    {
        return $this->hasMany(Employee::class, 'position_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($position) {
            $position->admin_created_id = Auth::id(); // Айди админа, который создает запись
        });

        static::updating(function ($position) {
            $position->admin_updated_id = Auth::id(); // Айди админа, который обновляет запись
        });

        static::deleting(function ($position) {
            // При удалении должности удаляем всех связанных сотрудников
            $position->employees()->delete();
        });
    }
}

