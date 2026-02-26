<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">{{ $exam->title }}</h2>
            <div id="timer" class="bg-red-100 text-red-700 font-mono font-bold px-4 py-2 rounded text-lg"></div>
        </div>
    </x-slot>
    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('student.exams.submit', $exam) }}" method="POST" id="examForm" class="space-y-6">
            @csrf
            @foreach($exam->questions as $i => $question)
                <div class="bg-white shadow rounded-lg p-5">
                    <p class="font-medium mb-3">{{ $i+1 }}. {{ $question->question_text }}
                        <span class="text-sm text-gray-400">({{ $question->marks }} mark{{ $question->marks > 1 ? 's' : '' }})</span>
                    </p>

                    @if($question->isMultipleChoice())
                        <div class="space-y-2">
                            @foreach($question->options as $option)
                                <label class="flex items-center gap-3 p-3 rounded border cursor-pointer hover:bg-indigo-50 transition
                            {{ $existingAnswers[$question->id] == $option->id ? 'border-indigo-400 bg-indigo-50' : 'border-gray-200' }}">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                                        {{ $existingAnswers[$question->id] == $option->id ? 'checked' : '' }}>
                                    <span>{{ $option->option_text }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <textarea name="answers[{{ $question->id }}]" rows="4"
                                  placeholder="Type your answer here..."
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500">{{ $textAnswers[$question->id] ?? '' }}</textarea>
                    @endif
                </div>
            @endforeach

            <div class="flex justify-end">
                <button type="submit" onclick="return confirm('Submit exam? You cannot change your answers after submission.')"
                        class="bg-indigo-600 text-white px-8 py-3 rounded-lg text-lg font-medium hover:bg-indigo-700">
                    Submit Exam
                </button>
            </div>
        </form>
    </div>

    <script>
        let remaining = {{ $remainingSeconds }};
        const timerEl = document.getElementById('timer');

        function formatTime(s) {
            const m = Math.floor(s / 60), sec = s % 60;
            return String(m).padStart(2, '0') + ':' + String(sec).padStart(2, '0');
        }

        function tick() {
            timerEl.textContent = formatTime(remaining);
            if (remaining <= 0) {
                timerEl.textContent = 'Time Up!';
                document.getElementById('examForm').submit();
                return;
            }
            if (remaining <= 60) timerEl.classList.add('animate-pulse');
            remaining--;
            setTimeout(tick, 1000);
        }
        tick();
    </script>
</x-app-layout>
