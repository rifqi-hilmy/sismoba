<x-main-layout>
@section('title', 'Perangkat')
@if (session()->has('success'))
    <div id="toast-default" class="flex items-center w-full max-w-xs p-4 mb-4 text-white bg-green-700 rounded-lg shadow-sm" role="alert">
        <div class="text-sm font-normal ms-3">
            {{ session('success') }}
        </div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 hover:text-green-900 rounded-lg focus:ring-2 focus:ring-green-300 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8" onclick="this.parentElement.remove()" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
@endif
<livewire:admin.perangkat.index-perangkat />
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('post-update', () => {
            // Tunggu hingga DOM di-render ulang
            setTimeout(() => {
                const dropdowns = FlowbiteInstances.getInstance('Dropdown');
                if(dropdowns) dropdowns.init();
            }, 100);
        });
    });
</script>
</x-main-layout>

