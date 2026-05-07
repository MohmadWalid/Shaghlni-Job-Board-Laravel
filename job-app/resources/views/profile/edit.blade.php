<x-app-layout>
    <div class="space-y-12">
        <!-- Header Section -->
        <div class="text-center space-y-4 opacity-0 animate-fade-in-up">
            <h1 class="text-5xl lg:text-6xl font-bold leading-tight">
                Account Settings
            </h1>
            <p class="text-2xl text-gray-300">
                Manage your <span class="gradient-text font-semibold">Profile</span> and preferences
            </p>
        </div>

        <div class="max-w-4xl mx-auto space-y-8 opacity-0 animate-fade-in-up" style="animation-delay: 0.2s;">
            <!-- Profile Info Section -->
            <div class="glass-effect rounded-2xl p-8 lg:p-12 shadow-2xl border border-gray-800/50">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Password Section -->
            <div class="glass-effect rounded-2xl p-8 lg:p-12 shadow-2xl border border-gray-800/50">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete User Section -->
            <div class="glass-effect rounded-2xl p-8 lg:p-12 shadow-2xl border border-gray-800/50 border-red-900/20">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
