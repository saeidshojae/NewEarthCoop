/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
    "./resources/js/**/*.vue",
    "./app/Modules/**/*.blade.php",
  ],
  darkMode: "class",
  theme: {
    extend: {
      fontFamily: {
        vazirmatn: ["Vazirmatn", "Poppins", "sans-serif"],
      },
    },
  },
  plugins: [],
};
