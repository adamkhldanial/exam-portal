<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            @if(auth()->user()->isLecturer())
                {{-- Lecturer Dashboard --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6 text-center">
                        <div class="text-3xl font-bold text-indigo-600">{{ $totalClasses }}</div>
                        <div class="text-gray-500 mt-1">Total Classes</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $totalExams }}</div>
                        <div class="text-gray-500 mt-1">Total Exams</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $totalStudents }}</div>
                        <div class="text-gray-500 mt-1">Total Students</div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-lg mb-4">Recent Exams</h3>
                    @forelse($recentExams as $exam)
                        <div class="flex justify-between items-center py-2 border-b">
                            <div>
                                <a href="{{ route('lecturer.exams.show', $exam) }}" class="font-medium text-indigo-600 hover:underline">{{ $exam->title }}</a>
                                <span class="text-sm text-gray-500 ml-2">{{ $exam->subject->name ?? 'N/A' }}</span>
                            </div>
                            <span class="text-xs px-2 py-1 rounded {{ $exam->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $exam->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500">No exams yet. <a href="{{ route('lecturer.exams.create') }}" class="text-indigo-600">Create one</a>.</p>
                    @endforelse
                </div>
            @else
                {{-- Student Dashboard --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6 text-center">
                        <div class="text-3xl font-bold text-indigo-600">{{ $enrolledClasses }}</div>
                        <div class="text-gray-500 mt-1">Enrolled Classes</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 text-center">
                        <div class="text-3xl font-bold text-yellow-600">{{ $availableExams }}</div>
                        <div class="text-gray-500 mt-1">Available Exams</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $completedExams }}</div>
                        <div class="text-gray-500 mt-1">Completed Exams</div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-lg mb-4">Recent Submissions</h3>
                    @forelse($recentSubmissions as $sub)
                        <div class="flex justify-between items-center py-2 border-b">
                            <div>
                                <span class="font-medium">{{ $sub->exam->title ?? 'N/A' }}</span>
                                <span class="text-sm text-gray-500 ml-2">{{ $sub->submitted_at?->format('d M Y, H:i') }}</span>
                            </div>
                            <span class="text-sm font-medium">
                                @if($sub->total_marks)
                                    {{ $sub->score }}/{{ $sub->total_marks }} ({{ $sub->percentage }}%)
                                @else
                                    Pending
                                @endif
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500">No submissions yet. <a href="{{ route('student.exams.index') }}" class="text-indigo-600">Take an exam</a>.</p>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
