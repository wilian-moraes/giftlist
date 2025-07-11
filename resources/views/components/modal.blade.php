@props(['name'])

<div>
    <div x-show="{{$name}}"
        x-transition:enter="transition ease-out duration-400"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-30"
        x-transition:leave="transition ease-in duration-400"
        x-transition:leave-start="opacity-30"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black opacity-30 z-20"
        x-init=" $watch('{{ $name }}', value => {isBodyScrollLocked = value }) "
    ></div>

    <div x-show="{{$name}}"
        x-transition:enter="transition ease-out duration-400"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-400"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-on:click.outside.prevent="{{$name}} = false"
        class="fixed inset-0 flex items-center justify-center z-30 "
    >
        {{ $slot }}
    </div>
</div>
