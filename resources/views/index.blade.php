@extends('layouts.auth')

@section('title', 'Login')

@php($showConfirmButton = true)

@section('textConfirmButton', 'Entrar')

@section('onConfirm', 'formController.advance()')

@php($showTextLink = true)

@section('textLink', 'Cadastre-se')

@section('hrefTextLink', route('signUp'))

@section('authBoxContent')
    <div x-data="formLogin()" x-init=" formController = $data ">
        <form class="space-y-4 mx-2" @submit.prevent>
            <div class="relative mt-6">
                <input type="email" name="email" id="email" x-model="user.email" class="peer placeholder-transparent h-10 w-full border-b-2 border-neutral-300 text-neutral-700 focus:outline-none focus:border-cyan-700 pr-2" placeholder="">
                <label for="email" class="absolute left-0 -top-4 text-neutral-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-4 peer-focus:text-neutral-600 peer-focus:text-sm">
                    Email
                </label>
            </div>

            <div class="relative mt-6 mb-10">
                <input type="password" name="password" id="password" x-model="user.pass" class="peer placeholder-transparent h-10 w-full border-b-2 border-neutral-300 text-neutral-700 focus:outline-none focus:border-cyan-700 pr-2" placeholder="">
                <label for="password" class="absolute left-0 -top-4 text-neutral-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-4 peer-focus:text-neutral-600 peer-focus:text-sm">
                    Senha
                </label>
            </div>

            <div class="relative mt-6 mb-10 flex justify-start gap-2">
                <input type="checkbox" name="remember" id="remember" x-model="user.remember" class="border-neutral-300">
                <label for="remember" class=" text-neutral-600 text-sm">
                    Manter-se logado
                </label>
            </div>
        </form>

        <template x-if="loading">
            <x-spinLoading name="loading"/>
        </template>
    </div>

@endsection

<script>
    function formLogin() {
        return {
            loading: false,
            messageError: '',
            user: {
                email: '',
                pass: '',
                remember: false
            },

            async advance() {
                this.messageError = '';
                this.loading = true;

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                try {
                    if (!this.user.email || !this.user.pass) {
                        throw new Error('Todos os campos são obrigatórios.');
                    }

                    if (!emailRegex.test(this.user.email)) {
                        throw new Error('Email inválido.');
                    }

                    if (this.user.pass.length < 6) {
                        throw new Error('A senha deve ter pelo menos 6 caracteres.');
                    }

                    const response = await axios.post('/login', this.user);
                    window.location.replace('{{ route('firstAccess.show') }}');

                } catch (e) {
                    this.messageError = `Erro ao validar usuário: ${e.response?.data?.message || e.message}`;
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
