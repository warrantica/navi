/*==============================================================================
Bootstrap Vue, jQuery, API and injecting requests with CSRF tokens
==============================================================================*/

require('./bootstrap');
window.Navi = require('./naviAPI.js');

/*==============================================================================
Components
==============================================================================*/

Vue.component('fund-card', require('./components/fundCard.vue'));
Vue.component('performance-graph', require('./components/performanceGraph.vue'));

import Index from './pages/index.vue';
import Fund from './pages/fund.vue';
import FundList from './pages/fundList.vue';
import FundAdd from './pages/fundAdd.vue';

/*==============================================================================
Vue Instance
==============================================================================*/

let App = Vue.extend({
  data(){ return {
    currentFundName: ''
  }},

  methods: {
    goToFund(){
      this.$router.go({name: 'fund', params: {fundname: this.currentFundName}});
      this.$broadcast('updateData');
    }
  },

  ready(){

  },

  events: {
    'updateCurrentFundName'(name){ this.currentFundName = name; }
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
  '/fund': { component: FundList },
  '/fund/add': { component: FundAdd }
});

router.beforeEach(transition => {
  $('#routerWrapper').animate({
    scrollTop: 0
  }, 200);
  transition.next();
});

router.start(App, 'body');
