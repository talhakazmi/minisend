<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Emails
            </h2>
        </template>
        <div class="py-12">
            <div class="p-3">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <div>
                                    <button @click="search" class="ml-2 float-right bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Search
                                    </button>
                                    <input class="ml-2 float-right rounded-md" v-model="sender" type="text" placeholder="Sender">
                                    <input class="ml-2 float-right rounded-md" v-model="receiver" type="text" placeholder="Receiver">
                                    <input class="ml-2 float-right rounded-md" v-model="subject" type="text" placeholder="Subject">
                                    <a :href="'/email/create'" class="ml-2 float-left bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Send Email
                                    </a>
                                </div>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs text-blue-500 uppercase tracking-wider">ID</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs text-blue-500 uppercase tracking-wider">Subject</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs text-blue-500 uppercase tracking-wider">Sender</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs text-blue-500 uppercase tracking-wider">Receiver</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs text-blue-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="email in emails">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ email.id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 underline">
                                                <a :href="'/emails/'+email.id">{{ email.subject }}</a>
                                            </td>
                                            <td class="px-6 py-4 whitespace text-sm text-gray-500">{{ email.sender }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 underline">
                                                <a :href="'/emails?receiver='+email.receiver">{{ email.receiver }}</a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ email.status }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full text-center">
                <a class="p-2" v-bind:class="{'underline font-bold': page.active}" v-show="page.url" :href="page.url" v-for="page in pagination.links" v-html="page.label"></a>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout'

export default {
    props: [
        'emails',
        'pagination'
    ],
    data() {
        return {
            subject: '',
            sender: '',
            receiver: '',
            message: '',
            error: '',
        }
    },
    components: {
        AppLayout,
    },
    methods: {
        search() {
            let search = '';
            if(this.sender)
                search += 'sender='+this.sender;
            if(this.receiver) {
                search = this.queryString(search);
                search += 'receiver=' + this.receiver;
            }
            if(this.subject) {
                search = this.queryString(search);
                search += 'subject=' + this.subject;
            }
            window.location = '/emails?'+search;
        },
        queryString(search) {
            if(search != '') {
                search += '&';
            }
            return search;
        }
    }
}
</script>
