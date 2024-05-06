@props(['id', 'name', 'label'])

<input {{ $attributes->merge(['class' => 'block mt-1 w-full', 'type' => 'file', 'id' => $id, 'name' => $name]) }} {{ !$attributes->has('required') ?: 'required' }}>
<x-input-error :messages="$errors->get($name)" class="mt-2" />

