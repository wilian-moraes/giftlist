@extends('layouts.base')

@section('content')

<div x-data="authStatus()" x-init="checkAuth()" class="relative min-h-screen bg-gray-100 bg-home-container">
    <template x-if="loading">
        <x-spinLoading name="loading"/>
    </template>

    <template x-if="authenticatedUser">
        <div>
            <template x-if="authenticatedUser.typeUserLabel	=== 'Anfitrião' && authenticatedUser.firstaccess">
                <div class="flex justify-center items-center h-screen font-semibold">
                    <p class="text-center">
                        <span x-text="authenticatedUser.name"></span>, você precisa preencher o formulário de
                        <a href="{{ route('firstAccess.show') }}" class="text-cyan-600 hover:text-cyan-700 hover:cursor-pointer py-2 mt-1">primeiro acesso</a>
                        para continuar
                    </p>
                </div>
            </template>
            <template x-if="authenticatedUser.typeUserLabel	!== 'Anfitrião' || !authenticatedUser.firstaccess">
                <div x-data="{ isSidebarOpen: false }" class="p-4">
                    <div x-data="host()" x-init="fetchHost()">
                        <div class="flex justify-between items-center p-5 z-10 border-b-2">
                            <div class="w-full flex">
                                <div class="flex items-center w-auto">
                                    <div class="mr-6">
                                        <button id="hamburger-button" class="text-neutral-800 focus:outline-none cursor-pointer" x-on:click="isSidebarOpen = !isSidebarOpen">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <template x-if="host && host.hostnames && host.hostnames.length > 0">
                                        <div class="text-lg font-semibold text-neutral-800 break-words max-w-[50vw] max-md:hidden">
                                            <span x-text="formatHostNames(host.hostnames)"></span>
                                        </div>
                                    </template>
                                </div>

                                <template x-if="host">
                                    <div class="flex flex-col justify-center items-center text-center flex-grow max-md:hidden">
                                        <span class="text-sm text-neutral-600">Lista será encerrada em</span>
                                        <span x-text="host.closelist" class="text-base font-medium text-neutral-800"></span>
                                    </div>
                                </template>
                            </div>

                            <img src="{{ asset('images/giftlist.png') }}" alt="Logo GiftList" class="h-10 ml-4 select-none" draggable="false">
                        </div>

                        <div x-show="isSidebarOpen"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-30"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-30"
                            x-transition:leave-end="opacity-0"
                            x-on:click="isSidebarOpen = false"
                            class="fixed inset-0 bg-black opacity-30 z-20"
                            ></div>

                        <nav id="sidebar"
                            x-show="isSidebarOpen"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="-translate-x-full"
                            x-transition:enter-end="translate-x-0"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="translate-x-0"
                            x-transition:leave-end="-translate-x-full"
                            x-on:click.outside.prevent="isSidebarOpen = false"
                            class="fixed top-0 left-0 w-64 bg-slate-50 h-full shadow-lg z-30 flex flex-col"
                            >

                            <div class="py-4 border-b">
                                <img src="{{ asset('images/giftlist.png') }}" alt="Logo GiftList" class="h-10 ml-4 select-none" draggable="false">

                                <button class="absolute top-6 right-4 text-neutral-600 hover:text-neutral-900 focus:outline-none cursor-pointer"
                                        x-on:click="isSidebarOpen = false">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="text-center text-neutral-700">
                                <div class="mt-4">
                                    <span class="text-xl" x-text="authenticatedUser.typeUserLabel"></span>
                                </div>
                                <div class="mt-4">
                                    <span class="text-xl break-words" x-text="authenticatedUser.name"></span>
                                </div>

                                <template x-if="host && host.hostnames && host.hostnames.length > 0">
                                    <div class="mt-4 md:hidden">
                                        <span x-text="formatHostNames(host.hostnames)" class="text-lg break-words"></span>
                                    </div>
                                </template>

                            </div>
                            <div class="mt-auto text-center pb-4">
                                <template x-if="host">
                                    <div>
                                        <template x-if="authenticatedUser.typeUserLabel	!== 'Convidado'">
                                            <ul class="mb-3">
                                                <li>
                                                    <button
                                                        @click="copyInviteLink"
                                                        class="block w-full px-4 py-2 text-neutral-600 italic cursor-pointer hover:bg-neutral-200"
                                                    >
                                                        Copiar link do convite
                                                    </button>
                                                </li>
                                            </ul>
                                        </template>
                                        <div class="flex flex-col items-center text-center flex-grow break-words md:hidden">
                                            <span class="text-sm text-neutral-600">Lista será encerrada em</span>
                                            <span x-text="host.closelist" class="text-base font-medium text-neutral-800"></span>
                                        </div>
                                    </div>
                                </template>

                                <ul class="mt-3">
                                    <li><a href="{{ route('user.logout') }}" class="block px-4 py-2 hover:bg-neutral-200">Sair</a></li>
                                </ul>
                            </div>
                        </nav>

                        <div>
                            @yield('homepageContent')
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </template>

    <template x-if="!loading && !authenticatedUser">
        <div class="flex justify-center items-center h-screen font-semibold">
            <p>Você não está logado. <a href="{{ route('index') }}" class="text-cyan-600 hover:text-cyan-700 hover:cursor-pointer py-2 mt-1">Faça login aqui</a></p>
        </div>
    </template>
</div>

@endsection

<script>
    function authStatus() {
        return {
            authenticatedUser: null,
            loading: true,

            async checkAuth() {
                this.loading = true;
                try {
                    const response = await axios.get('/authenticated-user');
                    this.authenticatedUser = response.data;

                    if (this.authenticatedUser && this.authenticatedUser.typeUserLabel === 'Convidado'){
                        const urlParams = new URLSearchParams(window.location.search);
                        const token = urlParams.get('token');
                        if (token) {
                            await axios.post(`/invitation/${token}`);
                        }
                    }

                } catch (e) {
                    this.authenticatedUser = null;
                } finally {
                    this.loading = false;
                }
            },

            copyInviteLink() {
                if (this.authenticatedUser?.share_token) {
                    const link = `${window.location.origin}/invitation/${this.authenticatedUser.share_token}`;
                    navigator.clipboard.writeText(link)
                        .then(() => alert('Link copiado para a área de transferência!'))
                        .catch(() => alert('Erro ao copiar o link.'));
                } else {
                    alert('Link de convite não disponível.');
                }
            },
        }
    }

    function host() {
        return {
            host: null,

            formatNames(namesArray) {
                if (!namesArray || namesArray.length === 0) {
                    return '';
                }
                const names = namesArray.map(h => h.name);
                let result = '';

                if (names.length === 1) {
                    result = names[0];
                } else if (names.length === 2) {
                    result = names.join(' e ');
                } else {
                    result = names.slice(0, -1).join(', ') + ' e ' + names[names.length - 1];
                }

                return result;
            },

            formatHostNames(hostNamesArray) {
                let result = this.formatNames(hostNamesArray);
                return `Lista de ${result}`;
            },

            async fetchHost() {
                try {
                    const response = await axios.get('/host');
                    this.host = response.data;
                } catch (e) {
                    this.host = null;
                }
            },

            formatGuestNamesForProduct(product) {
                if (!product || !product.chooseproducts) return '';

                let allGuestNames = [];

                product.chooseproducts.forEach(cp => {
                    if (this.authenticatedUser.typeUserLabel === 'Outro') {
                        cp.guestnames?.forEach(guest => {
                            allGuestNames.push(guest.name);
                        });
                    }
                    else if (
                        this.authenticatedUser.typeUserLabel === 'Anfitrião' &&
                        this.host.shownames &&
                        !this.host.userrevealid
                    ) {
                        cp.guestnames?.forEach(guest => {
                            allGuestNames.push(guest.name);
                        });
                    }
                    else if (
                        this.authenticatedUser.typeUserLabel === 'Convidado' &&
                        cp.userid === this.authenticatedUser.id
                    ) {
                        cp.guestnames?.forEach(guest => {
                            allGuestNames.push(guest.name);
                        });
                    }
                });

                if (allGuestNames.length === 0) return '';
                if (allGuestNames.length === 1) return allGuestNames[0];
                if (allGuestNames.length === 2) return allGuestNames.join(' e ');
                return allGuestNames.slice(0, -1).join(', ') + ' e ' + allGuestNames[allGuestNames.length - 1];
            },
        }
    }
</script>
