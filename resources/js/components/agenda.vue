<template>

    <v-layout align-center justify-center>
        <v-flex xs12 sm10 md10>
        <v-tabs
            v-model="tab"
            fixed-tabs
            background-color="indigo"
            dark
        >
            <v-tab key="0">
                Crud
            </v-tab>
            <v-tab key="1">
                Calendário (em contrução)
            </v-tab>
            <v-tabs-items v-model="tab">
                <v-tab-item
                    key="0"
                >


                        <v-flex xs12 text-xs-center>
                            <v-card hover>
                                <v-toolbar dark color="teal">
                                    <v-tooltip content-class="top" top  >
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-on="on" icon class="hidden-xs-only">
                                                <v-icon @click="setLogout()" >mdi-arrow-left</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Sair da aplicação</span>
                                    </v-tooltip>



                                    <v-toolbar-title>.:: Agenda ::.</v-toolbar-title>
                                    <v-spacer></v-spacer>
                                    <v-tooltip left>
                                        <template v-slot:activator="{ on }">
                                            <v-btn
                                                v-on="on"
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
                                        </template>
                                        <span>Incluir Evento</span>
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
                                        footer-props.items-per-page-text="Registros p/ página:"
                                        :loading="loading"
                                        footer-props.items-per-page-options="[5,10,25,{'text':'Todos','value':-1}]"

                                        :hide-default-header="!listAgenda.length"
                                        :hide-default-footer="!listAgenda.length"
                                    >
                                        <template v-slot:item.data="{ item }">
                                            {{ item.data | formatDate('DD/MM/YYYY') }}
                                        </template>

                                        <template v-slot:item.action="{ item }">
                                            <v-layout row fill-height justify-end align-center>
                                                <v-tooltip content-class="top" top  >
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn  icon small  class="mx-0"
                                                                @click="openViewEdit(item)"
                                                                v-on="on"
                                                        >
                                                            <v-icon color="teal">edit</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Editar</span>
                                                </v-tooltip>
                                                <v-tooltip content-class="top" top  >
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn  icon small  class="mx-0"
                                                                @click="abreConfirma(item)"
                                                                v-on="on"
                                                        >
                                                            <v-icon color="pink">delete</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Excluir</span>
                                                </v-tooltip>
                                            </v-layout>
                                        </template>
                                        <v-alert
                                            slot="no-results"
                                            :value="true"
                                            color="error"
                                            icon="warning"
                                            style="height:50px;"
                                        >
                                            Sua busca por "{{ search }}" não retornou dados.
                                        </v-alert>


                                        <template slot="pageText" slot-scope="props">
                                            Linhas {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>
                        </v-flex>

                </v-tab-item>
                <v-tab-item
                    key="1"
                >
                    <v-flex xs12 text-xs-center>
                    <agenda-calendario :lista="listAgenda">

                    </agenda-calendario>
                    </v-flex>


                    </v-tab-item>
            </v-tabs-items>
        </v-tabs>
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
    import AgendaCalendario from "./agenda-calendario";
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
                {text: 'Data', value: 'data', align: 'left'},
                {text: 'Hora', value: 'horario', align: 'center'},
                {text: 'Local', value: 'local', align: 'left', class: 'hidden-sm-and-down'},
                {text: 'Ações', value: 'action', align: 'right', sortable: false},
            ],
            items: [],
            tab:null,
        }),
        props: {
            source: String
        },
        components: {
            AgendaAddEdit,
            AgendaCalendario,
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
                var self = this;
                this.loading = true;
                bus.$emit('atonprogress:on');
                let temp = this.$parent.token_login;

                console.log('token atual: ' + this.$parent.token_login);
                this.$http.get('/api/agenda', {headers: {
                        Authorization: 'Bearer ' + this.$parent.token_login,
                    }})
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
                this.textoConfirma = 'Deseja excluir o evento ' + item.descricao + ' ?';
                this.openDialogExcluir = true;
            },
            excluirRegistro: function () {
                let self = this;
                if (this.loading) return false;

                const drago =  Object.assign('', self.itemSelected.descricao);

                this.loading = true;
                this.$http.get('/api/agenda/' + self.itemSelected.id + '/destroy', {headers: {
                        Authorization: 'Bearer ' + this.$parent.token_login,
                    }})
                    .then(response => {
                        self.loading = false;

                        let index = self.listAgenda.indexOf(self.itemSelected);
                        self.listAgenda.splice(index, 1);

                        // let msg = response.data;
                        //

                        bus.$emit('atonmsg', 'success', 'Evento ' + drago + ' excluido com sucesso!');

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

