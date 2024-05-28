<section class="delete-account-section">
    <header class="delete-account-header">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Account verwijderen') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Zodra je account is verwijderd, worden alle bronnen en gegevens permanent verwijderd. Download voordat u uw account verwijdert alle gegevens of informatie die u wilt behouden.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Account verwijderen') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        @auth('company')
            <form method="post" action="{{ route('company.destroy') }}" class="p-6">
        @else
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
        @endauth
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Weet je zeker dat je je account wilt verwijderen?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Zodra je account is verwijderd, worden alle bronnen en gegevens permanent verwijderd. Voer je wachtwoord in om te bevestigen dat je je account permanent wilt verwijderen.') }}
            </p>

            <div class="mt-6 form-group">
                <x-input-label for="password" value="{{ __('Wachtwoord') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Wachtwoord') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Annuleer') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Account verwijderen') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
