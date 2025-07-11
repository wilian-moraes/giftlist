@extends('layouts.auth')

@section('title', 'Convites')

@php($showTextLink = true)

@section('textLink', 'Sair')

@section('hrefTextLink', route('user.logout'))

@section('authBoxContent')
    <div x-data="invitation({!! e(json_encode($invitations)) !!})" x-init=" formController = $data ">
        <template x-if="loading">
            <x-spinLoading name="loading"/>
        </template>

        <ul class="space-y-4" x-show="!loading">
            <template x-for="invite in invitations" :key="invite.id">
                <li class="p-4 border rounded shadow-xl bg-white">
                    <p class="font-semibold text-neutral-800">Evento: <span x-text="invite.eventdate"></span></p>

                    <p x-text="formatNames(invite.hostnames)" class="mt-2 text-neutral-600 text-sm"></p>

                    <button @click="selectInvitation(invite.share_token)" class="bg-cyan-600 hover:bg-cyan-700 hover:cursor-pointer text-white py-2 px-5 rounded-md mt-4">
                        Acessar convite
                    </button>
                </li>
            </template>
        </ul>

    </div>

@endsection


<script>
    let formController = null;

    function invitation(serverInvitations = []) {
        return {
            invitations: serverInvitations,
            loading: false,
            messageError: '',

            async selectInvitation(shareToken) {
                this.loading = true;

                try {
                    const response = await axios.post(`/invitation/${shareToken}`);
                    window.location.replace('{{ route('homepage') }}');
                } catch (e) {
                    this.messageError = 'Erro ao selecionar o convite.';
                } finally {
                        this.loading = false;
                }
            },

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

                return `Lista de ${result}`;
            },
        }
    }

</script>
