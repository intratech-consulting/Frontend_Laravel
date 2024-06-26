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

        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->logo }}" class="object-cover rounded-full mb-2" style="width: 200px; height: 200px;"> <br>

        <div class="form-group">
            <x-input-label for="name" :value="__('Naam')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $company->name)" required autofocus autocomplete="name" />
            @error('name')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $company->email)" required autocomplete="email" />
            @error('email')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <x-input-label for="telephone" :value="__('Telefoon')" />
            <x-text-input id="telephone" name="telephone" type="text" class="mt-1 block w-full" :value="old('telephone', $company->telephone)" required autocomplete="telephone" />
            @error('telephone')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <x-input-label for="street" :value="__('Straat')" />
            <x-text-input id="street" name="street" type="text" class="mt-1 block w-full" :value="old('street', $company->street)" required autocomplete="street" />
            @error('street')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <x-input-label for="house_number" :value="__('Huisnummer')" />
            <x-text-input id="house_number" name="house_number" type="text" class="mt-1 block w-full" :value="old('house_number', $company->house_number)" required autocomplete="house_number" />
            @error('house_number')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <x-input-label for="city" :value="__('Stad')" />
            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $company->city)" required autocomplete="city" />
            @error('city')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <x-input-label for="zip" :value="__('Postcode')" />
            <x-text-input id="zip" name="zip" type="text" class="mt-1 block w-full" :value="old('zip', $company->zip)" required autocomplete="zip" />
            @error('zip')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <x-input-label for="state" :value="__('Provincie')" />
            <x-text-input id="state" name="state" type="text" class="mt-1 block w-full" :value="old('state', $company->state)" required autocomplete="state" />
            @error('state')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <x-input-label for="country" :value="__('Land')" />
            <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $company->country)" required autocomplete="country" />
            @error('country')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <x-input-label for="invoice" :value="__('IBAN')" />
            <x-text-input id="invoice" name="invoice" type="text" class="mt-1 block w-full" :value="old('invoice', $company->invoice)" required autocomplete="invoice" />
            @error('invoice')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Opslaan') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">
                    {{ __('Opgeslagen') }}
                </p>
            @endif
        </div>
    </form>
</section>
