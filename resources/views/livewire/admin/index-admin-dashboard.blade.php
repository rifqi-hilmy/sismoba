<div>
    <div class="mx-auto">
        <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
            <div class="w-full md:w-1/2">
                <h2 class="text-4xl font-extrabold">Dashboard</h2>
            </div>
            <div class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
                <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <svg class="w-6 h-6 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <span id="current-time" class="font-medium text-gray-500 text-large ms-1 md:ms-2">
                            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </span>
                    </li>
                </ol>
                </nav>
            </div>
        </div>
        <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
            <div class="grid w-full grid-cols-1 gap-4 mb-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Tampilan 1: Carousel Perangkat dengan Peta Leaflet dan Status Banjir -->
                <div class="col-span-2 row-span-2 p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <p class="mb-4 text-xl font-bold">Lokasi Perangkat & Status Banjir</p>

                    @if($isLoading)
                        <div class="flex items-center justify-center h-96">
                            <svg class="w-8 h-8 text-blue-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="mt-2 text-gray-600">Memuat data perangkat...</p>
                        </div>
                    @elseif(count($perangkat) > 0)
                        <div id="carousel-perangkat-maps" class="relative w-full overflow-hidden" style="height: 500px;">
                            <!-- Carousel wrapper -->
                            <div class="relative h-full transition-transform duration-500 ease-in-out">
                                @foreach($perangkat as $index => $p)
                                <div class="absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-carousel-item="{{ $index }}">
                                    <div class="flex flex-col h-full gap-4 md:flex-row">
                                        <!-- Informasi Perangkat -->
                                        <div class="w-full p-4 rounded-lg md:w-1/3 bg-gray-50">
                                            <div class="flex items-center mb-4">
                                                <div class="flex items-center justify-center w-10 h-10 mr-3 bg-blue-100 rounded-full">
                                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="text-lg font-bold text-gray-800">{{ $p['nama_perangkat'] }}</h3>
                                                    <p class="text-sm text-gray-600">{{ $p['lokasi'] }}</p>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <p class="mb-1 text-sm font-medium text-gray-500">Status Perangkat</p>
                                                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                                                    {{ $p['status'] === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($p['status']) }}
                                                </span>
                                            </div>

                                            <div class="mb-4">
                                                <p class="mb-1 text-sm font-medium text-gray-500">Tipe Perangkat</p>
                                                <span class="inline-block px-3 py-1 text-sm font-semibold text-blue-800 bg-blue-100 rounded-full">
                                                    {{ ucfirst($p['tipe']) }}
                                                </span>
                                            </div>

                                            @if(count($p['sensor_data']) > 0)
                                                @php
                                                    $latestData = $p['sensor_data'][0];
                                                    $status = $latestData['status_monitor']['status_banjir'] ?? null;
                                                    $colorClass = '';
                                                    $statusText = 'Tidak ada status';

                                                    if ($status === 'bahaya') {
                                                        $colorClass = 'bg-red-100 text-red-800';
                                                        $statusText = 'Banjir Bahaya';
                                                    } elseif ($status === 'siaga 1') {
                                                        $colorClass = 'bg-orange-100 text-orange-800';
                                                        $statusText = 'Siaga 1';
                                                    } elseif ($status === 'siaga 2') {
                                                        $colorClass = 'bg-yellow-100 text-yellow-800';
                                                        $statusText = 'Siaga 2';
                                                    } else {
                                                        $colorClass = 'bg-gray-100 text-gray-800';
                                                        $statusText = 'Normal';
                                                    }
                                                @endphp
                                                <div class="mb-4">
                                                    <p class="mb-1 text-sm font-medium text-gray-500">Status Banjir Terkini</p>
                                                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full {{ $colorClass }}">
                                                        {{ $statusText }}
                                                    </span>
                                                </div>

                                                <div class="mb-4">
                                                    <p class="mb-1 text-sm font-medium text-gray-500">Terakhir Update</p>
                                                    <p class="text-sm text-gray-800">
                                                        {{ \Carbon\Carbon::parse($latestData['created_at'])->translatedFormat('d F Y H:i') }}
                                                    </p>
                                                </div>

                                                <div class="grid grid-cols-3 gap-2 mb-4">
                                                    <div class="p-2 text-center rounded bg-blue-50">
                                                        <p class="text-xs text-gray-500">Ketinggian</p>
                                                        <p class="font-bold text-blue-600">{{ number_format($latestData['ketinggian_air'], 1) }} cm</p>
                                                    </div>
                                                    <div class="p-2 text-center rounded bg-green-50">
                                                        <p class="text-xs text-gray-500">Curah Hujan</p>
                                                        <p class="font-bold text-green-600">{{ number_format($latestData['curah_hujan'], 1) }} mm</p>
                                                    </div>
                                                    <div class="p-2 text-center rounded bg-purple-50">
                                                        <p class="text-xs text-gray-500">Debit Air</p>
                                                        <p class="font-bold text-purple-600">{{ number_format($latestData['debit_air'], 1) }} ml/s</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="p-4 text-center rounded bg-gray-50">
                                                    <p class="text-gray-500">Tidak ada data sensor</p>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Peta Lokasi Leaflet -->
                                        <div class="w-full overflow-hidden rounded-lg md:w-2/3">
                                            <div id="map-{{ $index }}" class="w-full h-full border border-gray-200 rounded-lg"></div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Slider indicators -->
                            {{-- <div class="absolute z-30 flex space-x-3 -translate-x-1/2 rtl:space-x-reverse bottom-5 left-1/2">
                                @foreach($perangkat as $index => $p)
                                <button type="button"
                                        class="w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400 {{ $index === 0 ? 'bg-blue-600' : '' }}"
                                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $index + 1 }}"
                                        data-carousel-slide-to="{{ $index }}"></button>
                                @endforeach
                            </div> --}}

                            <!-- Slider controls -->
                            <button type="button"
                                    class="absolute z-30 flex items-center justify-center w-10 h-10 -translate-y-1/2 rounded-full cursor-pointer top-1/2 start-0 bg-white/30 hover:bg-white/50 group focus:outline-none"
                                    data-carousel-prev="maps">
                                <svg class="w-4 h-4 text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                                </svg>
                                <span class="sr-only">Previous</span>
                            </button>
                            <button type="button"
                                    class="absolute z-30 flex items-center justify-center w-10 h-10 -translate-y-1/2 rounded-full cursor-pointer top-1/2 end-0 bg-white/30 hover:bg-white/50 group focus:outline-none"
                                    data-carousel-next="maps">
                                <svg class="w-4 h-4 text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="sr-only">Next</span>
                            </button>
                        </div>
                    @else
                        <div class="flex items-center justify-center text-gray-500 h-96">
                            <p>Tidak ada perangkat yang terhubung</p>
                        </div>
                    @endif
                </div>


                <!-- Tampilan 2: Carousel Radial Bar Ketinggian Air -->
                <div class="col-span-2 p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <p class="mb-4 text-xl font-bold text-center">Ketinggian Air</p>
                    @if(!$isLoading && count($perangkat) > 0)
                        <div id="carousel-ketinggian" class="relative w-full overflow-hidden" style="height: 300px;">
                            <!-- Carousel wrapper -->
                            <div class="relative h-full transition-transform duration-500 ease-in-out">
                                @foreach($perangkat as $index => $p)
                                <div class="absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-carousel-item="{{ $index }}">
                                    <div class="flex flex-col items-center justify-center h-full p-4">
                                        @if(count($p['sensor_data']) > 0)
                                            <div id="radial-ketinggian-{{ $index }}" class="w-full h-48"></div>
                                            <div class="mt-4 text-center">
                                                <p class="text-lg font-semibold text-gray-800">
                                                    {{ $p['nama_perangkat'] }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $p['lokasi'] }}
                                                </p>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($p['sensor_data'][0]['created_at'])->translatedFormat('d F Y H:i') }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center h-48 text-gray-500">
                                                <p>Tidak ada data sensor</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Slider indicators -->
                            {{-- <div class="absolute z-30 flex space-x-3 -translate-x-1/2 rtl:space-x-reverse bottom-5 left-1/2">
                                @foreach($perangkat as $index => $p)
                                <button type="button"
                                        class="w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400 {{ $index === 0 ? 'bg-blue-600' : '' }}"
                                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $index + 1 }}"
                                        data-carousel-slide-to="{{ $index }}"></button>
                                @endforeach
                            </div> --}}

                            <!-- Slider controls -->
                            <button type="button"
                                    class="absolute z-30 flex items-center justify-center w-8 h-8 -translate-y-1/2 rounded-full cursor-pointer top-1/2 start-0 bg-white/30 hover:bg-white/50 group focus:outline-none"
                                    data-carousel-prev>
                                <svg class="w-4 h-4 text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                                </svg>
                                <span class="sr-only">Previous</span>
                            </button>
                            <button type="button"
                                    class="absolute z-30 flex items-center justify-center w-8 h-8 -translate-y-1/2 rounded-full cursor-pointer top-1/2 end-0 bg-white/30 hover:bg-white/50 group focus:outline-none"
                                    data-carousel-next>
                                <svg class="w-4 h-4 text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="sr-only">Next</span>
                            </button>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-48 text-gray-500">
                            <p>{{ $isLoading ? 'Memuat data...' : 'Tidak ada perangkat' }}</p>
                        </div>
                    @endif
                </div>

                <!-- Tampilan 3: Carousel Radial Bar Curah Hujan -->
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <p class="mb-4 text-xl font-bold text-center">Curah Hujan</p>
                    @if(!$isLoading && count($perangkat) > 0)
                        <div id="carousel-curah" class="relative w-full overflow-hidden" style="height: 300px;">
                            <!-- Carousel wrapper -->
                            <div class="relative h-full transition-transform duration-500 ease-in-out">
                                @foreach($perangkat as $index => $p)
                                <div class="absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-carousel-item="{{ $index }}">
                                    <div class="flex flex-col items-center justify-center h-full p-4">
                                        @if(count($p['sensor_data']) > 0)
                                            <div id="radial-curah-{{ $index }}" class="w-full h-48"></div>
                                            <div class="mt-4 text-center">
                                                <p class="text-lg font-semibold text-gray-800">
                                                    {{ $p['nama_perangkat'] }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $p['lokasi'] }}
                                                </p>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($p['sensor_data'][0]['created_at'])->translatedFormat('d F Y H:i') }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center h-48 text-gray-500">
                                                <p>Tidak ada data sensor</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Slider indicators -->
                            {{-- <div class="absolute z-30 flex space-x-3 -translate-x-1/2 rtl:space-x-reverse bottom-5 left-1/2">
                                @foreach($perangkat as $index => $p)
                                <button type="button"
                                        class="w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400 {{ $index === 0 ? 'bg-blue-600' : '' }}"
                                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $index + 1 }}"
                                        data-carousel-slide-to="{{ $index }}"></button>
                                @endforeach
                            </div> --}}

                            <!-- Slider controls -->
                            <button type="button"
                                    class="absolute z-30 flex items-center justify-center w-8 h-8 -translate-y-1/2 rounded-full cursor-pointer top-1/2 start-0 bg-white/30 hover:bg-white/50 group focus:outline-none"
                                    data-carousel-prev>
                                <svg class="w-4 h-4 text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                                </svg>
                                <span class="sr-only">Previous</span>
                            </button>
                            <button type="button"
                                    class="absolute z-30 flex items-center justify-center w-8 h-8 -translate-y-1/2 rounded-full cursor-pointer top-1/2 end-0 bg-white/30 hover:bg-white/50 group focus:outline-none"
                                    data-carousel-next>
                                <svg class="w-4 h-4 text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="sr-only">Next</span>
                            </button>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-48 text-gray-500">
                            <p>{{ $isLoading ? 'Memuat data...' : 'Tidak ada perangkat' }}</p>
                        </div>
                    @endif
                </div>

                <!-- Tampilan 4: Carousel Radial Bar Debit Air -->
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <p class="mb-4 text-xl font-bold text-center">Debit Air</p>
                    @if(!$isLoading && count($perangkat) > 0)
                        <div id="carousel-debit" class="relative w-full overflow-hidden" style="height: 300px;">
                            <!-- Carousel wrapper -->
                            <div class="relative h-full transition-transform duration-500 ease-in-out">
                                @foreach($perangkat as $index => $p)
                                <div class="absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-carousel-item="{{ $index }}">
                                    <div class="flex flex-col items-center justify-center h-full p-4">
                                        @if(count($p['sensor_data']) > 0)
                                            <div id="radial-debit-{{ $index }}" class="w-full h-48"></div>
                                            <div class="mt-4 text-center">
                                                <p class="text-lg font-semibold text-gray-800">
                                                    {{ $p['nama_perangkat'] }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $p['lokasi'] }}
                                                </p>
                                                <p class="mt-1 text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($p['sensor_data'][0]['created_at'])->translatedFormat('d F Y H:i') }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center h-48 text-gray-500">
                                                <p>Tidak ada data sensor</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Slider indicators -->
                            {{-- <div class="absolute z-30 flex space-x-3 -translate-x-1/2 rtl:space-x-reverse bottom-5 left-1/2">
                                @foreach($perangkat as $index => $p)
                                <button type="button"
                                        class="w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400 {{ $index === 0 ? 'bg-blue-600' : '' }}"
                                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $index + 1 }}"
                                        data-carousel-slide-to="{{ $index }}"></button>
                                @endforeach
                            </div> --}}

                            <!-- Slider controls -->
                            <button type="button"
                                    class="absolute z-30 flex items-center justify-center w-8 h-8 -translate-y-1/2 rounded-full cursor-pointer top-1/2 start-0 bg-white/30 hover:bg-white/50 group focus:outline-none"
                                    data-carousel-prev>
                                <svg class="w-4 h-4 text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                                </svg>
                                <span class="sr-only">Previous</span>
                            </button>
                            <button type="button"
                                    class="absolute z-30 flex items-center justify-center w-8 h-8 -translate-y-1/2 rounded-full cursor-pointer top-1/2 end-0 bg-white/30 hover:bg-white/50 group focus:outline-none"
                                    data-carousel-next>
                                <svg class="w-4 h-4 text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="sr-only">Next</span>
                            </button>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-48 text-gray-500">
                            <p>{{ $isLoading ? 'Memuat data...' : 'Tidak ada perangkat' }}</p>
                        </div>
                    @endif
                </div>
                <!-- Tampilan 5: Carousel Timeline Status Banjir -->
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <p class="mb-4 text-xl font-bold">Timeline Status Banjir</p>

                    @if($isLoading)
                        <div class="flex items-center justify-center h-48 text-gray-500">
                            <svg class="w-8 h-8 text-blue-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="mt-2 text-gray-600">Memuat data timeline...</p>
                        </div>
                    @elseif(count($perangkat) > 0)
                        <div id="carousel-timeline" class="relative w-full overflow-hidden" style="height: 400px;">
                            <!-- Carousel wrapper -->
                            <div class="relative h-full transition-transform duration-500 ease-in-out">
                                @foreach($perangkat as $index => $p)
                                <div class="absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-carousel-item="{{ $index }}">
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-800">
                                            {{ $p['nama_perangkat'] }} - {{ $p['lokasi'] }}
                                        </h3>
                                        <p class="mb-3 text-sm text-gray-500">
                                            Timeline status banjir
                                        </p>

                                        @if(count($p['sensor_data']) > 0)
                                            <ol class="relative border-gray-200 border-s">
                                                @foreach(array_slice($p['sensor_data'], 0, 3) as $data)
                                                <li class="mb-4 ms-4">
                                                    <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -start-1.5 border border-white"></div>
                                                    <time class="mb-1 text-sm font-normal leading-none text-gray-400">
                                                        {{ \Carbon\Carbon::parse($data['created_at'])->translatedFormat('d F Y H:i') }}
                                                    </time>
                                                    <p class="text-base font-normal text-gray-500">
                                                        Ketinggian: {{ number_format($data['ketinggian_air'], 1) }} cm,
                                                        Curah Hujan: {{ number_format($data['curah_hujan'], 1) }} mm,
                                                        Debit: {{ number_format($data['debit_air'], 1) }} ml/s
                                                    </p>
                                                    <span class="inline-block mt-1">
                                                        @if(isset($data['status_monitor']['status_banjir']))
                                                            @php
                                                                $status = $data['status_monitor']['status_banjir'];
                                                                $colorClass = '';
                                                                if ($status === 'bahaya') {
                                                                    $colorClass = 'bg-red-100 text-red-800';
                                                                } elseif ($status === 'siaga 1') {
                                                                    $colorClass = 'bg-orange-100 text-orange-800';
                                                                } elseif ($status === 'siaga 2') {
                                                                    $colorClass = 'bg-yellow-100 text-yellow-800';
                                                                } else {
                                                                    $colorClass = 'bg-gray-100 text-gray-800';
                                                                }
                                                            @endphp
                                                            <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $colorClass }}">
                                                                {{ ucfirst($status) }}
                                                            </span>
                                                        @else
                                                            <span class="text-xs font-medium px-2.5 py-0.5 rounded bg-gray-100 text-gray-800">
                                                                Tidak ada status
                                                            </span>
                                                        @endif
                                                    </span>
                                                </li>
                                                @endforeach
                                            </ol>
                                        @else
                                            <div class="flex items-center justify-center h-32 text-gray-500">
                                                <p>Tidak ada data sensor</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Slider indicators -->
                            {{-- <div class="absolute z-30 flex space-x-3 -translate-x-1/2 rtl:space-x-reverse bottom-5 left-1/2">
                                @foreach($perangkat as $index => $p)
                                <button type="button"
                                        class="w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400 {{ $index === 0 ? 'bg-blue-600' : '' }}"
                                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $index + 1 }}"
                                        data-carousel-slide-to="{{ $index }}"></button>
                                @endforeach
                            </div> --}}

                            <!-- Slider controls -->
                            <button type="button"
                                    class="absolute z-30 flex items-center justify-center w-8 h-8 -translate-y-1/2 rounded-full cursor-pointer top-1/2 start-0 bg-white/30 hover:bg-white/50 group focus:outline-none"
                                    data-carousel-prev="timeline">
                                <svg class="w-4 h-4 text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                                </svg>
                                <span class="sr-only">Previous</span>
                            </button>
                            <button type="button"
                                    class="absolute z-30 flex items-center justify-center w-8 h-8 -translate-y-1/2 rounded-full cursor-pointer top-1/2 end-0 bg-white/30 hover:bg-white/50 group focus:outline-none"
                                    data-carousel-next="timeline">
                                <svg class="w-4 h-4 text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="sr-only">Next</span>
                            </button>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-48 text-gray-500">
                            <p>Tidak ada perangkat yang terhubung</p>
                        </div>
                    @endif
                </div>
                <!-- Tampilan 6: Bar Chart Statistik Perangkat -->
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="flex justify-between pb-4 mb-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg me-3">
                                <svg class="w-6 h-6 text-blue-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h5 class="pb-1 text-2xl font-bold leading-none text-gray-900">{{ $totalPerangkat }}</h5>
                                <p class="text-sm font-normal text-gray-500">Total Perangkat</p>
                            </div>
                        </div>
                        <div>
                            <span class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md">
                                {{ $totalPerangkat > 0 ? round(($totalAktif / $totalPerangkat) * 100, 1) : 0 }}% Aktif
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 mb-4">
                        <dl class="flex items-center">
                            <dt class="text-sm font-normal text-gray-500 me-1">Aktif:</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $totalAktif }}</dd>
                        </dl>
                        <dl class="flex items-center justify-end">
                            <dt class="text-sm font-normal text-gray-500 me-1">Nonaktif:</dt>
                            <dd class="text-sm font-semibold text-gray-900">{{ $totalNonaktif }}</dd>
                        </dl>
                    </div>
                    <div id="bar-chart-perangkat"></div>
                </div>
                <!-- Tampilan 7: Carousel Perangkat -->
                <div class="col-span-2 p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <p class="mb-4 text-2xl font-bold">Monitoring Perangkat</p>

                    @if($isLoading)
                        <div class="py-8 text-center">
                            <svg class="w-8 h-8 mx-auto text-blue-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="mt-2 text-gray-600">Memuat data perangkat...</p>
                        </div>
                    @elseif(count($perangkat) > 0)
                        <div id="perangkat-carousel" class="relative w-full" data-carousel="static">
                            <!-- Carousel wrapper -->
                            <div class="relative h-[500px] transition-transform duration-500 ease-in-out">
                                @foreach($perangkat as $index => $p)
                                <div class="absolute inset-0 w-full h-full transition-opacity duration-500 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" data-carousel-item="{{ $index }}">
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-800">
                                            {{ $p['nama_perangkat'] }} - {{ $p['lokasi'] }}
                                        </h3>
                                        <p class="mb-3 text-sm text-gray-500">
                                            Terakhir update:
                                            @if(count($p['sensor_data']) > 0)
                                                {{ \Carbon\Carbon::parse($p['sensor_data'][0]['created_at'])->translatedFormat('d F Y H:i') }}
                                            @else
                                                -
                                            @endif
                                        </p>

                                        <div class="flex-grow overflow-x-auto">
                                            <table class="w-full text-sm text-left text-gray-500">
                                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-3">Tanggal</th>
                                                        <th class="px-4 py-3">Jam</th>
                                                        <th class="px-4 py-3">Ketinggian (cm)</th>
                                                        <th class="px-4 py-3">Curah Hujan (mm)</th>
                                                        <th class="px-4 py-3">Debit Air (ml/s)</th>
                                                        <th class="px-4 py-3">Status Banjir</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse(array_slice($p['sensor_data'], 0, 10) as $data)
                                                    <tr class="bg-white border-b hover:bg-gray-50">
                                                        <td class="px-4 py-3">{{ $data['tanggal'] }}</td>
                                                        <td class="px-4 py-3">{{ substr($data['jam'], 0, 5) }}</td>
                                                        <td class="px-4 py-3">{{ number_format($data['ketinggian_air'], 1) }}</td>
                                                        <td class="px-4 py-3">{{ number_format($data['curah_hujan'], 1) }}</td>
                                                        <td class="px-4 py-3">{{ number_format($data['debit_air'], 1) }}</td>
                                                        <td class="px-4 py-3">
                                                            @if(isset($data['status_monitor']['status_banjir']))
                                                                @php
                                                                    $status = $data['status_monitor']['status_banjir'];
                                                                    $colorClass = '';
                                                                    if ($status === 'bahaya') {
                                                                        $colorClass = 'bg-red-100 text-red-800';
                                                                    } elseif ($status === 'siaga 1') {
                                                                        $colorClass = 'bg-orange-100 text-orange-800';
                                                                    } elseif ($status === 'siaga 2') {
                                                                        $colorClass = 'bg-yellow-100 text-yellow-800';
                                                                    } else {
                                                                        $colorClass = 'bg-gray-100 text-gray-800';
                                                                    }
                                                                @endphp
                                                                <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $colorClass }}">
                                                                    {{ ucfirst($status) }}
                                                                </span>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                                                            Tidak ada data sensor
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Slider indicators -->
                            {{-- <div class="absolute z-30 flex space-x-3 -translate-x-1/2 rtl:space-x-reverse bottom-5 left-1/2">
                                @foreach($perangkat as $index => $p)
                                <button type="button"
                                        class="w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400 {{ $index === 0 ? 'bg-blue-600' : '' }}"
                                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $index + 1 }}"
                                        data-carousel-slide-to="{{ $index }}"></button>
                                @endforeach
                            </div> --}}

                            <!-- Slider controls -->
                            <button type="button"
                                    class="absolute z-30 flex items-center justify-center w-10 h-10 -translate-y-1/2 rounded-full cursor-pointer top-1/2 start-0 bg-white/30 hover:bg-white/50 group focus:outline-none"
                                    data-carousel-prev>
                                <svg class="w-4 h-4 text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                                </svg>
                                <span class="sr-only">Previous</span>
                            </button>
                            <button type="button"
                                    class="absolute z-30 flex items-center justify-center w-10 h-10 -translate-y-1/2 rounded-full cursor-pointer top-1/2 end-0 bg-white/30 hover:bg-white/50 group focus:outline-none"
                                    data-carousel-next>
                                <svg class="w-4 h-4 text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="sr-only">Next</span>
                            </button>
                        </div>
                    @else
                        <div class="p-8 text-center text-gray-500">
                            <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="mt-4">Tidak ada perangkat yang terhubung</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.getElementById('perangkat-carousel');
        if (carousel) {
            const items = carousel.querySelectorAll('[data-carousel-item]');
            const indicators = carousel.querySelectorAll('[data-carousel-slide-to]');
            const prevButton = carousel.querySelector('[data-carousel-prev]');
            const nextButton = carousel.querySelector('[data-carousel-next]');

            let currentIndex = 0;
            const totalItems = items.length;

            function showSlide(index) {
                // Sembunyikan semua slide
                items.forEach(item => {
                    item.classList.remove('opacity-100', 'z-10');
                    item.classList.add('opacity-0', 'z-0');
                });

                // Tampilkan slide yang dipilih
                items[index].classList.remove('opacity-0', 'z-0');
                items[index].classList.add('opacity-100', 'z-10');

                // Update indikator
                indicators.forEach((indicator, i) => {
                    indicator.classList.toggle('bg-blue-600', i === index);
                    indicator.classList.toggle('bg-gray-300', i !== index);
                });

                currentIndex = index;
            }

            // Navigasi slide
            prevButton.addEventListener('click', () => {
                const newIndex = (currentIndex - 1 + totalItems) % totalItems;
                showSlide(newIndex);
            });

            nextButton.addEventListener('click', () => {
                const newIndex = (currentIndex + 1) % totalItems;
                showSlide(newIndex);
            });

            // Navigasi melalui indikator
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    showSlide(index);
                });
            });
        }
    });
</script>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
<script>
    // Fungsi untuk menginisialisasi radial bar chart
    function initRadialChart(elementId, value, max, label, color) {
        const options = {
            series: [value],
            chart: {
                height: '100%',
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    startAngle: -135,
                    endAngle: 135,
                    track: {
                        background: '#e7e7e7',
                        strokeWidth: '97%',
                        margin: 5,
                        dropShadow: {
                            enabled: true,
                            top: 2,
                            left: 0,
                            blur: 4,
                            opacity: 0.15
                        }
                    },
                    dataLabels: {
                        name: {
                            offsetY: -15,
                            fontSize: '14px',
                            color: '#888',
                            fontWeight: 600
                        },
                        value: {
                            offsetY: 20,
                            fontSize: '24px',
                            fontWeight: 700,
                            formatter: function(val) {
                                return val.toFixed(1);
                            }
                        }
                    },
                    hollow: {
                        margin: 15,
                        size: '65%'
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    shadeIntensity: 0.15,
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 50, 65, 91]
                },
            },
            stroke: {
                dashArray: 4
            },
            labels: [label],
            colors: [color],
        };

        return new ApexCharts(document.querySelector(elementId), options);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi chart untuk semua perangkat
        @if(!$isLoading && count($perangkat) > 0)
            const perangkat = @json($perangkat);

            perangkat.forEach((device, index) => {
                if (device.sensor_data.length > 0) {
                    const latestData = device.sensor_data[0];

                    // Ketinggian air (max 30 cm)
                    const ketinggianValue = Math.min(latestData.ketinggian_air, 30);
                    const chartKetinggian = initRadialChart(
                        `#radial-ketinggian-${index}`,
                        ketinggianValue,
                        30,
                        'Ketinggian (cm)',
                        '#3B82F6'
                    );
                    chartKetinggian.render();

                    // Curah hujan (max 100 mm)
                    const curahValue = Math.min(latestData.curah_hujan, 100);
                    const chartCurah = initRadialChart(
                        `#radial-curah-${index}`,
                        curahValue,
                        100,
                        'Curah Hujan (mm)',
                        '#10B981'
                    );
                    chartCurah.render();

                    // Debit air (max 20 ml/s)
                    const debitValue = Math.min(latestData.debit_air, 20);
                    const chartDebit = initRadialChart(
                        `#radial-debit-${index}`,
                        debitValue,
                        20,
                        'Debit Air (ml/s)',
                        '#EF4444'
                    );
                    chartDebit.render();
                }
            });

            // Inisialisasi carousel untuk ketinggian, curah, dan debit
            ['ketinggian', 'curah', 'debit'].forEach(type => {
                const carousel = document.getElementById(`carousel-${type}`);
                if (carousel) {
                    const items = carousel.querySelectorAll('[data-carousel-item]');
                    const indicators = carousel.querySelectorAll('[data-carousel-slide-to]');
                    const prevButton = carousel.querySelector('[data-carousel-prev]');
                    const nextButton = carousel.querySelector('[data-carousel-next]');

                    let currentIndex = 0;

                    function showSlide(index) {
                        items.forEach(item => {
                            item.classList.remove('opacity-100', 'z-10');
                            item.classList.add('opacity-0', 'z-0');
                        });

                        items[index].classList.remove('opacity-0', 'z-0');
                        items[index].classList.add('opacity-100', 'z-10');

                        indicators.forEach((indicator, i) => {
                            indicator.classList.toggle('bg-blue-600', i === index);
                            indicator.classList.toggle('bg-gray-300', i !== index);
                        });

                        currentIndex = index;
                    }

                    showSlide(0);

                    prevButton.addEventListener('click', () => {
                        const newIndex = (currentIndex - 1 + items.length) % items.length;
                        showSlide(newIndex);
                    });

                    nextButton.addEventListener('click', () => {
                        const newIndex = (currentIndex + 1) % items.length;
                        showSlide(newIndex);
                    });

                    indicators.forEach((indicator, index) => {
                        indicator.addEventListener('click', () => {
                            showSlide(index);
                        });
                    });
                }
            });
        @endif
    });
</script>
@endpush
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi carousel timeline
        const carouselTimeline = document.getElementById('carousel-timeline');
        if (carouselTimeline) {
            const items = carouselTimeline.querySelectorAll('[data-carousel-item]');
            const indicators = carouselTimeline.querySelectorAll('[data-carousel-slide-to]');
            const prevButton = carouselTimeline.querySelector('[data-carousel-prev="timeline"]');
            const nextButton = carouselTimeline.querySelector('[data-carousel-next="timeline"]');

            let currentIndex = 0;
            const totalItems = items.length;

            function showSlide(index) {
                // Sembunyikan semua slide
                items.forEach(item => {
                    item.classList.remove('opacity-100', 'z-10');
                    item.classList.add('opacity-0', 'z-0');
                });

                // Tampilkan slide yang dipilih
                items[index].classList.remove('opacity-0', 'z-0');
                items[index].classList.add('opacity-100', 'z-10');

                // Update indikator
                indicators.forEach((indicator, i) => {
                    indicator.classList.toggle('bg-blue-600', i === index);
                    indicator.classList.toggle('bg-gray-300', i !== index);
                });

                currentIndex = index;
            }

            // Navigasi slide
            prevButton.addEventListener('click', () => {
                const newIndex = (currentIndex - 1 + totalItems) % totalItems;
                showSlide(newIndex);
            });

            nextButton.addEventListener('click', () => {
                const newIndex = (currentIndex + 1) % totalItems;
                showSlide(newIndex);
            });

            // Navigasi melalui indikator
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    showSlide(index);
                });
            });
        }
    });
</script>
@endpush
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi bar chart statistik perangkat
        const perangkatData = {
            total: @json($totalPerangkat),
            aktif: @json($totalAktif),
            nonaktif: @json($totalNonaktif)
        };

        const options = {
            series: [{
                name: "Jumlah",
                data: [
                    perangkatData.total,
                    perangkatData.aktif,
                    perangkatData.nonaktif
                ]
            }],
            chart: {
                type: 'bar',
                height: '100%',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '70%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['Total Perangkat', 'Aktif', 'Nonaktif'],
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: 400
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Jumlah'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " perangkat"
                    }
                }
            },
            colors: ["#1A56DB"]
        };

        const chartElement = document.querySelector("#bar-chart-perangkat");
        if (chartElement) {
            const chart = new ApexCharts(chartElement, options);
            chart.render();
        }
    });
</script>
@endpush
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>
<style>
    .leaflet-container {
        background-color: #e9ecef !important;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    .leaflet-popup-content {
        margin: 8px 12px;
        font-size: 14px;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi carousel peta
        const carouselMaps = document.getElementById('carousel-perangkat-maps');
        if (carouselMaps) {
            const items = carouselMaps.querySelectorAll('[data-carousel-item]');
            const indicators = carouselMaps.querySelectorAll('[data-carousel-slide-to]');
            const prevButton = carouselMaps.querySelector('[data-carousel-prev="maps"]');
            const nextButton = carouselMaps.querySelector('[data-carousel-next="maps"]');

            let currentIndex = 0;
            const totalItems = items.length;
            const maps = []; // Untuk menyimpan instance peta

            // Fungsi untuk menampilkan slide
            function showSlide(index) {
                // Sembunyikan semua slide
                items.forEach(item => {
                    item.classList.remove('opacity-100', 'z-10');
                    item.classList.add('opacity-0', 'z-0');
                });

                // Tampilkan slide yang dipilih
                items[index].classList.remove('opacity-0', 'z-0');
                items[index].classList.add('opacity-100', 'z-10');

                // Update indikator
                indicators.forEach((indicator, i) => {
                    indicator.classList.toggle('bg-blue-600', i === index);
                    indicator.classList.toggle('bg-gray-300', i !== index);
                });

                // Jika peta di slide ini belum diinisialisasi, inisialisasi
                if (maps[index] === undefined) {
                    initMap(index);
                } else {
                    // Jika sudah, trigger resize agar peta menyesuaikan
                    setTimeout(() => {
                        maps[index].invalidateSize();
                    }, 300);
                }

                currentIndex = index;
            }

            // Fungsi untuk menginisialisasi peta
            function initMap(index) {
                const perangkat = @json($perangkat);
                const device = perangkat[index];
                const lat = parseFloat(device.latitude);
                const lng = parseFloat(device.longitude);

                if (isNaN(lat) || isNaN(lng)) {
                    console.error(`Koordinat tidak valid untuk perangkat ${device.nama_perangkat}`);
                    return;
                }

                const mapElement = document.getElementById(`map-${index}`);
                if (!mapElement) return;

                // Inisialisasi peta
                const map = L.map(mapElement).setView([lat, lng], 15);

                // Tambahkan tile layer (peta dasar)
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // Tentukan warna marker berdasarkan status banjir
                let markerColor = 'blue';
                if (device.sensor_data && device.sensor_data.length > 0) {
                    const status = device.sensor_data[0].status_monitor?.status_banjir;
                    if (status === 'bahaya') markerColor = 'red';
                    else if (status === 'siaga 1') markerColor = 'orange';
                    else if (status === 'siaga 2') markerColor = 'yellow';
                }

                // Buat ikon kustom
                const customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color:${markerColor}; border-radius:50%; width:24px; height:24px; display:flex; align-items:center; justify-content:center; color:white; font-weight:bold; border:2px solid white; box-shadow:0 0 5px rgba(0,0,0,0.5);">
                            <span>${device.nama_perangkat.charAt(0)}</span>
                           </div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });

                // Tambahkan marker
                const marker = L.marker([lat, lng], {icon: customIcon}).addTo(map);

                // Tambahkan popup dengan informasi perangkat
                marker.bindPopup(`
                    <div class="text-lg font-bold">${device.nama_perangkat}</div>
                    <div class="text-sm">${device.lokasi}</div>
                    <div class="mt-2">
                        <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                              ${device.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            Status: ${device.status}
                        </span>
                    </div>
                    <div class="mt-2 text-sm">
                        <div>Lat: ${lat.toFixed(6)}</div>
                        <div>Lng: ${lng.toFixed(6)}</div>
                    </div>
                `);

                maps[index] = map;
            }

            // Navigasi slide
            prevButton.addEventListener('click', () => {
                const newIndex = (currentIndex - 1 + totalItems) % totalItems;
                showSlide(newIndex);
            });

            nextButton.addEventListener('click', () => {
                const newIndex = (currentIndex + 1) % totalItems;
                showSlide(newIndex);
            });

            // Navigasi melalui indikator
            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => {
                    showSlide(index);
                });
            });

            // Inisialisasi peta untuk slide pertama
            showSlide(0);
        }
    });
</script>
@endpush
@push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const timeElement = document.getElementById('current-time');

        function updateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };

            // Format: Hari, Tanggal Bulan Tahun Jam:Menit:Detik
            const timeString = now.toLocaleDateString('id-ID', options);
            timeElement.textContent = timeString;
        }

        // Update waktu setiap detik
        updateTime();
        setInterval(updateTime, 1000);
    });
</script>

@endpush
