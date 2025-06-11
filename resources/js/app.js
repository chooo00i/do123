import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { ZiggyVue } from "ziggy";
import MainLayout from "@/Layouts/MainLayout.vue";
import "../css/app.css";
import "flowbite";
import "emoji-picker-element";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
// import '@toast-ui/calendar/dist/toastui-calendar.min.css'

createInertiaApp({
    resolve: async (name) => {
        const page = await resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        );
        page.default.layout = page.default.layout || MainLayout;
        return page;
    },

    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
});
