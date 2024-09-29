<form action="{{ route($route.'.kandidat.wawancara.store',$kandidat->id) }}" method="POST">
  @csrf
  <div class="modal-header">
    <h4 class="modal-title">{{ $title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close"></i></button>
  </div>
  <div class="modal-body">
    {{-- Pengajuan --}}
    <div class="form-group row">
      <label for="" class="col-3 text-bold">Nama Kandidat</label>
      <label for="" class="col">{{ $kandidat->tad->nama }}</label>
    </div>
    <div class="form-group row">
      <label for="" class="col-3 text-bold">Jabatan</label>
      <label for="" class="col">{{ $kandidat->requirement->jabatan->NM_UNIT }}</label>
    </div>
    <div class="form-group row">
      <label for="" class="col-3 text-bold">Cabang</label>
      <label for="" class="col">{{ $pengajuan->cabang->CAPEM }}</label>
    </div>
    <div class="separator separator-dashed separator-border-3 mt-3 mb-5"></div>
    {{-- Wawancara --}}
    <div class="alert alert-success mb-5 p-5" role="alert">
      <h4 class="alert-heading">Keterangan Nilai (Memberikan Centang Pada Lingkaran Angka Nilai)</h4>
      <div class="row">
        <div class="col">
          <p>1. Sangat Kurang</p>
          <p>2. Kurang</p>
          <p>3. Cukup</p>
        </div>
        <div class="col">
          <p>4. Baik</p>
          <p>5. Sangat Baik</p>
        </div>
      </div>
    </div>
    @foreach ($kompetensi as $i => $k)
      <h4 class="display-5 {{ ($i == 0 ? '' : 'mt-5') }}">{{ $k->kompetensi }}</h4>
      <span class="font-size-sm">{{ $k->uraian }}</span>
      <div class="separator separator-dashed separator-border-3 mt-3 mb-5"></div>
      @foreach ($k->pertanyaan as $key => $p)
        <div class="form-group row">
          <label for="" class="col-6">{{ $p->pertanyaan }}</label>      
          <div class="col col-form-label parent-group">
            <div class="radio-inline">
              <label class="radio radio-success">
                <input type="radio" checked name="question[{{ $p->id }}]" value="1" />
                <span></span>
                1
              </label>
              <label class="radio radio-success">
                <input type="radio" name="question[{{ $p->id }}]" value="2"/>
                <span></span>
                2
              </label>
              <label class="radio radio-success">
                <input type="radio" name="question[{{ $p->id }}]" value="3"/>
                <span></span>
                3
              </label>
              <label class="radio radio-success">
                <input type="radio" name="question[{{ $p->id }}]" value="4"/>
                <span></span>
                4
              </label>
              <label class="radio radio-success">
                <input type="radio" name="question[{{ $p->id }}]" value="5"/>
                <span></span>
                5
              </label>
            </div>
          </div>
        </div>
      @endforeach
    @endforeach
    <div class="separator separator-dashed separator-border-3 mt-3 mb-5"></div>
    <div class="form-group">
      <label for="" class="text-bold">Kesimpulan</label>
      <div class="row">
        <div class="col-5 parent-group">
          <select name="kesimpulan" id="" class="form-control base-plugin--select2">
            <option value="1">Sangat Disarankan</option>
            <option value="2">Disarankan</option>
            <option value="3">Cukup Disarankan</option>
            <option value="4">Kurang Disarankan</option>
            <option value="5">Tidak Disarankan</option>
            <option value="6">Tidak Hadir</option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="" class="text-bold">Saran</label>
      <textarea name="saran" id="" cols="30" rows="3" class="form-control" placeholder="Saran"></textarea>
    </div>
  </div>
  <div class="modal-footer pt-0 border-0">
    <x-btn-save via="base-form--submit-modal" />
  </div>
</form>