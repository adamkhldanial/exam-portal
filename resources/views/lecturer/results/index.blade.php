<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Results — {{ $exam->title }}</h2></x-slot>
    <div class="py-12 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Started</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @forelse($submissions as $sub)
                    <tr>
                        <td class="px-6 py-4">{{ $sub->student->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $sub->started_at->format('d M H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $sub->submitted_at?->format('d M H:i') ?? '—' }}</td>
                        <td class="px-6 py-4 font-medium">
                            @if($sub->total_marks)
                                {{ $sub->score }}/{{ $sub->total_marks }} ({{ $sub->percentage }}%)
                            @else — @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs px-2 py-1 rounded capitalize
                                {{ $sub->status === 'graded' ? 'bg-green-100 text-green-700' :
                                   ($sub->status === 'submitted' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ $sub->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('lecturer.results.show', [$exam, $sub]) }}" class="text-indigo-600 hover:underline text-sm">View / Grade</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No submissions yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
