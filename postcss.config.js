module.exports = {
    plugins: {
        tailwindcss: {},
        tailwindcss: {},
        autoprefixer: {},
        ...(process.env.NODE_ENV === 'production' ? { cssnano: {} } : {})
    }
}