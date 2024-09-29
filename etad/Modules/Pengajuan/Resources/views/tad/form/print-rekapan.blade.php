<!DOCTYPE html>
<html>

<head>
    <title>Pengajuan TAD {{ $record->no_tiket }}</title>

    @include('layouts.partials.print-style')
</head>

<body>
    <header>
        <table class="table-borderless table">
            <tr>
                <td valign="middle" style="vertical-align: middle">
                    <img src="/assets/images/export/bpd.jpg" alt="" style="height:90px" />
                </td>
                <td valign="middle" align="right" style="vertical-align: middle">
                    <img src="/assets/images/export/bjtm.jpg" alt="" style="height:60px" />
                </td>
            </tr>
        </table>
    </header>
    <footer>
        <img src="/assets/images/export/kop-footer.jpg" alt="" style="width:100%;" />
    </footer>

    <div class="card-body">
        <table class="table-borderless table" style="padding-top: 30px;">
            <tbody>
                <tr>
                    <td style="width: 80px;">Nomor</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="2">
                        {{ $record->no_tiket }}/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/HCP/OHC/SCB
                    </td>
                    <td align="right" style="padding-right: 150px">Surabaya,
                        {{ $record->verified_at->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td>Lampiran</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="3">-</td>
                </tr>
                <tr>
                    <td>Kepada Yth</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="3">
                        <b>Pemimpin {{ $record->so->name }} </b><br>
                        <b>PT Bank Pembangunan Daerah Jawa Timur Tbk</b><br>
                        Di <b>Tempat<b>
                    </td>
                </tr>
                <tr>
                    <td>Perihal</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="3"><b><u>Persetujuan Perekrutan Tenaga Alih Daya {{ $record->so->name }}</u></b>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table-borderless table">
            <tbody>
                <tr>
                    <td style="text-indent: 100px;">
                        <p>Terkait dengan pemenuhan kekosongan tenaga alih daya pada {{ $record->so->name }}
                            sesuai dengan surat no. {{ $record->no_tiket }} tanggal {{ $record->tanggal }} perihal
                            Permohonan
                            Penambahan Tenaga Alih Daya (TAD), maka dengan ini disampaikan bahwa:
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table-borderless table">
            <tbody>
                <tr>
                    <td style="width: 5px">1.</td>
                    <td>Divisi Human Capital menyetujui perekrutan tenaga alih daya baru a/n tersebut dibawah:</td>
                </tr>
            </tbody>
        </table>

        <table class="table-border table" style="padding-left: 30px;">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA</th>
                    <th>L/P</th>
                    <th>NIP</th>
                    <th>POSISI</th>
                    <th>VENDOR</th>
                    <th>PENEMPATAN</th>
                    <th>TGL MASUK</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $iteration = 1;
                    $vendors = [];
                    $tgl_penerimaans = [];
                @endphp
                @foreach ($record->requirement as $requirement)
                    @php
                        $vendors[] = $requirement->vendor->nama;
                    @endphp
                    @foreach ($requirement->kandidats as $kandidat)
                        @if ($kandidat->accepted == 1)
                            @php
                                // $tgl_penerimaans[] = $kandidat->employment->in_at;
                                $tgl_penerimaans[] = $kandidat->employment->in_at->format('Y-m-d');
                            @endphp
                            <tr>
                                <td style="text-align: center;">{{ $iteration++ }}</td>
                                <td>{{ $kandidat->tad->nama }}</td>
                                <td style="text-align: center;">{{ $kandidat->tad->jenis_kelamin }}</td>
                                <td>{{ $kandidat->tad->nik }}</td>
                                <td>{{ $requirement->jabatan->NM_UNIT }}</td>
                                <td>{{ $requirement->vendor->nama }}</td>
                                <td>{{ $record->so->name }}</td>
                                <td>
                                    {{ $kandidat->employment->in_at->format('d/m/Y') }}
                                    {{-- s/d --}}
                                    {{-- {{ $kandidat->employment->contract_due->format('d/m/Y') }} --}}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
                @php
                    $tgl_penerimaan = collect($tgl_penerimaans)
                        ->sort()
                        ->first();
                @endphp
                {{-- {{ dd($tgl_penerimaan) }} --}}
            </tbody>
        </table>

        <table class="table-borderless table" style="padding-bottom: 50px">
            <tbody>
                <tr>
                    <td style="width: 5px">2.</td>
                    <td style="text-align: justify;">Saudara wajib mengirimkan kekurangan data soft copy foto yang
                        bersangkutan ke Email:
                        rekruthc@bankjatim.co.id untuk dibuatkan ID Card serta dilampirkan format
                        pengiriman Nama Lengkap, Nama panggilan, Alamat, Kota, Agama dan untuk dapat
                        mempekerjakan tenaga yang dimaksud diatas per tanggal
                        {{ \Carbon\Carbon::createFromFormat('Y-m-d', $tgl_penerimaan)->format('d/m/Y') }} sesuai dengan
                        kontrak Perjanjian Kerjasama Bank Jatim dengan Vendor Tenaga Alih Daya.</td>
                </tr>
                <tr>
                    <td style="width: 5px">3.</td>
                    <td style="text-align: justify;">Data tersebut untuk dapat diterima di Divisi Human Capital paling
                        lambat 3 (tiga) hari kerja
                        setelah Saudara menerima Surat ini.
                    </td>
                </tr>
                <tr>
                    <td style="width: 5px">4.</td>
                    <td style="text-align: justify;">Apabila Tenaga Alih Daya (TAD) tersebut tidak masuk bekerja sampai
                        dengan 5 (lima) hari
                        sejak ditetapkannya tanggal efektif diatas (sesuai poin 2), maka Saudara diharapkan untuk
                        membuat surat/nota pemberitahuan ke Divisi Human Capital.
                    </td>
                </tr>
                <tr>
                    <td style="width: 5px">5.</td>
                    <td style="text-align: justify;">Untuk penambahan Tenaga Alih Daya berikutnya, Saudara wajib untuk
                        mendapatkan
                        persetujuan dari Divisi Human Capital sebelum dilakukan tes/diterima oleh
                        Unit Kerja/Vendor Tenaga Alih Daya dan berpedoman pada Surat Keputusan Direksi No.
                        060/03/55/DIR/HCP/KEP tanggal 24 Desember 2021 perihal Standard Operating Procedure
                        (SOP) Alih Daya Pekerjaan PT Bank Jatim, Tbk.
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: justify;">Demikian untuk dijadikan pedoman dan pelaksanaan.
                    </td>
                </tr>
            </tbody>
        </table>

        <h3 class="text-center" style="padding-bottom: 40px;">{{ getRoot()->name }}.<br>DIVISI HUMAN CAPITAL</h3>

        <table class="table-borderless table">
            {{-- <tr>
                @foreach ($record->approvals as $approval)
                    <td class="text-center w-50 font-size-lg">
                        <div style="height: 110px;">
                            {!! getQrcode('Approved by: '.$approval->user->name.', '.$record->approved_at) !!}
                        </div>
                        <div class="font-weight-bold"><u>{{ strtoupper($approval->user->name) }}</u></div>
                        {{ $approval->position->name }}
                    </td>
                @endforeach
            </tr> --}}
            <tr>
                <td class="font-size-lg text-center" style="width: 50%">
                    <div style="height: 110px;">
                    </div>
                    <div style="font-weight: bold">
                        <u>
                            Pimpinan Sub Divisi Operasional Human Capital
                        </u>
                    </div>
                    Pimpinan Sub Divisi Operasional Human Capital
                </td>
                <td class="w-50 font-size-lg text-center" style="width: 50%">
                    <div style="height: 110px;">
                    </div>
                    <div style="font-weight: bold">
                        <u>
                            Pimpinan Divisi Human Capital
                        </u>
                    </div>
                    Pimpinan Divisi Human Capital
                </td>
            </tr>
            <tr>
                <td style="font-size: 11px; padding-top: 20px; text-align: left; font-style: bold;" colspan="2">
                    <u>Tindasan :</u></td>
            </tr>
            @php
                $vendors = array_unique($vendors);
            @endphp
            @foreach ($vendors as $vendor)
                <tr>
                    <td style="font-size: 11px; text-align: left;" colspan="2">{{ $loop->iteration }}.
                        {{ $vendor }}</td>
                </tr>
            @endforeach
            <tr>
                <td style="font-size: 11px; text-align: left;" colspan="2">{{ count($vendors) + 1 }}. Arsip</td>
            </tr>
        </table>
    </div>
</body>

</html>
