require("./bootstrap");

window.Vue = require("vue").default;
import VueRouter from "vue-router";
import Vuetify from "vuetify";
import router from "./router";

Vue.use(VueRouter);
Vue.use(Vuetify);

const app = new Vue({
    el: "#app",
    router,
    vuetify: new Vuetify(),
});
