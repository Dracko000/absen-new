@extends('layouts.app')

@section('title', 'Import Students to ' . $class->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Import Students to {{ $class->name }}</h1>
        
        <p class="text-gray-600 mb-6">
            Upload an Excel file to import students into this class. The Excel file should contain columns: "nama" (name) and "nis". 
            Make sure no students are being double-enrolled in multiple classes.
        </p>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('superadmin.import.students', $class->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label for="file" class="block text-gray-700 font-medium mb-2">Excel File</label>
                <input type="file" name="file" id="file" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" accept=".xlsx,.xls,.csv" required>
                <p class="mt-2 text-sm text-gray-500">Only .xlsx, .xls, and .csv files are allowed.</p>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('superadmin.class.members', $class->id) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-white hover:bg-indigo-700">
                    Import Students
                </button>
            </div>
        </form>

        <div class="mt-8">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Template Download</h2>
            <p class="text-gray-600 mb-4">
                Download the template below to ensure your Excel file has the correct format:
            </p>
            <a href="#" class="text-indigo-600 hover:text-indigo-900">
                Download Student Import Template
            </a>
        </div>
    </div>
</div>
@endsection