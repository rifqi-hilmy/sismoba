<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin=""/>
    <style>
    [x-cloak] { display: none !important; }
    @keyframes blink {
        50% {
            opacity: 0;
        }
    }

    .animate-blink {
        animation: blink 2s steps(50, start) infinite;
    }

    </style>
    @vite(['../resources/css/app.css', '../resources/js/app.js'])
    @livewireStyles
