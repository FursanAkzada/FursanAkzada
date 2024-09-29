<!DOCTYPE html>
<html>

<head>
    <title>Pengajuan Resign {{ $record->no_tiket }}</title>

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
                    <td align="right" style="padding-right: 150px">Surabaya, {{ $record->updated_at->format('d/m/Y') }}
                    </td>
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
                    <td colspan="3"><b><u>Pengajuan Pengakhiran Perjanjian kontrak Kerja Tenaga Alih Daya</u></b>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- <table class="table table-borderless">
            <tbody>
                <tr>
                    <td style="text-indent: 100px;">
                        <p>Terkait dengan pengajuan pengunduran diri Tenaga Alih Daya
                            sesuai dengan surat no. {{ $record->no_tiket }}/944/MLG/UM.AKT/SRT tanggal {{ $record->created_at->format('d/m/Y') }}
                            perihal pengajuan pengunduran diri
                            Tenaga Alih Daya (TAD), maka dengan ini disampaikan bahwa:
                        </p>
                    </td>
                </tr>
            </tbody>
        </table> --}}

        <table class="table-borderless table" style="margin-left: 2em">
            <tbody>
                <tr>
                    <td colspan="2">Menunjuk:</td>
                </tr>
                <tr>
                    <td style="width: 1em">1.</td>
                    <td>
                        Surat {{ $record->so->name }} No. {{ $record->no_tiket }} tanggal
                        {{ $record->created_at->format('d/m/Y') }}
                        perihal Pengajuan Pengakhiran Perjanjian Kontrak Kerja
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Menindaklanjuti hal tersebut diatas, maka dengan ini disampaikan bahwa:
                    </td>
                </tr>
                <tr>
                    <td style="width: 1em">1.</td>
                    <td>
                        Memperhatikan rekomendasi dari Saudara, maka Divisi Human Capital <b><u>menyetujui</u></b>
                        Pengajuan Pengakhiran Perjanjian Kontrak Kerja Tenaga Alih Daya (TAD) a/n
                        tersebut dibawah :
                    </td>
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
                    <th>TGL RESIGN</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $iteration = 1;
                    $unit_kerjas = [];
                @endphp
                @foreach ($record->pegawai as $pegawai)
                    @php
                        $unit_kerjas[] = $pegawai->lastEmployment->unitKerja->name;
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $iteration++ }}</td>
                        <td>{{ $pegawai->nama }}</td>
                        <td style="text-align: center;">{{ $pegawai->jenis_kelamin }}</td>
                        <td>{{ $pegawai->nik }}</td>
                        <td>{{ $pegawai->jabatan->NM_UNIT }}</td>
                        <td>{{ $pegawai->vendor->nama }}</td>
                        <td>{{ $pegawai->lastEmployment->unitKerja->name }}</td>
                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $pegawai->pivot->tanggal_resign)->format('d/m/Y') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="table-borderless table" style="margin-left: 2em">
            <tbody>
                <tr>
                    <td style="width: 1em">2.</td>
                    <td>
                        Untuk diperhatikan terkait hak dan atau kewajiban Tenaga Alih Daya (TAD) yang
                        dilakukan Pengakhiran Perjanjian Kontrak Kerja.
                    </td>
                </tr>
                <tr>
                    <td style="width: 1em">3.</td>
                    <td>
                        Adapun Tenaga Alih Daya (TAD) yang bersangkutan diberhentikan terhitung mulai
                        tanggal 25 Juli 2022.
                        <br>
                        Demikian atas kerjasamanya disampaikan terimakasih.
                    </td>
                </tr>
            </tbody>
        </table>

        <h3 class="text-center" style="padding-bottom: 40px; padding-top: 20px">{{ getRoot()->name }}.<br>DIVISI HUMAN
            CAPITAL</h3>

        <table class="table-borderless table">
            {{-- <tr>
                @foreach ($record->approvals as $approval)
                    <td class="text-center w-50 font-size-lg">
                        <div style="height: 110px;">

                        </div>
                        <div class="font-weight-bold"><u>{{ strtoupper($approval->user->name) }}</u></div>
                        {{ $approval->position->name ?? '' }}
                    </td>
                @endforeach
            </tr> --}}
            <tr>
                <td class="font-size-lg text-center" style="width: 50%">
                    <div style="height: 110px;">
                    </div>
                    <div>
                        <u style="font-weight: bold">
                            Pimpinan Sub Divisi Operasional Human Capital
                        </u>
                    </div>
                    Pimpinan Sub Divisi Operasional Human Capital
                </td>
                <td class="w-50 font-size-lg text-center" style="width: 50%">
                    <div style="height: 110px;">
                    </div>
                    <div>
                        <u style="font-weight: bold">
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
                $unit_kerjas = array_unique($unit_kerjas);
            @endphp
            @foreach ($unit_kerjas as $unit_kerja)
                <tr>
                    <td style="font-size: 11px; text-align: left;" colspan="2">{{ $loop->iteration }}.
                        {{ $unit_kerja }}</td>
                </tr>
            @endforeach
            <tr>
                <td style="font-size: 11px; text-align: left;" colspan="2">{{ count($unit_kerjas) + 1 }}. Arsip</td>
            </tr>
        </table>
    </div>
</body>

</html>
