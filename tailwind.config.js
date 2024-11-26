/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./node_modules/flowbite-react/**/*.js",
  ],
  theme: {
    extend: {},
  },
  plugins: [  
    require('flowbite/plugin')
  ],
}

