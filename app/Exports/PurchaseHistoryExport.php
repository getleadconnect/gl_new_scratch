<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\PurchaseScratchHistory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchaseHistoryExport implements FromCollection, WithHeadings, WithMapping
{
    protected ?int $userId;
    protected ?string $dateFrom;
    protected ?string $dateTo;
    protected ?int $parentId;

    public function __construct(?int $userId = null, ?string $dateFrom = null, ?string $dateTo = null, ?int $parentId = null)
    {
        $this->userId   = $userId;
        $this->dateFrom = $dateFrom;
        $this->dateTo   = $dateTo;
        $this->parentId = $parentId;
    }

    public function collection()
    {
        $query = PurchaseScratchHistory::select(
                'purchase_scratch_history.*',
                'users.name as user_name',
                'users.unique_id as user_unique_id',
                'users.role_id as user_role_id',
                'users.country_code as user_country_code',
                'users.mobile as user_mobile'
            )
            ->join('users', 'purchase_scratch_history.user_id', '=', 'users.id')
            ->whereNull('users.deleted_at');

        if ($this->parentId) {
            $query->where('users.role_id', 3)->where('users.parent_id', $this->parentId);
        } else {
            $query->whereIn('users.role_id', [2, 3]);
        }

        if ($this->dateFrom) {
            $query->whereDate('purchase_scratch_history.created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('purchase_scratch_history.created_at', '<=', $this->dateTo);
        }
        if ($this->userId) {
            $query->where('purchase_scratch_history.user_id', $this->userId);
        }

        $query->orderBy('purchase_scratch_history.created_at', 'DESC');

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Unique ID',
            'User',
            'Mobile',
            'Role',
            'Narration',
            'Count',
            'Amount',
            'Date',
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $row->user_unique_id ?? '--',
            strtoupper($row->user_name ?? ''),
            ($row->user_country_code ?? '') . ' ' . ($row->user_mobile ?? ''),
            $row->user_role_id == 2 ? 'User' : 'Child',
            $row->narration,
            $row->scratch_count,
            number_format((float) ($row->amount ?? 0), 2),
            $row->created_at ? Carbon::parse($row->created_at)->format('d-m-Y h:i A') : '--',
        ];
    }
}
