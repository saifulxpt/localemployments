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
                    50: '#fff1f2',
                    100: '#ffe4e6',
                    200: '#fecdd3',
                    300: '#fda4af',
                    400: '#fb7185',
                    500: '#f43f5e',
                    600: '#e11d48',
                    700: '#be185d',
                    800: '#9f1239',
                    900: '#881337',
                    950: '#4c0519',
                    DEFAULT: '#e11d48',
                },
                accent: {
                    400: '#FBBF24',
                    500: '#F59E0B',
                    DEFAULT: '#F59E0B',
                    600: '#D97706',
                },
            },
            fontFamily: {
                sans: ['"Hind Siliguri"', 'Kalpurush', 'sans-serif'],
            },
            boxShadow: {
                'card': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                'card-hover': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
            },
            animation: {
                'float': 'float 6s ease-in-out infinite',
                'blob': 'blob 7s infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                blob: {
                    '0%': { transform: 'translate(0px, 0px) scale(1)' },
                    '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                    '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                    '100%': { transform: 'translate(0px, 0px) scale(1)' },
                }
            }
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
