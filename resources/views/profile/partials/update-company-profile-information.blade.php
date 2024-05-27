<section class="profile-section">
    <header class="profile-header">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Bedrijfsprofiel informatie') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Werk de bedrijfsprofielgegevens en het e-mailadres bij") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display: none;">
        @csrf
    </form>

    <form method="post" action="{{ route('company-profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('post')

        <div class="form-group">
            <x-input-label for="name" :value="__('Naam')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $company->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $company->email)" required autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="form-group">
            <x-input-label for="telephone" :value="__('Telefoon')" />
            <x-text-input id="telephone" name="telephone" type="text" class="mt-1 block w-full" :value="old('telephone', $company->telephone)" required autocomplete="telephone" />
            <x-input-error class="mt-2" :messages="$errors->get('telephone')" />
        </div>

        <div class="form-group">
            <x-input-label for="logo" :value="__('Logo')" />
            <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->logo }}" class="w-20 h-20 rounded-full mb-2">
            <input id="logo" name="logo" type="file" class="mt-1 block w-full" />
            <x-input-error class="mt-2" :messages="$errors->get('logo')" />
        </div>

        <div class="form-group">
            <x-input-label for="country" :value="__('Land')" />
            <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $company->country)" required autocomplete="country" />
            <x-input-error class="mt-2" :messages="$errors->get('country')" />
        </div>

        <div class="form-group">
            <x-input-label for="state" :value="__('Staat')" />
            <x-text-input id="state" name="state" type="text" class="mt-1 block w-full" :value="old('state', $company->state)" required autocomplete="state" />
            <x-input-error class="mt-2" :messages="$errors->get('state')" />
        </div>

        <div class="form-group">
            <x-input-label for="city" :value="__('Stad')" />
            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $company->city)" required autocomplete="city" />
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>

        <div class="form-group">
            <x-input-label for="zip" :value="__('Postcode')" />
            <x-text-input id="zip" name="zip" type="text" class="mt-1 block w-full" :value="old('zip', $company->zip)" required autocomplete="zip" />
            <x-input-error class="mt-2" :messages="$errors->get('zip')" />
        </div>

        <div class="form-group">
            <x-input-label for="street" :value="__('Straat')" />
            <x-text-input id="street" name="street" type="text" class="mt-1 block w-full" :value="old('street', $company->street)" required autocomplete="street" />
            <x-input-error class="mt-2" :messages="$errors->get('street')" />
        </div>

        <div class="form-group">
            <x-input-label for="house_number" :value="__('Huisnummer')" />
            <x-text-input id="house_number" name="house_number" type="text" class="mt-1 block w-full" :value="old('house_number', $company->house_number)" required autocomplete="house_number" />
            <x-input-error class="mt-2" :messages="$errors->get('house_number')" />
        </div>

        <div class="form-group">
            <x-input-label for="invoice" :value="__('IBAN')" />
            <x-text-input id="invoice" name="invoice" type="text" class="mt-1 block w-full" :value="old('invoice', $company->invoice)" required autocomplete="invoice" />
            <x-input-error class="mt-2" :messages="$errors->get('iban')" />
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
