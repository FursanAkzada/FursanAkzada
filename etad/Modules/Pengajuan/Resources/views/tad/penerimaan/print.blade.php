<html>

<head>
    <title>{{ $title }}</title>
    @include('layouts.partials.print-style')
</head>

<body class="page">
    <header>
        <table class="table-borderless table" style="border: none">
            <tr>
                <td style="border:none;width:60%;" width="auto">
                    <img src="{{ config('base.logo.bpd') }}" style="max-width: 200px; max-height: 60px">
                </td>
                <td style="border:none;width:40%; text-align:right; float:right; position:relative;">
                    <img src="{{ config('base.logo.print') }}" style="max-width: 200px; max-height: 120px">
                </td>
            </tr>
        </table>
    </header>
    <footer>
        <table width="100%" border="0" style="border: none;">
            <tr>
                <td style="border: none;" align="right"><span class="pagenum"></span></td>
            </tr>
        </table>
    </footer>
    <main>
        <table class="table-borderless table" border="0" style="border:none!important;padding-top: 0px;">
            <tbody style="border:none;">
                <tr>
                    <td style="width: 80px;border:none;">No. Surat</td>
                    <td class="text-center" style="width: 10px;border:none;">:</td>
                    <td colspan="2" style="border:none;">
                        {{ $record->wawancara->kandidat->summary->pengajuan->no_tiket }}</td>
                    <td align="right" style="border:none;">{{ getCompanyCityname() }},
                        {{ $record->updated_at->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td colspan="5" style="border:none;"><br></td>
                </tr>
                <tr>
                    <td style="vertical-align:top;border:none;">Kepada</td>
                    <td class="text-center" style="width: 10px;vertical-align:top;border:none;">:</td>
                    <td colspan="3" style="border:none;"></td>
                </tr>
                <tr>
                    <td colspan="5" style="border:none;">
                        <b>Yth.
                            @foreach ($record->wawancara->kandidat->summary->pengajuan->to as $item)
                                {{ $item->name }}
                            @endforeach
                        </b><br>
                        <b>{{ getRoot()->name }}</b><br>
                        {{ getRoot()->address }}<br>
                        Di - <br>
                        <b style="text-decoration: underline;">{{ getCompanyCityname() }}<b>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="border:none;"><br><br></td>
                </tr>
                <tr>
                    <td style="border:none;"><b>Perihal<b></td>
                    <td class="text-center" style="width: 10px;border:none;">:</td>
                    <td colspan="3" style="border:none;"><b
                            style="text-decoration: underline;">{{ __('Penerimaan Kandidat TAD') }}</b></td>
                </tr>
            </tbody>
        </table>
        <br><br>
        <table style="border:none; width:100%;">
            <tr>
                <td style="border:none; width:45%;">
                    <table style="border:none; width:100%;">
                        <tr>
                            <td style="border: none; width: 100px; vertical-align: top;">{{ __('No. Surat') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">
                                {{ $record->wawancara->kandidat->summary->pengajuan->no_tiket }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 100px; vertical-align: top;">{{ __('Unit Kerja') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">
                                {{ $record->wawancara->kandidat->summary->pengajuan->so->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 100px; vertical-align: top;">{{ __('Posisi TAD') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">
                                {{ $record->wawancara->kandidat->summary->requirement->jabatan->NM_UNIT . ' ( ' . $record->wawancara->kandidat->summary->requirement->jumlah . ' posisi ' . ')' }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="border:none; width:55%;text-align:right; float:right; position:relative;">
                    <table
                        style="border:none; width:100%; width:100%; position: relative; float:right;  width: auto !important;">
                        <tr>
                            <td style="border: none; width: 150px; vertical-align: top;">{{ __('Tgl Pengajuan') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">
                                {{ $record->wawancara->kandidat->summary->pengajuan->tgl_pengajuan->translatedFormat('d F Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 150px; vertical-align: top;">{{ __('Periode') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">
                                {{ $record->wawancara->kandidat->summary->pengajuan->year . '/' . $record->wawancara->kandidat->summary->pengajuan->semester }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 150px; vertical-align: top;">{{ __('Vendor') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">
                                {{ $record->wawancara->kandidat->summary->requirement->vendor->nama }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table class="table-borderless table" style="border:none; width:100%;">
            <tbody>
                <tr>
                    <td style="text-align: justify;">
                        <br>
                        {!! 'Dengan hormat, ' !!}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: justify;">
                        {!! $record->pembukaan !!}
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table-borderless table" style="border:none;padding-left:30px;">
            <tbody>
                <tr>
                    <td style="width: 180px;">Nama</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="19">{{ $record->wawancara->kandidat->tad->nama }}</td>
                </tr>
                <tr>
                    <td style="width: 180px;">NIO</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="19">{{ $record->nio }}</td>
                </tr>
                <tr>
                    <td style="width: 180px;">Skor Wawancara</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="19">{{ $record->wawancara->details->sum('value') }}</td>
                </tr>
                <tr>
                    <td style="width: 180px;">Tgl Keputusan</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="19">{{ $record->tgl_keputusan->translatedFormat('d F Y') }}</td>
                </tr>
                @if (!$record->tgl_contractdue && !$record->start_date_contract)
                    <tr>
                        <td style="width: 180px;">Hasil Keputusan</td>
                        <td class="text-center" style="width: 10px;">:</td>
                        <td colspan="19">{{ $record->keputusan }}</td>
                    </tr>
                @else
                    <tr>
                        <td>Tgl Awal Kontrak</td>
                        <td class="text-center" style="width: 10px;">:</td>
                        <td colspan="7">
                            {{ $record->start_date_contract ? $record->start_date_contract->translatedFormat('d F Y') : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Tgl Akhir Kontrak</td>
                        <td class="text-center" style="width: 10px;">:</td>
                        <td colspan="7">{{ $record->tgl_contractdue->translatedFormat('d F Y') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <table class="table-borderless table" style="border:none;">
            <tbody>
                <tr>
                    <td colspan="2" style="text-align: justify;text-indent: 100px;"><br></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: justify;">{!! $record->isi_surat !!}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: justify;text-indent: 100px;"><br></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: justify;">{!! $record->penutup !!}</td>
                </tr>
            </tbody>
        </table>

        @if ($record->approval($module)->exists())
            <div style="page-break-inside: avoid;">
                <br><br>
                <div class="text-bold" style="text-align: center;"><b>{{ getRoot()->name }}<b></div>
                <table style="border:none;margin-bottom:30px;">
                    <tbody>
                        @php
                            $ids = $record
                                ->approval($module)
                                ->orderBy('order', 'desc')
                                ->pluck('id')
                                ->toArray();
                            $length = count($ids);
                        @endphp
                        @foreach ($record->approval($module)->get()->reverse() as $approval)
                            <td style="border: none; text-align: center; width: 33%; vertical-align: bottom;">

                                @if ($approval->status == 'approved')
                                    <div style="height: 110px; padding-top: 15px;">
                                        {!! \Base::getQrcode('Approved by: ' . $approval->user->name . ', ' . $approval->approved_at) !!}
                                    </div>

                                    <div><b><u>{{ $approval->user->name }}</u></b></div>
                                    <div>{{ $approval->position->name }}</div>
                                @else
                                    <div style="height: 110px; padding-top: 15px;; color: white;">#</div>
                                    <div><b><u>(............................)</u></b></div>
                                    <div>{{ $approval->group->name }}</div>
                                @endif
                            </td>
                        @endforeach
                    </tbody>
                </table>
                <footer style="width:95%;">
                    <table table width="100%" border="0" style="border: none;">
                        <tr>
                            <td style="border: none;">
                                <small>
                                    <i>***Dokumen ini ditandatangani elektronik oleh
                                        {{ getRoot()->name }}.</i>
                                    <br><i>Tanggal Cetak: {{ now()->translatedFormat('d F Y H:i:s') }}</i>
                                </small>
                            </td>
                        </tr>
                    </table>
                </footer>
            </div>
        @endif
    </main>
</body>

</html>
