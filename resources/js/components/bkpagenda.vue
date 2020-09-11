<template>

    <v-layout align-center justify-center>
        <v-flex xs12 sm10 md8>
            <v-flex xs12 text-xs-center>
                <v-card hover>
                    <v-toolbar dark color="teal">
                        <v-toolbar-side-icon>
                            <v-tooltip right>
                                <v-icon slot="activator" @click="setLogout()">exit_to_app</v-icon>
                                <span>Sair da aplicação</span>
                            </v-tooltip>

                        </v-toolbar-side-icon>
                        <v-toolbar-title>.:: Agenda ::.</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-tooltip left>
                            <v-btn
                                    slot="activator"
                                    color="pink"
                                    dark
                                    small
                                    absolute
                                    bottom
                                    right
                                    fab
                                    @click="openViewAdd()"
                            >
                                <v-icon>add</v-icon>
                            </v-btn>
                            <span>Incluir Dragão</span>
                        </v-tooltip>
                    </v-toolbar>


                    <v-card-text class="blue-grey lighten-5">
                        <v-layout align-center justify-space-around row fill-height pb-1>
                            <v-flex sm12 md2>
                                <v-btn
                                        :block="$vuetify.breakpoint.smAndDown"
                                        :loading="loading"
                                        :disabled="loading"
                                        color="secondary"
                                        @click="loadDados()"
                                >
                                    Atualizar
                                </v-btn>
                            </v-flex>
                            <v-spacer v-if="$vuetify.breakpoint.mdAndUp"></v-spacer>
                            <v-flex md6 class="align-center text-xs-right">
                                <v-text-field
                                        prepend-inner-icon="search"
                                        clearable
                                        label="Filtrar por nome ou tipo"
                                        single-line
                                        hide-details
                                        v-model="search"
                                ></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                    <v-card-text>

                        <v-data-table
                                v-bind:headers="headers"
                                v-bind:items="listAgenda"
                                v-bind:search="search"
                                no-data-text="Sem dados para exibir"
                                rows-per-page-text="Registros p/ página:"
                                :loading="loading"
                                :rows-per-page-items="[5,10,25,{'text':'Todos','value':-1}]"
                                :pagination.sync="pagination"
                                :hide-headers="!listAgenda.length"
                                :hide-actions="!listAgenda.length"
                        >


                            <template slot="items" slot-scope="props">
                                <td class="text-xs-left">{{ props.item.name }}</td>
                                <td class="text-xs-left">{{ props.item.type }}</td>
                                <td class="text-xs-center hidden-sm-and-down">{{ props.item.createdAt | formatDate }}
                                </td>
                                <td class="text-xs-right">
                                    <v-layout row fill-height justify-end align-center>
                                        <v-tooltip content-class="top" top>
                                            <v-btn icon small class="mx-0"
                                                   @click="openViewEdit(props.item)"
                                                   slot="activator"
                                            >
                                                <v-icon color="teal">edit</v-icon>
                                            </v-btn>
                                            <span>Editar</span>
                                        </v-tooltip>
                                        <v-tooltip content-class="left" left>
                                            <v-btn icon small class="mx-0"
                                                   @click="abreConfirma(props.item)"
                                                   slot="activator"
                                            >
                                                <v-icon color="pink">delete</v-icon>
                                            </v-btn>
                                            <span>Excluir</span>
                                        </v-tooltip>
                                    </v-layout>
                                </td>
                            </template>

                            <template slot="pageText" slot-scope="props">
                                Linhas {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-flex>
        <sollus-msg-excluir
                @setOk="excluirRegistro()"
                @setCancel="val => openDialogExcluir = val"
                :openDialog="openDialogExcluir"
                :texto="textoConfirma"
        ></sollus-msg-excluir>
        <agenda-add-edit
                @setOk="loadDados()"
                @setCancel="val => openDialogAddEdit = val"
                :openDialog="openDialogAddEdit"
                :mode="modeAddEdit"
                :id="idAddEdit"
        ></agenda-add-edit>
        <aton-msg></aton-msg>
        <aton-progress></aton-progress>
    </v-layout>


</template>

<script>
    import SollusMsgExcluir from './sollus-dialog.vue';
    import AgendaAddEdit from "./agenda-add-edit.vue";
    import bus from './events/bus';
    import AtonMsg from './events/_atonmsg.vue';
    import AtonProgress from './events/_atonprogress.vue';

    export default {
        name: "agenda",
        data: () => ({
            listAgenda: [],

            loading: false,

            modeAddEdit: 'add',
            idAddEdit: 0,

            itemSelected: null,

            textoConfirma: 'Deseja excluir este registro?',

            pesquisa: {
                filtro: '',
            },

            openDialogExcluir: false,
            openDialogAddEdit: false,

            search: '',
            pagination: {
                rowsPerPage: 10
            },
            headers: [
                {text: 'Descrição', value: 'descricao', align: 'left'},
                {text: 'Data', value: 'type', align: 'left'},
                {text: 'Hora', value: 'createdAt', align: 'center'},
                {text: 'Local', value: 'createdAt', align: 'left', class: 'hidden-sm-and-down'},
                {text: 'Ações', value: '', align: 'right', sortable: false},
            ],
            items: []
        }),
        props: {
            source: String
        },
        components: {
            AgendaAddEdit,
            SollusMsgExcluir,
            AtonMsg,
            AtonProgress,

        },
        mounted() {
            this.loadDados();
        },
        methods: {
            setLogout: function(){
                this.$emit('logout');
            },
            loadDados: function () {
                if (this.loading) return false;
                let self = this;
                this.loading = true;
                bus.$emit('atonprogress:on');
                this.$http.get('/agenda')
                    .then(response => {
//                        console.log(response);
//                         self.loading = false;
                        self.listAgenda = response.data;
                    })
                    .catch(error => {
                        console.log(error.response);
                        // self.errors = error.response.data;
                    })
                    .finally(() => {
                        self.loading = false;
                        bus.$emit('atonprogress:close');
                    });
            },
            abreConfirma: function (item) {
                this.itemSelected = item;
                this.textoConfirma = 'Deseja excluir o dragão ' + item.name + ' ?';
                this.openDialogExcluir = true;
            },
            excluirRegistro: function () {
                let self = this;
                if (this.loading) return false;

                const drago =  Object.assign('', self.itemSelected.name);

                this.loading = true;
                this.$http.delete('/agenda/' + self.itemSelected.id + '/destroy')
                    .then(response => {
                        self.loading = false;

                        let index = self.listAgenda.indexOf(self.itemSelected);
                        self.listAgenda.splice(index, 1);

                        // let msg = response.data;
                        //

                        bus.$emit('atonmsg', 'success', 'Dragão ' + drago + ' excluido com sucesso!');

                        self.itemSelected = null;
                    })
                    .catch(error => {
                        bus.$emit('atonprogress:close');
                        // bus.$emit('atonmsg', 'error', 'Não foi possível excluir o registro');

                        self.loading = false;
                    })
                    .finally(() => {
                        bus.$emit('atonprogress:close');
                        self.loading = false;
                    });

            },
            openViewAdd: function (item) {
                this.modeAddEdit = 'add';
                this.idAddEdit = 0;
                this.openDialogAddEdit = true;
            },
            openViewEdit: function (item) {
                this.modeAddEdit = 'edit';
                this.idAddEdit = item.id;
                this.openDialogAddEdit = true;
            },
        }
    }
</script>

<style scoped>

</style>

<!--<span>-->
<!--<h4-->
<!--class="title font-weight-light text-xs-left"-->
<!--v-text="'Meus Dragões'"-->
<!--/>-->
<!--</span>-->
