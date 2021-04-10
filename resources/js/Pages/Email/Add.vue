<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Send Email
            </h2>
        </template>
        <div class="py-12">
            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-2">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Send Email</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Send New Email, Back to <a
                                class="inline-flex justify-center py-2 px-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-black hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                :href="'/emails'">List</a>
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div style="color:green" class="text-green-500">
                            {{ message }}
                        </div>
                        <div v-for="(err, index) in error" class="text-red-500">
                            {{ index }}: {{ err }}
                        </div>
                        <div class="shadow overflow-initial sm:rounded-md">
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <div class="grid grid-cols-6 gap-6">
                                    <div class="col-span-6 sm:col-span-4">
                                        <label for="sender" class="block text-sm font-medium text-gray-700">
                                            Sender
                                        </label>
                                        <input placeholder="Sender Email" id="sender" type="text" v-model="sender"
                                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <label for="receiver" class="block text-sm font-medium text-gray-700">
                                            Recipient
                                        </label>
                                        <input placeholder="Recipient Email" id="receiver" type="text"
                                               v-model="receiver"
                                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <label for="subject" class="block text-sm font-medium text-gray-700">
                                            Subject
                                        </label>
                                        <input placeholder="Email Subject" id="subject" type="text"
                                               v-model="subject"
                                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <label for="content" class="block text-sm font-medium text-gray-700">
                                            Content
                                        </label>
                                        <textarea placeholder="Email Content" id="content" type="text"
                                                  v-model="content"
                                                  class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </textarea>
                                    </div>
                                    <div class="col-span-6 sm:col-span-4">
                                        <label for="file" class="block text-sm font-medium text-gray-700">
                                            Attachments
                                        </label>
                                        <input type="file" multiple id="file" ref="file" @change="handleFileUpload"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button @click="sendEmail"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-black hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout'

export default {
    data() {
        return {
            subject: '',
            sender: '',
            receiver: '',
            content: '',
            attachment: [],
            message: '',
            error: '',
        }
    },
    components: {
        AppLayout,
    },
    methods: {
        sendEmail() {
            let formData = new FormData();

            formData.append('sender', this.sender);
            formData.append('receiver', this.receiver);
            formData.append('subject', this.subject);
            formData.append('content', this.content);

            if (this.attachment.length > 0) {
                this.attachment.forEach(file => {
                    formData.append('attachments[]', file);
                });
            }

            axios.post('/api/email/send',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then((response) => {
                this.message = response.data.message;
                this.error = '';
            }).catch(error => {
                console.log(error.response.data)
                this.error = error.response.data;
            });
        },
        handleFileUpload(event) {
            const files = event.target.files
            for (let i = 0; i < files.length; i++) {
                const fileReader = new FileReader()
                fileReader.readAsDataURL(files[i])
                fileReader.onload = e => {
                    let src = e.target.result;
                    this.$emit('loaded', {src, file});
                };
                this.attachment.push(files[i]);
            }
            console.log(this.attachment);
        }
    }
}
</script>
