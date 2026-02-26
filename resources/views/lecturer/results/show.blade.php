<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ $submission->student->name }} — {{ $exam->title }}
        </h2>
    </x-slot>
    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @if(session('success'))<div class="bg-green-100 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>@endif

        <div class="bg-white shadow rounded-lg p-5 flex gap-6 text-sm">
            <div><span class="text-gray-500">Score:</span> <strong>{{ $submission->score ?? '—' }}/{{ $submission->total_marks ?? '—' }}</strong></div>
            <div><span class="text-gray-500">Percentage:</span> <strong>{{ $submission->percentage ?? '—' }}%</strong></div>
            <div><span class="text-gray-500">Status:</span> <strong class="capitalize">{{ $submission->status }}</strong></div>
        </div>

        <form action="{{ route('lecturer.results.grade', [$exam, $submission]) }}" method="POST" class="space-y-4">
            @csrf
            @foreach($submission->answers as $answer)
                <div class="bg-white shadow rounded-lg p-5">
                    <p class="font-medium mb-2">{{ $loop->index + 1 }}. {{ $answer->question->question_text }}</p>
                    <p class="text-xs text-gray-400 mb-3 capitalize">{{ str_replace('_', ' ', $answer->question->type) }} · {{ $answer->question->marks }} mark(s)</p>

                    @if($answer->question->isMultipleChoice())
                        <div class="space-y-1">
                            @foreach($answer->question->options as $opt)
                                <div class="flex items-center gap-2 text-sm p-1 rounded
                                {{ $opt->is_correct ? 'text-green-700' : '' }}
                                {{ $answer->option_id == $opt->id && !$opt->is_correct ? 'text-red-600 bg-red-50' : '' }}
                                {{ $answer->option_id == $opt->id && $opt->is_correct ? 'bg-green-50' : '' }}">
                                    {{ $opt->is_correct ? '✓' : ($answer->option_id == $opt->id ? '✗' : '○') }}
                                    {{ $opt->option_text }}
                                    @if($answer->option_id == $opt->id) <span class="font-medium">(Student's answer)</span> @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 rounded p-3 text-sm mb-3">
                            <span class="font-medium">Student's Answer:</span><br>
                            {{ $answer->answer_text ?? '<em class="text-gray-400">No answer provided</em>' }}
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="text-sm font-medium text-gray-700">Marks Awarded:</label>
                            <input type="number" name="marks[{{ $answer->id }}]"
                                   value="{{ $answer->marks_awarded }}"
                                   min="0" max="{{ $answer->question->marks }}"
                                   class="w-20 border-gray-300 rounded-md shadow-sm text-sm">
                            <span class="text-sm text-gray-500">/ {{ $answer->question->marks }}</span>
                        </div>
                    @endif
                </div>
            @endforeach

            @if($submission->answers->contains(fn($a) => !$a->question->isMultipleChoice()))
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save Grades</button>
                </div>
            @endif
        </form>
    </div>
</x-app-layout>
