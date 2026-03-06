<?php

namespace App\Imports;

use App\Models\Branch;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class BranchImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    private int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Map each row to a Branch model.
     * Expected Excel columns: branch_name, status (optional)
     */
    public function model(array $row): ?Branch
    {
        $name = trim($row['branch_name'] ?? '');

        if ($name === '') {
            return null;
        }

        // Accept "active"/"inactive"/1/0 in the status column; default Active
        $rawStatus = strtolower(trim((string)($row['status'] ?? 'active')));
        $status    = ($rawStatus === '0' || $rawStatus === 'inactive') ? 0 : 1;

        return new Branch([
            'user_id'     => $this->userId,
            'branch_name' => $name,
            'status'      => $status,
        ]);
    }
}
