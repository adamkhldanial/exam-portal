<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Exams</h2>
            <a href="{{ route('lecturer.exams.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">+ New Exam</a>
        </div>
    </x-slot>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))<div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>@endif
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Questions</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @forelse($exams as $exam)
                    <tr>
                        <td class="px-6 py-4 font-medium">{{ $exam->title }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $exam->subject->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $exam->time_limit }} min</td>
                        <td class="px-6 py-4">{{ $exam->questions_count }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded {{ $exam->is_published ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $exam->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('lecturer.exams.show', $exam) }}" class="text-indigo-600 hover:underline">View</a>
                            <a href="{{ route('lecturer.results.index', $exam) }}" class="text-blue-600 hover:underline">Results ({{ $exam->submissions_count }})</a>
                            <a href="{{ route('lecturer.exams.edit', $exam) }}" class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('lecturer.exams.toggle-publish', $exam) }}" method="POST" class="inline">
                                @csrf
                                <button class="{{ $exam->is_published ? 'text-orange-600' : 'text-green-600' }} hover:underline">
                                    {{ $exam->is_published ? 'Unpublish' : 'Publish' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No exams yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
