<table>
    <thead>
        <tr>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Waktu Masuk</th>
            <th>Catatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attendances as $attendance)
        <tr>
            <td>{{ $attendance->user->name }}</td>
            <td>{{ $attendance->classModel->name }}</td>
            <td>{{ $attendance->date }}</td>
            <td>{{ $attendance->status }}</td>
            <td>{{ $attendance->time_in }}</td>
            <td>{{ $attendance->note }}</td>
        </tr>
        @endforeach
    </tbody>
</table>