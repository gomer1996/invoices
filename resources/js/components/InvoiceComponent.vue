<template>
    <tr>
        <td>{{ createdAt }}</td>
        <td>{{ number }}</td>
        <td>{{ suppliedAt }}</td>
        <td>{{ comment }}</td>
        <td><a href="#" @click.prevent="$modal.show('editInvoiceModal-'+id)"><small>edit</small></a></td>
        <td><a href="#" @click.prevent="deleteInvoice(id)" class="text-danger"><small>delete</small></a></td>
        <modal :name="'editInvoiceModal-'+ id" :adaptive="true" :height="320">
            <EditInvoiceComponent :user="user" :invoice="invoice" @invoiceUpdated="updateInvoice($event)"></EditInvoiceComponent>
        </modal>
    </tr>
</template>

<script>
export default {
    props: ['user', 'invoice'],
    data(){
        return {
            id: this.invoice.id,
            createdAt: this.invoice.created_at,
            number: this.invoice.number,
            suppliedAt: this.invoice.supplied_at,
            comment: this.invoice.comment
        }
    },
    methods: {
        deleteInvoice: function(id) {
            axios.delete('/api/invoices/'+id,
                {
                    headers: {
                        'Authorization' : 'Bearer '+ this.user.api_token
                    }
                })
                .then(res => {
                    this.$emit('invoiceDeleted', { id: res.data.invoice.id });
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
        updateInvoice: function(event) {
            this.$modal.hide('editInvoiceModal-'+event.invoice.id);
            this.suppliedAt = event.invoice.supplied_at;
            this.number = event.invoice.number;
            this.comment = event.invoice.comment;
        }
    }
}
</script>
