<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AttendanceExport implements FromView, WithEvents
{
    protected $query;
    protected $type; // daily, weekly, monthly, or all

    public function __construct($query = null, $type = 'all')
    {
        $this->query = $query;
        $this->type = $type;
    }

    public function view(): View
    {
        $attendances = $this->query ?? Attendance::with(['user', 'classModel'])->get();

        return view('exports.attendance', [
            'attendances' => $attendances,
            'type' => $this->type
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);
            },
        ];
    }
}
