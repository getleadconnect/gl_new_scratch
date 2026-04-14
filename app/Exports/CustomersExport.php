<?php

namespace App\Exports;

use App\Models\ScratchCustomer;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    protected int $userId;
    protected ?string $campaignId;
    protected ?string $winStatus;
    protected ?string $redeemStatus;
    protected ?string $dateFrom;
    protected ?string $dateTo;

    public function __construct(
        int $userId,
        ?string $campaignId = null,
        ?string $winStatus  = null,
        ?string $redeemStatus  = null,
        ?string $dateFrom   = null,
        ?string $dateTo     = null
    ) {
        $this->userId       = $userId;
        $this->campaignId   = $campaignId;
        $this->winStatus    = $winStatus;
        $this->redeemStatus = $redeemStatus;
        $this->dateFrom   = $dateFrom;
        $this->dateTo     = $dateTo;
    }

    public function collection()
    {
        $query = ScratchCustomer::with('campaign')
            ->where('user_id', $this->userId);

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

        return $query->orderBy('id', 'DESC')->get();
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Name',
            'Mobile',
            'Email',
            'Unique ID',
            'Campaign',
            'Offer',
            'Short Code',
            'Bill No',
            'Win Status',
            'Date',
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $row->name ?? '--',
            $row->cust_mobile ?? $row->mobile ?? '--',
            $row->email ?? $row->email ?? '--',
            $row->unique_id ?? '--',
            $row->campaign ? $row->campaign->campaign_name : '--',
            $row->offer_text ?? '--',
            $row->short_code ?? '--',
            $row->bill_no ?? '--',
            $row->win_status == 1 ? 'Win' : 'Loss',
            $row->created_at ? Carbon::parse($row->created_at)->format('d-m-Y H:i') : '--',
        ];
    }
}
