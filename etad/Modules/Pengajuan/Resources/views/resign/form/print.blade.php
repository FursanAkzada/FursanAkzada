<html>

<head>
    <title>{{ $title }}</title>
    @include('layouts.partials.print-style')
</head>

<body class="page">
    <header>
        <table style="border:none; width: 100%;">
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
                <td style="width: 10%;border: none;" align="right"><span class="pagenum"></span></td>
            </tr>
        </table>
    </footer>
    <main>
        <table class="table-borderless table" style="border:none;padding-top: 0px;">
            <tbody>
                <tr>
                    <td style="width: 80px;border:none;">No. Surat</td>
                    <td class="text-center" style="width: 10px;border:none;">:</td>
                    <td colspan="2" style="border:none;">
                        {{ $record->no_tiket }}</td>
                    <td align="right" style="border:none;">{{ getCompanyCityname() }},
                        {{ $record->updated_at->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td colspan="5"><br></td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">Kepada</td>
                    <td class="text-center" style="width: 10px;vertical-align:top;">:</td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="5">
                        <b>Yth.
                            @foreach ($record->to as $item)
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
                    <td colspan="5"><br><br></td>
                </tr>
                <tr>
                    <td><b>Perihal<b></td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="3"><b style="text-decoration: underline;">{{ $record->perihal }}</b></td>
                </tr>
            </tbody>
        </table>
        <br>
        <br>
        <table class="table-borderless table" style="border:none; width:100%;">
            <tbody>
                <tr>
                    <td colspan="2" style="text-align:justify;"><br>{!! 'Dengan hormat,' !!}<br></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:justify;">{!! $record->pembukaan !!}</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:justify;">{!! $record->isi_surat !!}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <table class="table-border table-data table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA</th>
                    <th>POSISI</th>
                    <th>PENEMPATAN</th>
                    <th>TGL RESIGN</th>
                    <th>TGL EFEKTIF</th>
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
                        <td>{{ $pegawai->nama }} ({{ $pegawai->jenis_kelamin }})</td>
                        <td style="text-align: center;">
                            {{ $pegawai->jabatan->NM_UNIT }}<br>({{ $pegawai->vendor->nama }})</td>
                        <td style="text-align: center;">{{ $pegawai->lastEmployment->unitKerja->name }}</td>
                        <td style="text-align: center;">
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $pegawai->pivot->tanggal_resign)->format('d/m/Y') }}
                        </td>
                        <td style="text-align: center;">
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $pegawai->pivot->tanggal_efektif)->format('d/m/Y') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>
            Demikian atas kerja samanya disampaikan terima kasih.
        </p>
        <div style="page-break-inside: avoid;">
            <br>
            <div style="text-align: left;">{{ __('Lampiran') }}:</div>
            <ul>
                @foreach ($record->files as $file)
                    <li><a href="{{ $file->file_url }}">{{ $file->file_name }}</a></li>
                @endforeach
            </ul>
        </div>
        @if ($record->approval($module)->exists())
            <div style="page-break-inside: avoid;">
                <br><br>
                <div class="text-bold" style="text-align: center;">
                    <b>{{ getRoot()->name }}</b>
                </div>
                <table style="border:none;">
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
                <br>
                @if ($record->cc()->exists())
                    <div style="page-break-inside: avoid; margin-bottom:30px;">
                        <div style="text-align: left;">{{ __('Tembusan') }}:</div>
                        <ul>
                            @foreach ($record->cc as $item)
                                <li>{{ $item->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endif
    </main>
</body>

</html>
