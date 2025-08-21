<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
    @include('includes.head')
    @stack('styles')
    <style>
    @keyframes fade-in-right {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-fade-in-right {
        animation: fade-in-right 0.3s ease-out;
    }

    .opacity-0 {
        opacity: 0;
        transition: opacity 0.5s ease;
    }
</style>
</head>
<body class="bg-gray-50">

@include('includes.navbar')

@php
    $userRoles = session('roles', []);
    // Pastikan $userRoles selalu array
    if (is_string($userRoles)) {
        $userRoles = [$userRoles];
    }
@endphp

@if(in_array('admin', $userRoles))
    @include('includes.sidebar.admin')
@elseif(in_array('petugas', $userRoles))
    @include('includes.sidebar.petugas')
@elseif(in_array('warga', $userRoles))
    @include('includes.sidebar.warga')
@endif

<main class="h-auto p-4 sm:ml-64 mt-14">
    {{ $slot }}
</main>
@include('includes.footer')
@stack('scripts')
<script>
    let lastNotifiedPerangkat = {};
    let initialized = false;
    let latestPerangkatData = null;
    let lastStatusCheckTime = Date.now();

    async function checkForBanjirBahaya() {
        try {
            const response = await fetch('https://api-monitoring-banjir.test/api/v1/sensor-monitor/connected');
            const result = await response.json();
            latestPerangkatData = result; // Simpan data untuk pengecekan status

            if (result.success && result.data && result.data.data) {
                const nodePerangkat = result.data.data.filter(perangkat => perangkat.tipe === 'node');

                nodePerangkat.forEach(perangkat => {
                    const sensorDataList = perangkat.sensor_data || [];
                    if (sensorDataList.length === 0) return;

                    const latestSensor = sensorDataList.reduce((latest, current) => {
                        return new Date(current.created_at) > new Date(latest.created_at) ? current : latest;
                    });

                    const perangkatId = perangkat.id;
                    const createdAt = new Date(latestSensor.created_at).getTime();
                    const status = latestSensor.status_monitor?.status_banjir?.toLowerCase() || '-';

                    const lastStatusData = lastNotifiedPerangkat[perangkatId];

                    const isStatusChanged = !lastStatusData || (
                        lastStatusData.createdAt !== createdAt ||
                        lastStatusData.status !== status
                    );

                    lastNotifiedPerangkat[perangkatId] = {
                        createdAt,
                        status,
                    };

                    if (initialized && isStatusChanged && status === 'bahaya') {
                        const title = 'Peringatan Banjir';
                        const body = `Status Banjir: BAHAYA di ${perangkat.nama_perangkat} (${perangkat.lokasi})`;

                        showWebNotification(title, body);
                        showToastNotification(title, body);
                    }
                });

                if (!initialized) initialized = true;

                // Setelah mengambil data baru, periksa status perangkat
                checkAndUpdateNodeStatus();
            }
        } catch (error) {
            console.error('Gagal mengambil data sensor:', error);
        }
    }

    // Fungsi untuk memperbarui status perangkat node
    async function checkAndUpdateNodeStatus() {
        if (!latestPerangkatData || !latestPerangkatData.success) return;

        try {
            const nodePerangkat = latestPerangkatData.data.data.filter(perangkat =>
                perangkat.tipe === 'node'
            );

            const now = Date.now();
            const oneMinutesAgo = now - (1 * 60 * 1000); // 1 menit dalam milidetik

            for (const perangkat of nodePerangkat) {
                const sensorDataList = perangkat.sensor_data || [];

                if (sensorDataList.length === 0) {
                    // Jika tidak ada data sensor sama sekali, nonaktifkan perangkat
                    if (perangkat.status === 'aktif') {
                        await updatePerangkatStatus(perangkat.id, 'nonaktif');
                    }
                    continue;
                }

                // Cari data sensor terbaru
                const latestSensor = sensorDataList.reduce((latest, current) => {
                    return new Date(current.created_at) > new Date(latest.created_at) ? current : latest;
                }, sensorDataList[0]);

                const latestTime = new Date(latestSensor.created_at).getTime();

                // Jika data terbaru lebih lama dari 1 menit, nonaktifkan perangkat
                if (latestTime < oneMinutesAgo) {
                    if (perangkat.status === 'aktif') {
                        await updatePerangkatStatus(perangkat.id, 'nonaktif');
                    }
                } else {
                    // Jika ada data baru dan perangkat nonaktif, aktifkan kembali
                    if (perangkat.status === 'nonaktif') {
                        await updatePerangkatStatus(perangkat.id, 'aktif');
                    }
                }
            }

            lastStatusCheckTime = now;
        } catch (error) {
            console.error('Gagal memeriksa status perangkat:', error);
        }
    }

    // Fungsi untuk memperbarui status perangkat
    async function updatePerangkatStatus(perangkatId, status) {
        try {
            const response = await fetch(`https://api-monitoring-banjir.test/api/v1/perangkat/${perangkatId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            });

            const result = await response.json();

            if (result.success) {
                console.log(`Perangkat ${perangkatId} berhasil diubah status menjadi ${status}.`);
                // Tidak menampilkan notifikasi kepada pengguna
            } else {
                console.error(`Gagal mengubah status perangkat ${perangkatId}:`, result.message);
            }
        } catch (error) {
            console.error(`Error saat mengubah status perangkat ${perangkatId}:`, error);
        }
    }

    function showWebNotification(title, body) {
        if (!("Notification" in window)) {
            console.warn("Browser tidak mendukung Notification API");
            return;
        }

        if (Notification.permission === "granted") {
            new Notification(title, {
                body,
                icon: "/icons/warning.png"
            });
        } else if (Notification.permission !== "denied") {
            Notification.requestPermission().then(permission => {
                if (permission === "granted") {
                    new Notification(title, {
                        body,
                        icon: "/icons/warning.png"
                    });
                }
            });
        }
    }

    function showToastNotification(title, message) {
        const toastContainer = document.getElementById('toast-container');

        const toast = document.createElement('div');
        toast.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-md mb-4 animate-fade-in-right';
        toast.innerHTML = `
            <strong class="block font-bold">${title}</strong>
            <span class="block text-sm">${message}</span>
        `;

        toastContainer.appendChild(toast);

        // Hapus toast setelah 5 detik
        setTimeout(() => {
            toast.classList.add('opacity-0');
            setTimeout(() => toast.remove(), 500);
        }, 5000);
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Tambahkan container toast
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-5 right-5 z-50 flex flex-col items-end space-y-2';
        document.body.appendChild(container);

        // Minta izin notifikasi saat awal load
        if ("Notification" in window && Notification.permission !== "granted") {
            Notification.requestPermission();
        }

        // Jalankan pengecekan berkala
        setInterval(checkForBanjirBahaya, 10000);
    });
</script>

</body>
</html>
