<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Email</th>
            <th>Kelas</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach($students as $student)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $student->name ?? 'N/A' }}</td>
            <td>{{ $student->email ?? 'N/A' }}</td>
            <td>{{ $className ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>