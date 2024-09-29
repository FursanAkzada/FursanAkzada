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
        <table class="table-borderless table" border="0" style="border:none!important;padding-top: 0px;">
            <tbody style="border:none;">
                <tr>
                    <td style="width: 80px;border:none;">No. Surat</td>
                    <td class="text-center" style="width: 10px;border:none;">:</td>
                    <td colspan="2" style="border:none;">{{ $record->kandidat->summary->pengajuan->no_tiket }}</td>
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
                            @foreach ($record->kandidat->summary->pengajuan->to as $item)
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
                            style="text-decoration: underline;">{{ __('Pengajuan Wawancara Kandidat TAD') }}</b></td>
                </tr>
            </tbody>
        </table>
        <br>
        <br>
        <table style="border:none; width:100%;">
            <tr>
                <td style="border:none; width:45%;">
                    <table style="border:none; width:100%;">
                        <tr>
                            <td style="border: none; width: 100px; vertical-align: top;">{{ __('No. Surat') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">
                                {{ $record->kandidat->summary->pengajuan->no_tiket }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 100px; vertical-align: top;">{{ __('Unit Kerja') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">
                                {{ $record->kandidat->summary->pengajuan->so->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 100px; vertical-align: top;">{{ __('Posisi TAD') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">
                                {{ $record->kandidat->summary->requirement->jabatan->NM_UNIT . ' ( ' . $record->kandidat->summary->requirement->jumlah . ' posisi ' . ')' }}
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
                                {{ $record->kandidat->summary->pengajuan->tgl_pengajuan->translatedFormat('d F Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 150px; vertical-align: top;">{{ __('Periode') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">
                                {{ $record->kandidat->summary->pengajuan->year . '/' . $record->kandidat->summary->pengajuan->semester }}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 150px; vertical-align: top;">{{ __('Vendor') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">
                                {{ $record->kandidat->summary->requirement->vendor->nama }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <hr>
        <br>
        <table style="border:none; width:100%;">
            <tr>
                <td style="border:none; vertical-align:top; width: 100px;">{{ __('Kandidat') }}</td>
                <td style="border:none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border:none; vertical-align:top; text-align: left;">{{ $record->kandidat->tad->nama }}</td>
            </tr>
        </table>
        @if ($record->is_fallback == 0)
            <hr>
            <div style="page-break-inside: auto;">
                <table class="table-data" width="100%" border="1">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 40px;">NO</th>
                            <th class="text-center" style="">PERTANYAAN</th>
                            <th class="text-center" style="width: 30px;">1</th>
                            <th class="text-center" style="width: 30px;">2</th>
                            <th class="text-center" style="width: 30px;">3</th>
                            <th class="text-center" style="width: 30px;">4</th>
                            <th class="text-center" style="width: 30px;">5</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total1 = 0;
                            $total2 = 0;
                            $total3 = 0;
                            $total4 = 0;
                            $total5 = 0;
                        @endphp
                        @forelse ($kompetensi as $category)
                            <tr>
                                <td class="text-bold text-center">{{ $loop->iteration }}</td>
                                <td class="text-bold text-left">{{ $category->kompetensi }}</td>
                                <td class="valign-middle text-center"></td>
                                <td class="valign-middle text-center"></td>
                                <td class="valign-middle text-center"></td>
                                <td class="valign-middle text-center"></td>
                                <td class="valign-middle text-center"></td>
                            </tr>
                            @foreach ($record->details as $dd)
                                @if ($dd->pertanyaan->kompetensi_id == $category->id)
                                    @php
                                        $answer1 = $record
                                            ->details()
                                            ->where('id', $dd->id)
                                            ->where('value', 1)
                                            ->count();
                                        $answer2 = $record
                                            ->details()
                                            ->where('id', $dd->id)
                                            ->where('value', 2)
                                            ->count();
                                        $answer3 = $record
                                            ->details()
                                            ->where('id', $dd->id)
                                            ->where('value', 3)
                                            ->count();
                                        $answer4 = $record
                                            ->details()
                                            ->where('id', $dd->id)
                                            ->where('value', 4)
                                            ->count();
                                        $answer5 = $record
                                            ->details()
                                            ->where('id', $dd->id)
                                            ->where('value', 5)
                                            ->count();
                                        $total1 += $answer1;
                                        $total2 += $answer2;
                                        $total3 += $answer3;
                                        $total4 += $answer4;
                                        $total5 += $answer5;
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <div class="text-nowrap">
                                                {{ $loop->parent->iteration . '.' . $loop->iteration }}</div>
                                        </td>
                                        <td class="text-left" style="padding-left: 20px;">
                                            {!! $dd->pertanyaan->pertanyaan !!}
                                        </td>
                                        <td class="width-30px valign-middle specialChar text-center">
                                            @if ($answer1 == 1)
                                                <div style="font-family: DejaVu Sans, sans-serif;">✔</div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="width-30px valign-middle specialChar text-center">
                                            @if ($answer2 == 1)
                                                <div style="font-family: DejaVu Sans, sans-serif;">✔</div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="width-30px valign-middle specialChar text-center">
                                            @if ($answer3 == 1)
                                                <div style="font-family: DejaVu Sans, sans-serif;">✔</div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="width-30px valign-middle specialChar text-center">
                                            @if ($answer4 == 1)
                                                <div style="font-family: DejaVu Sans, sans-serif;">✔</div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="width-30px valign-middle specialChar text-center">
                                            @if ($answer5 == 1)
                                                <div style="font-family: DejaVu Sans, sans-serif;">✔</div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endif

                                @if ($loop->parent->last && $loop->last)
                                    <tr style="background: #aaa;">
                                        <td class="text-bold text-right" colspan="2">TOTAL</td>
                                        <td class="text-bold valign-middle text-center" colspan="5">
                                            {{ $total1 + $total2 * 2 + $total3 * 3 + $total4 * 4 + $total5 * 5 }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @empty
                            <tr>
                                <td class="text-center" colspan="6">Data tidak tersedia!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <br>
                <div><b>Keterangan:</b></div>
                <div style="white-space: pre-wrap;text-align: justify;">{!! $record->keterangan !!}</div>
            </div>
        @else
            <table style="border:none; width:100%;">
                <tr>
                    <td style="border:none; vertical-align:top; width: 100px;">{{ __('Status') }}</td>
                    <td style="border:none; vertical-align:top; width: 10px; text-align: left;">:</td>
                    <td style="border:none; vertical-align:top; text-align: justify;">{!! 'Mengundurkan diri' !!}</td>
                </tr>
                <tr>
                    <td style="border:none; vertical-align:top; width: 100px;">{{ __('Alasan') }}</td>
                    <td style="border:none; vertical-align:top; width: 10px; text-align: left;">:</td>
                    <td style="border:none; vertical-align:top; text-align: justify;">{!! $record->alasan_pengunduran !!}</td>
                </tr>
            </table>
        @endif
        @if ($record->approval($module)->exists())
            <div style="page-break-inside: avoid;">
                <br>
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
    </main>
</body>

</html>
