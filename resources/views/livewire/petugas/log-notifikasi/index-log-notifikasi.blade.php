<div wire:poll.5s="refreshData">
    <div class="mx-auto">
        @if ($errors->any())
            <div class="relative px-4 py-3 mb-4 text-red-700 bg-red-100 border border-red-400 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
                <div class="w-full md:w-1/2">
                    <h2 class="text-4xl font-extrabold">Data Log Notifikasi</h2>
                </div>
                <div class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
                    <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            Dashboard
                        </a>
                        </li>
                        <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a class="text-sm font-medium text-gray-700 ms-1">Log Notifikasi</a>
                        </div>
                        </li>
                    </ol>
                    </nav>
                </div>
            </div>
        <div class="relative overflow-hidden bg-white shadow-md sm:rounded-lg">
            <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
                <div class="flex w-full space-x-2 md:w-1/2">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="simple-search"
                                wire:model.debounce.500ms="search"
                                class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Cari pengguna...">
                        </div>
                    </div>

                    <!-- Select Lokasi -->
                    <div class="flex-shrink-0 w-48">
                        <select wire:model="selectedLokasi"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Pilih Lokasi</option>
                            @foreach($lokasiList as $lokasi)
                                <option value="{{ $lokasi }}">{{ $lokasi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="p-4 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">Pesan</th>
                            <th class="px-4 py-3">Tipe</th>
                            <th class="px-4 py-3">Lokasi</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Penerima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logNotifikasi as $log)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $log['pesan'] }}</td>
                                <td class="px-4 py-3">{{ $log['type'] }}</td>
                                <td class="px-4 py-3">{{ $log['perangkat']['lokasi'] ?? '-' }}</td>
                                <td class="px-4 py-3 capitalize">
                                    @php
                                        $statusBanjir = $log['perangkat']['latest_sensor_data']['status_monitor']['status_banjir'] ?? null;
                                    @endphp
                                    @if($statusBanjir === 'bahaya')
                                        <span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">{{ $statusBanjir }}</span>
                                    @elseif($statusBanjir === 'siaga 2')
                                        <span class="bg-yellow-100 text-yellow-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">{{ $statusBanjir }}</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">{{ $statusBanjir ?? '-' }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $log['user']['name'] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center">Tidak ada data log notifikasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <nav class="flex flex-col items-start justify-between p-4 space-y-3 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
                <span class="text-sm font-normal text-gray-500">
                    Tampilan
                    <span class="font-semibold text-gray-900">{{ ($currentPage - 1) * $perPage + 1 }} - {{ min($currentPage * $perPage, $total) }}</span>
                    dari
                    <span class="font-semibold text-gray-900">{{ $total }}</span>
                    entri
                </span>
                <ul class="inline-flex items-stretch -space-x-px">
                    {{-- Previous --}}
                    <li>
                        <button wire:click="goToPage({{ $currentPage - 1 }})" @disabled($currentPage == 1)
                            class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 0 1 0 1.414L9.414 10l3.293 3.293a1 1 0 1 1-1.414 1.414l-4-4a1 1 0 0 1 0-1.414l4-4a1 1 0 0 1 1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </li>

                    {{-- Pages --}}
                    @for ($page = 1; $page <= $lastPage; $page++)
                        <li>
                            <button wire:click="goToPage({{ $page }})"
                                class="flex items-center justify-center px-3 py-2 text-sm leading-tight {{ $currentPage == $page ? 'text-white bg-blue-600' : 'text-gray-500 bg-white' }} border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
                                {{ $page }}
                            </button>
                        </li>
                    @endfor

                    {{-- Next --}}
                    <li>
                        <button wire:click="goToPage({{ $currentPage + 1 }})" @disabled($currentPage == $lastPage)
                            class="flex items-center justify-center h-full py-1.5 px-3 text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 0 1 0-1.414L10.586 10 7.293 6.707a1 1 0 0 1 1.414-1.414l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
</div>


