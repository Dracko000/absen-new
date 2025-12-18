<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance for ') . $class->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Attendance for {{ $class->name }}</h3>
                            <p class="text-sm text-gray-600">Date: {{ isset($date) ? \Carbon\Carbon::parse($date)->format('d M Y') : now()->format('d M Y') }}</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <div class="flex gap-1">
                                <a href="{{ route('admin.class.attendance.by.date', [$class->id, isset($date) ? \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d') : now()->subDay()->format('Y-m-d')]) }}"
                                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-3 rounded text-sm">
                                    &larr; Prev
                                </a>
                                <a href="{{ route('admin.class.attendance.by.date', [$class->id, now()->format('Y-m-d')]) }}"
                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded text-sm">
                                    Today
                                </a>
                                <a href="{{ route('admin.class.attendance.by.date', [$class->id, isset($date) ? \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d') : now()->addDay()->format('Y-m-d')]) }}"
                                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-3 rounded text-sm">
                                    Next &rarr;
                                </a>
                            </div>
                            <a href="{{ route('admin.class.take.attendance', $class->id) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Take Attendance
                            </a>
                        </div>
                    </div>

                    <!-- Schedule Dates Navigation -->
                    @if($class->schedules->count() > 0)
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Scheduled Dates (Next 7 Days):</h4>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $currentDate = now()->startOfDay();
                                $datesToShow = [];
                                for ($i = 0; $i < 7; $i++) {
                                    $dateToShow = $currentDate->copy()->addDays($i);
                                    $dayOfWeek = $dateToShow->dayOfWeek;
                                    if ($dayOfWeek == 0) $dayOfWeek = 7; // Convert Sunday from 0 to 7

                                    $hasSchedule = $class->schedules->contains('day_of_week', $dayOfWeek);
                                    if ($hasSchedule) {
                                        $dateStr = $dateToShow->format('Y-m-d');
                                        $isActive = (isset($date) ? $date : now()->format('Y-m-d')) == $dateStr;
                                        $datesToShow[] = [
                                            'date' => $dateToShow,
                                            'hasSchedule' => $hasSchedule,
                                            'isActive' => $isActive
                                        ];
                                    }
                                }
                            @endphp

                            @foreach($datesToShow as $item)
                                <a href="{{ route('admin.class.attendance.by.date', [$class->id, $item['date']->format('Y-m-d')]) }}"
                                   class="px-3 py-1 text-sm rounded-full {{ $item['isActive'] ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                                    {{ $item['date']->format('D, d M') }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($attendances as $attendance)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $attendance->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $attendance->time_in }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($attendance->status == 'Hadir') bg-green-100 text-green-800
                                                @elseif($attendance->status == 'Terlambat') bg-yellow-100 text-yellow-800
                                                @elseif($attendance->status == 'Izin') bg-blue-100 text-blue-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ $attendance->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->note ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No attendance records for today.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        <h4 class="text-md font-medium mb-4">Quick Stats</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-green-100 p-4 rounded-lg text-center">
                                <p class="text-2xl font-bold text-green-800">
                                    {{ $attendances->where('status', 'Hadir')->count() }}
                                </p>
                                <p class="text-sm text-green-700">Present</p>
                            </div>
                            <div class="bg-yellow-100 p-4 rounded-lg text-center">
                                <p class="text-2xl font-bold text-yellow-800">
                                    {{ $attendances->where('status', 'Terlambat')->count() }}
                                </p>
                                <p class="text-sm text-yellow-700">Late</p>
                            </div>
                            <div class="bg-red-100 p-4 rounded-lg text-center">
                                <p class="text-2xl font-bold text-red-800">
                                    {{ $attendances->where('status', 'Tidak Hadir')->count() }}
                                </p>
                                <p class="text-sm text-red-700">Absent</p>
                            </div>
                            <div class="bg-blue-100 p-4 rounded-lg text-center">
                                <p class="text-2xl font-bold text-blue-800">
                                    {{ $attendances->where('status', 'Izin')->count() }}
                                </p>
                                <p class="text-sm text-blue-700">Leave</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>