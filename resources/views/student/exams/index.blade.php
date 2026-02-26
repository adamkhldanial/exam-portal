<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">My Exams</h2></x-slot>
    <div class="py-12 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if($inProgressSubmission)
            <div class="mb-6 bg-yellow-50 border border-yellow-300 text-yellow-800 px-4 py-3 rounded flex justify-between items-center">
                <span>⚠ You have an in-progress exam: <strong>{{ $inProgressSubmission->exam->title }}</strong></span>
                <a href="{{ route('student.exams.show', $inProgressSubmission->exam) }}" class="bg-yellow-600 text-white px-4 py-2 rounded text-sm hover:bg-yellow-700">Resume</a>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($exams as $exam)
                <div class="bg-white shadow rounded-lg p-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-lg">{{ $exam->title }}</h3>
                            <p class="text-sm text-gray-500">{{ $exam->subject->name }} · {{ $exam->subject->classRoom->name }}</p>
                            <p class="text-sm text-gray-400 mt-1">{{ $exam->questions_count }} questions · {{ $exam->time_limit }} min · Pass: {{ $exam->passing_score }}%</p>
                            @if($exam->ends_at)
                                <p class="text-xs text-orange-600 mt-1">Closes: {{ $exam->ends_at->format('d M Y, H:i') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4">
                        @if($submittedExamIds->contains($exam->id))
                            <a href="{{ route('student.exams.result', $exam) }}" class="text-green-600 font-medium hover:underline">View Result →</a>
                        @elseif($exam->isAvailable())
                            <a href="{{ route('student.exams.show', $exam) }}" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700">
                                Take Exam
                            </a>
                        @else
                            <span class="text-gray-400 text-sm">Not available</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-12 text-gray-500">No exams assigned to your classes yet.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
