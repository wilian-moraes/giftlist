@extends('layouts.auth')

@section('title', 'Primeiro acesso')

@php($showConfirmButton = true)

@section('textConfirmButton', 'Avançar')

@section('onConfirm', 'formController.advance()')

@php($showTextLink = true)

@section('textLink', 'Login')

@section('hrefTextLink', route('index'))

@section('authBoxContent')
    <div x-data="formFirstAccess()" x-init="formController = $data">
        <form class="space-y-4 mx-2" @submit.prevent>
            <template x-if="step === 1">
                <div>
                    <div class="mb-3">
                        <div class="flex items-center space-x-2">
                            <div class="flex-grow">
                                <label class="block text-neutral-700 text-base font-bold mb-2">Quantos anfitriões</label>
                            </div>
                            <button type="button" @click="removeHost()" class="bg-neutral-200 hover:bg-neutral-300 text-neutral-800 font-bold py-1 px-3 rounded-md transition duration-200 cursor-pointer">
                                -
                            </button>
                            <input type="number" x-model="numHosts" min="1" class="w-16 text-center border-b-2 border-neutral-300 text-neutral-600 focus:outline-none focus:border-cyan-700 font-bold text-lg" readonly>
                            <button
                                type="button"
                                @click="addHost()"
                                :disabled="numHosts >= 20" :class="{
                                    'bg-neutral-200 hover:bg-neutral-300 text-neutral-800 cursor-pointer': numHosts < 20,
                                    'bg-neutral-200 text-neutral-400 cursor-not-allowed opacity-50': numHosts >= 20
                                }"
                                class="font-bold py-1 px-3 rounded-md transition duration-200"
                            >
                                +
                            </button>
                        </div>
                    </div>
                    <div id="hostNameFields" class="space-y-4 mb-10">
                        <template x-for="(host, index) in configHost.hostNames" :key="host.id">
                            <div class="relative mt-6">
                                <input type="text" :name="`host_names[${index}]`" :id="`host_name_${host.id}`" x-model="host.name" class="peer placeholder-transparent h-10 w-full border-b-2 border-neutral-300 text-neutral-700 focus:outline-none focus:border-cyan-700 pr-2" placeholder="">
                                <label :for="`host_name_${host.id}`" class="absolute left-0 -top-4 text-neutral-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-4 peer-focus:text-neutral-600 peer-focus:text-sm">
                                    Nome do Anfitrião
                                </label>
                            </div>
                        </template>
                    </div>
                    <div class="relative mt-6">
                        <input type="date" name="eventDate" id="eventDate" x-model="configHost.eventDate" class="peer placeholder-transparent h-10 w-full border-b-2 border-neutral-300 text-neutral-700 focus:outline-none focus:border-cyan-700 pr-2" placeholder="">
                        <label for="eventDate" class="absolute left-0 -top-4 text-neutral-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-4 peer-focus:text-neutral-600 peer-focus:text-sm">
                            Data do evento
                        </label>
                    </div>
                    <div class="relative mt-6">
                        <input type="date" name="listEndDate" id="listEndDate" x-model="configHost.listEndDate" class="peer placeholder-transparent h-10 w-full border-b-2 border-neutral-300 text-neutral-700 focus:outline-none focus:border-cyan-700 pr-2" placeholder="">
                        <label for="listEndDate" class="absolute left-0 -top-4 text-neutral-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-4 peer-focus:text-neutral-600 peer-focus:text-sm">
                            Encerrar lista
                        </label>
                    </div>
                </div>
            </template>

            <template  x-if="step === 2">
                <div>
                    <div class="mb-3">
                        <div class="flex items-center space-x-2">
                            <div class="flex-grow">
                                <label class="block text-neutral-700 text-base font-bold mb-2">Deseja revelar os nomes?</label>
                            </div>
                            <select name="showNames" id="showNames" x-model.boolean="configHost.showNames" class="block w-1/4 px-3 py-2 border border-neutral-300 bg-white text-neutral-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                                <option selected value="true">Sim</option>
                                <option value="false">Não</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3" x-show="configHost.showNames" x-transition.duration.500ms>
                        <div class="flex items-center space-x-2">
                            <div class="flex-grow">
                                <label class="block text-neutral-700 text-base font-bold mb-2">Revelar os nomes a quem?</label>
                            </div>
                            <select name="revealNames" id="revealNames" x-model="configHost.revealNames" class="block w-1/4 px-3 py-2 border border-neutral-300 bg-white text-neutral-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                                <option selected value="outro">Outro</option>
                                <option value="eu">Eu</option>
                            </select>
                        </div>
                    </div>
                    <div x-show="configHost.revealNames === 'outro' && configHost.showNames" x-transition.duration.500ms>
                        <div class="relative mt-6">
                            <input type="text" name="name" id="name" x-model="configHost.newUser.name" class="peer placeholder-transparent h-10 w-full border-b-2 border-neutral-300 text-neutral-700 focus:outline-none focus:border-cyan-700 pr-2" placeholder="">
                            <label for="name" class="absolute left-0 -top-4 text-neutral-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-4 peer-focus:text-neutral-600 peer-focus:text-sm">
                                Nome
                            </label>
                        </div>

                        <div class="relative mt-6">
                            <input type="email" name="email" id="email" x-model="configHost.newUser.email" class="peer placeholder-transparent h-10 w-full border-b-2 border-neutral-300 text-neutral-700 focus:outline-none focus:border-cyan-700 pr-2" placeholder="">
                            <label for="email" class="absolute left-0 -top-4 text-neutral-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-4 peer-focus:text-neutral-600 peer-focus:text-sm">
                                Email
                            </label>
                        </div>

                        <div class="relative mt-6 mb-10">
                            <input type="password" name="password" id="password" x-model="configHost.newUser.pass" class="peer placeholder-transparent h-10 w-full border-b-2 border-neutral-300 text-neutral-700 focus:outline-none focus:border-cyan-700 pr-2" placeholder="">
                            <label for="password" class="absolute left-0 -top-4 text-neutral-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-4 peer-focus:text-neutral-600 peer-focus:text-sm">
                                Senha
                            </label>
                        </div>
                    </div>
                </div>
            </template>
        </form>
        <div class="flex justify-center" x-show="step === 2">
            <span @click="back()" class="text-cyan-600 hover:text-cyan-700 hover:cursor-pointer">Voltar</span>
        </div>
    </div>
@endsection


<script>
    function formFirstAccess() {
        return {
            step: 1,
            numHosts: 1,
            messageError: '',
            configHost: {
                hostNames: [{ id: Date.now(), name: '' }],
                eventDate: '',
                listEndDate: '',
                showNames: true,
                revealNames: 'outro',
                newUser: {
                    name: '',
                    email: '',
                    pass: '',
                    typeuser: 3,
                },
            },


            addHost() {
                if(this.numHosts < 20){
                    this.numHosts++;
                    this.configHost.hostNames.push({id: Date.now(), name: '' })
                }
            },
            removeHost() {
                if (this.numHosts > 1) {
                    this.numHosts--;
                    this.configHost.hostNames.pop();
                }
            },
            async advance() {
                if (this.step === 1) {
                    this.messageError = '';

                    for (let i = 0; i < this.configHost.hostNames.length; i++) {
                        if (!this.configHost.hostNames[i].name.trim()) {
                            if(this.configHost.hostNames.length === 1){
                                this.messageError = `O nome do anfitrião deve estar preenchido`;
                                return;
                            }
                            this.messageError = `Todos os nomes dos anfitriões devem estar preenchidos`;
                            return;
                        }
                    }

                    if (!this.configHost.eventDate) {
                        this.messageError = 'Por favor, selecione a data do evento';
                        return;
                    }

                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    const eventDateObj = new Date(this.configHost.eventDate);
                    if (eventDateObj < today) {
                        this.messageError = 'A data do evento não pode ser no passado';
                        return;
                    }

                    if (!this.configHost.listEndDate) {
                        this.messageError = 'Por favor, selecione a data de encerramento da lista.';
                        return;
                    }

                    const listEndDateObj = new Date(this.configHost.listEndDate);
                    if (listEndDateObj >= eventDateObj) {
                        this.messageError = 'A data de encerramento da lista deve ser antes da data do evento.';
                        return;
                    }

                    if (listEndDateObj < today) {
                        this.messageError = 'A data de encerramento não pode ser no passado';
                        return;
                    }

                    this.step++;
                } else if (this.step === 2) {
                    this.messageError = '';

                    try {
                        if(this.configHost.revealNames === 'outro' && this.configHost.showNames){

                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                            if (!this.configHost.newUser.name || !this.configHost.newUser.email || !this.configHost.newUser.pass) {
                                throw new Error('Preencha todos os campos para o novo usuário');
                                return;
                            }

                            if (!emailRegex.test(this.configHost.newUser.email)) {
                                throw new Error('Email inválido.');
                                return;
                            }

                            if (this.configHost.newUser.pass.length < 6) {
                                throw new Error('A senha deve ter pelo menos 6 caracteres.');
                                return;
                            }
                        }else{
                            this.configHost.newUser.name = '';
                            this.configHost.newUser.email = '';
                            this.configHost.newUser.pass = '';
                        }

                    const response = await axios.post('/create-firstAccess', this.configHost);
                    alert("Configurações realizadas com sucesso!");
                    window.location.replace('{{ route('homepage') }}');

                    } catch (e) {
                        this.messageError = `Erro: ${e.response?.data?.message || e.message}`;
                    }
                }
            },
            back() {
                if (this.step > 1) this.step--;
            }
        }
    }
</script>
