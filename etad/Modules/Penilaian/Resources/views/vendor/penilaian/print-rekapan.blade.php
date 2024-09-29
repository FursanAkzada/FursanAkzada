<!DOCTYPE html>
<html>

<head>
    <title>Penilaian Vendor</title>

    @include('layouts.partials.print-style')
</head>

<body>
    <header>
        <table class="table-borderless table">
            <tr>
                <td style="border:none;" width="100px">
                    <img src="{{ config('base.logo.print') }}" style="max-width: 100px; max-height: 60px">
                </td>
                <td style="border:none;  text-align: center; font-size: 14pt;" width="auto">
                    <b>{{ __('Penilaian Vendor') }}</b>
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
                    <td class="pb-4 text-center" colspan="5"><b style="font-size: 1.5em">Penilaian Vendor</b></td>
                </tr>
                <tr>
                    <td style="width: 80px;">Unit Kerja</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="2">{{ $record->unitKerja->name }}</td>
                </tr>
                <tr>
                    <td>Periode</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="3">Semester {{ $record->semester }} - Tahun {{ $record->tahun }}</td>
                </tr>
                <tr>
                    <td>Vendor</td>
                    <td class="text-center" style="width: 10px;">:</td>
                    <td colspan="3">{{ $record->vendor->nama }}</td>
                </tr>
            </tbody>
        </table>

        <table class="table-border table" style="margin-top: 25px; padding: 0 8px; width: 100%">
            <thead>
                <tr>
                    <th style="width: 25px">No</th>
                    <th>Pertanyaan</th>
                    <th>Jawaban</th>
                </tr>
            </thead>
            <tbody>
                @php
                    const JAWABAN_MAP = [
                        1 => 'Kurang',
                        2 => 'Cukup',
                        3 => 'Baik',
                        4 => 'Baik Sekali',
                    ];
                @endphp
                @foreach ($record->jawaban as $jawaban)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $jawaban->pertanyaan->pertanyaan }}</td>
                        <td style="text-align: center; width: 100px;">{{ JAWABAN_MAP[$jawaban->value] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table-border table" style="margin-top: 25px;padding-bottom: 0 8px; width: 100%">
            <tbody>
                <tr>
                    <td style="text-align: justify; width: 100px">
                        <b>Kesimpulan</b>
                    </td>
                    <td style="text-align: center; width: 2em">:</td>
                    <td>{{ $record->kesimpulan }}</td>
                </tr>
                <tr>
                    <td style="text-align: justify;">
                        <b>Kritik</b>
                    </td>
                    <td style="text-align: center;">:</td>
                    <td>{{ $record->kritik }}</td>
                </tr>
                <tr>
                    <td style="text-align: justify;">
                        <b>Saran</b>
                    </td>
                    <td style="text-align: center;">:</td>
                    <td>{{ $record->saran }}</td>
                </tr>
            </tbody>
        </table>

        <h3 class="text-center" style="padding-bottom: 40px;">{{ getRoot()->name }}<br>DIVISI HUMAN CAPITAL</h3>

        <table class="table-borderless table">
            <tr>
                @foreach ($record->approvals as $approval)
                    <td class="w-50 font-size-lg text-center">
                        <div style="height: 110px;">

                        </div>
                        <div class="font-weight-bold"><u>{{ strtoupper($approval->user->name) }}</u></div>
                        {{ $approval->position->name ?? '' }}
                    </td>
                @endforeach
            </tr>
        </table>
    </div>
</body>

</html>
