<x-guest-layout>
<h1>Debugging Registration Form</h1>
    <form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

        <!-- First Name -->
        <div>
            <x-input-label for="first_name" :value="__('First Name')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="mt-4">
            <x-input-label for="last_name" :value="__('Last Name')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Telephone -->
        <div class="mt-4">
            <x-input-label for="telephone" :value="__('Telephone')" />
            <x-text-input id="telephone" class="block mt-1 w-full" type="text" name="telephone" :value="old('telephone')" />
            <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
        </div>

        <!-- Birthday -->
        <div class="mt-4">
            <x-input-label for="birthday" :value="__('Birthday')" />
            <x-date-input id="birthday" class="block mt-1 w-full" name="birthday" :value="old('birthday')" />
            <x-input-error :messages="$errors->get('birthday')" class="mt-2" />
        </div>

        <!-- Address -->
        <div class="mt-4">
            <x-input-label for="country" :value="__('Country')" />
            <x-text-input id="country" class="block mt-1 w-full" type="text" name="country" :value="old('country')" />
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="state" :value="__('State')" />
            <x-text-input id="state" class="block mt-1 w-full" type="text" name="state" :value="old('state')" />
            <x-input-error :messages="$errors->get('state')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="city" :value="__('City')" />
            <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" />
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="zip" :value="__('ZIP Code')" />
            <x-text-input id="zip" class="block mt-1 w-full" type="text" name="zip" :value="old('zip')" />
            <x-input-error :messages="$errors->get('zip')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="street" :value="__('Street')" />
            <x-text-input id="street" class="block mt-1 w-full" type="text" name="street" :value="old('street')" />
            <x-input-error :messages="$errors->get('street')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="house_number" :value="__('House Number')" />
            <x-text-input id="house_number" class="block mt-1 w-full" type="text" name="house_number" :value="old('house_number')" />
            <x-input-error :messages="$errors->get('house_number')" class="mt-2" />
        </div>

        <!-- Company Email -->
        <div class="mt-4">
            <x-input-label for="company_email" :value="__('Company Email')" />
            <x-text-input id="company_email" class="block mt-1 w-full" type="email" name="company_email" :value="old('company_email')" />
            <x-input-error :messages="$errors->get('company_email')" class="mt-2" />
        </div>

        <!-- Company ID -->
        <div class="mt-4">
            <x-input-label for="company_id" :value="__('Company ID')" />
            <x-text-input id="company_id" class="block mt-1 w-full" type="text" name="company_id" :value="old('company_id')" />
            <x-input-error :messages="$errors->get('company_id')" class="mt-2" />
        </div>

        <!-- Source -->
        <div class="mt-4">
            <x-input-label for="source" :value="__('Source')" />
            <x-text-input id="source" class="block mt-1 w-full" type="text" name="source" :value="old('source')" />
            <x-input-error :messages="$errors->get('source')" class="mt-2" />
        </div>

        <!-- User Role -->
        <div class="mt-4">
            <x-input-label for="user_role" :value="__('User Role')" />
            <select id="user_role" name="user_role" class="block mt-1 w-full">
                <option value="Speaker">Speaker</option>
                <option value="Individual">Individual</option>
                <option value="Employee">Employee</option>
            </select>
            <x-input-error :messages="$errors->get('user_role')" class="mt-2" />
        </div>

        <!-- Invoice -->
        <div class="mt-4">
            <x-input-label for="invoice" :value="__('Invoice')" />
            <select id="invoice" name="invoice" class="block mt-1 w-full">
                <option value="Yes">Yes</option>
            </select>
            <x-input-error :messages="$errors->get('invoice')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

