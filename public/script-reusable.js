/*--------------------------------------------------------------
# Time out, Toast
--------------------------------------------------------------*/
// timeout
let timeoutId = null;

// Toast
var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

/*--------------------------------------------------------------
# Spinner
--------------------------------------------------------------*/

// Content Spinner
const content_spinner = `
    <div class="d-flex justify-content-center align-items-center">
        <div class="spinner-border spinner-border-sm" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
`;

// Spinner Saving
const spinner_saving = `
    <div class="d-flex justify-content-center align-items-center">
        <div class="spinner-border spinner-border-sm" role="status">
            <span class="sr-only">Saving...</span>
        </div>
        <span class="ml-2">Saving</span>
    </div>
`;

// Spinner Loading
const spinner_loading = `
    <div class="d-flex justify-content-center align-items-center">
        <div class="spinner-border spinner-border-sm" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <span class="ml-2">Loading</span>
    </div>
`;

// Spinner Redirecting
const spinner_redirecting = `
    <div class="d-flex justify-content-center align-items-center">
        <div class="spinner-border spinner-border-sm" role="status">
            <span class="sr-only">Redirecting...</span>
        </div>
        <span class="ml-2">Redirecting</span>
    </div>
`;

// Toastr Config
toastr.options = {
    closeButton: false,
    debug: false,
    newestOnTop: false,
    progressBar: false,
    positionClass: 'toast-top-center',
    preventDuplicates: true,
    onclick: null,
    showDuration: 300,
    hideDuration: 1000,
    timeOut: 5000,
    extendedTimeOut: 1000,
    showEasing: 'swing',
    hideEasing: 'linear',
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut'
};

/*--------------------------------------------------------------
# Handle Export Data
--------------------------------------------------------------*/

// Fungsi untuk melakukan export data
const handleExportData = (url, formData, method = 'POST') => {
    $.ajax({
        url: url, // Endpoint penghapusan data
        type: method,
        data: formData,
        beforeSend: () => {
            handleBeforeSendForm(); // Tampilkan loading SweetAlert sebelum request dikirim
        },
        success: async (response) => {
            // Tampilkan notifikasi sukses menggunakan Toastify atau SweetAlert
            Toast.fire({
                icon: "success",
                title: response.message,
            });
        },
        error: function (xhr) {
            handleFormError(xhr); // Tampilkan notifikasi error jika request gagal
        }
    });
}

/*--------------------------------------------------------------
# Tanggal dan Waktu
--------------------------------------------------------------*/

// Fungsi mengambil tanggal dan jam saat ini
function getDateTime() {
    const now = new Date();

    // Format tanggal lokal: YYYY-MM-DD
    const tanggal = now.getFullYear() +
        '-' + String(now.getMonth() + 1).padStart(2, '0') +
        '-' + String(now.getDate()).padStart(2, '0');

    // Ambil jam dan menit saja untuk input type="time"
    const waktu = now.getHours().toString().padStart(2, '0') + ':' +
                now.getMinutes().toString().padStart(2, '0');

    const data = {
        tanggal: tanggal,
        waktu: waktu
    };

    return data;
};

// Fungsi konversi format Tanggal
const formatTanggal = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric', hours: '2-digit' });
};

// Fungsi konversi format Waktu H:i
function formatWaktuHi(timeString) {
    // Tangani format yang dipisahkan spasi: "YYYY-MM-DD HH:mm:ss"
    // Ganti spasi ' ' dengan 'T' agar bisa diparse dengan Date constructor jika tidak ada 'T'
    // Dan tambahkan 'Z' jika tidak ada untuk memastikan ini diperlakukan sebagai UTC (opsional, tergantung kebutuhan zona waktu)
    let processedTimeString = timeString;

    // Periksa apakah string sudah dalam format ISO 8601 standar (dengan T dan Z)
    const isoRegex = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\.\d+)?Z?$/;
    if (!isoRegex.test(timeString)) {
        // Jika tidak, asumsikan format "YYYY-MM-DD HH:mm:ss" atau "YYYY-MM-DD HH:mm"
        // Ganti spasi dengan 'T' untuk memudahkan parsing oleh Date constructor
        processedTimeString = timeString.replace(' ', 'T');
        // Opsional: Jika Anda ingin memperlakukan ini sebagai UTC, tambahkan 'Z'
        // processedTimeString += 'Z'; // Hati-hati dengan ini jika waktu sebenarnya adalah waktu lokal
    }

    // Sekarang, coba parse dengan new Date()
    const date = new Date(processedTimeString);

    // Periksa apakah parsing tanggal valid
    if (isNaN(date.getTime())) {
        // Jika parsing gagal, kembali ke metode pemotongan string sebagai fallback
        console.warn(`Peringatan: Gagal mem-parse waktu "${timeString}" menggunakan Date. Melakukan fallback ke pemotongan string.`);
        return timeString.split(':').slice(0, 2).join(':');
    }

    // Dapatkan jam dan menit
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');

    return `${hours}:${minutes}`;
}

// Fungsi hitung durasi waktu
function hitungDurasiWaktu(tanggal_mulai, tanggal_selesai, waktu_mulai, waktu_selesai) {
    // Fungsi bantu: Normalisasi waktu ke format HH:mm:ss
    const normalisasiWaktu = (waktuStr) => {
        if (typeof waktuStr !== 'string') return null;
        const bagian = waktuStr.split(':');
        if (bagian.length < 2 || bagian.length > 3) return null;
        if (bagian.length === 2) bagian.push('00'); // tambah detik
        const [jam, menit, detik] = bagian;
        return `${jam.padStart(2, '0')}:${menit.padStart(2, '0')}:${detik.padStart(2, '0')}`;
    };

    const waktuMulaiNorm = normalisasiWaktu(waktu_mulai);
    const waktuSelesaiNorm = normalisasiWaktu(waktu_selesai);

    const regexWaktu = /^\d{2}:\d{2}:\d{2}$/;
    if (!waktuMulaiNorm || !waktuSelesaiNorm || !regexWaktu.test(waktuMulaiNorm) || !regexWaktu.test(waktuSelesaiNorm)) {
        console.error("Format waktu tidak valid.");
        return null;
    }

    // Gabungkan tanggal dan waktu menjadi ISO String
    const mulaiISO = `${tanggal_mulai}T${waktuMulaiNorm}`;
    const selesaiISO = `${tanggal_selesai}T${waktuSelesaiNorm}`;

    const mulai = new Date(mulaiISO);
    const selesai = new Date(selesaiISO);

    if (isNaN(mulai.getTime()) || isNaN(selesai.getTime())) {
        console.error("Tanggal atau waktu tidak valid.");
        return null;
    }

    // Jika selesai < mulai, asumsikan lewat tengah malam / hari berikutnya (tapi seharusnya tidak terjadi jika tanggalnya valid)
    if (selesai < mulai) {
        console.warn("Waktu selesai lebih awal dari mulai. Menyesuaikan dengan hari berikutnya.");
        selesai.setDate(selesai.getDate() + 1);
    }

    const selisihMs = selesai.getTime() - mulai.getTime();
    const totalDetik = selisihMs / 1000;
    const totalMenit = totalDetik / 60;
    const totalJam = totalMenit / 60;
    const totalHari = totalJam / 24;

    // Pecah ke komponen
    let sisa = totalDetik;
    const hari = Math.floor(sisa / 86400);
    sisa %= 86400;
    const jam = Math.floor(sisa / 3600);
    sisa %= 3600;
    const menit = Math.floor(sisa / 60);
    const detik = Math.floor(sisa % 60);

    const formatJamMenit = () => {
        const jamTotal = Math.floor(totalJam);
        const menitSisa = Math.round((totalJam - jamTotal) * 60);
        if (jamTotal === 0 && menitSisa === 0) return "0 menit";
        let hasil = [];
        if (jamTotal > 0) hasil.push(`${jamTotal} jam`);
        if (menitSisa > 0) hasil.push(`${menitSisa} menit`);
        return hasil.join(' ');
    };

    const formatLengkap = () => {
        let bagian = [];
        if (hari > 0) bagian.push(`${hari} hari`);
        if (jam > 0) bagian.push(`${jam} jam`);
        if (menit > 0) bagian.push(`${menit} menit`);
        if (detik > 0) bagian.push(`${detik} detik`);
        return bagian.length > 0 ? bagian.join(' ') : "0 detik";
    };

    const data_hasil = {
        totalDetik,
        totalMenit,
        totalJam,
        totalHari,
        rincian: { hari, jam, menit, detik },
        format: {
            jamDanMenit: formatJamMenit(),
            lengkap: formatLengkap()
        }
    }

    return data_hasil;
}

function formatTanggalPelaksanaan(tanggalMulaiStr, tanggalSelesaiStr) {
    const bulanIndonesia = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    const hariIndonesia = [
        "Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"
    ];

    const tanggalMulai = new Date(tanggalMulaiStr);
    const tanggalSelesai = new Date(tanggalSelesaiStr);

    const hariMulai = hariIndonesia[tanggalMulai.getDay()];
    const hariSelesai = hariIndonesia[tanggalSelesai.getDay()];

    const tglMulai = tanggalMulai.getDate();
    const tglSelesai = tanggalSelesai.getDate();

    const blnMulai = bulanIndonesia[tanggalMulai.getMonth()];
    const blnSelesai = bulanIndonesia[tanggalSelesai.getMonth()];

    const thnMulai = tanggalMulai.getFullYear();
    const thnSelesai = tanggalSelesai.getFullYear();

    let hari;
    let tanggal;

    if (tanggalMulaiStr === tanggalSelesaiStr) {
        hari = hariMulai;
        tanggal = `${tglMulai} ${blnMulai} ${thnMulai}`;
    } else {
        hari = `${hariMulai} - ${hariSelesai}`;

        if (tanggalMulai.getMonth() === tanggalSelesai.getMonth() && thnMulai === thnSelesai) {
            // Bulan dan tahun sama
            tanggal = `${tglMulai}-${tglSelesai} ${blnMulai} ${thnMulai}`;
        } else if (thnMulai === thnSelesai) {
            // Tahun sama, bulan beda
            tanggal = `${tglMulai} ${blnMulai} - ${tglSelesai} ${blnSelesai} ${thnMulai}`;
        } else {
            // Tahun beda
            tanggal = `${tglMulai} ${blnMulai} ${thnMulai} - ${tglSelesai} ${blnSelesai} ${thnSelesai}`;
        }
    }

    const data_hasil = {
        hari: hari,
        tanggal: tanggal
    }

    return data_hasil;
}

/*--------------------------------------------------------------
# Check Logo Bank di Penyimpanan
--------------------------------------------------------------*/

const checkLogoBank = (logo_bank) => {
    let exists = false;

    $.ajax({
        type: "HEAD", // Lebih ringan, hanya cek keberadaan file
        url: `/website-resource/logo/bank/svg/${logo_bank}`,
        async: false, // Sinkron, tunggu hingga request selesai
        success: function () {
            exists = true;
        },
        error: function () {
            exists = false;
        }
    });

    return exists;
};

/*--------------------------------------------------------------
# Header Judul
--------------------------------------------------------------*/

// Fungsi melakukan Set Header Modal
const setHeaderModal = (section_header, title) => {
    section_header.innerHTML = title;
};

/*--------------------------------------------------------------
# Alert
--------------------------------------------------------------*/

// Fungsi Reset Alert
const resetAlert = (input, alert) => {
    input.classList.remove('is-valid', 'is-invalid');
    alert.classList.remove('valid-feedback', 'invalid-feedback');
    alert.innerHTML = "";
    alert.classList.add('d-none');
};

// Fungsi Set Alert
const setAlert = (status, input, alert, message) => {
    resetAlert(input, alert);

    if (status) {
        input.classList.add('is-valid');
        alert.classList.remove('d-none');
        alert.classList.add('valid-feedback');
        alert.innerHTML = message;
    } else {
        input.classList.add('is-invalid');
        alert.classList.remove('d-none');
        alert.classList.add('invalid-feedback');
        alert.innerHTML = message;
    }
};

/*--------------------------------------------------------------
# Button
--------------------------------------------------------------*/

// Fungsi Disable Button
const disableButton = (button, status, content) => {
    status ? button.disabled = true : button.disabled = false;
    button.innerHTML = content;
};

/*--------------------------------------------------------------
# Redirect
--------------------------------------------------------------*/

// Tambahkan input
const addInput = (form_isian, name_input, value_input) => {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = name_input;
    input.value = value_input;
    form_isian.appendChild(input);
};

// Fungsi Handle Redirect Page
const handleRedirectPage = (button, route, formData) => {
    const button_content = button.innerHTML;

    disableButton(button, true, content_spinner); // Nonaktifkan tombol yang men-trigger

    // Tampilkan Swal Loading
    Swal.fire({
        title: "Mengalihkan",
        text: "Harap tunggu sebentar",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Buat form dinamis
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = route;

    // Tambahkan token CSRF dan method GET
    addInput(form, '_token', "{{ csrf_token() }}");
    addInput(form, '_method', "GET");

    // Loop isi formData dan tambahkan input otomatis
    for (const [key, value] of Object.entries(formData)) {
        addInput(form, key, value);
    }

    // Submit form
    document.body.appendChild(form);
    form.submit();

    // Kode ini akan mencoba berjalan setelah 3 detik (Nanti FE perlu penyesuaian agar bisa merubah kode ini menjadi lebih efektif dari segi UX)
    setTimeout(() => {
        disableButton(button, false, button_content);
        endHandleGetData();
    }, 3000);
};

/*--------------------------------------------------------------
# Handle Ajax
--------------------------------------------------------------*/

// Fungsi handle get Data
const handleGetData = () => {
    Swal.fire({
        title: "Mengambil data",
        text: "Harap tunggu sebentar",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

// Fungsi Handle Success Form
const handleAlertGetData = () => {
    Swal.fire({
        title: "Mengambil Data",
        text: "Harap tunggu sebentar",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
};

// Fungsi handle end get Data
const endHandleGetData = () => {
    // Tutup SweetAlert setelah setOptionSelect selesai dan kondisi (jika ada) terpenuhi
    Swal.close();
}

// Fungsi Handle Success Form
const handleBeforeSendForm = () => {
    Swal.fire({
        title: "Memproses...",
        text: "Harap tunggu sebentar",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
};

// Fungsi Handle Error Form
const handleFormError = (xhr) => {
    console.log(xhr);

    if (xhr.status === 419) {
        // Session expired
        if (timeoutId) clearTimeout(timeoutId);

        Swal.fire({
            icon: 'error',
            title: 'Session expired, reloading page...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        timeoutId = setTimeout(() => {
            location.reload();
        }, 2000);

    } else if (xhr.status === 403) {
        Swal.fire({
            icon: 'error',
            title: xhr.statusText,
            text: xhr.responseJSON?.message || 'Akses ditolak',
            allowOutsideClick: false,
        });

    } else if (xhr.status === 422) {
        const messages = Object.values(xhr.responseJSON || {}).flat();

        Swal.fire({
            title: "<strong>Perhatian!</strong>",
            icon: "warning",
            html: `
                <ul class="tw-text-sm">
                    ${messages.map(m => `<li>- ${m}</li>`).join('')}
                </ul>
            `,
            showDenyButton: true,
            denyButtonText: `Tutup`,
            showConfirmButton: false,
            allowOutsideClick: false,
        });

    } else {
        Swal.fire({
            icon: 'error',
            title: xhr.statusText || 'Error',
            text: xhr.responseJSON.message ?? 'Gagal memproses data',
            showDenyButton: true,
            denyButtonText: `Tutup`,
            showConfirmButton: false,
            allowOutsideClick: false,
        });
    }
};

/*--------------------------------------------------------------
# Handle Input dan Select
--------------------------------------------------------------*/

// Mengisi option pada select
const fillSelectOptions = (selectElement, items, getValue, getLabel, placeholder = '-- Pilih --') => {
    selectElement.innerHTML = `<option value="" selected disabled>${placeholder}</option>`;
    items.forEach(item => {
        selectElement.innerHTML += `<option value="${getValue(item)}">${getLabel(item)}</option>`;
    });
};

// Melakukan set value pada select2
const setSelectValue = (selector, value) => {
    $(selector).val(value).trigger('change');
};

// Fungsi melakukan set option pada select
const setOptionSelect = (select, placeholder, data) => {
    select.innerHTML = `
        <option value="" selected disabled>-- ${placeholder} --</option>
        ${data.map(item => `<option value="${item.value}">${item.name}</option>`)}
    `;
}

/*--------------------------------------------------------------
# Menghilangkan element
--------------------------------------------------------------*/

function hideElements(elements) {
    elements.forEach(group => {
        group.forEach(el => el.classList.add('d-none'));
    });
}

/*--------------------------------------------------------------
# Handle Konfigurasi DataTable
--------------------------------------------------------------*/

// Fungsi set DataTable
const getDataTableConfig = (nama_id_tabel) => ({
    dom:
        "<'row mb-1'<'col-sm-6'l><'col-sm-6 d-flex justify-content-sm-end'>>" +
        "<'row mb-2'<'col-sm-6'B><'col-sm-6 d-flex justify-content-sm-end'f>>" +
        "<'row'<'col-12'tr>>" +
        "<'row mt-3'<'col-sm-5'i><'col-sm-7'p>>",
    pageLength: 25,
    buttons: [
        {
            extend: 'colvis',
            text: '<i class="fas fa-columns"></i> Kolom',
            className: 'btn btn-warning btn-sm'
        },
        {
            extend: 'excelHtml5',
            text: '<i class="fas fa-file-excel"></i> Excel',
            className: 'btn btn-success btn-sm',
            exportOptions: {
                columns: ':visible'  // Hanya ekspor kolom yang terlihat
            },
        },
        {
            extend: 'pdfHtml5',
            text: '<i class="fas fa-file-pdf"></i> PDF',
            className: 'btn btn-danger btn-sm',
            orientation: 'landscape',
            pageSize: 'A4',
            exportOptions: {
                columns: ':visible'  // Hanya ekspor kolom yang terlihat
            },
            customize: function (doc) {
                // Mengatur warna latar belakang dan teks header di PDF
                doc.content[1].table.headerRows = 1;
                
                // Setel gaya header (thead) untuk PDF
                doc.content[1].table.body.forEach(function (row, rowIndex) {
                    row.forEach(function (cell, cellIndex) {
                        if (rowIndex === 0) {  // Header row
                            cell.fillColor = '#0c3f50';  // Warna latar belakang header
                            cell.color = 'white';  // Warna teks header
                            cell.style = 'font-weight: bold; vertical-align: middle; text-align: center;'; // Teks tebal dan vertikal di tengah
                        } else {
                            // Setel gaya untuk td (cell body)
                            cell.style = 'vertical-align: middle; text-align: center;';  // Vertikal tengah dan teks di tengah
                        }
                    });
                });
            }
        },
        {
            extend: 'print',
            text: '<i class="fas fa-print"></i> Print',
            className: 'btn btn-secondary btn-sm',
            orientation: 'landscape',
            exportOptions: {
                columns: ':visible'  // Hanya ekspor kolom yang terlihat
            },
            customize: function (win) {
                $(win.document.body).css('font-size', '10pt');
                $(win.document.body).find('table')
                    .addClass('table table-bordered')
                    .css('font-size', 'inherit')
                    .find('thead th') // Mengubah header table
                    .css({
                        'background-color': '#0c3f50',  // Warna latar belakang header
                        'color': 'white',  // Warna teks header
                        'font-weight': 'bold',  // Font tebal
                        'vertical-align': 'middle',  // Rata tengah vertikal
                        'text-align': 'center'  // Rata tengah horizontal
                    })
                    .end()
                    .find('td') // Mengubah td
                    .css({
                        'vertical-align': 'middle',  // Rata tengah vertikal
                        'text-align': 'center'  // Rata tengah horizontal
                    });
            }
        }
    ],
    lengthChange: true,
    responsive: true,
    initComplete: function () {
        const input = $(`#${nama_id_tabel}_filter input`);
        input.attr('placeholder', 'Cari data...');
        input.addClass('tw-border tw-border-gray-300 tw-text-gray-900 tw-text-sm tw-rounded-lg focus:tw-ring-blue-500 focus:tw-border-blue-500 tw-block tw-w-full');
        $(`#${nama_id_tabel}_filter label`).addClass('d-flex align-items-center gap-2 mb-0');
    }
});

/*--------------------------------------------------------------
# Format Uang dan unformat Uang
--------------------------------------------------------------*/

function formatUang(nominal) {
    // Ubah ke string, hilangkan desimal (kalau ada)
    nominal = nominal.toString().split(/[.,]/)[0];

    // Format nominal dengan titik setiap 3 digit
    return nominal.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function unformatUang(nominal) {
    return nominal.replace(/\./g, '');
};

/*--------------------------------------------------------------
# Fungsi Warna
--------------------------------------------------------------*/

// Merubah string menjadi warna
function stringToColor(str) {
    // Tambah 'salt' untuk beda hasil meski awalan huruf sama
    str = 'Z!' + str + '#x';

    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }

    hash = Math.abs(hash);

    const hue = hash % 360;
    const saturation = 60 + (hash % 20); // 60–80%
    const lightness = 50 + (hash % 10);  // 50–60%

    return `hsl(${hue}, ${saturation}%, ${lightness}%)`;
}

/*--------------------------------------------------------------
# Handle Data dari AJAX
--------------------------------------------------------------*/
// Get Data pada database
const getData = async (endpoint, formData = null, method = "GET") => {
    try {
        // Mengonfigurasi pengaturan untuk request
        const config = {
            method: method,
            headers: {
                "Accept": "application/json", // Memberitahu server bahwa kita ingin menerima JSON
                "Content-Type": "application/json",  // Jika kita mengirim data, kita mengirimkan JSON
            }
        };

        // Jika ada formData dan metode bukan GET (karena GET tidak mengirimkan data dalam body)
        if (formData && method !== "GET") {
            config.body = JSON.stringify(formData);  // Menyertakan formData dalam body request
        }

        // Melakukan request
        const response = await fetch(endpoint, config);

        // Memeriksa apakah responsnya OK (status HTTP 200-299)
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        // Mengambil data dalam format JSON
        const json = await response.json();
        console.log(json);
        
        return json;
    } catch (error) {
        console.error("Error fetching data:", error);
        throw error; // Lemparkan kembali error
    }
};

const getDataApi = async (endpoint, formData = null, method = "GET") => {
    try {
        // Mengonfigurasi pengaturan untuk request
        const config = {
            method: method,
            headers: {
                "Accept": "application/json", // Memberitahu server bahwa kita ingin menerima JSON
                "Content-Type": "application/json",  // Jika kita mengirim data, kita mengirimkan JSON
                "X-App-Token": "Bz31vdAw2HykT1ORisq4mXfhALuPwTx3ynIsxCwd"
            }
        };

        // Jika ada formData dan metode bukan GET (karena GET tidak mengirimkan data dalam body)
        if (formData && method !== "GET") {
            config.body = JSON.stringify(formData);  // Menyertakan formData dalam body request
        }

        // Melakukan request
        const response = await fetch(endpoint, config);

        // Memeriksa apakah responsnya OK (status HTTP 200-299)
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        // Mengambil data dalam format JSON
        const json = await response.json();
        console.log(json);
        
        return json;
    } catch (error) {
        console.error("Error fetching data:", error);
        throw error; // Lemparkan kembali error
    }
}

// Melakukan set data dari fungsi get data
const setData = async (function_get) => {
    const json = await function_get;
    const data = json.data;
    
    return data;
};
