<section class="profile-section">
    <header class="profile-header">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profiel informatie') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("De profielgegevens en het e-mailadres van je account bijwerken") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display: none;">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('post')

        <div class="form-group">
            <x-input-label for="first_name" :value="__('Voornaam')" />
            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" required autofocus autocomplete="first_name" />
            @error('first_name')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <x-input-label for="last_name" :value="__('Achtenaam')" />
            <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" required autofocus autocomplete="last_name" />
            @error('last_name')
            <span class="error-message">{{ $message }}</span>
            @enderror        </div>

        <div class="form-group">
            <x-input-label for="birthday" :value="__('Geboortedatum')" />
            <x-text-input id="birthday" name="birthday" type="date" class="mt-1 block w-full" :value="old('birthday', $user->birthday)" required autofocus autocomplete="birthday" />
            @error('birthday')
            <span class="error-message">{{ $message }}</span>
            @enderror        </div>

        <div class="form-group">
            <x-input-label for="telephone" :value="__('Telefoon')" />
            <x-text-input id="telephone" name="telephone" type="text" class="mt-1 block w-full" :value="old('telephone', $user->telephone)" required autofocus autocomplete="telephone" />
            @error('telephone')
            <span class="error-message">{{ $message }}</span>
            @enderror        </div>

        <div class="form-group">
            <x-input-label for="street" :value="__('Straat')" />
            <x-text-input id="street" name="street" type="text" class="mt-1 block w-full" :value="old('street', $user->street)" required autofocus autocomplete="street" />
            @error('street')
            <span class="error-message">{{ $message }}</span>
            @enderror        </div>

        <div class="form-group">
            <x-input-label for="house_number" :value="__('Huisnummer')" />
            <x-text-input id="house_number" name="house_number" type="text" class="mt-1 block w-full" :value="old('house_number', $user->house_number)" required autofocus autocomplete="house_number" />
            @error('house_number')
            <span class="error-message">{{ $message }}</span>
            @enderror        </div>

        <div class="form-group">
            <x-input-label for="city" :value="__('Stad')" />
            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $user->city)" required autofocus autocomplete="city" />
            @error('city')
            <span class="error-message">{{ $message }}</span>
            @enderror        </div>

        <div class="form-group">
            <x-input-label for="zip" :value="__('Postcode')" />
            <x-text-input id="zip" name="zip" type="text" class="mt-1 block w-full" :value="old('zip', $user->zip)" required autofocus autocomplete="zip" />
            @error('zip')
            <span class="error-message">{{ $message }}</span>
            @enderror        </div>

        <div class="form-group">
            <x-input-label for="state" :value="__('Provincie')" />
            <x-text-input id="state" name="state" type="text" class="mt-1 block w-full" :value="old('state', $user->state)" required autofocus autocomplete="state" />
            @error('state')
            <span class="error-message">{{ $message }}</span>
            @enderror        </div>

        <div class="form-group">
            <x-input-label for="country" :value="__('Land')" />
            <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $user->country)" required autofocus autocomplete="country" />
            @error('country')
            <span class="error-message">{{ $message }}</span>
            @enderror        </div>

        <div class="form-group">
            <label for="invoice" class="block font-medium text-gray-700">Factuur</label>
            <select id="invoice" name="invoice" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="No" {{ (old('invoice') == 'No') ? 'selected' : '' }}>Nee</option>
                <option value="Yes" {{ (old('invoice') == 'Yes') ? 'selected' : '' }}>Ja</option>
            </select>
        </div>

        @if(Auth::user()->company_id != null)
            <div class="form-group">
                <label for="user_role" class="block font-medium text-gray-700">Type account</label>
                <select id="user_role" name="user_role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="employee" {{ (Auth::user()->user_role == 'employee') ? 'selected' : '' }}>Werknemer</option>
                    <option value="speaker" {{ (Auth::user()->user_role == 'speaker') ? 'selected' : '' }}>Spreker</option>
                </select>
            </div>
        @endif

        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            @error('email')
            <span class="error-message">{{ $message }}</span>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800">
                        {{ __('Je e-mailadres is niet geverifieerd.') }}
                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik hier om de verificatiemail opnieuw te versturen.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Er is een nieuwe verificatielink naar uw e-mailadres verzonden.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Opslaan') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">
                    {{ __('Opgeslagen') }}
                </p>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </form>
</section>
