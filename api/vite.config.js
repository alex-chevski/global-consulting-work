import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/sass/app.scss", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            jQuery: "jquery",
            vue: "vue/dist/vue.esm-bundler.js",
            // jquery: path.resolve(__dirname, "node_modules/jquery/dist/jquery.js"),
        },
    },
});
