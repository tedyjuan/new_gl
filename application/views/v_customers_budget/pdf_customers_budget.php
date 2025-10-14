<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Proposal Penawaran</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            line-height: 1.5;
            margin: 40px;
            color: #000;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 14pt;
            margin: 0;
            text-transform: uppercase;
        }

        .header p {
            font-size: 10pt;
            margin: 2px 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px;
            text-align: left;
            font-size: 11pt;
        }

        th {
            background: #f3f3f3;
        }

        .no-border td {
            border: none !important;
        }

        h2 {
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 4px;
            font-size: 13pt;
        }

        p.center {
            text-align: center;
            margin-top: 2px;
            margin-bottom: 10px;
            font-size: 11pt;
        }

        hr {
            margin: 10px 0 20px;
        }

        .note {
            margin-top: 15px;
            font-style: italic;
        }

        .ttd-table {
            margin-top: 50px;
            width: 100%;
            border: none;
        }

        .ttd-table td {
            border: none;
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 11pt;
        }

        .sign-space {
            border: 1px dashed #999;
            height: 80px;
            width: 200px;
            margin: 10px auto;
        }

        footer {
            margin-top: 40px;
            font-size: 9pt;
            color: #555;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- KOP SURAT -->
    <div class="header">
        <h1><?= strtoupper($dataHeader->company_name) ?></h1>
        <p><?= $dataHeader->company_address ?? 'Alamat perusahaan belum tersedia' ?></p>
        <p>
            Telp. <?= $dataHeader->company_phone ?? '-' ?> |
            Email: <?= $dataHeader->company_email ?? '-' ?>
        </p>
    </div>

    <h2>PROPOSAL PENAWARAN HARGA</h2>
    <p class="center">No: <?= $dataHeader->uuid ?></p>

    <table class="no-border" style="margin-bottom: 20px;">
        <tr>
            <td style="width:25%;">Kepada Yth.</td>
            <td><strong><?= $dataHeader->customer_name ?></strong></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td><?= $dataHeader->address ?? '-' ?></td>
        </tr>
        <tr>
            <td>Telepon</td>
            <td><?= $dataHeader->phone ?? '-' ?></td>
        </tr>
        <tr>
            <td>Nama Proyek</td>
            <td><strong><?= $dataHeader->project_name ?></strong></td>
        </tr>
        <tr>
            <td>Deskripsi</td>
            <td><?= nl2br($dataHeader->description) ?></td>
        </tr>
        <tr>
            <td>Periode Pekerjaan</td>
            <td>
                <?= date('d/m/Y', strtotime($dataHeader->start_timeline)) ?> s/d
                <?= date('d/m/Y', strtotime($dataHeader->end_timeline)) ?>
            </td>
        </tr>
    </table>

    <h4 style="margin-bottom: 6px;">Rincian Penawaran</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tipe</th>
                <th>Nama Item</th>
                <th>Qty</th>
                <th>Satuan</th>
                <th>Harga Satuan (Rp)</th>
                <th>Subtotal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($dataItem as $item) : ?>
                <tr>
                    <td style="text-align:center;"><?= $no++ ?></td>
                    <td><?= ucfirst($item->item_type) ?></td>
                    <td><?= $item->item_name ?></td>
                    <td style="text-align:right;"><?= number_format($item->qty, 0, ',', '.') ?></td>
                    <td><?= $item->unit ?></td>
                    <td style="text-align:right;"><?= number_format($item->unit_price, 0, ',', '.') ?></td>
                    <td style="text-align:right;"><?= number_format($item->qty * $item->unit_price, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" style="text-align:right;">TOTAL</th>
                <th style="text-align:right;"><?= number_format($dataHeader->total_budget, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <?php if (!empty($dataHeader->notes)) : ?>
        <p class="note"><strong>Catatan:</strong> <?= nl2br($dataHeader->notes) ?></p>
    <?php endif; ?>

    <!-- TANDA TANGAN -->
    <table class="ttd-table">
        <tr>
            <td>
                <p><strong>Disetujui Oleh,</strong></p>
                <div class="sign-space"></div>
                <p><strong><?= $dataHeader->customer_name ?></strong></p>
                <p>(Customer)</p>
            </td>
            <td>
                <p><strong>Diajukan Oleh,</strong></p>
                <div class="sign-space"></div>
                <p><strong><?= $dataHeader->company_owner ?></strong></p>
                <p>(Authorized Signature)</p>
            </td>
        </tr>
    </table>

    <footer>
        Dokumen ini dicetak dari sistem dan sah setelah ditandatangani kedua belah pihak.
    </footer>

</body>

</html>
