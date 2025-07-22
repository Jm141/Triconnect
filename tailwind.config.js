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
            colors: {
                triconnect: {
                    light: '#e3f0ff',
                    DEFAULT: '#2563eb',
                    dark: '#1e40af',
                    accent: '#38bdf8',
                    muted: '#60a5fa',
                },
            },
        },
    },

    plugins: [forms],
};
