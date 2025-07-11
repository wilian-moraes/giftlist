@props(['name'])

<div>
    <div x-show="{{$name}}"
         x-transition.opacity
         class="fixed inset-0 bg-black opacity-70 flex justify-center items-center z-100">
            <div class="flex flex-col items-center">
                <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-cyan-500"></div>
                <span class="ml-4 mt-4 text-white text-xl">Carregando...</span>
            </div>
    </div>
</div>
