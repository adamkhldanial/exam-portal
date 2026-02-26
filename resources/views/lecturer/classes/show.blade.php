<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ $classRoom->name }} <span class="text-gray-400 text-base">({{ $classRoom->code }})</span></h2>
    </x-slot>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if(session('success'))<div class="bg-green-100 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>@endif

        {{-- Assign Students --}}
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Enroll Students</h3>
                <a href="{{ route('lecturer.students.create', ['class_room_id' => $classRoom->id]) }}" class="text-sm bg-indigo-600 text-white px-3 py-1.5 rounded hover:bg-indigo-700">+ New Student</a>
            </div>
            <form action="{{ route('lecturer.classes.assign-students', $classRoom) }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-4">
                    @foreach($allStudents as $student)
                        <label class="flex items-center space-x-2 p-2 border rounded cursor-pointer hover:bg-gray-50">
                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                {{ $classRoom->students->contains($student->id) ? 'checked' : '' }}>
                            <span class="text-sm">{{ $student->name }} <span class="text-gray-400">({{ $student->email }})</span></span>
                        </label>
                    @endforeach
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Save Enrollment</button>
            </form>
        </div>

        {{-- Subjects --}}
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between mb-4">
                <h3 class="font-semibold text-lg">Subjects</h3>
            </div>
            @forelse($classRoom->subjects as $subject)
                <div class="flex justify-between items-center py-2 border-b">
                    <div>
                        <span class="font-medium">{{ $subject->name }}</span>
                        <span class="text-sm text-gray-500 ml-2">{{ $subject->code }}</span>
                        <span class="text-sm text-gray-400 ml-2">({{ $subject->exams->count() }} exams)</span>
                    </div>
                    <a href="{{ route('lecturer.exams.index') }}" class="text-indigo-600 text-sm hover:underline">View Exams</a>
                </div>
            @empty
                <p class="text-gray-500">No subjects yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
