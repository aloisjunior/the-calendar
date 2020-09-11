<template>
    <v-dialog v-model="openDialog" persistent max-width="600px">
        <v-card
                @keyup.esc.prevent="setCancel()"
                @keyup.enter.prevent="setOk()">
            <v-toolbar dark color="teal darken-3">
                <v-toolbar-title v-if="mode=='edit'">.:: Alteração de Evento ::.</v-toolbar-title>
                <v-toolbar-title v-else>.:: Inclusão de Evento ::.</v-toolbar-title>
                <v-spacer></v-spacer>
            </v-toolbar>

            <v-card-text>
                <v-form v-model="formCtrl" ref="form" lazy-validation>
                    <v-text-field v-model="dadosDB.descricao" label="Descrição" required
                                  class="mt-3"
                                  ref="descricao"
                                  autofocus
                                  :rules="rules.required"
                    ></v-text-field>

                    <v-text-field v-model="dadosDB.local" label="Local" required
                                  ref="local"
                                  :rules="rules.required"
                    ></v-text-field>

                    <v-row>
                        <v-col cols="12" lg="6">
                            <v-menu
                                ref="menu1"
                                v-model="menu1"
                                :close-on-content-click="false"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="dadosDB.dataFormatted"
                                        label="Date"
                                        hint="MM/DD/YYYY format"
                                        persistent-hint
                                        prepend-icon="event"
                                        v-bind="attrs"
                                        @blur="dadosDB.data = parseDate(dadosDB.dataFormatted)"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="dadosDB.data" no-title @input="menu1 = false"></v-date-picker>
                            </v-menu>
                            <p>Formato ISO: <strong>{{ dadosDB.data }}</strong></p>
                        </v-col>
                        <v-col cols="12" lg="6">
                            <v-menu
                                ref="menu"
                                v-model="menu2"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                :return-value.sync="dadosDB.horario"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="dadosDB.horario"
                                        label="Horário"
                                        prepend-icon="access_time"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-time-picker
                                    v-if="menu2"
                                    v-model="dadosDB.horario"
                                    full-width
                                    format="24hr"
                                    @click:minute="$refs.menu.save(dadosDB.horario)"
                                ></v-time-picker>
                            </v-menu>
                        </v-col>
                    </v-row>




                </v-form>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="red--text" @click="setCancel()">Cancelar</v-btn>
                <v-btn class="primary--text"
                       @click="callPost()"
                       :disabled="!formCtrl"
                >Salvar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

</template>

<script>
    import SollusRules from './base/sollus-rules';
    import bus from './events/bus';

    export default {
        mixins: [
            SollusRules,
        ],

        name: "agenda-add-edit",
        props: {
            openDialog: {
                default: false,
                type: Boolean,
            },
            mode: {
                default: 'add',
                type: String,
            },
            id: 0,
        },

        loading: false,
        data() {
            return {
                dadosDB: {
                    name: '',
                    id: 0,
                    type: '',
                    createdAt: null,
                    histories: [],
                },
                formCtrl: false,
                tiposDB: [],
                menu1:false,
                menu2:false,
            }
        },
        mounted() {
            const self = this;

            // self.$refs.form.reset();
            // self.loadTipos();

            // this.$nextTick(() =>
            //     self.$refs.descricao.$el.focus()
            // );
        },
        watch: {
            "dadosDB.data" (val) {
                this.dadosDB.dataFormatted = this.formatDate(this.dadosDB.data)
            },
            openDialog: function (newValue, oldValue) {
                // if (newValue && (newValue !== oldValue) ){
                //     this.loadTipos(!this.isEdit);
                // }

                if (newValue && this.isEdit) {
                    this.loadItemEdit();
                } else {
                    this.$refs.form.reset();
                    this.dadosDB.id = null;
                    this.dadosDB.descricao = '';
                    this.dadosDB.horario = '12:00';
                    this.dadosDB.data = null;
                    this.dadosDB.local = '';
                }

                // this.$nextTick(() =>
                //     this.$refs.descricao.$el.focus()
                // );
            }
        },
        computed: {
            createdAtFormatted: function () {
                return this.formatDateTime(this.dadosDB.createdAt);
            },

            isEdit: function () {
                return this.mode !== 'add';
            },
            listTipos: function () {
                return this.tiposDB.reduce((acc, cur) => {
                    const tipo = cur.type;

                    if (!acc.includes(tipo)) {
                        acc.push(tipo)
                    }

                    return acc;
                }, []).sort();

            },
        },
        methods: {
            formatDate (date) {
                if (!date) return null

                const [year, month, day] = date.split('-')
                return `${day}/${month}/${year}`
            },
            parseDate (date) {
                if (!date) return null

                const [day, month,  year] = date.split('/')
                return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
            },

            formatDateTime: function (value) {
                return moment(String(value), ['DD/MM/YYYY HH:mm', "YYYY/MM/DD HH:mm"]).format('DD/MM/YYYY HH:mm');
            },
            callPost: function () {
                if (this.loading) return false;

                if (!this.validation()) return false;

                let self = this;
                this.loading = true;

                bus.$emit('atonprogress:open', 'Atualizando dados');


                let _url = '/api/agenda';

                let postData = this.dadosDB;


                if (this.isEdit) {
                    _url = '/api/agenda/' + self.id + '/update';

                    this.$http.post(_url, postData, {headers: {
                            Authorization: 'Bearer ' + self.$my_token,
                        }})
                        .then(response => {
                            self.loading = false;

                            bus.$emit('atonmsg', 'success', 'Evento ' + self.dadosDB.descricao + ' alterado com sucesso!');

                            self.setOk();
                            bus.$emit('player:updated');
                        })
                        .catch(error => {
                            console.log(response);
                            bus.$emit('atonprogress:close');
                            bus.$emit('atonmsg', 'error', 'Dados não foram inseridos');

                            self.loading = false;
                        });
                } else {
                    let addData = {
                        descricao: this.dadosDB.descricao,
                        data: this.dadosDB.data,
                        horario: this.dadosDB.horario,
                        local: this.dadosDB.local,
                    };

                    postData = addData;

                    this.$http.post(_url, postData, {headers: {
                        Authorization: 'Bearer ' + self.$my_token,
                    }})
                        .then(response => {
                            self.loading = false;

                            bus.$emit('atonmsg', 'success', 'Evento ' + self.dadosDB.descricao + ' incluído com sucesso!');

                            self.setOk();
                            bus.$emit('player:updated');
                        })
                        .catch(error => {
                            console.log(response);
                            bus.$emit('atonprogress:close');
                            bus.$emit('atonmsg', 'error', 'Dados não foram inseridos');

                            self.loading = false;
                        });
                }


            },
            loadItemEdit: function () {
                let self = this;
                // self.activesAjax++;
                bus.$emit('atonprogress:open');
                this.loading = true;

                this.$http.get('/api/agenda/' + self.id+'/update',  {headers: {
                        Authorization: 'Bearer ' + self.$my_token,
                    }})
                    .then(response => {
                        self.dadosDB = response.data;
                        // self.$nextTick(() =>
                        //     self.$refs.descricao.$el.focus()
                        // );
                    })
                    .catch(error => {
                        console.log(error.response);
                    }).finally(() => {
                    self.loading = false;
                    bus.$emit('atonprogress:close');
                });
            },
            // loadTipos: function (closeProgress = false) {
            //     let self = this;
            //     // self.activesAjax++;
            //     bus.$emit('atonprogress:open', 'Carregando Tags');
            //     self.loading = true;
            //     this.$http.get('//5c4b2a47aa8ee500142b4887.mockapi.io/api/v1/dragon')
            //         .then(response => {
            //             self.tiposDB = response.data;
            //         })
            //         .catch(error => {
            //             console.log(error.response);
            //             bus.$emit('atonprogress:close');
            //         }).finally(() => {
            //         self.loading = false;
            //         if (closeProgress){
            //             bus.$emit('atonprogress:close');
            //         }
            //
            //     });
            //
            //
            // },

            setOk: function () {
                this.$emit('setOk');
                this.setCancel();
            },
            setCancel: function () {
                this.$emit('setCancel', false);
            },
            resetValidation: function () {
                this.$refs.form.resetValidation()
            },
            validation: function () {
                let valid = this.$refs.form.validate();
                this.formCtrl = valid;
                return valid;
            }
        },
    }
</script>

<style scoped>

</style>
