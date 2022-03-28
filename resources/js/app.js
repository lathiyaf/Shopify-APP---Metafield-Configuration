require('./bootstrap');
window.Vue = require('vue');

import App from './components/layouts';
    import VTooltip from 'v-tooltip';

    Vue.use(VTooltip)
//import './components'
import router from './routes';
import helper from './helper';


const app = new Vue({
    el: '#app',
    router,
    render: h => h(App),
});
