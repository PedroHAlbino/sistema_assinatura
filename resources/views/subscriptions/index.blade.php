<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assinar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                   <form action="{{ route('subscriptions.store') }}" method="post" id="form">
                        @csrf
                        <div class="col-span-6 sm:col-span-4 py-2">
                            <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400  dark:focus:ring-blue-500 dark:focus:border-blue-500" type="text" name="card-holder-name" id="card-holder-name" placeholder="Nome no cartão">
                        </div>
                        <div class="col-span-6 sm:col-span-4 py-2">
                        <div id="card-element"></div>
                        </div>
                        <div class="col-span-6 sm:col-span-4 py-2">
                            <button id="card-buttom" data-secret="{{ $intent->client_secret }}" class="bg-blue-500" type="submit">Enviar</button>
                        </div>
                        
                   </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>

    const stripe = Stripe("{{ config('cashier.key') }}");
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const form = document.getElementById('form');
    const cardHolderName = document.getElementById('card-holder-name');
    const carButton = document.getElementById('card-buttom');
    const clientSecret = carButton.dataset.secret;

    form.addEventListener('submit', async(e)=>{
        e.preventDefault()

        const {setupIntent, error} = await stripe.confirmCardSetup(
            clientSecret,{
                payment_method:{
                    card: cardElement,
                    billing_details:{
                        name: cardHolderName.value
                    }
                }
            }
            
       );

       if(error){
        alert('Errou')
        console.log(error)
        return;
       }

       let token = document.createElement('input')
       token.setAttribute('type','hidden')
       token.setAttribute('name','token')
       token.setAttribute('value',setupIntent.payment_method)
       form.appendChild(token)
       
       form.submit();

    })

</script>
