<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pengajuan Izin') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Detail Pengajuan Izin</h3>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Siswa</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $leaveRequest->user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $leaveRequest->user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Pengajuan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $leaveRequest->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p class="mt-1 text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($leaveRequest->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($leaveRequest->status == 'approved') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    @if($leaveRequest->status == 'pending') Menunggu
                                    @elseif($leaveRequest->status == 'approved') Disetujui
                                    @else Ditolak
                                    @endif
                                </span>
                            </p>
                        </div>
                        @if($leaveRequest->approvedBy)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Disetujui oleh</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $leaveRequest->approvedBy->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Disetujui</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $leaveRequest->approved_at ? $leaveRequest->approved_at->format('d M Y H:i') : '-' }}</p>
                        </div>
                        @endif
                        @if($leaveRequest->notes)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Catatan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $leaveRequest->notes }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Alasan Izin</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $leaveRequest->reason }}</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Tanggal Izin</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('d M Y') }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Lampiran</label>
                        <div class="mt-2">
                            <a href="{{ route('leave.request.download', basename($leaveRequest->attachment)) }}" target="_blank">
                                <img src="{{ route('leave.request.image', basename($leaveRequest->attachment)) }}" alt="Lampiran Izin" class="max-w-md h-auto rounded border object-contain" style="max-height: 300px;">
                            </a>
                        </div>
                    </div>

                    @if($leaveRequest->status == 'pending')
                    <div class="mt-8">
                        <h4 class="text-md font-medium text-gray-900 mb-4">Tindakan</h4>
                        <div class="flex space-x-4">
                            <form method="POST" action="{{ route('superadmin.leave.request.approve', $leaveRequest->id) }}" class="inline">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label for="approve_notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (opsional)</label>
                                    <textarea name="notes" id="approve_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                </div>
                                <button type="submit" class="px-4 py-2 bg-green-600 border border-transparent rounded-md text-white hover:bg-green-700">
                                    Setujui
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('superadmin.leave.request.reject', $leaveRequest->id) }}" class="inline">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan Penolakan</label>
                                    <textarea name="notes" id="reject_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                </div>
                                <button type="submit" class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-white hover:bg-red-700">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>