<?php
namespace App\Livewire\DepartmentHead;

use Livewire\Component;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceStats extends Component
{
    public $activeList = null; // কোন লিস্ট দেখানো হবে তা ট্র্যাক করবে ('present', 'absent', বা 'total')

    // কার্ডে ক্লিক করলে এই মেথড কল হবে
    public function viewList($type)
    {
        // যদি একই কার্ডে আবার ক্লিক করে, তবে লিস্ট বন্ধ হয়ে যাবে
        if ($this->activeList === $type) {
            $this->activeList = null; 
        } else {
            $this->activeList = $type;
        }
    }

    public function render()
    {
        $departmentId = Auth::user()->department_id;
        $today = Carbon::today();

        // ১. ওই ডিপার্টমেন্টের সব ইউজার
        $allUsers = User::where('department_id', $departmentId)->with('designation')->get();
        $totalTeamCount = $allUsers->count();

        // ২. যারা আজ পাঞ্চ করেছে তাদের ID বের করা
        $presentUserIds = Attendance::whereHas('user', function($query) use ($departmentId) {
            $query->where('department_id', $departmentId);
        })
        ->whereDate('created_at', $today)
        ->pluck('user_id')
        ->toArray();

        // ৩. লিস্ট আলাদা করা
        $presentUsers = $allUsers->whereIn('id', $presentUserIds);
        $absentUsers = $allUsers->whereNotIn('id', $presentUserIds);

        return view('livewire.department-head.attendance-stats', [
            'totalTeamCount' => $totalTeamCount,
            'presentCount' => $presentUsers->count(),
            'absentCount' => $absentUsers->count(),
            'presentUsers' => $presentUsers,
            'absentUsers' => $absentUsers,
            'allUsers' => $allUsers,
        ]);
    }
}