/** @type {import('tailwindcss').Config} */
export default {
  /**
   * Menentukan file mana saja yang akan dipindai class Tailwind-nya.
   * Ini mencakup index.html dan semua file .vue di dalam folder src.
   */
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        'harpa-navy': '#063f5c',
        'harpa-gold': '#f7ad1a',
        'humanity-blue': '#063f5c',
        'humanity-green': '#f7ad1a',
        primary: {
          50: '#edf5f9',
          100: '#d5e7f1',
          200: '#b0cfe1',
          500: '#0e567d',
          600: '#063f5c',
          700: '#04324a',
        },
        secondary: {
          50: '#fffbeb',
          100: '#fef3c7',
          200: '#fde68a',
          500: '#f7ad1a',
          600: '#df9408',
          700: '#b97307',
        },
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
