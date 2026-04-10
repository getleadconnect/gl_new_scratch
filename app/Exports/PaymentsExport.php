<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected ?string $filterStatus;
    protected ?string $dateFrom;
    protected ?string $dateTo;

    public function __construct(
        ?string $filterStatus = null,
        ?string $dateFrom     = null,
        ?string $dateTo       = null
    ) {
        $this->filterStatus = $filterStatus;
        $this->dateFrom     = $dateFrom;
        $this->dateTo       = $dateTo;
    }

    public function collection()
    {
        $query = DB::table('payment_history')
            ->leftJoin('users', 'payment_history.user_id', '=', 'users.id')
            ->select(
                'payment_history.*',
                'users.name as user_name',
                'users.email as user_email',
                'users.unique_id as user_unique_id',
                'users.country_code as user_country_code',
                'users.mobile as user_mobile'
            )
            ->orderBy('payment_history.id', 'DESC');

        if ($this->filterStatus) {
            $query->where('payment_history.status', $this->filterStatus);
        }
        if ($this->dateFrom) {
            $query->whereDate('payment_history.created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('payment_history.created_at', '<=', $this->dateTo);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'User Name',
            'Email',
            'Mobile',
            'Unique ID',
            'Order ID',
            'Payment ID',
            'Scratch Count',
            'Amount',
            'Currency',
            'Status',
            'Date',
        ];
    }

    public function map($row): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $row->user_name ?? '--',
            $row->user_email ?? '--',
            ($row->user_country_code ?? '') . ' ' . ($row->user_mobile ?? ''),
            $row->user_unique_id ?? '--',
            $row->razorpay_order_id ?? '--',
            $row->razorpay_payment_id ?? '--',
            $row->scratch_count ?? 0,
            $row->amount ?? 0,
            $row->currency ?? 'INR',
            ucfirst($row->status ?? 'pending'),
            $row->created_at ? Carbon::parse($row->created_at)->format('d-m-Y H:i') : '--',
        ];
    }
}
