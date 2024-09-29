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
                    <td style="width: 80px;border:none;">Nomor</td>
                    <td class="text-center" style="width: 10px;border:none;">:</td>
                    <td colspan="2" style="border:none;">{{ $record->sk }}</td>
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
                        <b>Yth. @if ($record->toUser)
                                {{ $record->toUser->position->name ?? 'Vendor ' . $record->toUser->vendor->nama }}
                            @endif
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
                            style="text-decoration: underline;">{{ __('Pembinaan TAD') }}</b></td>
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
                            <td style="border: none; width: 100px;">{{ __('Nomor SK') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">{{ $record->sk }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 100px;">{{ __('Unit Kerja') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">
                                {{ $record->kepegawaian->unitKerja->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 100px;">{{ __('Posisi TAD') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">{{ $record->kepegawaian->jabatan->NM_UNIT }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="border:none; width:55%;text-align:right; float:right; position:relative;">
                    <table
                        style="border:none; width:100%; width:100%; position: relative; float:right;  width: auto !important;">
                        <tr>
                            <td style="border: none; width: 100px;">{{ __('Tanggal SK') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">{{ $record->tanggal_sk->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 100px;">{{ __('Vendor') }}</td>
                            <td style="border: none; width: 10px; text-align: left;">:</td>
                            <td style="border: none; text-align: left;">{{ $record->tad->vendor->nama }}</td>
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
                <td style="border:none; vertical-align:top; width: 150px;">{{ __('TAD') }}</td>
                <td style="border:none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border:none; vertical-align:top; text-align: justify;">{!! $record->tad->nama !!}</td>
            </tr>
            <tr>
                <td style="border:none; vertical-align:top; width: 150px;">{{ __('Jenis Pembinaan') }}</td>
                <td style="border:none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border:none; vertical-align:top; text-align: justify;">
                    @foreach (\App\Entities\EHC\JenisPunishment::pembinaan()->get() as $item)
                        {{ $item->Lengkap }}
                    @endforeach
                </td>
            </tr>
            <tr>
                <td style="border:none; vertical-align:top; width: 150px;">{{ __('Tgl Pembinaan') }}</td>
                <td style="border:none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border:none; vertical-align:top; text-align: justify;">
                    {{ $record->getTanggalFormatMulaiSelesai($record->tanggal_mulai, $record->tanggal_selesai) }}
                </td>
            </tr>
            <tr>
                <td style="border:none; vertical-align:top; width: 150px;">{{ __('Deskripsi') }}</td>
                <td style="border:none; vertical-align:top; width: 10px; text-align: left;">:</td>
                <td style="border:none; vertical-align:top; text-align: justify;">{!! $record->eviden !!}</td>
            </tr>
        </table>
        <br>
        <div style="page-break-inside: avoid;">
            <div style="text-align:left;">Lampiran</div>
            @if ($record->files()->exists())
                <ol style="padding-left:20px; margin: 0;">
                    @foreach ($record->files()->get() as $file)
                        <li><a href="{{ $file->file_url }}">{{ $file->file_name }}</a></li>
                    @endforeach
                </ol>
            @else
                <div>Data tidak tersedia!</div>
            @endif
        </div>
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
        @else
            <div style="page-break-inside: avoid;">
                <br><br>
                <div style="text-align: center; margin-top:20px;">{{ getCompanyCityname() }},
                    {{ $record->updated_at->translatedFormat('d/m/Y') }}</div>
                <div style="text-align: center;">{{ __('Dibuat Oleh') }},</div>
                <table style="border:none;margin-bottom:30px;">
                    <tbody>
                        <tr>
                            @if ($record->status == 'completed')
                                <td style="border: none; text-align: center; width: 33%; vertical-align: bottom;">
                                    <div style="height: 110px; padding-top: 15px;">
                                        {!! \Base::getQrcode('Approved by: ' . $record->updater->name . ', ' . $record->updated_at) !!}
                                    </div>
                                    <div><b><u>{{ $record->updater->name }}</u></b></div>
                                    <div>{{ $record->updater->position->name }}</div>
                                </td>
                            @endif
                        </tr>
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
