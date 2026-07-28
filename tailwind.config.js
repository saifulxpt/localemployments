/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/Http/Livewire/**/*.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#ECFDF5',
                    100: '#D1FAE5',
                    200: '#A7F3D0',
                    400: '#34D399',
                    600: '#059669',
                    700: '#047857',
                    800: '#065F46',
                    900: '#0B4F3C',
                    DEFAULT: '#0B4F3C',
                },
                accent: {
                    400: '#FBBF24',
                    500: '#F59E0B',
                    DEFAULT: '#F59E0B',
                    600: '#D97706',
                },
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
            boxShadow: {
                'card': '0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04)',
                'card-hover': '0 4px 12px rgba(0,0,0,0.08), 0 12px 32px rgba(0,0,0,0.06)',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
