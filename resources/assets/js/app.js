/*==============================================================================
Bootstrap Vue, jQuery, API and injecting requests with CSRF tokens
==============================================================================*/

require('./bootstrap');
window.Navi = require('./naviAPI.js');

/*==============================================================================
Components
==============================================================================*/

Vue.component('fund-card', require('./components/fundCard.vue'));

import Index from './pages/index.vue';
import Fund from './pages/fund.vue';
import FundAdd from './pages/fundAdd.vue';

/*==============================================================================
Vue Instance
==============================================================================*/

let App = Vue.extend({
  methods: {

  },

  ready(){

  }
});

/*==============================================================================
Router Instance
==============================================================================*/

let router = new VueRouter({
  history: true
});
router.mode = 'html5';
router.map({
  '/': { component: Index },
  '/fund/:fundname': { component: Fund, name: 'fund' },
  '/fund/add': { component: FundAdd }
});

router.beforeEach(transition => {
  $('#routerWrapper').animate({
    scrollTop: 0
  }, 200);
  transition.next();
});

router.start(App, 'body');
