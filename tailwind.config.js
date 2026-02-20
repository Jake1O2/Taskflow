module.exports = {
    darkMode: 'class', // ← Important! Utilise class au lieu de media
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primary: 'var(--primary)',
                'text-primary': 'var(--text-primary)',
                'bg-primary': 'var(--bg-primary)',
            },
        },
    },
    plugins: [],
};