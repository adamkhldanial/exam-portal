<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Result — {{ $exam->title }}</h2></x-slot>
    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

        {{-- Score Card --}}
        <div class="bg-white shadow rounded-lg p-6 text-center">
            @php $passed = $submission->percentage >= $exam->passing_score; @endphp
            <div class="text-6xl font-bold {{ $passed ? 'text-green-600' : 'text-red-500' }}">
                {{ $submission->percentage ?? '?' }}%
            </div>
            <div class="text-gray-500 mt-2">{{ $submission->score ?? '?' }} / {{ $submission->total_marks ?? '?' }} marks</div>
            <div class="mt-3 text-lg font-semibold {{ $passed ? 'text-green-600' : 'text-red-500' }}">
                {{ $passed ? '🎉 Passed!' : '❌ Not Passed' }}
            </div>
            <div class="text-sm text-gray-400 mt-1">Passing score: {{ $exam->passing_score }}% · Status: <span class="capitalize">{{ $submission->status }}</span></div>
        </div>

        {{-- Answer Review --}}
        <h3 class="font-semibold text-lg">Answer Review</h3>
        @foreach($submission->answers as $i => $answer)
            <div class="bg-white shadow rounded-lg p-5">
                <p class="font-medium mb-2">{{ $i+1 }}. {{ $answer->question->question_text }}</p>

                @if($answer->question->isMultipleChoice())
                    <div class="space-y-1">
                        @foreach($answer->question->options as $opt)
                            <div class="flex items-center gap-2 text-sm p-2 rounded
                        {{ $opt->is_correct ? 'bg-green-50 text-green-700' : '' }}
                        {{ $answer->option_id == $opt->id && !$opt->is_correct ? 'bg-red-50 text-red-600' : '' }}">
                                {{ $opt->is_correct ? '✓' : ($answer->option_id == $opt->id ? '✗' : '○') }}
                                {{ $opt->option_text }}
                                @if($opt->is_correct) <span class="font-medium">(Correct)</span> @endif
                                @if($answer->option_id == $opt->id && !$opt->is_correct) <span>(Your answer)</span> @endif
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs mt-2 {{ $answer->option && $answer->option->is_correct ? 'text-green-600' : 'text-red-600' }}">
                        {{ $answer->option && $answer->option->is_correct ? '✓ Correct' : '✗ Incorrect' }}
                    </p>
                @else
                    <div class="bg-gray-50 rounded p-3 text-sm">{{ $answer->answer_text ?? 'No answer' }}</div>
                    @if($submission->status === 'graded')
                        <p class="text-sm mt-2 font-medium text-indigo-700">
                            Marks: {{ $answer->marks_awarded ?? 0 }} / {{ $answer->question->marks }}
                        </p>
                    @else
                        <p class="text-sm mt-2 text-yellow-600 italic">Pending manual grading</p>
                    @endif
                @endif
            </div>
        @endforeach

        <div class="flex justify-center">
            <a href="{{ route('student.exams.index') }}" class="text-indigo-600 hover:underline">← Back to Exams</a>
        </div>
    </div>
</x-app-layout>
