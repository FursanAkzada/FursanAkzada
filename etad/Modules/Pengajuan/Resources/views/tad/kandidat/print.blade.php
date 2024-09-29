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
                    <td style="width: 80px;">No. Surat</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="2">{{ $record->pengajuan->no_tiket }}</td>
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
                        <b>Yth.
                            @foreach ($record->pengajuan->to as $item)
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
                    <td colspan="3"><b style="text-decoration: underline;">{{ __('Pengajuan Kandidat TAD') }}</b>
                    </td>
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
                            <td style="border: none; text-align: left; vertical-align: top;">{{ $record->pengajuan->no_tiket }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 100px; vertical-align: top;">{{ __('Unit Kerja') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">{{ $record->pengajuan->so->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 100px; vertical-align: top;">{{ __('Posisi TAD') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">
                                {{ $record->requirement->jabatan->NM_UNIT . ' ( ' . $record->requirement->jumlah . ' posisi ' . ')' }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="border:none; width:55%;text-align:right; float:right; position:relative;">
                    <table
                        style="border:none; width:100%; width:100%; position: relative; float:right;  width: auto !important;">
                        <tr>
                            <td style="border: none; width: 110px; vertical-align: top;">{{ __('Tgl Pengajuan') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">
                                {{ $record->pengajuan->tgl_pengajuan->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 110px; vertical-align: top;">{{ __('Periode') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">
                                {{ $record->pengajuan->year . '/' . $record->pengajuan->semester }}</td>
                        </tr>
                        <tr>
                            <td style="border: none; width: 110px; vertical-align: top;">{{ __('Vendor') }}</td>
                            <td style="border: none; width: 10px; text-align: left; vertical-align: top;">:</td>
                            <td style="border: none; text-align: left; vertical-align: top;">{{ $record->requirement->vendor->nama }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <br>
        <div style="page-break-inside: avoid;">
            <table class="table-data" width="100%" border="1">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 30px;">No</th>
                        <th class="text-center">{{ __('Nama') }}</th>
                        <th class="text-center">{{ __('NIK') }}</th>
                        <th class="text-center">{{ __('Email') }}</th>
                        <th class="text-center">{{ __('No Telp') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($record->details()->get() as $part)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">
                                {{ $part->tad->nama }}
                            </td>
                            <td class="text-center">
                                {{ $part->tad->nik }}
                            </td>
                            <td class="text-center">
                                {{ $part->tad->email }}
                            </td>
                            <td class="text-center">
                                {{ $part->tad->telepon }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="6">{{ __('Data tidak tersedia!') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($record->approval($module)->exists())
            <div style="page-break-inside: avoid;">
                <br>
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
