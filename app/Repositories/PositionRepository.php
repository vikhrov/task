<?php

namespace App\Repositories;

use App\Models\Position;
use Illuminate\Support\Collection;

class PositionRepository
{
    public function __construct(
        private readonly Position $model
    )
    {
    }

    public function getIdsExceptCurrent(Position $position): Collection
    {
        return $this->model::where('id', '<>', $position->id)->pluck('id');
    }

    public function delete(Position $position): bool
    {
        return (bool) $position->delete();
    }
}
