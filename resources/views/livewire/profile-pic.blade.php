<div>
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0" style="color: var(--bs-body-color);">Profile Saya</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile Saya</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto py-6 px-4">
        <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col md:flex-row items-center gap-6">
            <!-- Foto Profil -->
            <div class="flex-shrink-0">
                <img src="./assets/img/user4-128x128.jpg" alt="Foto Pegawai"
                    class="w-28 h-28 rounded-full border object-cover">
            </div>

            <!-- Info Karyawan -->
            <div class="flex-1 w-full">
                <!-- Header: Nama, Role, ID, Status -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center w-full">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Nadia Safira Khairunnisa</h2>
                        <p class="text-sm text-gray-500 leading-tight">Marketing</p>
                        <p class="text-sm text-gray-500 leading-tight">ID: 12345678</p>
                    </div>
                    <span class="mt-2 md:mt-0 bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full">Aktif</span>
                </div>

                <!-- Details -->
                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Details</h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p>Email: <span class="font-medium">dmarketing2@gmail.com</span></p>
                        <p>No. Hp: <span class="font-medium">087876543216</span></p>
                    </div>
                </div>

                <!-- Performance -->
                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Performance</h3>
                    <div class="flex items-center justify-between">
                        <div class="flex items-end gap-1">
                            <span class="text-2xl font-bold text-gray-800">87</span>
                            <span class="text-gray-400 text-sm">/100</span>
                        </div>
                        <img src="./assets/img/categories/04.jpg" alt="Top Performer" class="h-10 object-contain">
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                        <div class="bg-blue-500 h-2.5 rounded-full" style="width: 87%"></div>
                    </div>
                </div>

                <!-- Info Tambahan -->
                <div class="mt-6 text-sm text-gray-600 space-y-1">
                    <p><span class="font-medium">Tanggal Bergabung:</span> 10 Nov 2024</p>
                    <p><span class="font-medium">Rekruiter:</span> Dimas Pradana</p>
                </div>

                <div class="mt-6 text-center">
                    <button class="btn btn-primary" data-bs-target="#">
                        View Details
                    </button>
                    {{-- <a href="#"
                        class="inline-block px-5 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                        View Details
                    </a> --}}
                </div>
            </div>
        </div>
    </div>
</div>