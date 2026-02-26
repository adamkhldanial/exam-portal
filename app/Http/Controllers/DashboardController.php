<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isLecturer()) {
            $data = [
                'totalClasses' => $user->createdClasses()->count(),
                'totalExams' => $user->createdExams()->count(),
                'totalStudents' => \App\Models\User::where('role', 'student')->count(),
                'recentExams' => $user->createdExams()->with('subject')->latest()->take(5)->get(),
            ];
        } else {
            $data = [
                'enrolledClasses' => $user->classRooms()->count(),
                'availableExams' => $this->getAvailableExams($user)->count(),
                'completedExams' => $user->submissions()->where('status', '!=', 'in_progress')->count(),
                'recentSubmissions' => $user->submissions()->with('exam')->latest()->take(5)->get(),
            ];
        }

        return view('dashboard', $data);
    }

    private function getAvailableExams($user)
    {
        $classIds = $user->classRooms()->pluck('class_rooms.id');

        return \App\Models\Exam::whereHas('subject', fn ($q) => $q->whereIn('class_room_id', $classIds))
            ->where('is_published', true)
            ->get();
    }
}
