@extends('layouts.beApp')

@section('title')
    Bidang Pekerjaan
@endsection

@push('styles')
    <!-- DataTables Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.colVis.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@push('scripts')
    <!-- DataTables core + Buttons + Bootstrap 4 integration -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

    <!-- Select2 -->
    <script src="{{asset('AdminLTE')}}/plugins/select2/js/select2.full.min.js"></script>

    <script>
        let data_bidang_pekerjaan = []; // Deklarasi data yang akan ditampilkan
        const nama_id_tabel = "tabel-bidang-pekerjaan"; // Deklarasi nama tabel
        const div_tabel = document.getElementById(`div-${nama_id_tabel}`); // Deklarasi variabel berisi div yang memuat tabel

        // Start fungsi display Data
        const displayData = () => {
            // Mengambil data dari fungsi setBidangPekerjaan internal
            const bidang = data_bidang_pekerjaan;

            console.log(bidang);
            // Jika tabel sudah diinisialisasi sebagai DataTable, hancurkan terlebih dahulu
            if ($.fn.DataTable.isDataTable(`#${nama_id_tabel}`)) {
                $(`#${nama_id_tabel}`).DataTable().destroy();
            };

            // Jika data lebih dari 0
            if (bidang.length > 0) {
                div_tabel.innerHTML = `
                    <table id="${nama_id_tabel}" class="table table-bordered table-hover table-sm tw-text-sm">
                        <thead class="tw-bg-[#0c3f50] tw-text-white">
                            <tr>
                                <th class="align-middle text-center">#</th>
                                <th class="align-middle text-center">Nama Bidang</th>
                                <th class="align-middle text-center">Jumlah Amil</th>
                                <th class="align-middle text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${bidang.map((item, index) => {
                                return `
                                    <tr>
                                        <!-- Penomoran Index -->
                                        <td class="align-middle text-center">${index+1}</td>

                                        <!-- Nama Bidang -->
                                        <td class="align-middle text-nowrap">
                                            ${item.nama_bidang}
                                        </td>

                                        <!-- Jumlah Amil -->
                                        <td class="align-middle text-center text-nowrap">
                                            ${item.jumlah_amil} Amil
                                        </td>

                                        <!-- Action -->
                                        <td class="align-middle text-center text-nowrap">
                                            <!-- Edit bidang -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-success btn-bidang"
                                                    data-id="${item.id}"
                                                    data-bidang='${JSON.stringify(item)}'
                                            >
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <!-- Hapus bidang -->
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger btn-hapus"
                                                    data-id="${item.id}"
                                                    data-bidang='${JSON.stringify(item)}'
                                            >
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>`
                                }).join('')}
                        </tbody>
                    </table>
                `;

            // Jika data kurang dari sama dengan 0
            } else {
                div_tabel.innerHTML = `
                    <table id="${nama_id_tabel}" class="table table-bordered table-hover table-sm tw-text-sm">
                        <thead class="tw-bg-[#0c3f50] tw-text-white">
                            <tr>
                                <th class="align-middle text-center">#</th>
                                <th class="align-middle text-center">Nama Bidang</th>
                                <th class="align-middle text-center">Jumlah Amil</th>
                                <th class="align-middle text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                `;
            };

            // Deklarasikan DataTable baru pada tabel yang ditampilkan
            $(`#${nama_id_tabel}`).DataTable(getDataTableConfig(nama_id_tabel));
        };
        // End fungsi display Data

        // Start Fungsi Create atau Update Data
        $(document).on('click', '.btn-bidang', async function () {
            const button_trigger = this;
            const trigger_original_content = $(this).html();

            disableButton(button_trigger, true, content_spinner); // Nonaktifkan button trigger
            
            const data_id = $(button_trigger).data('id'); // Mengambil atribut data id pada button
            const data_edit = data_id ? $(button_trigger).data('bidang') : null; // Mengambil data donatur

            const id_modal = 'modal-crud-bidang-pekerjaaan';
            const id_form = 'form-crud-bidang-pekerjaaan';
            const modal_bidang_pekerjaan = `<x-modal-bidang id="${id_modal}" idForm="${id_form}"/>`;

            $('body').append(modal_bidang_pekerjaan); // Masukkan modal ke dalam elemen body
            setHeaderModal(document.getElementById('judul_modal'), `${data_id ? 'Edit' : 'Tambah'} Bidang Pekerjaan`); // Set judul modal

            // Deklarasi elemen input pada Form
            const [form, nama_bidang, submit_btn] = [id_form, 'nama_bidang', 'btn_save'].map(id => document.getElementById(id));
            const content_btn_form = $(submit_btn).html(); // Deklarasi konten original button submit form

            // Mode Edit
            if (data_id != null) {
                nama_bidang.value = data_edit.nama_bidang;
            }

            // Menampilkan modal
            $(`#${id_modal}`).modal('show');

            // Ketika form disubmit
            $(`#${id_modal}`).on('shown.bs.modal', function () {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault(); // Hindari reload halaman

                    // Membuat objek formData
                    const formData = {
                        _token: "{{ csrf_token() }}",
                        // Isi data transaksi pada dataForm
                        nama_bidang: nama_bidang.value,
                    };

                    // Tambahkan id donatur jika update
                    if (data_id != null) {
                        formData['id'] = data_id;
                    }

                    // Proses kirim data via AJAX
                    $.ajax({
                        type: "POST",
                        url: form.action,
                        data: formData,
                        beforeSend: () => {
                            disableButton(submit_btn, true, spinner_saving); // Disable submit button
                            handleBeforeSendForm(); // Tampilkan loading
                        },
                        success: async (response) => {
                            disableButton(submit_btn, false, content_btn_form);
                            $(`#${id_modal}`).modal('hide'); // Tutup modal

                            // Refresh data dan tampilkan notifikasi sukses
                            data_bidang_pekerjaan = await setBidangPekerjaan();
                            displayData();

                            Toast.fire({
                                icon: "success",
                                title: response.message,
                                text: response.data.nama_bidang
                            });
                        },
                        error: (xhr) => {
                            disableButton(submit_btn, false, content_btn_form);
                            handleFormError(xhr); // Tampilkan pesan error swal
                        }
                    });
                });
            });

            // Fungsi setelah modal ditutup
            $(`#${id_modal}`).on('hidden.bs.modal', function () {
                $(this).remove(); // Hapus modal dari DOM
                $(`#${id_modal}`).off('shown.bs.modal');
                $(`#${id_modal}`).off('hidden.bs.modal');
            });

            disableButton(button_trigger, false, trigger_original_content); // Enable kembali tombol trigger utama
        });
        // End Fungsi Create atau Update Data

        // Start Fungsi Hapus Data
        $(document).on('click', '.btn-hapus', function () {
            const data_id = $(this).data('id'); // Mengambil ID kelas dari atribut data-id pada tombol
            const bidang = $(this).data('bidang'); // Mengambil nama kelas dari atribut data-nama pada tombol

            // Tampilkan konfirmasi menggunakan SweetAlert sebelum menghapus
            Swal.fire({
                title: 'Hapus Data Ini?',
                html: `<p class="tw-text-sm">Setelah menghapus bidang pekerjaan <strong>${bidang.nama_bidang}</strong> ini, data tidak dapat dikembalikan.</p>`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                allowOutsideClick: false,
            }).then((result) => {
                // Jika user menekan tombol "Ya, Hapus!"
                if (result.isConfirmed) {
                    // Lakukan request AJAX untuk menghapus data
                    $.ajax({
                        url: "{{ route('bidang-pekerjaan.delete') }}", // Endpoint penghapusan data
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: data_id // Kirim ID kelas yang akan dihapus
                        },
                        beforeSend: () => {
                            handleBeforeSendForm(); // Tampilkan loading SweetAlert sebelum request dikirim
                        },
                        success: async (response) => {
                            // Jika berhasil, refresh data dari server
                            data_bidang_pekerjaan = await setBidangPekerjaan(); // Ambil ulang data kelas dari server
                            displayData(); // Render ulang data di tampilan

                            // Tampilkan notifikasi sukses menggunakan Toastify atau SweetAlert
                            Toast.fire({
                                icon: "success",
                                title: response.message,
                                text: response.data.nama_bidang
                            });
                        },
                        error: function (xhr) {
                            handleFormError(xhr); // Tampilkan notifikasi error jika request gagal
                        }
                    });
                }
            });
        });
        // End Fungsi Hapus Data

        // Start Set
        const setBidangPekerjaan = async () => {
            const bidang_mentah = await setData(getData('/get-data/bidang-pekerjaan'));
            const data_amil = await setData(getData('/get-data/amil'));
            
            bidang_mentah.forEach(bidang_olah => {
                let jumlah = 0;
                
                data_amil.forEach(amil => {

                    if (amil.biodata.pekerjaan == bidang_olah.nama_bidang) {
                        jumlah++;
                    }
                });

                bidang_olah['jumlah_amil'] = jumlah;
            });

            return bidang_mentah;
        };
        // End Set

        // Sebelum melakukan load halaman, lakukan set data yang akan ditampilkan
        window.onload = async () => {
            data_bidang_pekerjaan = await setBidangPekerjaan();
            displayData();
        };
    </script>
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>@yield('title')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">@yield('title')</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header tw-bg-gradient-to-r tw-from-[#0c3f50] tw-to-[#2a9d8f]">
                                <h3 class="card-title tw-font-bold tw-text-lg tw-text-white">Data @yield('title')</h3>

                                <button type="button" class="float-right btn btn-sm btn-success shadow btn-bidang">
                                    <i class="fa fa-plus"></i> @yield('title')
                                </button>
                            </div>
                            <!-- /.card-header -->

                            <div class="card-body">
                                <div class="table-responsive" id="div-tabel-bidang-pekerjaan">
                                    <table id="tabel-bidang-pekerjaan" class="table table-bordered table-hover table-sm tw-text-sm">
                                        <thead class="tw-bg-[#0c3f50] tw-text-white">
                                            <tr>
                                                <th class="align-middle text-center">#</th>
                                                <th class="align-middle text-center">Nama Bidang</th>
                                                <th class="align-middle text-center">Jumlah Amil</th>
                                                <th class="align-middle text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="4">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <div class="spinner-border spinner-border-sm" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                        <span class="ml-2 tw-font-bold">Mengambil data...</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection