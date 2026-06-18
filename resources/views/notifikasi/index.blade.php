<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Notifikasi Pengingat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- <!-- Status WhatsApp -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Status WhatsApp Service</h3>
                        <button onclick="cekStatusWhatsApp()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                            Cek Status
                        </button>
                    </div>
                    <div id="whatsapp-status" class="text-sm text-gray-600 dark:text-gray-400">
                        Klik "Cek Status" untuk memverifikasi koneksi WhatsApp
                    </div>
                </div>
            </div> --}}

            <!-- Kirim Notifikasi H-3, H-2, H-1 -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Kirim Pengingat Berdasarkan Hari</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Kirim notifikasi ke karyawan yang akan jatuh tempo H-3, H-2, atau H-1</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- H-3 -->
                        <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <span class="text-2xl mr-2">📋</span>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">H-3 (3 Hari Lagi)</h4>
                            </div>
                            <div class="space-y-2">
                                <button onclick="kirimNotifikasiBatch(3, 'email')" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                    Email
                                </button>

                                {{-- <button onclick="kirimNotifikasiBatch(3, 'whatsapp')" class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                    WhatsApp
                                </button> --}}

                                {{-- <button onclick="kirimNotifikasiBatch(3, 'semua')" class="w-full px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600">
                                    Email + WhatsApp
                                </button> --}}

                            </div>
                        </div>

                        <!-- H-2 -->
                        <div class="border border-yellow-400 dark:border-yellow-600 rounded-lg p-4">
                            <div class="flex items-center mb-3">
                                <span class="text-2xl mr-2">⚠️</span>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">H-2 (2 Hari Lagi)</h4>
                            </div>
                            <div class="space-y-2">
                                <button onclick="kirimNotifikasiBatch(2, 'email')" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                    Email
                                </button>

                                {{-- <button onclick="kirimNotifikasiBatch(2, 'whatsapp')" class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                    WhatsApp
                                </button> --}}

                                {{-- <button onclick="kirimNotifikasiBatch(2, 'semua')" class="w-full px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600">
                                    Email + WhatsApp
                                </button> --}}

                            </div>
                        </div>

                        <!-- H-1 -->
                        <div class="border border-red-500 dark:border-red-700 rounded-lg p-4 bg-red-50 dark:bg-red-900/20">
                            <div class="flex items-center mb-3">
                                <span class="text-2xl mr-2">🚨</span>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">H-1 (BESOK!)</h4>
                            </div>
                            <div class="space-y-2">
                                <button onclick="kirimNotifikasiBatch(1, 'email')" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                    Email
                                </button>

                                {{-- <button onclick="kirimNotifikasiBatch(1, 'whatsapp')" class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                    WhatsApp
                                </button> --}}

                                {{-- <button onclick="kirimNotifikasiBatch(1, 'semua')" class="w-full px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600">
                                    Email + WhatsApp
                                </button> --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kirim Notifikasi Terlambat -->
            <div class="bg-red-50 dark:bg-red-900/20 overflow-hidden shadow-sm sm:rounded-lg border-2 border-red-500">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-4">⚠️ Peringatan Keterlambatan</h3>
                    <p class="text-sm text-red-700 dark:text-red-300 mb-6">Kirim peringatan ke semua karyawan yang terlambat mengembalikan aset</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button onclick="kirimNotifikasiTerlambat('email')" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-semibold">
                            📧 Email
                        </button>

                        {{-- <button onclick="kirimNotifikasiTerlambat('whatsapp')" class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 font-semibold">
                            💬 WhatsApp
                        </button>
                        <button onclick="kirimNotifikasiTerlambat('semua')" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold">
                            🚨 Email + WhatsApp
                        </button> --}}

                    </div>
                </div>
            </div>

            <!-- Log Hasil -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Log Notifikasi</h3>
                    <div id="log-notifikasi" class="bg-gray-100 dark:bg-gray-900 p-4 rounded-lg min-h-[200px] max-h-[400px] overflow-y-auto font-mono text-sm">
                        <p class="text-gray-600 dark:text-gray-400">Belum ada aktivitas...</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function addLog(message, type = 'info') {
            const logDiv = document.getElementById('log-notifikasi');
            const timestamp = new Date().toLocaleTimeString('id-ID');
            const colorClass = {
                'info': 'text-blue-600 dark:text-blue-400',
                'success': 'text-green-600 dark:text-green-400',
                'error': 'text-red-600 dark:text-red-400',
                'warning': 'text-yellow-600 dark:text-yellow-400'
            }[type] || 'text-gray-600 dark:text-gray-400';

            logDiv.innerHTML += `<p class="${colorClass}">[${timestamp}] ${message}</p>`;
            logDiv.scrollTop = logDiv.scrollHeight;
        }

        function cekStatusWhatsApp() {
            addLog('Mengecek status WhatsApp Service...', 'info');

            fetch('{{ route("notifikasi.statusWhatsapp") }}')
                .then(response => response.json())
                .then(data => {
                    const statusDiv = document.getElementById('whatsapp-status');
                    if (data.status) {
                        statusDiv.innerHTML = `<span class="text-green-600 dark:text-green-400">✓ WhatsApp Service Aktif</span>`;
                        addLog('✓ WhatsApp Service Terkoneksi', 'success');
                    } else {
                        statusDiv.innerHTML = `<span class="text-red-600 dark:text-red-400">✗ ${data.message}</span>`;
                        addLog('✗ WhatsApp Service Error: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    addLog('✗ Error: ' + error.message, 'error');
                });
        }

        function kirimNotifikasiBatch(hari, tipe) {
            const hariLabel = ['', 'H-1', 'H-2', 'H-3'][hari];
            const tipeLabel = {'email': 'Email', 'whatsapp': 'WhatsApp', 'semua': 'Email+WhatsApp'}[tipe];

            if (!confirm(`Kirim notifikasi ${hariLabel} via ${tipeLabel}?`)) {
                return;
            }

            addLog(`🔄 Mengirim notifikasi ${hariLabel} via ${tipeLabel}...`, 'info');

            fetch('{{ route("notifikasi.kirimBatch") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    hari: hari,
                    tipe: tipe
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    addLog(`✓ ${data.message}`, 'success');
                    addLog(`  Total: ${data.total} | Berhasil: ${data.berhasil} | Gagal: ${data.gagal}`, 'success');

                    if (data.detail && data.detail.length > 0) {
                        data.detail.forEach(function(item) {
                            let status = (item.email || item.whatsapp) ? "✅" : "❌";
                            let info = item.pesan_error ? `(Error: ${item.pesan_error})` : "";
                            addLog(`    - ${status} ${item.karyawan} (${item.barang}) ${info}`, item.pesan_error ? 'error' : 'info');
                        });
                    }
                } else {
                    addLog(`⚠ ${data.message}`, 'warning');
                }
            })
            .catch(error => {
                addLog(`✗ Error: ${error.message}`, 'error');
            });
        }

        function kirimNotifikasiTerlambat(tipe) {
            const tipeLabel = {'email': 'Email', 'whatsapp': 'WhatsApp', 'semua': 'Email+WhatsApp'}[tipe];

            if (!confirm(`Kirim peringatan keterlambatan via ${tipeLabel}?`)) {
                return;
            }

            addLog(`🔄 Mengirim peringatan keterlambatan via ${tipeLabel}...`, 'info');

            fetch('{{ route("notifikasi.kirimTerlambat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    tipe: tipe
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    addLog(`✓ ${data.message}`, 'success');
                    addLog(`  Total: ${data.total} | Berhasil: ${data.berhasil} | Gagal: ${data.gagal}`, 'success');

                    if (data.detail && data.detail.length > 0) {
                        data.detail.forEach(function(item) {
                            let status = (item.email || item.whatsapp) ? "✅" : "❌";
                            let info = item.pesan_error ? `(Error: ${item.pesan_error})` : "";
                            addLog(`    - ${status} ${item.karyawan} (${item.barang}) ${info}`, item.pesan_error ? 'error' : 'info');
                        });
                    }
                } else {
                    addLog(`⚠ ${data.message}`, 'warning');
                }
            })
            .catch(error => {
                addLog(`✗ Error: ${error.message}`, 'error');
            });
        }
    </script>
    @endpush
</x-app-layout>
