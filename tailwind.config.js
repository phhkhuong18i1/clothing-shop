import forms from '@tailwindcss/forms';

export default {
    content: ['./resources/**/*.blade.php', './resources/**/*.js'],
    theme: { extend: { colors: { ink: '#23201B', paper: '#F4EFE6', clay: '#8C5E3C', olive: '#4d5e43' }, fontFamily: { sans: ['Be Vietnam Pro', 'system-ui', 'sans-serif'], display: ['Fraunces', 'Georgia', 'serif'] } } },
    plugins: [forms],
};
