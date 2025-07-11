@extends('layouts.base')

@section('content')

    <div class="relative min-h-screen bg-gray-100 flex items-center justify-center p-4 bg-auth-container pt-52">

        <div class="absolute top-0 left-0 ps-10 pt-10 z-10">
            <img src="{{ asset('images/giftlist.png') }}" alt="Logo GiftList" class="h-20 select-none" draggable="false">
            <p class="ps-1 mt-2 text-xl md:text-2xl lg:text-3xl pe-5 text-neutral-700 leading-tight">
                Crie e compartilhe suas listas de desejos de forma prática e personalizada com o GiftList!
            </p>
        </div>

        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md mx-auto max-h-[70vh] z-20 overflow-y-auto">
            @yield('authBoxContent')

            <div class="flex flex-col items-center mt-2">
                @if(!empty($showConfirmButton))
                    <button type="button" onclick="@yield('onConfirm')" class="bg-cyan-600 hover:bg-cyan-700 hover:cursor-pointer text-white font-bold py-2 px-20 rounded-full mt-4">
                        @yield('textConfirmButton')
                    </button>
                @endif
                @if(!empty($showTextLink))
                    <a href="@yield('hrefTextLink')" class="text-cyan-600 hover:text-cyan-700 hover:cursor-pointer py-2 mt-1">
                        @yield('textLink')
                    </a>
                @endif

                <span class="text-red-400 text-center" x-show="formController?.messageError" x-text="formController?.messageError"></span>

            </div>
        </div>

    </div>

@endsection
