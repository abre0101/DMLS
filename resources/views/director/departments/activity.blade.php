@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 py-10">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-8 border-b-4 border-indigo-600 pb-3 tracking-wide">
        Department Activity
    </h1>

    <div class="overflow-x-auto bg-white rounded-lg shadow-lg border border-gray-200">
        <table class="min-w-full border-separate border-spacing-x-0 border-spacing-y-0 table-auto">
            <thead class="bg-indigo-50">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-indigo-700 uppercase tracking-wider border-r border-gray-300">
                        Department
                    </th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-indigo-700 uppercase tracking-wider border-r border-gray-300">
                        Total Documents
                    </th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-indigo-700 uppercase tracking-wider border-r border-gray-300">
                        Total Letters
                    </th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-indigo-700 uppercase tracking-wider border-r border-gray-300">
                        Total Items
                    </th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-indigo-700 uppercase tracking-wider">
                        Employees
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse($departments as $dept)
                    <tr class="hover:bg-indigo-100 transition-colors duration-300">
                        <td class="px-6 py-5 text-gray-900 font-semibold text-lg border-r border-gray-200">
                            {{ $dept->name }}
                        </td>
                        <td class="px-6 py-5 text-right text-gray-700 font-medium border-r border-gray-200">
                            {{ number_format($dept->documents_count) }}
                        </td>
                        <td class="px-6 py-5 text-right text-gray-700 font-medium border-r border-gray-200">
                            {{ number_format($dept->letters_count) }}
                        </td>
                        <td class="px-6 py-5 text-right text-indigo-700 font-bold border-r border-gray-200">
                            {{ number_format($dept->documents_count + $dept->letters_count) }}
                        </td>
                        <td class="px-6 py-5 text-right text-gray-700 font-medium">
                            {{ number_format($dept->employees_count ?? 0) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">
                            No department activity data available.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
