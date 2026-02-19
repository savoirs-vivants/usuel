/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        'sv-blue': '#222A60',
        'sv-green': '#16987C',
      },
      fontFamily: {
        'grotesk': ['"Space Grotesk"', 'sans-serif'],
        'mono': ['"Space Mono"', 'monospace'],
      }
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}
