<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col items-center sm:pt-0 bg-gray-100 pb-12">
            <div class="flex flex-col items-center text-center pt-6">
                <img class="max-w-72 rounded-lg p-2 border-grey border-2 bg-white" src="{{ asset('images/areas-mapping-logo.png') }}" alt="Image">
                <p class="max-w-[750px] text-left pt-4">The Area Mapping tool is built with openlayers and allows you create, modify and save your geojson data. You can draw your own areas, upload a geojson file or add a geojson object directly to a form field. The data can be previewed and modified in realtime for a better user experience. Log in to give it a go!</p>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>


    </body>
</html>
