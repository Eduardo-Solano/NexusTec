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
            // --- AGREGA ESTO AQUÍ ---
            colors: {
                'ito-orange': '#F05E23', // Naranja Oficial
                'tecnm-blue': '#1B396A', // Azul Oficial
                'tecnm-blue-dark': '#122646', // Azul Oscuro (para hover)
            }
            // ------------------------
        },
    },

    plugins: [forms],
};