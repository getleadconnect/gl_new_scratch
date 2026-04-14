<?php

namespace App\Exports;

use App\Models\ScratchCustomer;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdminCustomersExport implements FromCollection, WithHeadings, WithMapping
{
    protected array $childIds;
    protected ?string $filterUserId;
    protected ?string $campaignId;
    protected ?string $winStatus;
    protected ?string $redeemStatus;
    protected ?string $dateFrom;
    protected ?string $dateTo;

    public function __construct(
        array   $childIds,
        ?string $filterUserId  = null,
        ?string $campaignId    = null,
        ?string $winStatus     = null,
        ?string $redeemStatus  = null,
        ?string $dateFrom      = null,
        ?string $dateTo        = null
    ) {
        $this->childIds      = $childIds;
        $this->filterUserId  = $filterUserId;
        $this->campaignId    = $campaignId;
        $this->winStatus     = $winStatus;
        $this->redeemStatus  = $redeemStatus;
        $this->dateFrom      = $dateFrom;
        $this->dateTo        = $dateTo;
    }

    public function collection()
    {
        $query = ScratchCustomer::with(['campaign', 'branches', 'user'])
            ->whereIn('user_id', $this->childIds);

        if ($this->filterUserId) {
            $query->where('user_id', $this->filterUserId);
        }

        if ($this->campaignId) {
            $query->where('campaign_id', $this->campaignId);
        }

        if ($this->winStatus !== null && $this->winStatus !== '') {
            $query->where('win_status', $this->winStatus);
        }

        if ($this->redeemStatus !== null && $this->redeemStatus !== '') {
            $query->where('redeem', $this->redeemStatus);
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        if (!$this->dateFrom && !$this->dateTo) {
            $sdate = Carbon::parse(date('Y-m-d'))->subMonths(3)->format('Y-m-d');
            $edate = Carbon::now()->format('Y-m-d');
            $query->whereDate('created_at', '>=', $sdate)
                  ->whereDate('created_at', '<=', $edate);
        }

        return $query->orderBy('id', 'DESC')->get();
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'User',
            'Name',
            'Mobile',
            'Email',
            'Unique ID',
            'Campaign',
            'Offer',
            'Branch',
            'Bill No',
            'Win Status',
            'Redeem',
            'Date',
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $row->user ? $row->user->name : '--',
            $row->name ?? '--',
            $row->cust_mobile ?? $row->mobile ?? '--',
            $row->email ?? $row->email ?? '--',
            $row->unique_id ?? '--',
            $row->campaign ? $row->campaign->campaign_name : '--',
            $row->offer_text ?? '--',
            $row->branches ? $row->branches->branch_name : '--',
            $row->bill_no ?? '--',
            $row->win_status == 1 ? 'Win' : 'Loss',
            ($row->redeem == 1 && $row->win_status == 1) ? 'Yes' : (($row->redeem == 0 && $row->win_status == 1) ? 'No' : '--'),
            $row->created_at ? Carbon::parse($row->created_at)->format('d-m-Y H:i') : '--',
        ];
    }
}
