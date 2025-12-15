<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ClassMembersExport implements FromView, WithEvents
{
    protected $students;
    protected $className;

    public function __construct($students, $className = 'Class')
    {
        $this->students = $students;
        $this->className = $className;
    }

    public function view(): View
    {
        return view('exports.class-members', [
            'students' => $this->students,
            'className' => $this->className
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:D1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);
            },
        ];
    }
}
