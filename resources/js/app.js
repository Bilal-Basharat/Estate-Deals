import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import '../css/app.css';

createInertiaApp({

     // Browser tab titles per page instead of a permanent "Laravel".
    title: (title) => (title ? `${title} - EstateSphere` : "EstateSphere"),

    // Slim loading bar during every Inertia navigation, in brand color.
    progress: {
        color: "#054fbe",
        showSpinner: false,
        delay: 150,
    },

    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});
