<div class="mx-auto">
        <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
                <div class="w-full md:w-1/2">
                    <h2 class="text-3xl font-extrabold">Tambah Perangkat</h2>
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
                            <a href="{{ route('admin.perangkat.index') }}" class="text-sm font-medium text-gray-700 ms-1 hover:text-blue-600">Perangkat</a>
                        </div>
                        </li>
                        <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a class="text-sm font-medium text-gray-700 ms-1">Tambah Perangkat</a>
                        </div>
                        </li>
                    </ol>
                    </nav>
                </div>
            </div>
            <div class="relative p-4 overflow-hidden bg-white shadow-md sm:rounded-lg">
                <div class="overflow-x-auto">
                    <form wire:submit.prevent="submit">
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                            <div class="sm:col-span-2">
                                <label for="nama_perangkat" class="block mb-2 text-sm font-medium text-gray-900">Nama Perangkat <span class="text-red-700">*</span></label>
                                <input type="text" wire:model.defer="nama_perangkat" name="nama_perangkat" id="nama_perangkat" class="@error('nama_perangkat') border-red-600 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
                                @error('nama_perangkat') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="lokasi" class="block mb-2 text-sm font-medium text-gray-900">Lokasi <span class="text-red-700">*</span></label>
                                <input type="text" wire:model.defer="lokasi" name="lokasi" id="lokasi" class="@error('lokasi') border-red-600 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
                                @error('lokasi') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status <span class="text-red-700">*</span></label>
                                <select id="status" wire:model.defer="status" name="status" class="@error('status') border-red-600 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                    <option selected="">Pilih Status</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                                @error('status') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full">
                                <label for="latitude" class="block mb-2 text-sm font-medium text-gray-900">Latitude <span class="text-red-700">*</span></label>
                                <input type="text" wire:model.defer="latitude" name="latitude" id="latitude" class="@error('latitude') border-red-600 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"  required="">
                                @error('latitude') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full">
                                <label for="longitude" class="block mb-2 text-sm font-medium text-gray-900">Longitude <span class="text-red-700">*</span></label>
                                <input type="text" wire:model.defer="longitude" name="longitude" id="longitude" class="@error('longitude') border-red-600 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
                                @error('longitude') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full">
                                <label for="tipe" class="block mb-2 text-sm font-medium text-gray-900">Tipe</label>
                                <select id="tipe" wire:model.defer="tipe" name="tipe" class="@error('tipe') border-red-600 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                    <option selected="">Pilih Tipe</option>
                                    <option value="gateway">Gateway</option>
                                    <option value="node">Node</option>
                                </select>
                                @error('tipe') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full">
                                <label for="perangkat_parent_id" class="block mb-2 text-sm font-medium text-gray-900">Parent Perangkat</label>
                                <select id="perangkat_parent_id" wire:model.defer="perangkat_parent_id" name="perangkat_parent_id"
                                    class="@error('perangkat_parent_id') border-red-600 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                    <option value="">Pilih Parent Perangkat</option>
                                    @foreach($parentDevices as $device)
                                        <option value="{{ $device['id'] }}">
                                            {{ $device['nama_perangkat'] }} ({{ $device['lokasi'] }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('perangkat_parent_id') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 hover:bg-blue-800">
                            Tambah Perangkat
                        </button>
                        <a href="{{ route('admin.perangkat.index') }}" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-yellow-700 rounded-lg focus:ring-4 focus:ring-yellow-200 hover:bg-yellow-800">
                            Kembali
                        </a>
                    </form>
                    @error('api')
                        <div class="sm:col-span-2">
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        </div>
                    @enderror
                </div>
            </div>
</div>
