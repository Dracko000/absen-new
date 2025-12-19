<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StudentController extends Controller
{
    public function index()
    {
        $attendances = Attendance::where('user_id', auth()->id())
            ->where('date', today())
            ->with('classModel')
            ->get();

        $recentAttendances = Attendance::where('user_id', auth()->id())
            ->with('classModel')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return view('student.dashboard', compact('attendances', 'recentAttendances'));
    }

    public function attendanceHistory()
    {
        $attendances = Attendance::where('user_id', auth()->id())
            ->with('classModel')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('student.attendance-history', compact('attendances'));
    }

    public function showQrCode()
    {
        $user = auth()->user();

        // Check if user has NIS
        if (empty($user->nis)) {
            return redirect()->back()->with('error', 'NIS not found. Please contact admin to set your NIS.');
        }

        $qrCode = base64_encode(QrCode::format('png')
            ->size(200)
            ->generate($user->nis));

        return view('student.qr-code', compact('qrCode', 'user'));
    }

    public function showLeaveRequestForm()
    {
        return view('student.leave-request-form');
    }

    public function submitLeaveRequest(Request $request)
    {
        // Validate the form inputs
        $request->validate([
            'reason' => 'required|string|max:500',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            // Require either attachment file or captured image
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'captured_image' => 'nullable|string', // For captured image from camera
        ]);

        // Validate that at least one image is provided
        if (!$request->hasFile('attachment') && !$request->filled('captured_image')) {
            return redirect()->back()
                ->withErrors(['attachment' => 'Silakan pilih atau ambil foto terlebih dahulu.'])
                ->withInput();
        }

        // Process the image based on the source
        if ($request->hasFile('attachment')) {
            // Handle uploaded file - validation already done by Laravel
            $attachmentPath = $request->file('attachment')->store('leave-requests', 'public');
        } elseif ($request->filled('captured_image')) {
            // Handle captured image from camera
            $imageData = $request->captured_image;

            // Remove data:image/jpeg;base64, part if present
            if (strpos($imageData, 'data:image') === 0) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
            }

            // Decode the base64 image data
            $imageBinary = base64_decode($imageData);

            if ($imageBinary === false) {
                return redirect()->back()
                    ->withErrors(['captured_image' => 'Format gambar tidak valid.'])
                    ->withInput();
            }

            // Check image size - max 2MB (2048 KB)
            if (strlen($imageBinary) > 2048 * 1024) {
                return redirect()->back()
                    ->withErrors(['captured_image' => 'Ukuran gambar terlalu besar. Maksimal 2MB.'])
                    ->withInput();
            }

            // Determine image extension based on the data URL
            $imageInfo = getimagesize('data://application/octet-stream;base64,' . base64_encode($imageBinary));
            $extension = 'jpg'; // Default to jpg
            if ($imageInfo) {
                switch ($imageInfo[2]) {
                    case IMAGETYPE_PNG:
                        $extension = 'png';
                        break;
                    case IMAGETYPE_GIF:
                        $extension = 'gif';
                        break;
                    case IMAGETYPE_JPEG:
                        $extension = 'jpg';
                        break;
                }
            }

            // Validate image type
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                return redirect()->back()
                    ->withErrors(['captured_image' => 'Format gambar tidak didukung. Gunakan JPEG, PNG, atau GIF.'])
                    ->withInput();
            }

            // Generate unique filename
            $filename = 'leave-request-' . auth()->id() . '-' . time() . '.' . $extension;

            // Store the image in the leave-requests directory
            $path = storage_path('app/public/leave-requests/' . $filename);
            $directory = dirname($path);

            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            file_put_contents($path, $imageBinary);
            $attachmentPath = 'leave-requests/' . $filename;
        } else {
            return redirect()->back()
                ->withErrors(['attachment' => 'Silakan pilih atau ambil foto terlebih dahulu.'])
                ->withInput();
        }

        \App\Models\LeaveRequest::create([
            'user_id' => auth()->id(),
            'reason' => $request->reason,
            'attachment' => $attachmentPath,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending', // Default to pending
        ]);

        return redirect()->route('student.leave.requests')->with('success', 'Permohonan izin berhasil diajukan.');
    }

    public function showLeaveRequests()
    {
        $leaveRequests = \App\Models\LeaveRequest::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('student.leave-requests', compact('leaveRequests'));
    }
}
