<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;
    protected static ?Collection $positionIds = null;
    protected static ?Collection $adminIds = null;
    protected static ?Collection $parentIds = null;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'position_id' => self::$positionIds?->random(),
            'email' => $this->faker->unique()->email,
            'date_of_employment' => $this->faker->dateTimeThisDecade,
            'phone_number' => $this->faker->numerify(sprintf('+380%s', str_repeat('#', 9))),
            'salary' => $this->faker->numberBetween(20000, 50000),
            'photo' => 0,
            'admin_created_id' => $adminId = self::$adminIds->random(),
            'admin_updated_id' =>  $adminId,
            'created_at' => now(),
            'updated_at' => now(),
            'parent_id' => self::$parentIds?->random(),
            'level' => 5,
        ];
    }

    public function forPositions(Collection $positionIds): self
    {
        self::$positionIds = $positionIds;

        return $this;
    }

    public function forAdmins(Collection $adminIds): self
    {
        self::$adminIds = $adminIds;

        return $this;
    }

    public function forParents(?Collection $parentIds): self
    {
        self::$parentIds = $parentIds;

        return $this;
    }

    public function forLevel(int $level): self
    {
        return $this->state(fn() => ['level' => $level]);
    }
}
