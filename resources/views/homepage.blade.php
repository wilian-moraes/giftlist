@extends('layouts.home')

@section('title', 'Início')


@section('homepageContent')
    <div x-data="productsData()" x-init="loadProducts()">
        <template x-if="loading">
            <x-spinLoading name="loading"/>
        </template>
        <template x-if="authenticatedUser.typeUserLabel	=== 'Anfitrião'">
            <div class="flex justify-center">
                <button @click="isModalItensOpen = true" class="bg-cyan-600 hover:bg-cyan-700 hover:cursor-pointer text-white font-bold py-3 px-6 rounded-md mt-4">
                    Adicionar itens
                </button>
            </div>
        </template>
        <div class="m-10">
            <div>
                <template x-if="host && host.products && host.products.length > 0">
                    <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-5">
                        <template x-for="product in host.products" :key="product.id">
                            <div class="max-w-sm bg-gray-100 border-4 border-gray-200 rounded-lg shadow-md">
                                <template x-if="product.link">
                                    <a :href="product.link.startsWith('http') ? product.link : 'https://' + product.link" target="_blank" rel="noopener noreferrer">
                                        <img class="rounded-t-lg w-full h-32 object-cover" :src="product.productimg" :alt="product.name" />
                                    </a>
                                </template>
                                <template x-if="!product.link">
                                    <img class="rounded-t-lg w-full h-32 object-cover" :src="product.productimg" :alt="product.name" />
                                </template>
                                <div class="p-5">
                                    <h5 class="mb-2 text-xl font-bold tracking-tight text-neutral-800" x-text="product.name"></h5>
                                    <template x-if="product.chooseproducts?.length > 0">
                                        <div class="mb-3 font-normal text-gray-500">
                                            <span x-text="formatGuestNamesForProduct(product)"></span>
                                        </div>
                                    </template>
                                    <template x-if="authenticatedUser.typeUserLabel === 'Anfitrião'">
                                        <button @click="removeProduct(product.id)" class="outline-2 outline-red-600 hover:bg-red-700 hover:cursor-pointer hover:text-white text-neutral-600 font-semibold py-2 px-4 rounded-md mt-4 transition-colors">
                                            Remover
                                        </button>
                                    </template>
                                    <template x-if="authenticatedUser.typeUserLabel === 'Convidado' && product.chooseproducts?.length === 0">
                                        <button @click="selectedProduct(product)" class="outline-2 outline-cyan-600 hover:bg-cyan-700 hover:cursor-pointer hover:text-white text-neutral-600 font-semibold py-2 px-4 rounded-md mt-4 transition-colors">
                                            Selecionar
                                        </button>
                                    </template>
                                    <template x-if="authenticatedUser.typeUserLabel === 'Convidado' && product.chooseproducts?.length !== 0">
                                        <button class="border-2 border-red-600 bg-red-600 text-white font-semibold py-2 px-4 rounded-md mt-4">
                                            Esgotado
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        <x-modal name="isModalItensOpen">
            <div class="p-5 bg-white shadow-lg rounded-lg">
                <div class="flex justify-between">
                    <span class="mx-auto text-xl mb-1 text-neutral-800">Adicionar novo produto</span>
                    <button class="ml-2 text-neutral-600 hover:text-neutral-900 focus:outline-none cursor-pointer"
                            x-on:click="isModalItensOpen = false">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div>
                    <div class="flex flex-wrap justify-center items-center gap-x-8 mt-5 mb-10">
                        <div class="flex flex-col items-center">
                            <template x-if="previewImage">
                                <img class="rounded-lg h-20 w-auto object-cover" :src="previewImage" alt="Prévia da Imagem do Produto" />
                            </template>
                            <template x-if="!previewImage">
                                <div class="rounded-lg h-20 w-35 bg-gray-200 border border-gray-300 flex items-center justify-center text-gray-500 text-xs">
                                    Sem Imagem
                                </div>
                            </template>
                        </div>
                        <div class="flex flex-col items-center">
                            <span class="text-neutral-700 mb-2 break-words max-w-[100px] text-center">Insira uma imagem</span>
                            <input type="file" accept="image/*" @change="handleFileUpload($event)" class="hidden" x-ref="fileInput">
                            <button @click="$refs.fileInput.click()" class="bg-cyan-600 hover:bg-cyan-700 hover:cursor-pointer text-white py-1 px-5 rounded-md max-w-[140px] truncate" x-text="fileName || 'Imagem'">
                            </button>
                        </div>
                    </div>
                    <div class="relative mt-6">
                        <input type="product" name="product" id="product" x-model="newProduct.name" class="peer placeholder-transparent h-10 w-full border-b-2 border-neutral-300 text-neutral-700 focus:outline-none focus:border-cyan-700 pr-2" placeholder="">
                        <label for="product" class="absolute left-0 -top-4 text-neutral-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-4 peer-focus:text-neutral-600 peer-focus:text-sm">
                            Nome do produto
                        </label>
                    </div>
                    <div class="relative mt-6">
                        <input type="linkProduct" name="linkProduct" id="linkProduct" x-model="newProduct.link" class="peer placeholder-transparent h-10 w-full border-b-2 border-neutral-300 text-neutral-700 focus:outline-none focus:border-cyan-700 pr-2" placeholder="">
                        <label for="linkProduct" class="absolute left-0 -top-4 text-neutral-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-4 peer-focus:text-neutral-600 peer-focus:text-sm">
                            Link de compra (opcional)
                        </label>
                    </div>
                </div>
                <div class="text-center">
                    <button @click="insertProduct()" class="bg-cyan-600 hover:bg-cyan-700 hover:cursor-pointer text-white font-bold py-2 px-15 rounded-full mt-8">
                        Inserir
                    </button>
                </div>
            </div>
        </x-modal>

        <x-modal name="isModalSelectedOpen">
            <div class="p-5 bg-white shadow-lg rounded-lg max-h-[70vh] overflow-y-auto">
                <div class="flex justify-between">
                    <div class="mx-auto text-xl mb-1 text-neutral-800">
                        <span>Você escolheu </span>
                        <span x-text="productSelectedName" class="font-semibold"></span>!
                    </div>
                    <button class="ml-2 text-neutral-600 hover:text-neutral-900 focus:outline-none cursor-pointer"
                            x-on:click="isModalSelectedOpen = false">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="mt-8">
                    <div class="mb-3">
                        <div class="flex items-center space-x-2">
                            <div class="flex-grow me-5">
                                <label class="block text-neutral-700 text-base font-bold mb-2">Quantos convidados</label>
                            </div>
                            <button type="button" @click="removeGuest()" class="bg-neutral-200 hover:bg-neutral-300 text-neutral-800 font-bold py-1 px-3 rounded-md transition duration-200 cursor-pointer">
                                -
                            </button>
                            <input type="number" x-model="numGuests" min="1" class="w-16 text-center border-b-2 border-neutral-300 text-neutral-600 focus:outline-none focus:border-cyan-700 font-bold text-lg" readonly>
                            <button type="button" @click="addGuest()" class="bg-neutral-200 hover:bg-neutral-300 text-neutral-800 font-bold py-1 px-3 rounded-md transition duration-200 cursor-pointer">
                                +
                            </button>
                        </div>
                    </div>
                    <div id="guestNameFields" class="space-y-4 mb-10">
                        <template x-for="(guest, index) in guestNames" :key="guest.id">
                            <div class="relative mt-6">
                                <input type="text" :name="`guest_names[${index}]`" :id="`guest_name_${guest.id}`" x-model="guest.name" class="peer placeholder-transparent h-10 w-full border-b-2 border-neutral-300 text-neutral-700 focus:outline-none focus:border-cyan-700 pr-2" placeholder="">
                                <label :for="`guest_name_${guest.id}`" class="absolute left-0 -top-4 text-neutral-600 text-sm peer-placeholder-shown:text-base peer-placeholder-shown:text-neutral-400 peer-placeholder-shown:top-2 transition-all peer-focus:-top-4 peer-focus:text-neutral-600 peer-focus:text-sm">
                                    Nome do convidado
                                </label>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="text-center">
                    <button @click="cofirmSelectedProduct()" class="bg-cyan-600 hover:bg-cyan-700 hover:cursor-pointer text-white font-bold py-2 px-15 rounded-full mt-8">
                        Confirmar
                    </button>
                </div>
            </div>
        </x-modal>

    </div>
@endsection


<script>
    function productsData() {
        return {
            host: { products: [] },
            loading: false,
            newProduct: {
                imageBase64: null,
                name: '',
                link: '',
            },
            previewImage: null,
            fileName: '',
            isModalItensOpen: false,
            isModalSelectedOpen: false,
            productSelectedName: '',
            productSelectedId: null,
            numGuests: 1,
            guestNames: [{ id: Date.now(), name: '' }],

            async loadProducts(){
                try {
                    const response = await axios.get('/host');
                    this.host = response.data;
                } catch (error) {
                    alert('Erro ao carregar produtos');
                }
            },

            handleFileUpload(event) {
                const file = event.target.files[0];
                if(file){
                    this.fileName = file.name;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.previewImage = e.target.result;
                        this.newProduct.imageBase64 = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    this.previewImage = null;
                    this.newProduct.imageBase64 = null;
                    this.fileName = '';
                }
            },

            async insertProduct() {
                try {
                    if(!this.newProduct.imageBase64 || !this.newProduct.name) {
                        throw new Error("Insira ao menos o nome e imagem para continuar!");
                    }

                    const response = await axios.post('/create-product', this.newProduct);
                    alert(`${this.newProduct.name} inserido com sucesso!`);

                    this.newProduct = { imageBase64: null, name: '', link: '' };
                    this.previewImage = null;
                    this.fileName = '';

                    this.isModalItensOpen = false;
                    await this.loadProducts();
                } catch (e) {
                    const errorMessage = e.response?.data?.message || e.message ||  'Erro desconhecido';
                    alert(`Erro: ${errorMessage}`);
                }
            },

            async removeProduct(productId) {
                try {
                    await axios.delete(`/products/${productId}`);
                    await this.loadProducts();
                } catch (e) {
                    const errorMessage = e.response?.data?.message || 'Erro desconhecido';
                    alert(`Erro: ${errorMessage}`);
                }
            },

            addGuest() {
                this.numGuests++;
                this.guestNames.push({id: Date.now(), name: '' })
            },
            removeGuest() {
                if (this.numGuests > 1) {
                    this.numGuests--;
                    this.guestNames.pop();
                }
            },

            selectedProduct(product) {
                this.numGuests = 1;
                this.guestNames = [{ id: Date.now(), name: '' }],
                this.productSelectedName = product.name;
                this.productSelectedId = product.id;
                this.isModalSelectedOpen = true;
            },

            async cofirmSelectedProduct() {
                this.loading = true;
                try {
                    if (!this.productSelectedId) {
                        throw new Error('Produto não selecionado');
                    }

                    const filledNames = this.guestNames.filter(g => g.name.trim() !== '');

                    if (filledNames.length < 1) {
                        throw new Error('Adicione pelo menos um nome de convidado');
                    }

                    await axios.post(`/choose-product/${this.productSelectedId}`, {
                        guestNames: this.guestNames.map(g => ({ name: g.name }))
                    })

                    alert('Produto selecionado com sucesso!');
                    this.isModalSelectedOpen = false;
                    this.productSelectedId = null;
                    this.guestNames = [{ id: Date.now(), name: '' }];
                    this.numGuests = 1;
                    await this.loadProducts();
                } catch (e) {
                    const errorMessage = e.response?.data?.message || e.message || 'Erro desconhecido';
                    alert(`Erro ao confirmar seleção: ${errorMessage}`);
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
