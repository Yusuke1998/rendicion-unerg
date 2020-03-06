import Vue from 'vue';
import Router from 'vue-router';
import HomePage from "./pages/HomePage.vue";
import NotFound from "./pages/NotFountPage.vue";

Vue.use(Router)

export default new Router({
	mode: 'history',
	routes: [
		{
			path: '*',
			name: 'home',
			component: HomePage
		}
	]
});
