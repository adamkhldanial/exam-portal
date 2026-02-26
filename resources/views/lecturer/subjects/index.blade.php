<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Subjects</h2>
            <a href="{{ route('lecturer.subjects.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">+ New Subject</a>
        </div>
    </x-slot>
    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))<div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>@endif
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exams</th>
                    <th class="px-6 py-3"></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @forelse($subjects as $subject)
                    <tr>
                        <td class="px-6 py-4 font-medium">{{ $subject->name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $subject->code }}</td>
                        <td class="px-6 py-4">{{ $subject->classRoom->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $subject->exams_count }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('lecturer.subjects.edit', $subject) }}" class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('lecturer.subjects.destroy', $subject) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No subjects yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
