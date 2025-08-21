<div>
    <div class="mx-auto">
        <div class="flex flex-col items-center justify-between p-4 mb-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
            <div class="w-full md:w-1/2">
                <h2 class="text-4xl font-extrabold">Detail Monitoring</h2>
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
                        <a href="{{ route('warga.monitoring.index') }}" class="text-sm font-medium text-gray-700 ms-1">Monitoring</a>
                    </div>
                    </li>
                    <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1 text-gray-400 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a class="text-sm font-medium text-gray-700 ms-1">Detail</a>
                    </div>
                    </li>
                </ol>
                </nav>
            </div>
        </div>
        <div class="relative overflow-hidden bg-white shadow-md sm:rounded-lg">
            <div class="mb-4 border-b border-gray-200">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-styled-tab" data-tabs-toggle="#default-styled-tab-content" data-tabs-active-classes="text-purple-600 hover:text-purple-600 border-purple-600" data-tabs-inactive-classes="text-gray-500 hover:text-gray-600 border-gray-100 hover:border-gray-300" role="tablist">
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-styled-tab" data-tabs-target="#styled-profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Informasi Perangkat</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="dashboard-styled-tab" data-tabs-target="#styled-dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">Data Telemetri</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="settings-styled-tab" data-tabs-target="#styled-settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Data Status</button>
                    </li>
                    <li role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="contacts-styled-tab" data-tabs-target="#styled-contacts" type="button" role="tab" aria-controls="contacts" aria-selected="false">Grafik</button>
                    </li>
                </ul>
            </div>
            <div id="default-styled-tab-content">
                <div class="hidden p-4 bg-white rounded-lg" id="styled-profile" role="tabpanel" aria-labelledby="profile-tab">
                    @if(!empty($sensorData))
                        @php
                            $first = $sensorData[0];
                        @endphp

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="p-5 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
                                <h4 class="mb-2 text-lg font-semibold text-gray-700">Nama Perangkat</h4>
                                <p class="text-gray-900">{{ $namaPerangkat ?? 'Tidak diketahui' }}</p>
                            </div>
                            <div class="p-5 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
                                <h4 class="mb-2 text-lg font-semibold text-gray-700">Lokasi</h4>
                                <p class="text-gray-900">{{ $lokasi ?? 'Tidak diketahui' }}</p>
                            </div>
                            <div class="p-5 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
                                <h4 class="mb-2 text-lg font-semibold text-gray-700">Status</h4>
                                <p class="text-gray-900 capitalize">{{ $status ?? 'Tidak diketahui' }}</p>
                            </div>
                            <div class="p-5 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
                                <h4 class="mb-2 text-lg font-semibold text-gray-700">Latitude</h4>
                                <p class="text-gray-900">{{ $latitude ?? '-' }}</p>
                            </div>
                            <div class="p-5 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
                                <h4 class="mb-2 text-lg font-semibold text-gray-700">Longitude</h4>
                                <p class="text-gray-900">{{ $longitude ?? '-' }}</p>
                            </div>
                            <div class="p-5 border border-gray-200 rounded-lg shadow-sm bg-gray-50">
                                <h4 class="mb-2 text-lg font-semibold text-gray-700">Terakhir Diperbarui</h4>
                                <p class="text-gray-900">{{ \Carbon\Carbon::parse($lastUpdate ?? now())->translatedFormat('d F Y H:i') }}</p>
                            </div>
                        </div>
                    @else
                        <div class="italic text-gray-500">Informasi perangkat tidak tersedia.</div>
                    @endif
                </div>
                <div class="hidden p-4 bg-white rounded-lg" id="styled-dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                    <div class="p-4 overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500" id="data-telemetri-table">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3">Tanggal</th>
                                        <th class="px-4 py-3">Jam</th>
                                        <th class="px-4 py-3">Ketinggian Air (cm)</th>
                                        <th class="px-4 py-3">Perubahan Ketinggian (cm)</th>
                                        <th class="px-4 py-3">Curah Hujan (mm)</th>
                                        <th class="px-4 py-3">Debit Air (ml/s)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sensorData as $data)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-4 py-3">{{ $data['tanggal'] }}</td>
                                            <td class="px-4 py-3">{{ $data['jam'] }}</td>
                                            <td class="px-4 py-3">{{ number_format($data['ketinggian_air'], 1) }}</td>
                                            <td class="px-4 py-3">
                                                @if($data['perubahan_ketinggian'] > 0)
                                                    <span class="text-red-500">+{{ number_format($data['perubahan_ketinggian'], 1) }}</span>
                                                @elseif($data['perubahan_ketinggian'] < 0)
                                                    <span class="text-green-500">{{ number_format($data['perubahan_ketinggian'], 1) }}</span>
                                                @else
                                                    {{ number_format($data['perubahan_ketinggian'], 1) }}
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">{{ number_format($data['curah_hujan'], 1) }}</td>
                                            <td class="px-4 py-3">{{ number_format($data['debit_air'], 1) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-4 text-center">
                                                @if(!$isLoading)
                                                    Tidak ada data sensor yang tersedia
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                    </div>
                </div>
                <div class="hidden p-4 bg-white rounded-lg" id="styled-settings" role="tabpanel" aria-labelledby="settings-tab">
                    <div class="p-4 overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500" id="data-status-table">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="px-4 py-3">Jam</th>
                                    <th class="px-4 py-3">Status Ketinggian Air</th>
                                    <th class="px-4 py-3">Status Curah Hujan</th>
                                    <th class="px-4 py-3">Status Debit Air</th>
                                    <th class="px-4 py-3">Status Banjir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sensorData as $data)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $data['tanggal'] }}</td>
                                        <td class="px-4 py-3">{{ $data['jam'] }}</td>
                                        @if(isset($data['status_monitor']))
                                            <td class="px-4 py-3">{{ $data['status_monitor']['status_ketinggian_air'] }}</td>
                                            <td class="px-4 py-3">{{ $data['status_monitor']['status_curah_hujan'] }}</td>
                                            <td class="px-4 py-3">{{ $data['status_monitor']['status_debit_air'] }}</td>
                                            <td class="px-4 py-3">
                                                @if($data['status_monitor']['status_banjir'] === 'bahaya')
                                                    <span class="font-semibold text-red-600 animate-pulse">{{ strtoupper($data['status_monitor']['status_banjir']) }}</span>
                                                @else
                                                    {{ ucfirst($data['status_monitor']['status_banjir']) }}
                                                @endif
                                            </td>
                                        @else
                                            <td colspan="4" class="px-4 py-3 italic text-center text-gray-400">Status belum tersedia</td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 text-center text-gray-500">Tidak ada data status tersedia</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="hidden p-4 bg-white rounded-lg" id="styled-contacts" role="tabpanel" aria-labelledby="contacts-tab">
                    @if (!empty($sensorData))
                        <div class="space-y-6">
                            <div class="max-w-full p-4 bg-white rounded-lg shadow-sm md:p-6">
                                <h2 class="mb-2 text-lg font-semibold text-gray-800">Ketinggian Air</h2>
                                <div id="chart-ketinggian-air" class="w-full h-64"></div>
                            </div>

                            <div class="max-w-full p-4 bg-white rounded-lg shadow-sm md:p-6">
                                <h2 class="mb-2 text-lg font-semibold text-gray-800">Curah Hujan</h2>
                                <div id="chart-curah-hujan" class="w-full h-64"></div>
                            </div>

                            <div class="max-w-full p-4 bg-white rounded-lg shadow-sm md:p-6">
                                <h2 class="mb-2 text-lg font-semibold text-gray-800">Debit Air</h2>
                                <div id="chart-debit-air" class="w-full h-64"></div>
                            </div>
                        </div>
                    @else
                        <p class="italic text-gray-500">Data sensor tidak tersedia.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = document.querySelector('#data-telemetri-table');
            if (table) {
                new simpleDatatables.DataTable(table, {
                    searchable: true,
                    perPage: 20,
                });
            }
            const statusTable = document.querySelector('#data-status-table');
            if (statusTable) {
                new simpleDatatables.DataTable(statusTable, {
                    searchable: true,
                    perPage: 20,
                });
            }
        });
    </script>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const data = @json($sensorData);

        const latestData = data.slice(0, 20);
        const jamLabels = latestData.map(d => d.jam);
        const ketinggian = latestData.map(d => d.ketinggian_air);
        const curahHujan = latestData.map(d => d.curah_hujan);
        const debitAir = latestData.map(d => d.debit_air);

        const buildChart = (elementId, label, color, values, thresholds = []) => {
            const annotations = thresholds.map(t => ({
                y: t.value,
                borderColor: t.color,
                label: {
                    text: t.label,
                    borderColor: t.color,
                    style: {
                        color: '#fff',
                        background: t.color,
                        fontSize: '10px'
                    }
                }
            }));

            const options = {
                chart: {
                    height: "100%",
                    maxWidth: "100%",
                    type: "area",
                    fontFamily: "Inter, sans-serif",
                    toolbar: { show: false }
                },
                series: [{
                    name: label,
                    data: values,
                    color: color,
                }],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        endingShape: 'rounded',
                    }
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        cssClass: 'text-xs text-white font-medium'
                    },
                    background: {
                        enabled: true,
                        foreColor: '#fff',
                        borderRadius: 4,
                        padding: 4,
                        opacity: 0.9,
                    }
                },
                grid: {
                    show: true,
                    strokeDashArray: 4,
                    padding: {
                        left: 10,
                        right: 10,
                        top: 0,
                    },
                },
                xaxis: {
                    categories: jamLabels,
                    title: {
                        text: 'Jam'
                    }
                },
                yaxis: {
                    title: {
                        text: label
                    }
                },
                tooltip: {
                    enabled: true
                },
                annotations: {
                    yaxis: annotations
                }
            };

            const chart = new ApexCharts(document.querySelector(elementId), options);
            chart.render();
        };

        const ketinggianThresholds = [
            { value: 5, label: 'Normal', color: '#22c55e' }, // green
            { value: 10, label: 'Waspada', color: '#eab308' }, // yellow
            { value: 15, label: 'Siaga', color: '#f97316' }, // orange
            { value: 16, label: 'Awas', color: '#ef4444' } // red
        ];

        const curahHujanThresholds = [
            { value: 5, label: 'Berawan', color: '#a3a3a3' },
            { value: 20, label: 'Hujan Ringan', color: '#60a5fa' },
            { value: 40, label: 'Hujan Sedang', color: '#3b82f6' },
            { value: 60, label: 'Hujan Lebat', color: '#1d4ed8' },
            { value: 61, label: 'Sangat Lebat', color: '#7e22ce' }
        ];

        const debitAirThresholds = [
            { value: 6, label: 'Lambat', color: '#22c55e' },
            { value: 12, label: 'Rata-rata', color: '#eab308' },
            { value: 13, label: 'Cepat', color: '#ef4444' }
        ];


        buildChart("#chart-ketinggian-air", "Ketinggian Air (cm)", "#1A56DB", ketinggian, ketinggianThresholds);
        buildChart("#chart-curah-hujan", "Curah Hujan (mm)", "#F59E0B", curahHujan, curahHujanThresholds);
        buildChart("#chart-debit-air", "Debit Air (ml/detik)", "#10B981", debitAir, debitAirThresholds);

    });
</script>
@endpush
