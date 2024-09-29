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
        <br><br>
        <table class="table-borderless table" border="0" style="border:none!important;padding-top: 0px;">
            <tbody style="border:none;">
                <tr>
                    <td style="width: 80px;border:none;">No. Surat</td>
                    <td class="text-center" style="width: 10px;border:none;">:</td>
                    <td colspan="2" style="border:none;">
                        {{ $record->no_pengajuan }}</td>
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
                            {{ $record->toUser->position->name ?? 'Vendor ' . $record->toUser->vendor->nama }}
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
                            style="text-decoration: underline;">{{ __('Perpanjangan Kontrak TAD') }}</b></td>
                </tr>
            </tbody>
        </table>
        <br><br>
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
                    <td colspan="19">{{ $record->tad->nama }}</td>
                </tr>
                <tr>
                    <td style="width: 180px;">NIO</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="19">{{ $record->kepegawaian->nio }}</td>
                </tr>
                <tr>
                    <td style="width: 180px;">Posisi TAD</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="19">{{ $record->kepegawaian->jabatan->NM_UNIT }}</td>
                </tr>
                <tr>
                    <td style="width: 180px;">Vendor TAD</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="19">{{ $record->kepegawaian->vendor->nama }}</td>
                </tr>
                <tr>
                    <td style="width: 180px;">Unit Kerja</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="19">{{ $record->unitKerja->name }}</td>
                </tr>
                <tr>
                    <td style="width: 180px;">Tgl Akhir Kontrak Lama</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="19">
                        {{ $record->tgl_akhir_kontrak_lama ? $record->tgl_akhir_kontrak_lama->translatedFormat('d F Y') : ($record->tad->date_old_contract ? $record->tad->date_old_contract->translatedFormat('d F Y') : '') }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 180px;">Tgl Awal Kontrak Baru</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="19">{{ $record->tgl_awal_kontrak_baru->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td style="width: 180px;">Tgl Akhir Kontrak Baru</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="19">{{ $record->tgl_akhir_kontrak_baru->translatedFormat('d F Y') }}</td>
                </tr>
            </tbody>
        </table>
        <table class="table-borderless table" style="border:none;">
            <tbody>
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
                        @for ($i = 0; $i < $length; $i += 4)
                            <tr>
                                @if (!empty($ids[$i]))
                                    <td style="border: none; text-align: center; width: 33%; vertical-align: bottom;">
                                        @if ($approval = $record->approval($module)->find($ids[$i]))
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
                                        @endif
                                    </td>
                                @endif
                                @if (!empty($ids[$i + 1]))
                                    <td style="border: none; text-align: center; width: 33%; vertical-align: bottom;">
                                        @if ($approval = $record->approval($module)->find($ids[$i + 1]))
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
                                        @endif
                                    </td>
                                @endif
                                @if (!empty($ids[$i + 2]))
                                    <td style="border: none; text-align: center; width: 33%; vertical-align: bottom;">
                                        @if ($approval = $record->approval($module)->find($ids[$i + 2]))
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
                                        @endif
                                    </td>
                                @endif
                                @if (!empty($ids[$i + 3]))
                                    <td style="border: none; text-align: center; width: 33%; vertical-align: bottom;">
                                        @if ($approval = $record->approval($module)->find($ids[$i + 3]))
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
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endfor
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
        <br>
        @if ($record->cc()->exists())
            <div style="page-break-inside: avoid;">
                <div style="text-align: left;">{{ __('Tembusan') }}:</div>
                <ol>
                    @foreach ($record->cc()->get() as $cc)
                        <li>{{ $cc->name }}</li>
                    @endforeach
                </ol>
            </div>
        @endif
    </main>
</body>

</html>
