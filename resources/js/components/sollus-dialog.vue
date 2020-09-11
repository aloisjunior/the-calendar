<script>
    export default {
        props: {
            openDialog: {
                default: false,
                type: Boolean,
            },
            texto: {
                default: 'Deseja excluir este registro?',
                type: String
            },
        },

        data() {
            return {
                name: 'sollus-dialog',
            }
        },
        methods: {
            setOk: function () {
                this.$emit('setOk');
                this.setCancel();
            },
            setCancel: function () {
                this.$emit('setCancel', false);
            },
        },
    }
</script>

<template>
    <v-dialog
        v-model="openDialog"
        persistent
        max-width="400px"
        @keydown.esc="setCancel()"
        @keydown.enter="setOk()"
    >
        <v-card
            @keydown.esc="setCancel()"
            @keydown.enter="setOk()"
        >

            <v-card-text class="blue-grey lighten-5 pa-2"
                         @keydown.esc="setCancel()"
                         @keydown.enter="setOk()">

                <v-icon x-large color="red lighten-2 ">help</v-icon>

                <span class="text-body-1 dark font-weight-bold mx-2">
                    {{ texto }}
                </span>

            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn depressed class="green--text" @click.native="setCancel()">Cancelar</v-btn>
                <v-btn ref="btnok" raised  class="red--text" @click.native="setOk()">Sim</v-btn>
            </v-card-actions>
        </v-card>

    </v-dialog>
</template>

<style scoped="">

</style>
