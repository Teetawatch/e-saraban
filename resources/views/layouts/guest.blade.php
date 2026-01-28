<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ระบบสารบรรณอิเล็กทรอนิกส์ รร.พธ.พธ.ทร.</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
        <!-- Snowfall Effect -->
        <style>
            .snowflakes {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
                z-index: 9999;
                overflow: hidden;
            }
            
            .snowflake {
                position: absolute;
                top: -20px;
                color: #fff;
                font-size: 1em;
                text-shadow: 0 0 5px rgba(255, 255, 255, 0.8);
                animation: snowfall linear infinite;
                opacity: 0.9;
            }
            
            @keyframes snowfall {
                0% {
                    transform: translateY(-20px) rotate(0deg);
                    opacity: 1;
                }
                100% {
                    transform: translateY(100vh) rotate(360deg);
                    opacity: 0.3;
                }
            }
        </style>
        
        <div class="snowflakes" aria-hidden="true">
            @for ($i = 0; $i < 50; $i++)
                <div class="snowflake" style="
                    left: {{ rand(0, 100) }}%; 
                    animation-duration: {{ rand(8, 20) }}s;
                    animation-delay: {{ rand(0, 10) }}s;
                    font-size: {{ rand(8, 20) }}px;
                    opacity: {{ rand(40, 100) / 100 }};
                ">❄</div>
            @endfor
        </div>
    </body>
</html>

