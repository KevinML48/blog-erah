import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                'erah': '0 0px 10px 0 rgb(0 0 0 / 0.05)',
                'inner-erah': 'inset 0 0px 4px 0 rgb(0 0 0 / 0.05)',
            },
        },
    },

    plugins: [forms],
};
