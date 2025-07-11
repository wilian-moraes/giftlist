@extends('layouts.auth')

@section('title', 'Cadastro')

@php($showConfirmButton = true)

@section('textConfirmButton', 'Cadastrar')

@section('onConfirm', 'formController.advance()')

@php($showTextLink = true)

@section('textLink', 'ou faça login')

@section('hrefTextLink', route('firstAccess.show'))

@section('authBoxContent')
    <div x-data="formNewUser() " x-init=" formController = $data">
        <form class="space-y-4 mx-2" @submit.prevent>
            <div class="relative mt-6">
                <label for="typeUser" class="block text-sm font-medium text-neutral-700 mb-1">Você é...?</label>
                <select name="typeUser" id="typeUser" x-model="newUser.typeuser" class="block w-full px-3 py-2 border border-neutral-300 bg-white text-neutral-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                    <option selected value="1">Anfitrião</option>
                    <option value="2">Convidado</option>
                </select>
            </div>

            <div class="relative mt-6">
                <input type="text" name="name" id="name" x-model="newUser.name" class="peer placeholder-transparent h-10 w-full border-b-2 border-neutral-300 text-neutral-700 focus:outline-none focus:border-cyan-700 pr-2" placeholder="">
                <label for="name" class="absolute left-0 -top-4 text-neutral-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-4 peer-focus:text-neutral-600 peer-focus:text-sm">
                    Nome
                </label>
            </div>

            <div class="relative mt-6">
                <input type="email" name="email" id="email" x-model="newUser.email" class="peer placeholder-transparent h-10 w-full border-b-2 border-neutral-300 text-neutral-700 focus:outline-none focus:border-cyan-700 pr-2" placeholder="">
                <label for="email" class="absolute left-0 -top-4 text-neutral-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-4 peer-focus:text-neutral-600 peer-focus:text-sm">
                    Email
                </label>
            </div>

            <div class="relative mt-6 mb-10">
                <input type="password" name="pass" id="pass" x-model="newUser.pass" class="peer placeholder-transparent h-10 w-full border-b-2 border-neutral-300 text-neutral-700 focus:outline-none focus:border-cyan-700 pr-2" placeholder="">
                <label for="pass" class="absolute left-0 -top-4 text-neutral-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-4 peer-focus:text-neutral-600 peer-focus:text-sm">
                    Senha
                </label>
            </div>
        </form>
    </div>
@endsection

<script>
    function formNewUser() {
        return {
            newUser: {
                name: '',
                email: '',
                pass: '',
                typeuser: 1,
            },
            messageError: '',

            async advance() {
                this.messageError = '';

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                try {
                    if (!this.newUser.name || !this.newUser.email || !this.newUser.pass || this.newUser.typeuser === null) {
                        throw new Error('Todos os campos são obrigatórios.');
                        return;
                    }

                    if (!emailRegex.test(this.newUser.email)) {
                        throw new Error('Email inválido.');
                        return;
                    }

                    if (this.newUser.pass.length < 6) {
                        throw new Error('A senha deve ter pelo menos 6 caracteres.');
                        return;
                    }

                    const response = await axios.post('/create-user', this.newUser);
                    alert('Usuário adicionado com sucesso!');
                    window.location.replace('{{ route('index') }}');
                } catch (e) {
                    this.messageError = `Erro ao adicionar usuário: ${e.response?.data?.message || e.message}`
                }

            }
        }
    }
</script>
