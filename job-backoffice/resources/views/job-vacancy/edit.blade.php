<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Job Vacancy: {{ $jobVacancy->title }}
            </h2>
            <div class="flex space-x-4 pr-8">
                <!-- Back Button -->
                <x-buttons.back-button
                    href="{{ request()->query('redirect') == 'show'
                        ? route('job-vacancies.show', $jobVacancy->id)
                        : route('job-vacancies.index') }}">
                    Back
                </x-buttons.back-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('job-vacancies.update', $jobVacancy->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Add this hidden input to preserve the redirect parameter --}}
                <input type="hidden" name="redirect" value="{{ request()->query('redirect', 'index') }}">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-xl font-semibold mb-4">Edit Job Vacancy</h2>

                        <!-- 1. Title Field (Text) -->
                        <div class="mb-5">
                            <label for="title" class="block text-sm font-medium text-gray-700">Job Title</label>
                            <input type="text" name="title" id="title"
                                value="{{ old('title', $jobVacancy->title) }}"
                                class="mt-1 block w-full rounded-md shadow-sm sm:text-sm p-3 transition-colors duration-200
                            {{ $errors->has('title') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-indigo-500' }} focus:border-indigo-500"
                                placeholder="e.g., Senior Software Engineer">
                            @error('title')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category Selection (Multiple) -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                            <div
                                class="border rounded-md p-3 bg-white shadow-sm max-h-60 overflow-y-auto
                                {{ $errors->has('category_ids') ? 'border-red-500' : 'border-gray-300' }}">
                                @foreach ($categories as $category)
                                    <label
                                        class="flex items-center p-2 rounded hover:bg-indigo-50 cursor-pointer transition-colors duration-150 group">
                                        <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                                            {{ in_array($category->id, old('category_ids', $jobVacancy->categories->pluck('id')->toArray())) ? 'checked' : '' }}
                                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2 cursor-pointer">
                                        <span class="ml-3 text-sm text-gray-700 group-hover:text-indigo-700">
                                            {{ $category->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Select one or more categories</p>
                            @error('category_ids')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Company Field -->
                        <div class="mb-4">
                            <label for="company_id" class="block text-sm font-medium text-gray-700">Company</label>
                            @if (auth()->user()->role === 'company-owner')
                                @php
                                    $userCompany = auth()->user()->companies()->first();
                                @endphp
                                <div class="mt-1">
                                    <input type="text" value="{{ $userCompany?->name ?? 'No Company Assigned' }}"
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100 shadow-sm sm:text-sm p-3 text-gray-500 cursor-not-allowed"
                                        disabled readonly>
                                    <input type="hidden" name="company_id" value="{{ $userCompany?->id }}">
                                </div>
                            @else
                                <div class="relative mt-1">
                                    <select name="company_id" id="company_id"
                                        class="appearance-none w-full rounded-md shadow-sm sm:text-sm p-2 pr-10 transition-colors duration-200
                            {{ $errors->has('company_id') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-indigo-500' }}
                            border focus:border-indigo-500 focus:outline-none focus:ring-2 bg-white cursor-pointer
                            hover:border-indigo-400">
                                        <option value="" disabled>Select a Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ old('company_id', $jobVacancy->company_id) == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('company_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>

                        <!-- Type Dropdown -->
                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                            <div class="relative mt-1">
                                <select name="type" id="type"
                                    class="appearance-none w-full rounded-md shadow-sm sm:text-sm p-2 pr-10 transition-colors duration-200
                                    {{ $errors->has('type') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-indigo-500' }}
                                    border focus:border-indigo-500 focus:outline-none focus:ring-2 bg-white cursor-pointer
                                    hover:border-indigo-400">
                                    <option value="" disabled>Select Type</option>
                                    <option value="full-time"
                                        {{ old('type', $jobVacancy->type) === 'full-time' ? 'selected' : '' }}>
                                        Full-Time</option>
                                    <option value="contract"
                                        {{ old('type', $jobVacancy->type) === 'contract' ? 'selected' : '' }}>
                                        Contract</option>
                                    <option value="remote"
                                        {{ old('type', $jobVacancy->type) === 'remote' ? 'selected' : '' }}>Remote
                                    </option>
                                    <option value="hybrid"
                                        {{ old('type', $jobVacancy->type) === 'hybrid' ? 'selected' : '' }}>Hybrid
                                    </option>
                                </select>
                            </div>
                            @error('type')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 4. Location Field (Text) -->
                        <div class="mb-5">
                            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" name="location" id="location"
                                value="{{ old('location', $jobVacancy->location) }}"
                                class="mt-1 block w-full rounded-md shadow-sm sm:text-sm p-3 transition-colors duration-200
                            {{ $errors->has('location') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-indigo-500' }} focus:border-indigo-500"
                                placeholder="e.g., New York, NY (or Remote)">
                            @error('location')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 5. Salary Field (Text) -->
                        <div class="mb-5">
                            <label for="salary" class="block text-sm font-medium text-gray-700">Salary
                                (annual in USD)</label>
                            <input type="number" step="0.01" name="salary" id="salary"
                                value="{{ old('salary', $jobVacancy->salary) }}"
                                class="mt-1 block w-full rounded-md shadow-sm sm:text-sm p-3 transition-colors duration-200
                            {{ $errors->has('salary') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-indigo-500' }} focus:border-indigo-500"
                                placeholder="e.g., 120000.00">
                            @error('salary')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 3. Required Skills Field (Text) -->
                        <div class="mb-5">
                            <label for="required_skills" class="block text-sm font-medium text-gray-700">Required
                                Skills
                                (Comma Separated)</label>
                            <input type="text" name="required_skills" id="required_skills"
                                value="{{ old('required_skills', $jobVacancy->required_skills) }}"
                                class="mt-1 block w-full rounded-md shadow-sm sm:text-sm p-3 transition-colors duration-200
                                {{ $errors->has('required_skills') ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-indigo-500' }} focus:border-indigo-500"
                                placeholder="e.g., Laravel, Vue.js, MySQL, Tailwind CSS">
                            @error('required_skills')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Job Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Job Description
                            </label>

                            <textarea name="description" id="description" rows="10"
                                placeholder="• What will the person do day-to-day?&#10;• Tech stack and tools&#10;• Team size and culture&#10;• Growth opportunities"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-4
                            font-mono text-sm leading-relaxed resize-y
                            @error('description') border-red-500 ring-2 ring-red-500 @enderror">{{ old('description', $jobVacancy->description) }}</textarea>

                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <p class="mt-2 text-xs text-gray-500">
                                Press Enter for new lines • Formatting is preserved automatically
                            </p>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-2">
                            <x-buttons.back-button
                                href="{{ request()->query('redirect') == 'show'
                                    ? route('job-vacancies.show', $jobVacancy->id)
                                    : route('job-vacancies.index') }}">
                                Cancel
                            </x-buttons.back-button>

                            {{-- Update Button --}}
                            <x-buttons.submit-button>Update Job Vacancy</x-buttons.submit-button>
                        </div>


                    </div>
            </form>
        </div>
    </div>
</x-app-layout>
