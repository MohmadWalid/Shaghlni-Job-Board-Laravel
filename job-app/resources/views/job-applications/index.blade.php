<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-100 leading-tight mb-1">
                    My Applications
                </h2>
                <p class="text-sm text-gray-400">Track and manage your job applications</p>
            </div>

            <!-- Quick Stats -->
            <div class="flex gap-2 flex-wrap">
                <span
                    class="px-3 py-1.5 bg-yellow-600/20 border border-yellow-600/30 text-yellow-400 rounded-lg text-sm font-medium">
                    {{ $statusCounts['Pending'] ?? 0 }} Pending
                </span>
                <span
                    class="px-3 py-1.5 bg-green-600/20 border border-green-600/30 text-green-400 rounded-lg text-sm font-medium">
                    {{ $statusCounts['Accepted'] ?? 0 }} Accepted
                </span>
                <span
                    class="px-3 py-1.5 bg-red-600/20 border border-red-600/30 text-red-400 rounded-lg text-sm font-medium">
                    {{ $statusCounts['Rejected'] ?? 0 }} Rejected
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-toast-message />

            <!-- Filters Section -->
            <div class="glass-effect rounded-2xl overflow-hidden shadow-xl border border-gray-800 mb-6"
                x-data="{ showFilters: false }">
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-100">Filters</h3>
                    </div>
                    <button @click="showFilters = !showFilters"
                        class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-200 rounded-lg text-sm font-medium transition-colors">
                        <span x-text="showFilters ? 'Hide' : 'Show'"></span>
                    </button>
                </div>

                <form method="GET" action="{{ route('job-applications.index') }}" x-show="showFilters" x-cloak>
                    <div class="px-4 pb-4 border-t border-gray-800">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                            <!-- Status Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Status</label>
                                <select name="status"
                                    class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-lg text-gray-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 transition-colors">
                                    <option value="">All Statuses</option>
                                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="Accepted" {{ request('status') === 'Accepted' ? 'selected' : '' }}>
                                        Accepted</option>
                                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>
                            </div>

                            <!-- Job Type Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Job Type</label>
                                <select name="type"
                                    class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-lg text-gray-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 transition-colors">
                                    <option value="">All Types</option>
                                    <option value="full-time" {{ request('type') === 'full-time' ? 'selected' : '' }}>
                                        Full-time</option>
                                    <option value="contract" {{ request('type') === 'contract' ? 'selected' : '' }}>
                                        Contract</option>
                                    <option value="remote" {{ request('type') === 'remote' ? 'selected' : '' }}>Remote
                                    </option>
                                    <option value="hybrid" {{ request('type') === 'hybrid' ? 'selected' : '' }}>Hybrid
                                    </option>
                                </select>
                            </div>

                            <!-- Sort By -->
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Sort By</label>
                                <select name="sort"
                                    class="w-full px-4 py-2.5 bg-gray-800 border border-gray-700 rounded-lg text-gray-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 transition-colors">
                                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest
                                        First</option>
                                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest
                                        First</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-4">
                            <button type="submit"
                                class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-lg font-medium transition-all">
                                Apply Filters
                            </button>
                            <a href="{{ route('job-applications.index') }}"
                                class="px-6 py-2 bg-gray-800 hover:bg-gray-700 text-gray-200 rounded-lg font-medium transition-colors">
                                Clear Filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            @if ($applications->isEmpty())
                <!-- Empty State -->
                <div class="glass-effect rounded-2xl overflow-hidden shadow-xl border border-gray-800 p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-100 mb-3">
                            @if (request()->has('status') || request()->has('type'))
                                No Applications Found
                            @else
                                No Applications Yet
                            @endif
                        </h3>
                        <p class="text-gray-400 mb-6">
                            @if (request()->has('status') || request()->has('type'))
                                Try adjusting your filters to see more results.
                            @else
                                Start applying to jobs and track your applications here.
                            @endif
                        </p>
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Browse Jobs
                        </a>
                    </div>
                </div>
            @else
                <!-- Applications List -->
                <div class="space-y-4">
                    @foreach ($applications as $application)
                        <div x-data="{ showFeedback: false }"
                            class="glass-effect rounded-2xl overflow-hidden shadow-xl border border-gray-800 hover:border-gray-700 transition-all duration-300 group">
                            <div class="p-6">
                                <div class="flex flex-col lg:flex-row lg:items-start gap-4">
                                    <!-- Company Logo -->
                                    <div
                                        class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>

                                    <!-- Application Details -->
                                    <div class="flex-1 min-w-0">
                                        <!-- Header Row -->
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-3">
                                            <div class="flex-1">
                                                <a href="{{ route('job-vacancies.show', $application->job_vacancy->id) }}"
                                                    class="text-xl font-bold text-gray-100 hover:text-indigo-400 transition-colors block mb-1">
                                                    {{ $application->job_vacancy->title }}
                                                </a>
                                                <div class="flex items-center gap-2 text-gray-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                    <span>{{ $application->job_vacancy->company->name }}</span>
                                                </div>
                                            </div>

                                            <!-- Status Badge -->
                                            @php
                                                $statusConfig = match ($application->status) {
                                                    'Pending' => [
                                                        'bg' => 'bg-yellow-600',
                                                        'text' => 'Pending Review',
                                                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                                    ],
                                                    'Accepted' => [
                                                        'bg' => 'bg-green-600',
                                                        'text' => 'Accepted',
                                                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                                    ],
                                                    'Rejected' => [
                                                        'bg' => 'bg-red-600',
                                                        'text' => 'Rejected',
                                                        'icon' =>
                                                            'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                                                    ],
                                                    default => [
                                                        'bg' => 'bg-gray-600',
                                                        'text' => $application->status,
                                                        'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                                    ],
                                                };
                                            @endphp
                                            <span
                                                class="inline-flex items-center gap-2 px-4 py-2 {{ $statusConfig['bg'] }} text-white rounded-full text-sm font-semibold whitespace-nowrap">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="{{ $statusConfig['icon'] }}" />
                                                </svg>
                                                {{ $statusConfig['text'] }}
                                            </span>
                                        </div>

                                        <!-- Job Info Badges -->
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            <!-- Location -->
                                            <span
                                                class="inline-flex items-center px-3 py-1 bg-gray-800 text-gray-300 rounded-lg text-sm">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                </svg>
                                                {{ $application->job_vacancy->location }}
                                            </span>

                                            <!-- Job Type -->
                                            @php
                                                $typeColor = match ($application->job_vacancy->type) {
                                                    'full-time' => 'bg-green-600/20 text-green-400 border-green-600/30',
                                                    'contract' => 'bg-blue-600/20 text-blue-400 border-blue-600/30',
                                                    'remote' => 'bg-purple-600/20 text-purple-400 border-purple-600/30',
                                                    'hybrid' => 'bg-yellow-600/20 text-yellow-400 border-yellow-600/30',
                                                    default => 'bg-gray-600/20 text-gray-400 border-gray-600/30',
                                                };
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-3 py-1 {{ $typeColor }} border rounded-lg text-sm font-medium">
                                                {{ ucfirst(str_replace('-', ' ', $application->job_vacancy->type)) }}
                                            </span>

                                            <!-- Salary -->
                                            @if ($application->job_vacancy->salary)
                                                <span
                                                    class="inline-flex items-center px-3 py-1 bg-emerald-600/20 text-emerald-400 border border-emerald-600/30 rounded-lg text-sm font-medium">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    ${{ number_format($application->job_vacancy->salary, 0) }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Timeline & Actions Row -->
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4 border-t border-gray-800">
                                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                                <span class="flex items-center gap-1.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Applied {{ $application->created_at->diffForHumans() }}
                                                </span>
                                                <span class="flex items-center gap-1.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $application->created_at->format('M d, Y') }}
                                                </span>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('job-vacancies.show', $application->job_vacancy) }}"
                                                    class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-200 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    View Job
                                                </a>

                                                {{-- <a href="{{ route('job-applications.show', $application) }}"
                                                    class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-lg text-sm font-medium transition-all flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    View Details
                                                </a> --}}

                                                @if($application->ai_generated_score !== null)
                                                <button @click="showFeedback = !showFeedback"
                                                    class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-lg text-sm font-medium transition-all flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                    </svg>
                                                    <span x-text="showFeedback ? 'Hide Feedback' : 'AI Feedback'"></span>
                                                </button>
                                                @else
                                                <div class="px-4 py-2 bg-gray-800 text-gray-400 rounded-lg text-sm font-medium flex items-center gap-2 cursor-not-allowed border border-gray-700" title="Our AI is currently analyzing your resume. Check back in a few minutes!">
                                                    <svg class="w-4 h-4 animate-spin text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                    <span>Analyzing...</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- AI Feedback Section -->
                            @if($application->ai_generated_score !== null)
                            <div x-show="showFeedback" x-collapse x-cloak class="border-t border-gray-800 bg-gray-900/50">
                                <div class="p-6">
                                    <div class="flex items-center gap-4 mb-6">
                                        <div class="flex flex-col items-center justify-center w-16 h-16 rounded-2xl bg-{{ $application->score_color }}-500/20 text-{{ $application->score_color }}-400 border border-{{ $application->score_color }}-500/30">
                                            <div class="flex items-baseline">
                                                <span class="text-2xl font-bold">{{ $application->score_out_of10 }}</span>
                                                <span class="text-sm opacity-70">/10</span>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-gray-100">AI Compatibility Analysis</h4>
                                            <p class="text-sm text-gray-400">Based on your resume and the job requirements</p>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-3 pl-2">
                                        @forelse($application->feedback_bullet_points as $point)
                                        <div class="flex items-start gap-3">
                                            <div class="mt-1 flex-shrink-0 w-5 h-5 rounded-full bg-indigo-500/20 flex items-center justify-center border border-indigo-500/30">
                                                <svg class="w-3 h-3 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <p class="text-gray-300 leading-relaxed">{{ $point }}</p>
                                        </div>
                                        @empty
                                        <p class="text-gray-500 italic">No specific feedback generated.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($applications->hasPages())
                    <div class="mt-6">
                        {{ $applications->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <style>
        .glass-effect {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(10px);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</x-app-layout>
