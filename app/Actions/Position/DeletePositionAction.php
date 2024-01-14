<?php

namespace App\Actions\Position;

use App\Exceptions\EntityDeleteException;
use App\Models\Position;
use App\Repositories\EmployeeRepository;
use App\Repositories\PositionRepository;
use DB;
use Throwable;

class DeletePositionAction
{
    public function __construct(
        private readonly PositionRepository $positionRepository,
        private readonly EmployeeRepository $employeeRepository
    ) {
    }

    /**
     * @throws EntityDeleteException
     */
    public function __invoke(Position $position): void
    {
        try {
            DB::transaction(function () use ($position): void {
                $positionIds = $this->positionRepository->getIdsExceptCurrent($position);
                $this->employeeRepository->setNewPositionsForOldPosition($position, $positionIds);
                $this->positionRepository->delete($position);
            });
        } catch (Throwable) {
            throw new EntityDeleteException();
        }
    }
}
