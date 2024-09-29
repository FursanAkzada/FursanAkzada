{{-- {{ dd(json_decode($kandidat->requirement)) }} --}}
<form action="{{ route($route . '.kandidat.penerimaan.store', $kandidat->id) }}" method="POST" autocomplete="off">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        {{-- <div class="alert alert-custom alert-light-primary">
            <div class="alert-icon"><i class="flaticon-warning"></i></div>
            <div class="alert-text">
                Yakin menerima <b>{{ $kandidat->tad->nama }}</b> sebagai tenaga ahli daya ? <br>
                Kandidat Akan tercatat diterima pada tanggal {{ \Carbon\Carbon::now()->format('d/m/Y') }}
            </div>
        </div> --}}
        <div class="form-group row">
            <label for="" class="col-3">Status Penerimaan</label>
            <div class="col parent-group">
                <select class="form-control base-plugin--select2" id="penerimaanCtrl" name="accepted">
                    <option value="1">Diterima</option>
                    <option value="2">Ditolak</option>
                </select>
            </div>
        </div>
        <div class="form-group row tgl-ctrl">
            <label for="" class="col-3">Tanggal Mulai Bekerja</label>
            <div class="col parent-group">
                <input type="text" class="form-control base-plugin--datepicker"
                    data-format="dd/mm/yyyy"
                    data-options='@json(['startDate' => now()])' data-language="en"
                    placeholder="Tanggal Mulai Bekerja" name="in_at">
            </div>
        </div>
        <div class="form-group row tgl-ctrl">
            <label for="" class="col-3">Tanggal Berakhir Kontrak</label>
            <div class="col parent-group">
                <input type="text" class="form-control base-plugin--datepicker"
                    data-format="dd/mm/yyyy"
                    data-options='@json(['startDate' => now()])' data-language="en"
                    placeholder="Tanggal Berakhir Kontrak" name="contract_due">
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-3">Alasan</label>
            <div class="col parent-group">
                <textarea class="form-control" name="alasan" placeholder="Alasan"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-3">Posisi TAD</label>
            <div class="col parent-group">
                {{ $kandidat->requirement->jabatan->NM_UNIT }}
            </div>
        </div>
        <div class="form-group row" style="display: none">
            <label for="" class="col">Jenis Jabatan</label>
            <div class="col parent-group">
                <select name="jenis_jabatan" id="" class="form-control base-plugin--select2">
                    <option value=""></option>
                    <option value="admin">Admin</option>
                    <option value="non-admin">Non Admin</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer pt-0 border-0">
        <x-btn-save via="base-form--submit-modal" />
    </div>
</form>

<script>
    $('#modal .modal-md').removeClass('modal-md').addClass('modal-lg');
    $('#penerimaanCtrl').change(function(){
        if ($('#penerimaanCtrl').val() == 1) {
            $('.tgl-ctrl').show();
        } else if ($('#penerimaanCtrl').val() == 2) {
            $('.tgl-ctrl').hide();
        }
    });
</script>
