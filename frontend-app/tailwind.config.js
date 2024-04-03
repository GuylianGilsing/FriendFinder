/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './src/**/*.vue'
    ],
    theme: {
        extend: {
            colors: {
                'primary': '#F53B57',
                'primary-text': '#000',
                'secondary-text': '#fff',
                'divider': '#BDBDBD',
                'danger': '#c0392b',
                'background-light': '#fff',
            },
        },
    },
    plugins: [],
}

