@extends('layouts.app')

@section('title', 'Edit Kelas Siswa - ' . $user->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Kelas Siswa</h1>
        
        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <h2 class="text-lg font-semibold text-gray-800">Informasi Siswa</h2>
            <p class="text-gray-600"><strong>Nama:</strong> {{ $user->name }}</p>
            <p class="text-gray-600"><strong>NIS:</strong> {{ $user->nis }}</p>
            <p class="text-gray-600"><strong>Email:</strong> {{ $user->email }}</p>
        </div>

        <form action="{{ route('superadmin.users.update.class', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="class_id" class="block text-gray-700 font-medium mb-2">Kelas Baru</label>
                <select name="class_id" id="class_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="">Pilih Kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $currentClassId == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
                @error('class_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('superadmin.users') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-white hover:bg-indigo-700">
                    Update Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection