/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
      colors: {
        primary: {
          DEFAULT: '#B91C1C', // Merah Maroon (Base)
          light: '#DC2626',   // Merah Terang (Hover)
          dark: '#7F1D1D',    // Merah Gelap (Sidebar)
        },
        accent: {
          DEFAULT: '#F59E0B', // Kuning Emas
          hover: '#D97706',
        },
        surface: '#F3F4F6', // Abu-abu background aplikasi
        paper: '#FFFFFF',   // Putih kertas
      },
      boxShadow: {
        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
        'card': '0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025)',
      }
    },
  },
  plugins: [],
}