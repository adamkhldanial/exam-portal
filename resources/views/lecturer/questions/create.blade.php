<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Add Question — {{ $exam->title }}</h2></x-slot>
    <div class="py-12 max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('lecturer.questions.store', $exam) }}" method="POST" class="space-y-4" id="questionForm">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Question Text</label>
                    <textarea name="question_text" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('question_text') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Question Type</label>
                        <select name="type" id="typeSelect" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" onchange="toggleOptions()">
                            <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="open_text" {{ old('type') == 'open_text' ? 'selected' : '' }}>Open Text</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Marks</label>
                        <input type="number" name="marks" value="{{ old('marks', 1) }}" min="1" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>

                <div id="optionsSection">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Options (select the correct answer)</label>
                    <div id="optionsList" class="space-y-2">
                        @for($i = 0; $i < 4; $i++)
                            <div class="flex items-center gap-3">
                                <input type="radio" name="correct_option" value="{{ $i }}" {{ $i == 0 ? 'checked' : '' }}>
                                <input type="text" name="options[{{ $i }}][text]" value="{{ old("options.$i.text") }}" placeholder="Option {{ $i+1 }}" class="flex-1 border-gray-300 rounded-md shadow-sm text-sm" required>
                            </div>
                        @endfor
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('lecturer.exams.show', $exam) }}" class="px-4 py-2 border rounded text-gray-600">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Add Question</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function toggleOptions() {
            const type = document.getElementById('typeSelect').value;
            const section = document.getElementById('optionsSection');
            section.style.display = type === 'multiple_choice' ? 'block' : 'none';
            section.querySelectorAll('input').forEach(el => el.required = type === 'multiple_choice');
        }
        toggleOptions();
    </script>
</x-app-layout>
