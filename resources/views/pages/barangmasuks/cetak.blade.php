<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Barang Masuk - Cafe Hom Padang</title>
        <style>
            /* Reset dan gaya dasar */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            body {
                color: #333;
                line-height: 1.5;
                padding: 20px;
                background-color: #fef9e7;
            }

            /* Container utama untuk halaman cetak */
            .container {
                max-width: 1000px;
                margin: 0 auto;
                background-color: white;
                padding: 25px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 5px;
                border: 1px solid #ffcc80;
            }

            /* Kop surat */
            .header {
                display: flex;
                align-items: center;
                margin-bottom: 30px;
                padding-bottom: 20px;
                border-bottom: 3px solid #ff9800;
            }
.logo {
    width: 80px;
    height: 80px;
    margin-right: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* logo tidak gepeng */
}

            .company-info {
                flex: 1;
                text-align: center;
            }

            .company-name {
                color: #ff9800;
                font-size: 36px;
                font-weight: 700;
                margin-bottom: 5px;
            }

            .company-tagline {
                font-size: 18px;
                color: #ff9800;
                margin-bottom: 10px;
                font-style: italic;
                font-weight: 600;
            }

            .company-contact {
                font-size: 18px;
                line-height: 1.6;
                color: #555;
            }

            /* Judul laporan */
            .report-title {
                text-align: center;
                margin-bottom: 25px;
                padding-bottom: 15px;
                border-bottom: 2px solid #ffcc80;
            }

            .report-title h1 {
                color: #333;
                font-size: 24px;
                margin-bottom: 5px;
            }

            .report-title .subtitle {
                color: #ff9800;
                font-size: 16px;
                font-weight: 600;
            }

            /* Informasi transaksi */
            .transaction-info {
                display: flex;
                justify-content: center;
                margin-bottom: 25px;
                padding: 15px;
                background-color: #fff8e1;
                border-radius: 5px;
                border-left: 4px solid #ff9800;
            }

            .info-column {
                flex: 1;
                max-width: 50%;
            }

            .info-label {
                font-weight: 600;
                color: #ff9800;
                display: inline-block;
                width: 150px;
            }

            /* Tabel barang masuk */
            .table-container {
                margin-bottom: 30px;
                overflow-x: auto;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            th {
                background-color: #ff9800;
                color: white;
                text-align: left;
                padding: 12px 15px;
                font-weight: 600;
            }

            td {
                padding: 10px 15px;
                border-bottom: 1px solid #eee;
            }

            tr:nth-child(even) {
                background-color: #fff8e1;
            }

            tr:hover {
                background-color: #ffecb3;
            }

            /* Tanda tangan hanya manajer */
            .signature-section {
                display: flex;
                justify-content: flex-end;
                margin-top: 40px;
                padding-top: 20px;
                border-top: 1px solid #ddd;
            }

            .signature-box {
                text-align: center;
                width: 250px;
            }

            .signature-line {
                height: 1px;
                background-color: #333;
                margin: 40px 0 10px;
            }

            /* Tombol cetak */
            .print-controls {
                text-align: center;
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px dashed #ffcc80;
            }

            .print-button {
                background-color: #ff9800;
                color: white;
                border: none;
                padding: 12px 30px;
                font-size: 16px;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s;
                font-weight: 600;
            }

            .print-button:hover {
                background-color: #f57c00;
            }

            /* Gaya untuk cetak */
            @media print {
                body {
                    background-color: white;
                    padding: 0;
                }

                .container {
                    box-shadow: none;
                    padding: 15px;
                    max-width: 100%;
                    border: none;
                }

                .print-controls {
                    display: none;
                }

                .print-button {
                    display: none;
                }

                table {
                    page-break-inside: auto;
                }

                tr {
                    page-break-inside: avoid;
                    page-break-after: auto;
                }

thead {
                    display: table-header-group;
                }

                .signature-section {
                    page-break-before: avoid;
                    page-break-inside: avoid;
                }
            }

            /* Informasi tambahan */
            .footer {
                margin-top: 30px;
                text-align: center;
                font-size: 12px;
                color: #777;
                padding-top: 15px;
                border-top: 1px solid #ffcc80;
            }
        </style>
    </head>

    <body onload="window.print()">
        <div class="container">
            <!-- Kop Surat -->
            <div class="header">
                <div class="logo">
                    <img src="{{ asset('src/assets/foto/logo_hom.jpg') }}" alt="Logo Cafe Hom Padang">
                </div>
                <div class="company-info">
                    <h1 class="company-name">CAFE HOM PADANG</h1>
                    <p class="company-tagline">Rasa Autentik Minangkabau sejak 1995</p>
                    <div class="company-contact">
                        <p>Jl. Sudirman No. 45, Padang, Sumatera Barat</p>
                        <p>Telepon: (0751) 776543 | Email: info@cafehompadang.com</p>
                        <p>Website: www.cafehompadang.com | Instagram: @cafehompadang</p>
                    </div>
                </div>
            </div>

            <!-- Judul Laporan -->
            <div class="report-title">
                <h1>LAPORAN BARANG MASUK</h1>
                <div class="subtitle">Periode: {{ $start_date->format('d F Y') }} - {{ $end_date->format('d F Y') }}
                </div>
            </div>

            <!-- Informasi Transaksi -->
            <div class="transaction-info">
                <div class="info-column">
                    <p><span class="info-label">Periode:</span> {{ $start_date->format('d F Y') }} -
                        {{ $end_date->format('d F Y') }}</p>
                </div>
                <div class="info-column">
                    <p><span class="info-label">Dicetak pada:</span> <span id="print-date"></span></p>
                </div>
            </div>

            <!-- Tabel Barang Masuk -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Transaksi</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th>Tanggal Kadaluarsa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detail->barangMasuk->nomor_transaksi }}</td>
                                <td>{{ $detail->barang->kode_barang }}</td>
                                <td>{{ $detail->barang->nama }}</td>
                                <td>{{ $detail->barang->satuan->nama }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>{{ $detail->tanggal_kadaluarsa ? $detail->tanggal_kadaluarsa->format('d F Y') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #fff3e0; font-weight: 600;">
                            <td colspan="4" style="text-align: right; color: #ff9800;">TOTAL</td>
                            <td></td>
                            <td>{{ $total_jumlah }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Tanda Tangan hanya Manajer -->
            <div class="signature-section">
                <div class="signature-box">
                                        <p>Padang, <span id="signature-date"></span></p>

                    <p>Mengetahui,</p>
                    <p>Manajer Cafe Hom Padang</p>
                    <div class="signature-line"></div>
                    <p><strong>{{ $manajer->nama }}</strong></p>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>Laporan ini dicetak secara otomatis dari sistem manajemen inventori Cafe Hom Padang.</p>
            </div>

            <!-- Kontrol Cetak -->
            <div class="print-controls">
                <button class="print-button" onclick="window.print()">Cetak Laporan</button>
            </div>
        </div>

        <script>
            // Menambahkan tanggal hari ini secara otomatis
            document.addEventListener('DOMContentLoaded', function() {
                const now = new Date();
                const printOptions = {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',

                };
                const signatureOptions = {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                };
                const formattedPrintDate = now.toLocaleDateString('id-ID', printOptions);
                const formattedSignatureDate = now.toLocaleDateString('id-ID', signatureOptions);
                document.getElementById('print-date').textContent = formattedPrintDate;
                document.getElementById('signature-date').textContent = formattedSignatureDate;
            });
        </script>
    </body>

</html>
