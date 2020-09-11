<template>

    <v-layout align-center justify-center>
        <v-flex xs12 sm8 md4>
            <v-flex xs12 text-xs-center>
                <img src="/logo.png" alt="" style="width:350px">
            </v-flex>
            <v-flex mt-3 xs12 text-xs-center>
                <v-card hover>
                    <v-toolbar dark color="primary">
                        <v-toolbar-title>.:: Bem-vindo a Agenda ::.</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>
                        <v-form>
                            <v-text-field
                                    v-model="dados.email"
                                    clearable
                                    autofocus
                                    prepend-icon="person" name="email" label="Email do Usuário" type="text"></v-text-field>
                            <v-text-field
                                    v-model="dados.password"

                                    :append-icon="showPass ? 'visibility' : 'visibility_off'"
                                    :type="showPass ? 'text' : 'password'"
                                    @click:append="showPass = !showPass"

                                    id="password"
                                    prepend-icon="lock"
                                    name="password"
                                    label="Senha"></v-text-field>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                                :disabled="(!dados.email) || (dados.password.length < 4)"
                                color="primary"
                                @click="sendLogin()"
                        >Entrar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-flex>
    </v-layout>

</template>

<script>
    export default {
        name: "login",
        data: () => ({
            user:null,
            password:'',
            showPass: false,

            dados: {
                password: '',
                email: '',
                device_name: 'web',
            },
        }),
        props: {
            source: String
        },
        methods: {
            // callLogin: function () {
            //     if ((this.user =='admin')&&(this.password=='senha')){
            //         this.$emit('loginOk');
            //     }
            // },
            sendLogin: function () {
                var self = this;

                this.loading = true;
                this.inError = false;
                this.errors = [];

                this.$http.post('/api/login/token', this.dados)
                    .then(response => {
                        self.$my_token = response.data;
                        // console.log(response.data);
                        console.log(self.$my_token);
                        self.$emit('loginOk');
                        // self.iniciar = false;
                        // window.location.href = '/admin';
                    })
                    .catch(error => {

                        self.loading = false;
                        self.iniciar = true;

                        console.log(error);

                        this.$emit('logout');

                        if (_.isUndefined(error.response)){
                            window.location.reload();
                        }

                        // if ( (error.response.status == '422') || (error.response.status == '419')) {
                        //     self.inError = true;
                        //     self.errors = this.ValisNotEmptyNull(error.response.data.errors) ? error.response.data.errors : error.response.data ;
                        //
                        // } else if (error.response.status == '403') {
                        //     self.sendValidaView();
                        // }
                    });
            }
        }
    }
</script>

<style scoped>

</style>
