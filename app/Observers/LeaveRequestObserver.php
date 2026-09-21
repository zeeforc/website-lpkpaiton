<?php

namespace App\Observers;

use App\Models\LeaveRequest;
use App\Models\Attendance;
use Carbon\Carbon;

class LeaveRequestObserver
{
    /**
     * Handle the LeaveRequest "updated" event.
     */
    public function updated(LeaveRequest $leaveRequest): void
    {
        if ($leaveRequest->wasChanged('status') && $leaveRequest->status === 'approved') {
            
            $startDate = Carbon::parse($leaveRequest->date);
            $endDate = $leaveRequest->end_date ? Carbon::parse($leaveRequest->end_date) : $startDate->copy();
            
            // Loop from start date to end date
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $dateString = $date->format('Y-m-d');
                
                // Skip if attendance already exists for this user and this date
                $existingAttendance = Attendance::where('user_id', $leaveRequest->user_id)
                                                ->where('date', $dateString)
                                                ->first();
                                                
                if (!$existingAttendance) {
                    Attendance::create([
                        'user_id' => $leaveRequest->user_id,
                        'date' => $dateString,
                        'status' => ucfirst($leaveRequest->type), // 'Izin' or 'Sakit'
                        'notes' => 'Di-generate otomatis dari pengajuan izin: ' . $leaveRequest->reason,
                    ]);
                } else {
                    // Update the existing attendance if it's not present (e.g. absent or pending)
                    if (in_array(strtolower($existingAttendance->status), ['tidak hadir', 'alpha', 'absen', 'pending'])) {
                        $existingAttendance->update([
                            'status' => ucfirst($leaveRequest->type),
                            'notes' => 'Di-generate otomatis dari pengajuan izin: ' . $leaveRequest->reason,
                        ]);
                    }
                }
            }
        }
    }
}
