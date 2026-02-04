import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                // บังคับใช้ Kanit เป็นฟอนต์หลักทั้งระบบ
                sans: ['Kanit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // กำหนดสีธีมหลัก (ตัวอย่าง: สีน้ำเงินกรมท่าแบบสมัยใหม่)
                // Semantic Color System
                brand: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc', // Sky
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1', // Main Naval Blue
                    800: '#075985',
                    900: '#0c4a6e',
                    950: '#082f49',
                },
                // Primary is aliased to brand for backward compatibility
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                },
                accent: {
                    500: '#f43f5e', // Rose
                    600: '#e11d48',
                }
            }
        },
    },
    plugins: [forms],
};