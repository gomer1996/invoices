<template>
    <div class="card">
        <div class="card-header">
            Invoices
            <button class="btn btn-primary float-right" @click="$modal.show('newInvoiceModal')">Add new</button>
        </div>

        <div class="card-body">
            <div v-if="status" class="alert alert-success" role="alert">
                status
            </div>

            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">Created date</th>
                    <th scope="col">No</th>
                    <th scope="col">Supply date</th>
                    <th scope="col">Comment</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                    <InvoiceComponent
                        v-for="invoice in invoices"
                        :invoice="invoice"
                        @invoiceDeleted="removeInvoiceElem($event)"
                        v-bind:key="invoice.id"
                        :user="user"
                    ></InvoiceComponent>
                </tbody>
            </table>
        </div>
        <modal name="newInvoiceModal" :adaptive="true" :height="320">
            <NewInvoiceComponent :user="user" @invoiceAdded="addInvoice($event)"></NewInvoiceComponent>
        </modal>
        <notifications group="invoiceAlert" position="top right" />
    </div>
</template>
<script>
import VModal from 'vue-js-modal/dist/index.nocss.js';
import 'vue-js-modal/dist/styles.css';
import Notifications from 'vue-notification'
import InvoiceComponent from './InvoiceComponent';
import NewInvoiceComponent from './NewInvoiceComponent';

Vue.use(VModal);
Vue.use(Notifications);

export default {
        props: ['user'],
        components:{
            InvoiceComponent,
            NewInvoiceComponent
        },
        async mounted() {
            await this.getInvoices();
        },
        data(){
            return {
                status: false,
                invoices: []
            }
        },
        methods: {
            getInvoices: function() {
                axios.get('/api/invoices',
                    {
                        headers: {
                            'Authorization' : 'Bearer '+ this.user.api_token
                        }
                    })
                    .then(res => {
                        this.invoices = res.data.invoices;
                    }).catch((e) => {
                        const status = e.response.status;
                        this.$notify({
                            group: 'invoiceAlert',
                            title: 'Error',
                            text: status === 422 ? Object.values(e.response.data.errors)[0][0] : e.response.data.message,
                            type: 'error'
                        });
                    });
            },
            removeInvoiceElem: function(event){
                const itemIndex = this.invoices.findIndex(item => item.id === event.id);
                this.invoices.splice(itemIndex, 1);
            },
            addInvoice: function(event) {
                this.invoices.unshift(event.invoice);
                this.$modal.hide('newInvoiceModal');
                this.$notify({
                    group: 'invoiceAlert',
                    title: 'Success',
                    text: "Invoice added",
                    type: 'success'
                });
            }
        }
    }
</script>
