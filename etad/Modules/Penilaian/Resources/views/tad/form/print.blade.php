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
                <td style="border: none;" align="right"><span class="pagenum"></span></td>
            </tr>
        </table>
    </footer>
    <main>
        <table class="table-borderless table" style="border:none;padding-top: 0px;">
            <tbody>
                <tr>
                    <td style="width: 80px;">Nomor</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="2">{{ $record->no_surat }}</td>
                    <td align="right">{{ getCompanyCityname() }}, {{ $record->updated_at->translatedFormat('d F Y') }}
                    </td>
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
                        <b>Yth. {{ $record->toUser->position->name ?? 'Vendor ' . $record->toUser->vendor->nama }}
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
                    <td colspan="3"><b style="text-decoration: underline;">{{ __('Penilaian TAD') }}</b></td>
                </tr>
            </tbody>
        </table>
        <br>
        <br>
        <table class="table-borderless table" style="border:none;padding-top: 0px;">
            <tbody>
                <tr>
                    <td>Personil</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="3">{{ $record->tad->nama }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="3">{{ $record->kepegawaian->jabatan->NM_UNIT ?? '' }}</td>
                </tr>
                <tr>
                    <td>Unit Kerja</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="3">{{ $record->kepegawaian->unitKerja->name }}</td>
                </tr>
                <tr>
                    <td>Vendor</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="3">{{ $record->tad->vendor->nama }}</td>
                </tr>
                <tr>
                    <td>Periode</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="3">Semester {{ $record->semester }} - Tahun {{ $record->tahun }}</td>
                </tr>
            </tbody>
        </table>
        @php
            const JAWABAN_MAP = [
                1 => 'Sangat Kurang',
                2 => 'Kurang',
                3 => 'Cukup',
                4 => 'Baik',
                5 => 'Sangat Baik',
            ];
        @endphp

        @foreach ($pertanyaan as $index => $item)
            <div style="page-break-inside: avoid; page-break-before: avoid; margin-top:20px;">
                <h3>{{ $loop->iteration . '. ' . $item->judul }}</h3>
                <table class="table-data" width="100%" border="1">
                    <thead>
                        <tr>
                            <th style="width: 25px">No</th>
                            <th>Pertanyaan</th>
                            <th>Jawaban</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($item->child as $child)
                            @php
                                $jawaban = $child
                                    ->jawaban()
                                    ->where('penilai', 0)
                                    ->where('penilaian_id', $record->id)
                                    ->first();
                            @endphp
                            <tr>
                                <td style="text-align: center;">{{ $loop->iteration }}</td>
                                <td>{{ $child->pertanyaan ?? '' }}</td>
                                <td style="text-align: center; width: 100px;">{{ JAWABAN_MAP[$jawaban->value] ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
        <br>
        <div style="page-break-inside: avoid;">
            <table class="table-data" width="100%" border="1">
                <tr>
                    <td style="text-align: justify; width: 150px">
                        <b>Prestasi</b>
                    </td>
                    <td style="text-align: center; width: 2em">:</td>
                    <td>{{ $record->prestasi }}</td>
                </tr>
                <tr>
                    <td style="text-align: justify;">
                        <b>Indisipliner</b>
                    </td>
                    <td style="text-align: center;">:</td>
                    <td>{{ $record->indisipliner }}</td>
                </tr>
                <tr>
                    <td style="text-align: justify;">
                        <b>Saran & Perbaikan</b>
                    </td>
                    <td style="text-align: center;">:</td>
                    <td>{{ $record->saran }}</td>
                </tr>
            </table>
        </div>
        @if ($record->approval($module)->exists())
            <div style="page-break-inside: avoid;">
                <br><br>
                <div class="text-bold" style="text-align: center;"><b>{{ getRoot()->name }}<b></div>
                <table style="border:none; margin-bottom:30px;">
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
    </main>
</body>

</html>
