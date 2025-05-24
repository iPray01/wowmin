/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'metal-gold': {
          DEFAULT: '#D4AF37',
          50: '#F7F1DC',
          100: '#F2E7C7',
          200: '#E9D49D',
          300: '#E0C173',
          400: '#D7AE49',
          500: '#D4AF37',
          600: '#B3922C',
          700: '#8C7222',
          800: '#655219',
          900: '#3E320F'
        },
        'teal': {
          DEFAULT: '#008080',
          50: '#E6F3F3',
          100: '#CCE7E7',
          200: '#99CFCF',
          300: '#66B7B7',
          400: '#339F9F',
          500: '#008080',
          600: '#006B6B',
          700: '#005656',
          800: '#004141',
          900: '#002C2C'
        },
        'crimson': {
          DEFAULT: '#DC143C',
          50: '#FCE6EB',
          100: '#F9CCD7',
          200: '#F299AF',
          300: '#EC6687',
          400: '#E5335F',
          500: '#DC143C',
          600: '#B81032',
          700: '#8F0C27',
          800: '#66091C',
          900: '#3D0511'
        },
        primary: {
          50: '#f0f9ff',
          100: '#e0f2fe',
          200: '#bae6fd',
          300: '#7dd3fc',
          400: '#38bdf8',
          500: '#0ea5e9',
          600: '#0284c7',
          700: '#0369a1',
          800: '#075985',
          900: '#0c4a6e',
        },
        secondary: {
          50: '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          800: '#1e293b',
          900: '#0f172a',
        },
      },
      fontFamily: {
        sans: ['Inter var', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
