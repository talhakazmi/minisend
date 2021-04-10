<template>
    <app-layout>
        <div class="flex min-h-screen">

            <div class="flex flex-col w-full">
                <div class="text-white bg-blue-400 flex flex-shrink-0 flex-col">
                    <div class="flex relative items-center p-4 h-12">
                        <span class="text-2xl tracking-wide">Email Detail</span>
                    </div>
                </div>
                <div class="p-3">
                    <span class="text-lg font-bold capitalize">{{ email.subject }} - To:<span class="uppercase">{{email.receiver}}</span></span>
                    <span class="float-right text-green-500 capitalize">Status: {{email.status}}</span>
                    <p class="py-3" v-html="email.content"></p>
                    <p class="py-3">Sender: {{email.sender}}</p>
                </div>
            </div>

        </div>
    </app-layout>
</template>
<script>
import AppLayout from '../../Layouts/AppLayout'

export default {
    props: [ 'email' ],
    data() {
        return {
            message: '',
            error: ''
        }
    },
    components: {
        AppLayout,
    }
}
</script>
