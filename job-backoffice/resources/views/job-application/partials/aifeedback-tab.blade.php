{{--
    All data preparation (score conversion, bullet point parsing, colour mapping)
    lives in JobApplication model accessors:
      - $jobApplication->score_out_of10
      - $jobApplication->feedback_bullet_points
      - $jobApplication->score_color
--}}

<div class="p-6 bg-gradient-to-br from-gray-50 to-white">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h3 class="text-3xl font-bold text-gray-900">AI Feedback</h3>
            <p class="text-sm text-gray-500 mt-1">Automated analysis and recommendations</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">

        {{-- ── AI Score Card ──────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="none">
                        <title>Score Metrics Chart</title>
                        <rect x="3" y="3" width="18" height="18" fill="#f0f0f0" rx="1" />
                        <rect x="5" y="15" width="4" height="6" fill="#4ade80" rx="1" />
                        <rect x="10" y="11" width="4" height="10" fill="#3b82f6" rx="1" />
                        <rect x="15" y="7" width="4" height="14" fill="#ef4444" rx="1" />
                        <line x1="3" y1="21" x2="21" y2="21" stroke="#333" stroke-width="1" />
                    </svg>
                </div>

                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-4">AI Score</h4>

                    @if ($jobApplication->score_out_of10 !== null)
                        @php
                            // Purely presentational: map the model's colour key to Tailwind classes
                            $badgeClasses = [
                                'emerald' => 'bg-emerald-50 border-emerald-200 text-emerald-700',
                                'amber'   => 'bg-amber-50 border-amber-200 text-amber-700',
                                'rose'    => 'bg-rose-50 border-rose-200 text-rose-700',
                                'indigo'  => 'bg-indigo-50 border-indigo-200 text-indigo-700',
                            ];
                            $barClasses = [
                                'emerald' => 'bg-emerald-500',
                                'amber'   => 'bg-amber-500',
                                'rose'    => 'bg-rose-500',
                                'indigo'  => 'bg-indigo-500',
                            ];
                            $color = $jobApplication->score_color;
                        @endphp

                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            {{-- Score badge --}}
                            <div class="inline-flex items-baseline gap-1 px-5 py-3 rounded-2xl border-2 {{ $badgeClasses[$color] }}">
                                <span class="text-4xl font-extrabold leading-none">{{ $jobApplication->score_out_of10 }}</span>
                                <span class="text-lg font-semibold opacity-70">/10</span>
                            </div>

                            {{-- Progress bar --}}
                            <div class="flex-1 max-w-xs">
                                <p class="text-xs text-gray-400 mb-1 uppercase tracking-wide">Overall Match Score</p>
                                <div class="w-full bg-gray-100 rounded-full h-2.5">
                                    <div class="{{ $barClasses[$color] }} h-2.5 rounded-full transition-all duration-500"
                                         style="width: {{ $jobApplication->score_out_of10 * 10 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 italic text-sm">No score available</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── AI Feedback Card ───────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="none">
                        <title>Gradient Comment/Score Bubble</title>
                        <defs>
                            <linearGradient id="gradientBubble" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#a855f7;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#ec4899;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <path fill="url(#gradientBubble)"
                            d="M21 4c1.1 0 2 .9 2 2v8c0 1.1-.9 2-2 2h-5.17l-4.98 4.98c-.19.19-.45.29-.7.29s-.51-.1-.71-.29L10.17 16H3c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2h18z" />
                        <circle cx="8" cy="10" r="1" fill="#ffffff" />
                        <circle cx="12" cy="10" r="1" fill="#ffffff" />
                        <circle cx="16" cy="10" r="1" fill="#ffffff" />
                    </svg>
                </div>

                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-3">AI Feedback</h4>

                    @if (!empty($jobApplication->feedback_bullet_points))
                        <ul class="space-y-2.5">
                            @foreach ($jobApplication->feedback_bullet_points as $point)
                                <li class="flex items-start gap-2.5">
                                    <span class="mt-1.5 flex-shrink-0 w-2 h-2 rounded-full bg-indigo-400"></span>
                                    <span class="text-gray-700 leading-relaxed text-sm">{{ $point }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @elseif ($jobApplication->ai_generated_feedback)
                        {{-- Fallback: show raw text if accessor returned no points --}}
                        <p class="text-gray-700 leading-relaxed text-sm">{{ $jobApplication->ai_generated_feedback }}</p>
                    @else
                        <p class="text-gray-500 italic text-sm">No feedback available</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
