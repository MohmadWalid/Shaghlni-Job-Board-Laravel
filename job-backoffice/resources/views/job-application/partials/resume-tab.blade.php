{{--
    skills    → auto-decoded to PHP array via Resume::$casts ['skills' => 'array']
    education → auto-decoded to PHP array via Resume::$casts ['education' => 'array']
    No manual json_decode() needed here.
--}}

<div class="p-6 bg-gradient-to-br from-gray-50 to-white min-h-screen">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h3 class="text-3xl font-bold text-gray-900">Resume</h3>
            <p class="text-sm text-gray-500 mt-1">Professional profile overview</p>
        </div>

        @if ($resume->file_url ?? false)
            <a href="{{ $resume->file_url }}" target="_blank"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download PDF
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-6">

        {{-- ── Summary Card ──────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-2">Summary</h4>
                    <p class="text-gray-700 leading-relaxed text-sm">{{ $resume->summary ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- ── Skills Card ────────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-3">Skills</h4>

                    @if (!empty($resume->skills))
                        {{-- $resume->skills is already a PHP array thanks to $casts --}}
                        <div class="flex flex-wrap gap-2">
                            @foreach ($resume->skills as $skill)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                             bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic text-sm">No skills listed</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Experience Card ─────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-2">Experience</h4>
                    <p class="text-gray-700 leading-relaxed text-sm whitespace-pre-line">{{ $resume->experience ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- ── Education Card ──────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-3">Education</h4>

                    @if (!empty($resume->education))
                        {{-- $resume->education is already a PHP array thanks to $casts --}}
                        <div class="space-y-3">
                            @foreach ($resume->education as $edu)
                                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                                    @if (!empty($edu['degree']))
                                        <p class="text-sm font-semibold text-gray-900 leading-snug">
                                            {{ $edu['degree'] }}
                                        </p>
                                    @endif
                                    @if (!empty($edu['institution']))
                                        <p class="mt-1 text-sm text-indigo-600 font-medium">
                                            {{ $edu['institution'] }}
                                        </p>
                                    @endif
                                    @if (!empty($edu['year']))
                                        <p class="mt-1.5 inline-flex items-center gap-1 text-xs text-gray-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $edu['year'] }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic text-sm">No education listed</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
