<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\EmployeeFactory;
use Eloquent;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Static_;

/**
 * @property int $id
 * @property string $name
 * @property int $position_id
 * @property Carbon $date_of_employment
 * @property string $phone_number
 * @property string $email
 * @property int $salary
 * @property string $photo
 * @property ?int $parent_id
 * @property ?int $admin_created_id
 * @property ?int $admin_updated_id
 * @property int $level
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 *
 * @property-read Position $position
 * @property-read Employee $manager
 *
 * @method static EmployeeFactory factory($count = null, $state = [])
 * @method static Builder|Employee wherePositionId(int $positionId)
 *
 * @mixin Eloquent
 */
class Employee extends Model
{
    use HasFactory;

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

    protected $casts = [
        'date_of_employment' => 'date',
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'parent_id');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($employee) {
            $employee->admin_created_id = Auth::id(); // Айди админа, который создает запись
        });

        static::updating(function ($employee) {
            $employee->admin_updated_id = Auth::id(); // Айди админа, который обновляет запись
        });
    }

    protected static function newFactory(): EmployeeFactory
    {
        return new EmployeeFactory();
    }
}

