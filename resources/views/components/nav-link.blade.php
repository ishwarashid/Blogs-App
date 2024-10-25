@props(['active' => false, 'btn_type' => 'a'])

@if ($btn_type == 'a')
    <a
        {{ $attributes->merge(['class' => 'text-[#1A1A1A] hover:text-[#243642] hover:underline text-lg font-medium transition duration-200 ease-in-out ' . ($active ? 'underline text-[#243642]' : '')]) }}>
        {{ $slot }}
    </a>
@else
    <button
        {{ $attributes->merge(['class' => 'text-[#1A1A1A] hover:text-[#243642] hover:underline text-lg font-medium transition duration-200 ease-in-out ' . ($active ? 'underline text-[#243642]' : '')]) }}>
        {{ $slot }}
    </button>
@endif
