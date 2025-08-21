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
                    <h2 class="text-4xl font-extrabold">Data Perangkat</h2>
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
                            <a class="text-sm font-medium text-gray-700 ms-1">Perangkat</a>
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

                    <!-- Select Role -->
                    <div class="flex-shrink-0 w-48">
                        <select wire:model="selectedStatus"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Pilih Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <div class="flex-shrink-0 w-48">
                        <select wire:model="selectedTipe"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Pilih Tipe</option>
                            <option value="gateway">Gateway</option>
                            <option value="node">Node</option>
                        </select>
                    </div>
                </div>

                <!-- Button -->
                <div class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
                    <a href="{{ route('admin.perangkat.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none">
                        Tambah Perangkat
                    </a>
                </div>
            </div>
            <div class="p-4 overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Lokasi</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Tipe</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perangkat as $prkt)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $prkt['nama_perangkat'] }}</td>
                                <td class="px-4 py-3">{{ $prkt['lokasi'] }}</td>
                                <td class="px-4 py-3 capitalize">
                                    @if($prkt['status'] == 'aktif')
                                        <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">{{ $prkt['status'] }}</span>
                                    @elseif($prkt['status'] == 'nonaktif')
                                        <span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">{{ $prkt['status'] }}</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">{{ $prkt['status'] }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 capitalize">
                                    @if($prkt['tipe'] == 'gateway')
                                        <span class="bg-yellow-100 text-yellow-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">{{ $prkt['tipe'] }}</span>
                                    @elseif($prkt['tipe'] == 'node')
                                        <span class="bg-indigo-100 text-indigo-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">{{ $prkt['tipe'] }}</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded-sm">{{ $prkt['tipe'] }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div x-data="{ openDropdown: false, openModal: false }" class="relative">
                                        <button @click="openDropdown = !openDropdown" class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none" type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                        </button>
                                        <div
                                            x-show="openDropdown"
                                            x-cloak
                                            @click.outside="openDropdown = false"
                                            class="absolute right-0 z-10 bg-white divide-y divide-gray-100 rounded shadow w-44"
                                        >
                                            <ul class="py-1 text-sm text-gray-700">
                                                <li>
                                                    <a href="{{ route('admin.perangkat.edit', $prkt['id']) }}" class="block px-4 py-2 hover:bg-gray-100">Ubah</a>
                                                </li>
                                                <li>
                                                    <button @click="openModal = true" class="block w-full px-4 py-2 text-left hover:bg-gray-100">
                                                        Hapus
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- Modal Hapus (AlpineJS Version) -->
                                        <div x-show="openModal"
                                            x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                            <div class="relative w-full max-w-md p-4 bg-white rounded-lg shadow-xl">
                                                <button @click="openModal = false"
                                                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex items-center justify-center">
                                                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l6 6m0 0l6 6M7 7l6-6M7 7l-6 6"/>
                                                    </svg>
                                                </button>
                                                <div class="p-4 text-center md:p-5">
                                                    <svg class="w-12 h-12 mx-auto mb-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7L5 7M10 11v6m4-6v6m5-10v10a2 2 0 01-2 2H7a2 2 0 01-2-2V7h14zM9 7V5a2 2 0 012-2h2a2 2 0 012 2v2"/>
                                                    </svg>
                                                    <h3 class="mb-5 text-lg font-normal text-gray-500">Apakah kamu yakin ingin menghapus perangkat ini?</h3>
                                                    <form action="{{ route('admin.perangkat.destroy', $prkt['id']) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                                            Ya, hapus
                                                        </button>
                                                        <button @click="openModal = false"
                                                                type="button"
                                                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700">
                                                            Batal
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center">Tidak ada data pengguna.</td>
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


