<template>
    <transition name="slide-fade"
                mode="out-in"
    >
        <component :is="activeComp"  @SetToken="(payload) => token_login=payload" @loginOk="activeComp = 'agenda'" @logout="activeComp = 'login'">
        </component>
    </transition>

</template>

<script>
    import Login from './login';
    import Agenda from './agenda';

    export default {
        name: "frontendEnube",
        data: () => ({
            activeComp: 'login',
            token_login: '',
        }),

        components: {
            Login,
            Agenda,
        },
    }
</script>

<style scoped>
    .slide-fade-enter-active {
        transition: all .3s ease;
    }

    .slide-fade-leave-active {
        transition: all .4s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }

    .slide-fade-enter, .slide-fade-leave-to
        /* .slide-fade-leave-active for <2.1.8 */
    {
        transform: translateX(7px);
        opacity: 0;
    }


</style>
