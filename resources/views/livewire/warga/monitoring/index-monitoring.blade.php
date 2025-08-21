<div wire:poll.5s="refreshData">
    <div class="mx-auto">
        <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
            <div class="w-full md:w-1/2">
                <h2 class="text-4xl font-extrabold">Data Monitoring</h2>
            </div>
            <div class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
                <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="{{ route('warga.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            Dashboard
                        </a>
                    </li>
                    <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1 text-gray-400 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a class="text-sm font-medium text-gray-700 ms-1">Monitoring</a>
                    </div>
                    </li>
                </ol>
                </nav>
            </div>
        </div>
        <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
            <div class="grid w-full grid-cols-1 gap-4 mb-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($perangkatSensor as $device)
                    <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="flex justify-between mb-2">
                            @php
                                $tipeWarna = $device['tipe'] === 'gateway'
                                    ? 'bg-yellow-100 border border-yellow-300 text-yellow-800'
                                    : 'bg-indigo-100 border border-indigo-300 text-indigo-800';
                            @endphp
                            <span class="text-sm font-medium px-2.5 py-0.5 rounded-sm {{ $tipeWarna }}">
                                {{ ucfirst($device['tipe']) }}
                            </span>
                            @php
                                $statusBanjir = $device['latest_sensor_data']['status_monitor']['status_banjir'] ?? null;

                                $warnaBanjir = match ($statusBanjir) {
                                    'bahaya' => 'bg-red-100 text-red-800 animate-blink',
                                    'siaga 1' => 'bg-orange-100 text-orange-800',
                                    'siaga 2' => 'bg-yellow-100 text-yellow-800',
                                    'aman' => 'bg-blue-100 text-blue-800',
                                    default => 'bg-gray-100 text-gray-500',
                                };
                            @endphp
                            <span class="text-sm font-medium px-2.5 py-0.5 rounded-full {{ $warnaBanjir }}">
                                {{ ucfirst($statusBanjir ?? 'Tidak Ada Data') }}
                            </span>
                        </div>
                        <h6 class="text-lg font-semibold tracking-tight text-gray-700">
                            {{ $device['lokasi'] }}
                        </h6>
                        <div class="flex items-center mb-2 space-x-2">
                            <h5 class="text-2xl font-bold tracking-tight text-gray-900">
                                {{ $device['nama_perangkat'] }}
                            </h5>
                            <span class="text-sm font-medium px-2.5 py-0.5 rounded-sm
                                {{ $device['status'] === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-500' }}">
                                {{ ucfirst($device['status']) }}
                            </span>
                        </div>
                        <hr class="h-px my-4 bg-gray-200 border-0">
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            @php
                                $sensor = $device['latest_sensor_data'] ?? null;
                            @endphp
                            <div class="flex flex-col items-center justify-between">
                                <p class="text-sm text-gray-700">Ketinggian Air</p>
                                <p class="text-sm text-gray-700">
                                    {{ $sensor['ketinggian_air'] ?? '-' }} cm
                                </p>
                            </div>
                            <div class="flex flex-col items-center justify-between">
                                <p class="text-sm text-gray-700">Curah Hujan</p>
                                <p class="text-sm text-gray-700">
                                    {{ $sensor['curah_hujan'] ?? '-' }} mm
                                </p>
                            </div>
                            <div class="flex flex-col items-center justify-between">
                                <p class="text-sm text-gray-700">Debit Air</p>
                                <p class="text-sm text-gray-700">
                                    {{ $sensor['debit_air'] ?? '-' }} ml/s
                                </p>
                            </div>
                        </div>
                        <hr class="h-px my-4 bg-gray-200 border-0">
                        <div class="flex flex-col items-center">
                            <a href="{{ route('warga.monitoring.show', $device['id']) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                Detail
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 col-span-full">Tidak ada data perangkat atau sensor tersedia.</p>
                @endforelse
            </div>
        </div>
        <div class="block p-4 mx-4 bg-white border border-gray-200 rounded-t-lg shadow-sm">
            <div class="flex items-center justify-center">
                <h3 class="text-xl font-semibold text-gray-900">Status Telemetri</h3>
            </div>
        </div>
        <div class="block p-4 mx-4 mb-4 bg-white border border-gray-200 shadow-sm" x-data="{ open: {{ count($perangkatSensorConnected) > 0 ? 0 : 'null' }} }">
            <div>
                @foreach($perangkatSensorConnected as $index => $device)
                    <h2>
                        <button
                            type="button"
                            @click="open === {{ $index }} ? open = null : open = {{ $index }}"
                            class="flex items-center justify-between w-full gap-3 p-5 text-lg text-gray-900 border border-b-0 border-gray-200 focus:ring-4 focus:ring-blue-200 hover:bg-blue-100">
                            <span>{{ $device['nama_perangkat'] }} - {{ $device['lokasi'] }}</span>
                            <svg class="w-3 h-3 transition-transform duration-200" :class="open === {{ $index }} ? '' : 'rotate-180'" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M9 5 5 1 1 5"/>
                            </svg>
                        </button>
                    </h2>
                    <div x-show="open === {{ $index }}" x-transition>
                        <div class="p-4 border border-b-0 border-gray-200">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3">Tanggal</th>
                                            <th class="px-4 py-3">Jam</th>
                                            <th class="px-4 py-3">Ketinggian Air (cm)</th>
                                            <th class="px-4 py-3">Perubahan Ketinggian (cm)</th>
                                            <th class="px-4 py-3">Curah Hujan (mm)</th>
                                            <th class="px-4 py-3">Debit Air (ml/detik)</th>
                                            <th class="px-4 py-3">Status Banjir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sortedData = collect($device['sensor_data'])
                                                ->sortByDesc(function ($sensor) {
                                                    return $sensor['tanggal'] . ' ' . $sensor['jam'];
                                                })
                                                ->take(15)
                                                ->values();
                                        @endphp
                                        @forelse($sortedData as $index => $sensor)
                                            @php
                                                $nextSensor = $sortedData[$index + 1] ?? null;

                                                $currentHeight = $sensor['ketinggian_air'];
                                                $previousHeight = $nextSensor['ketinggian_air'] ?? null;

                                                $difference = $previousHeight !== null ? $currentHeight - $previousHeight : null;

                                                $differenceText = $difference !== null
                                                    ? ($difference > 0 ? '+' . number_format($difference, 2) . ' ↑' : number_format($difference, 2) . ' ↓')
                                                    : '-';

                                                $status = $sensor['status_monitor']['status_banjir'] ?? '-';
                                                $badgeColor = match($status) {
                                                    'bahaya' => 'bg-red-100 text-red-800 animate-blink',
                                                    'siaga 1' => 'bg-orange-100 text-orange-800',
                                                    'siaga 2' => 'bg-yellow-100 text-yellow-800',
                                                    'aman' => 'bg-blue-100 text-blue-800',
                                                    default => 'bg-gray-100 text-gray-800',
                                                };
                                            @endphp

                                            <tr class="bg-white border-gray-900 hover:bg-gray-50">
                                                <td class="px-4 py-3">{{ $sensor['tanggal'] }}</td>
                                                <td class="px-4 py-3">{{ $sensor['jam'] }}</td>
                                                <td class="px-4 py-3">{{ $currentHeight }}</td>
                                                <td class="px-4 py-3">
                                                    <span class="{{ $difference > 0 ? 'text-red-600' : ($difference < 0 ? 'text-green-600' : 'text-gray-500') }}">
                                                        {{ $differenceText }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3">{{ $sensor['curah_hujan'] }}</td>
                                                <td class="px-4 py-3">{{ $sensor['debit_air'] }}</td>
                                                <td class="px-4 py-3">
                                                    <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $badgeColor }}">
                                                        {{ ucfirst($status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-4 py-3 text-center text-gray-500">Tidak ada data sensor.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="flex flex-col items-center justify-between p-4 mx-4 space-y-3 bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row md:space-y-0 md:space-x-4">
            <div wire:ignore id="map" class="rounded-lg">
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        #map {
            height: 50vh;
            width: 100%;
        }
    </style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map = L.map('map').setView([-6.9883627, 107.6338651], 14);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Icon marker khusus
        const icons = {
            gateway: L.divIcon({
                className: 'custom-icon',
                html: '<div class="w-4 h-4 bg-yellow-400 border border-yellow-600"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10],
            }),
            node: L.divIcon({
                className: 'custom-icon',
                html: '<div class="w-4 h-4 bg-indigo-500 border border-indigo-700 rounded-full"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10],
            })
        };

        // Ambil data dari PHP
        const perangkatData = @json($perangkatSensor);

        perangkatData.forEach(perangkat => {
            const lat = parseFloat(perangkat.latitude);
            const lng = parseFloat(perangkat.longitude);

            if (!isNaN(lat) && !isNaN(lng)) {
                const icon = icons[perangkat.tipe] || icons.node;

                let popupContent = `
                    <strong>${perangkat.nama_perangkat}</strong><br>
                    ${perangkat.lokasi}<br>
                    <span class="text-sm text-gray-600">Tipe: ${perangkat.tipe}</span><br>
                    <span class="text-sm text-gray-600">Status: ${perangkat.status}</span>
                `;

                if (perangkat.latest_sensor_data && perangkat.latest_sensor_data.status_monitor) {
                    const status = perangkat.latest_sensor_data.status_monitor.status_banjir;
                    popupContent += `<br><span class="text-sm font-semibold">Status Banjir: ${status}</span>`;
                }

                L.marker([lat, lng], { icon }).addTo(map).bindPopup(popupContent);
            }
        });
    });
</script>
@endpush

