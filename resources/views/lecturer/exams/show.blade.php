<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">{{ $exam->title }}</h2>
            <div class="space-x-2">
                <a href="{{ route('lecturer.questions.create', $exam) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">+ Add Question</a>
                <a href="{{ route('lecturer.exams.edit', $exam) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit Exam</a>
                <form action="{{ route('lecturer.exams.toggle-publish', $exam) }}" method="POST" class="inline">
                    @csrf
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        {{ $exam->is_published ? 'Unpublish' : 'Publish' }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>
    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @if(session('success'))<div class="bg-green-100 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>@endif

        <div class="bg-white shadow rounded-lg p-6">
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div><span class="text-gray-500">Subject:</span> <span class="font-medium">{{ $exam->subject->name }}</span></div>
                <div><span class="text-gray-500">Class:</span> <span class="font-medium">{{ $exam->subject->classRoom->name ?? 'N/A' }}</span></div>
                <div><span class="text-gray-500">Time Limit:</span> <span class="font-medium">{{ $exam->time_limit }} min</span></div>
                <div><span class="text-gray-500">Passing Score:</span> <span class="font-medium">{{ $exam->passing_score }}%</span></div>
                <div><span class="text-gray-500">Status:</span>
                    <span class="px-2 py-0.5 rounded text-xs {{ $exam->is_published ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $exam->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
                <div><span class="text-gray-500">Total Marks:</span> <span class="font-medium">{{ $exam->total_marks }}</span></div>
            </div>
        </div>

        <h3 class="font-semibold text-lg">Questions ({{ $exam->questions->count() }})</h3>

        @forelse($exam->questions as $i => $question)
            <div class="bg-white shadow rounded-lg p-5">
                <div class="flex justify-between">
                    <div class="flex-1">
                        <div class="flex items-start gap-3">
                            <span class="text-gray-400 font-bold">{{ $i+1 }}.</span>
                            <div>
                                <p class="font-medium">{{ $question->question_text }}</p>
                                <span class="text-xs text-gray-500 mt-1 inline-block capitalize">{{ str_replace('_', ' ', $question->type) }} · {{ $question->marks }} mark(s)</span>
                            </div>
                        </div>
                        @if($question->isMultipleChoice())
                            <ul class="mt-3 ml-6 space-y-1">
                                @foreach($question->options as $opt)
                                    <li class="flex items-center gap-2 text-sm {{ $opt->is_correct ? 'text-green-700 font-medium' : 'text-gray-600' }}">
                                        {{ $opt->is_correct ? '✓' : '○' }} {{ $opt->option_text }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-400 mt-2 ml-6 italic">Open-text response</p>
                        @endif
                    </div>
                    <div class="flex gap-2 ml-4">
                        <a href="{{ route('lecturer.questions.edit', [$exam, $question]) }}" class="text-yellow-600 hover:underline text-sm">Edit</a>
                        <form action="{{ route('lecturer.questions.destroy', [$exam, $question]) }}" method="POST" onsubmit="return confirm('Delete question?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline text-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white shadow rounded-lg p-8 text-center text-gray-500">
                No questions yet. <a href="{{ route('lecturer.questions.create', $exam) }}" class="text-indigo-600">Add your first question</a>.
            </div>
        @endforelse
    </div>
</x-app-layout>
