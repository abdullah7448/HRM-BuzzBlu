<div class="bg-white p-6 border border-gray-200 rounded-lg shadow-sm">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Daily Attendance</h3>

    @if(!$todayAttendance)
        <!-- Has not punched in yet -->
        <p class="text-gray-600 mb-4">You have not started your shift today.</p>
        <button wire:click="punchIn" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-md shadow hover:bg-green-700 transition">
            Punch In
        </button>

    @elseif($todayAttendance && !$todayAttendance->punch_out)
        <!-- Punched in, needs to punch out -->
        <div class="mb-4">
            <p class="text-green-600 font-medium">Shift active. You are currently clocked in.</p>
            <p class="text-sm text-gray-500 mt-1">Punched In at: {{ \Carbon\Carbon::parse($todayAttendance->punch_in)->format('h:i A') }}</p>
        </div>
        <button wire:click="punchOut" class="px-6 py-3 bg-red-600 text-white font-semibold rounded-md shadow hover:bg-red-700 transition">
            Punch Out
        </button>

    @else
        <!-- Shift completed for the day -->
        <div class="p-4 bg-gray-50 rounded-md border border-gray-100">
            <p class="text-gray-800 font-medium mb-2">Shift completed for today.</p>
            <ul class="text-sm text-gray-600 space-y-1">
                <li><strong>Punch In:</strong> {{ \Carbon\Carbon::parse($todayAttendance->punch_in)->format('h:i A') }}</li>
                <li><strong>Punch Out:</strong> {{ \Carbon\Carbon::parse($todayAttendance->punch_out)->format('h:i A') }}</li>
            </ul>
        </div>
    @endif
</div>