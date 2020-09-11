
import Vue from 'vue'
import Vuetify from 'vuetify'
// import theme from './theme'
import "vuetify/dist/vuetify.min.css";
import "material-design-icons-iconfont/dist/material-design-icons.css";
import '@mdi/font/css/materialdesignicons.css'

// Translation provided by Vuetify (typescript)
import pt from 'vuetify/es5/locale/pt'

import colors from 'vuetify/es5/util/colors'


Vue.use(Vuetify);
// window.Vue = Vue;
export default new Vuetify({
    // theme: theme,
    // theme: {
    //     dark: false,
    //     themes: {
    //         light:     {
    //             primary: colors.light-blue.base,
    //             secondary: colors.purple.base,
    //             accent: colors.light-green.base,
    //             error: colors.red.base,
    //             warning: colors.deep-orange.base,
    //             info: colors.cyan.base,
    //             success: colors.green.base
    //         },
    //     },
    // },

    icons: {
        iconfont: 'mdi' // default
    },
    lang: {
        locales: { pt },
        current: 'pt'
    }
});
