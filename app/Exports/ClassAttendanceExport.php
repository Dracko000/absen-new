<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ClassAttendanceExport implements FromView, WithEvents
{
    protected $attendances;
    protected $className;

    public function __construct($attendances, $className = 'Class')
    {
        $this->attendances = $attendances;
        $this->className = $className;
    }

    public function view(): View
    {
        return view('exports.class-attendance', [
            'attendances' => $this->attendances,
            'className' => $this->className
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);
            },
        ];
    }
}