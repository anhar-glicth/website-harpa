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
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                montserrat: ['Montserrat', 'sans-serif'],
            },
            colors: {
                primary: {
                    50: '#edf5f9',
                    100: '#d5e7f1',
                    200: '#b0cfe1',
                    300: '#81b1cd',
                    400: '#4d8cb5',
                    500: '#0e567d',
                    600: '#063f5c',
                    650: '#053953',
                    700: '#04324a',
                    800: '#032537',
                    900: '#021a28',
                    950: '#010f18',
                },
                secondary: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f7ad1a',
                    550: '#eda214',
                    600: '#df9408',
                    650: '#cb8407',
                    700: '#b97307',
                    800: '#93580d',
                    900: '#78470f',
                },
                emerald: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f7ad1a',
                    550: '#eda214',
                    600: '#b97307',
                    650: '#cb8407',
                    700: '#93580d',
                    800: '#78470f',
                },
                amber: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f7ad1a',
                    550: '#eda214',
                    600: '#df9408',
                    650: '#cb8407',
                    700: '#b97307',
                    800: '#93580d',
                    900: '#78470f',
                },
                brand: {
                    navy: '#063f5c',
                    gold: '#f7ad1a',
                    white: '#ffffff',
                }
            },
        },
    },

    plugins: [forms],
};
