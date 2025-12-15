<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Student/Teacher Name</th>
            <th>Email</th>
            <th>Class</th>
            <th>Date</th>
            <th>Time In</th>
            <th>Status</th>
            <th>Note</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attendances as $attendance)
        <tr>
            <td>{{ $attendance->id }}</td>
            <td>{{ $attendance->user->name ?? 'N/A' }}</td>
            <td>{{ $attendance->user->email ?? 'N/A' }}</td>
            <td>{{ $attendance->classModel->name ?? 'N/A' }}</td>
            <td>{{ $attendance->date }}</td>
            <td>{{ $attendance->time_in }}</td>
            <td>{{ $attendance->status }}</td>
            <td>{{ $attendance->note ?? '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>