import Router from "vue-router";
import Home from "./components/Home.vue";
import App from "./components/App.vue";

export default new Router({
    mode: "history",
    routes: [
        {
            path: "/",
            component: App,
            children: [
                {
                    path: "/",
                    name: "home",
                    component: Home,
                },
            ],
        },
    ],
});
