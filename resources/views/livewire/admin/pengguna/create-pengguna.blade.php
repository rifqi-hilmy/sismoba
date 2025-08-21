<div class="mx-auto">
        <div class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
                <div class="w-full md:w-1/2">
                    <h2 class="text-3xl font-extrabold">Tambah Pengguna</h2>
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
                            <a href="{{ route('admin.pengguna.index') }}" class="text-sm font-medium text-gray-700 ms-1 hover:text-blue-600">Pengguna</a>
                        </div>
                        </li>
                        <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a class="text-sm font-medium text-gray-700 ms-1">Tambah Pengguna</a>
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
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama <span class="text-red-700">*</span></label>
                                <input type="text" wire:model.defer="name" name="name" id="name" class="@error('name') border-red-600 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
                                @error('name') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full">
                                <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username <span class="text-red-700">*</span></label>
                                <input type="text" wire:model.defer="username" name="username" id="username" class="@error('username') border-red-600 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"  required="">
                                @error('username') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-full">
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email <span class="text-red-700">*</span></label>
                                <input type="email" wire:model.defer="email" name="email" id="email" class="@error('email') border-red-600 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
                                @error('email') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password <span class="text-red-700">*</span></label>
                                <input type="password" wire:model.defer="password" name="password" id="password" class="@error('password') border-red-600 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
                                @error('password') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">Nomor Telepon/WA <span class="text-red-700">*</span></label>
                                <input type="number" wire:model.defer="phone" name="phone" id="phone" class="@error('phone') border-red-600 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required="">
                                @error('phone') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="roles" class="block mb-2 text-sm font-medium text-gray-900">Roles</label>
                                <select id="roles" wire:model.defer="roles" name="roles" class="@error('roles') border-red-600 @enderror bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                    <option selected="">Pilih Kategori</option>
                                    <option value="admin">Admin</option>
                                    <option value="petugas">Petugas</option>
                                    <option value="warga">Warga</option>
                                </select>
                                @error('roles') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="alamat" class="block mb-2 text-sm font-medium text-gray-900">Alamat <span class="text-red-700">*</span></label>
                                <textarea id="alamat" wire:model.defer="alamat" name="alamat" rows="8" class="@error('alamat') border-red-600 @enderror block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500"></textarea>
                                @error('alamat') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 hover:bg-blue-800">
                            Tambah Pengguna
                        </button>
                        <a href="{{ route('admin.pengguna.index') }}" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-yellow-700 rounded-lg focus:ring-4 focus:ring-yellow-200 hover:bg-yellow-800">
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
