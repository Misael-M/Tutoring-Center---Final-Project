import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import flowbite from 'flowbite/plugin';
import wireui from './vendor/wireui/wireui/tailwind.config.js';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    presets: [
        wireui
    ],

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',

        // Rutas de WireUI
        './vendor/wireui/wireui/src/*.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/WireUi/**/*.php',
        './vendor/wireui/wireui/src/Components/**/*.php',

        './node_modules/flowbite/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: colors.blue,
                gray: colors.gray,
                green: colors.emerald,
                red: colors.red,
                warning: colors.amber,
                info: colors.blue
            },
        },
    },

    plugins: [forms, typography, flowbite],
};
