@extends('layout.index')
@section('title', 'Donatur')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
@endsection

@section('js')
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>

<!-- select2 -->
<script src="/adminlte/plugins/select2/js/select2.full.min.js"></script>

<script>
    let data_donatur = []; // Deklarasi data yang akan ditampilkan
    const nama_id_tabel = "tabel-donatur"; // Deklarasi nama tabel
    const div_tabel = document.getElementById(`div-${nama_id_tabel}`); // Deklarasi variabel berisi div yang memuat tabel

    const displayData = () => {
        const donatur = data_donatur;

        if ($.fn.DataTable.isDataTable(`#${nama_id_tabel}`)) {
            $(`#${nama_id_tabel}`).DataTable().destroy();
        };

        if (donatur.length > 0) {
            div_tabel.innerHTML = `
                <table id="${nama_id_tabel}" class="table table-bordered table-hover table-sm tw-text-sm">
                    <thead class="tw-bg-[#0c3f50] tw-text-white">
                        <tr>
                            <th class="align-middle text-center">#</th>
                            <th class="align-middle text-center">Nama Lenkap</th>
                            <th class="align-middle text-center">Jenis kelamin</th>
                            <th class="align-middle text-center">Identitas</th>
                            <th class="align-middle text-center">Email</th>
                            <th class="align-middle text-center">No Telepon</th>
                            <th class="align-middle text-center">Alamat</th>
                            <th class="align-middle text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${donatur.map((item, index) => {
                            return `
                                <tr>
                                    <!-- Penomoran Index -->
                                    <td class="align-middle text-center">${index+1}</td>

                                    <!-- Nama Lengkap -->
                                    <td class="align-middle text-nowrap">
                                            <div class="tw-text-blue-600">${item.nama_lengkap}</div>
                                            <div class="tw-text-xs">Create at : ${formatTanggal(item.created_at)} - ${formatWaktuHi(item.created_at)} by <span class="tw-text-yellow-700">${item.pembuat ? item.pembuat.nama_lengkap : '-'}</span></div>
                                            ${
                                                item.updated_at != item.created_at ? `<div class="tw-text-xs">Last Update : ${formatTanggal(item.updated_at)} - ${formatWaktuHi(item.updated_at)} by <span class="tw-text-purple-900">${item.pengupdate.nama_lengkap}</span></div>` : null
                                            }
                                        </td>

                                    <!-- Jenis Kelamin -->
                                    <td class="align-middle text-center text-nowrap">
                                        ${item.kelamin} Amil
                                    </td>
                                    
                                    <!-- Identitas -->
                                    <td class="align-middle text-center ${item.jenis_identitas && item.nomor_identitas ? '' : 'text-danger'}">
                                        ${item.jenis_identitas && item.nomor_identitas ? item.jenis_identitas + ' - ' + item.nomor_identitas : 'Belum Update'}
                                    </td>
                                    
                                    <!-- Email -->
                                    <td class="align-middle text-center text-nowrap">
                                        ${item.jumlah_amil} Amil
                                    </td>

                                    <!-- No HP -->
                                    <td class="align-middle text-center ${item.nomor_whatsapp ? '' : 'text-danger'}">
                                        ${
                                            item.nomor_whatsapp ?
                                            `<a href="https://wa.me/${item.nomor_whatsapp}" target="_blank" class="btn btn-sm btn-success d-flex align-items-center"><i class="fab fa-whatsapp mr-1"></i> ${item.nomor_whatsapp}</a>` : 'Belum Update'
                                        }
                                    </td>

                                    <!-- Alamat -->
                                    <td class="align-middle text-center text-nowrap">
                                        ${item.jumlah_amil} Amil
                                    </td>

                                    <!-- Action -->
                                    <td class="align-middle text-center text-nowrap">
                                        <!-- Edit donatur -->
                                        <button type="button" 
                                                class="btn btn-sm btn-success btn-donatur"
                                                data-id="${item.id}"
                                                data-donatur='${JSON.stringify(item)}'
                                        >
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <!-- Hapus donatur -->
                                        <button type="button" 
                                                class="btn btn-sm btn-danger btn-hapus"
                                                data-id="${item.id}"
                                                data-donatur='${JSON.stringify(item)}'
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
                            <th class="align-middle text-center">Nama Lenkap</th>
                            <th class="align-middle text-center">Jenis kelamin</th>
                            <th class="align-middle text-center">Identitas</th>
                            <th class="align-middle text-center">Email</th>
                            <th class="align-middle text-center">No Telepon</th>
                            <th class="align-middle text-center">Alamat</th>
                            <th class="align-middle text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            `;
        };

        // Deklarasikan DataTable baru pada tabel yang ditampilkan
        $(`#${nama_id_tabel}`).DataTable(getDataTableConfig(nama_id_tabel));
    }

    $(document).on('click', '.btn-donatur', async function () {
        const button_trigger = this;
        const trigger_original_content = $(this).html();

        disableButton(button_trigger, true, content_spinner); // Nonaktifkan button trigger
        
        const data_id = $(button_trigger).data('id'); // Mengambil atribut data id pada button
        const data_edit = data_id ? $(button_trigger).data('donatur') : null; // Mengambil data donatur

        const id_modal = 'modal-crud-donatur';
        const id_form = 'form-crud-donatur';
        const modal_donatur_pekerjaan = `<x-modal-donatur id="${id_modal}" idForm="${id_form}"/>`;

        $('body').append(modal_donatur_pekerjaan); // Masukkan modal ke dalam elemen body
        setHeaderModal(document.getElementById('judul_modal'), `${data_id ? 'Edit' : 'Tambah'} donatur Pekerjaan`); // Set judul modal

        // Deklarasi elemen input pada Form
        const [
            form, nama_lenkap, jenis_kelamin, jenis_identitas, nomor_identitas, email, no_hp, alamat, submit_btn
        ] = [
            id_form, 'nama_lenkap', 'jenis_kelamin', 'jenis_identitas', 'nomor_identitas', 'email', 'no_hp', 'alamat', 'btn_save'
        ].map(id => document.getElementById(id));
        const content_btn_form = $(submit_btn).html(); // Deklarasi konten original button submit form

        // Mode Edit
        if (data_id != null) {
            nama_lenkap.value = data_edit.nama_lenkap, 
            jenis_kelamin.value = data_edit.jenis_kelamin, 
            jenis_identitas.value = data_edit.jenis_identitas, 
            nomor_identitas.value = data_edit.nomor_identitas, 
            email.value = data_edit.email, 
            no_hp.value = data_edit.no_hp, 
            alamat.value = data_edit.alamat
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
                    nama_lenkap: nama_lenkap.value, 
                    jenis_kelamin: jenis_kelamin.value, 
                    jenis_identitas: jenis_identitas.value, 
                    nomor_identitas: nomor_identitas.value, 
                    email: email.value, 
                    no_hp: no_hp.value, 
                    alamat: alamat.value
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
                        data_donatur = await setDonatur();
                        displayData();

                        Toast.fire({
                            icon: "success",
                            title: response.message,
                            text: response.data.nama_lengkap
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
       
    $(document).on('click', '.btn-hapus', function () {
        const data_id = $(this).data('id'); // Mengambil ID kelas dari atribut data-id pada tombol
        const donatur = $(this).data('donatur'); // Mengambil nama kelas dari atribut data-nama pada tombol

        // Tampilkan konfirmasi menggunakan SweetAlert sebelum menghapus
        Swal.fire({
            title: 'Hapus Data Ini?',
            html: `<p class="tw-text-sm">Setelah menghapus Donatur <strong>${donatur.nama_lengkap}</strong> ini, data tidak dapat dikembalikan.</p>`,
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
                    url: "{{ route('donatur.delete') }}", // Endpoint penghapusan data
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
                        data_donatur = await setDonatur(); // Ambil ulang data kelas dari server
                        displayData(); // Render ulang data di tampilan

                        // Tampilkan notifikasi sukses menggunakan Toastify atau SweetAlert
                        Toast.fire({
                            icon: "success",
                            title: response.message,
                            text: response.data.nama_lengkap
                        });
                    },
                    error: function (xhr) {
                        handleFormError(xhr); // Tampilkan notifikasi error jika request gagal
                    }
                });
            }
        });
    });

    const setDonatur = async () => {
            const donatur_mentah = await setData(getData('/donatur/index'));
            return donatur_mentah;
        };

    // Sebelum melakukan load halaman, lakukan set data yang akan ditampilkan
    window.onload = async () => {
        data_donatur = await setDonatur();
        displayData();
    };
</script>
@endsection

@section('content')
<div class="card mt-3">
    <div class="card-header tw-bg-gradient-to-r tw-from-[#0c3f50] tw-to-[#2a9d8f]">
        <h3 class="card-title tw-font-bold tw-text-lg tw-text-white">Data @yield('title')</h3>

        <button type="button" class="float-right btn btn-sm btn-success shadow btn-donatur">
            <i class="fa fa-plus"></i> @yield('title')
        </button>
    </div>
    <!-- /.card-header -->

    <div class="card-body">
        <div class="table-responsive" id="div-tabel-donatur">
            <table id="tabel-donatur" class="table table-bordered table-hover table-sm tw-text-sm">
                <thead class="tw-bg-[#0c3f50] tw-text-white">
                    <tr>
                        <th class="align-middle text-center">#</th>
                        <th class="align-middle text-center">Nama Lenkap</th>
                        <th class="align-middle text-center">Jenis kelamin</th>
                        <th class="align-middle text-center">Identitas</th>
                        <th class="align-middle text-center">Email</th>
                        <th class="align-middle text-center">No Telepon</th>
                        <th class="align-middle text-center">Alamat</th>
                        <th class="align-middle text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8">
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
@endsection