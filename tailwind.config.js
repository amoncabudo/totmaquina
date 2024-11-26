/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./App/Views/**/*.php",
    "./public/**/*.php",
    "./App/**/*.php"
  ],
  theme: {
    extend: {
      colors: {
        'brand': '#1a1a1a',
      }
    },
  },
  plugins: [],
}