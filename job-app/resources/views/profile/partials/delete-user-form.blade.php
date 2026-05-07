<section class="space-y-6">
    <header>
        <h2 class="text-2xl font-bold text-white">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-2 text-gray-400 text-lg">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-3 bg-red-600/20 hover:bg-red-600/40 text-red-400 hover:text-red-300 border border-red-900/30 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
        {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 space-y-6">
            @csrf
            @method('delete')

            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-white">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>

                <p class="text-gray-400">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>
            </div>

            <div class="space-y-2">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input id="password" name="password" type="password"
                    class="block w-full px-5 py-4 glass-effect border border-gray-700/50 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/30 transition-all text-white"
                    placeholder="{{ __('Confirm your password to delete') }}" />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-6 py-3 glass-effect border border-gray-700/50 text-gray-300 hover:text-white rounded-xl transition-all">
                    {{ __('Cancel') }}
                </button>

                <button type="submit"
                    class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-xl hover:shadow-red-500/40 transform hover:scale-105">
                    {{ __('Permanently Delete') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
