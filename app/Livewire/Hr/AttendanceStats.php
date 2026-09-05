<?php
namespace App\Livewire\Hr;

use Livewire\Component;
use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;

class AttendanceStats extends Component
{
    public $activeList = null;

    public function viewList($type)
    {
        $this->activeList = ($this->activeList === $type) ? null : $type;
    }

    public function render()
    {
        $today = Carbon::today();
        $lateThreshold = '09:10:00'; // 9:10 AM

        // সব ইউজার (যাতে সবার ডেটা একবারে পাই)
        $allUsers = User::with(['department', 'designation'])->get();

        // ১. আজ যারা ছুটিতে আছে (Approved Leave)
        $onLeaveUserIds = LeaveRequest::where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->pluck('user_id')
            ->toArray();

        $onLeaveUsers = $allUsers->whereIn('id', $onLeaveUserIds);

        // ২. আজকের অ্যাটেনডেন্স/পাঞ্চ ডেটা
        $attendances = Attendance::whereDate('date', $today)->get()->groupBy('user_id');

        $presentUsers = collect();
        $lateUsers = collect();

        foreach ($attendances as $userId => $userAttendances) {
            $firstPunch = $userAttendances->sortBy('punch_in')->first();
            $user = $allUsers->firstWhere('id', $userId);
            
            if ($user) {
                // পাঞ্চ টাইম কি ৯:১০ এর পরে?
                $punchTime = Carbon::parse($firstPunch->punch_in)->format('H:i:s');
                if ($punchTime > $lateThreshold) {
                    $lateUsers->push($user);
                } else {
                    $presentUsers->push($user);
                }
            }
        }

        // ৩. যারা আসেনি এবং ছুটিতেও নেই (Absent)
        $absentUsers = $allUsers->whereNotIn('id', array_merge(
            $presentUsers->pluck('id')->toArray(),
            $lateUsers->pluck('id')->toArray(),
            $onLeaveUserIds
        ));

        return view('livewire.hr.attendance-stats', [
            'presentCount' => $presentUsers->count(),
            'lateCount'    => $lateUsers->count(),
            'absentCount'  => $absentUsers->count(),
            'onLeaveCount' => $onLeaveUsers->count(),
            
            'presentUsers' => $presentUsers,
            'lateUsers'    => $lateUsers,
            'absentUsers'  => $absentUsers,
            'onLeaveUsers' => $onLeaveUsers,
        ]);
    }
}