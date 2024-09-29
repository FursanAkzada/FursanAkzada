
  <div class="modal-header">
    <h4 class="modal-title">{{ $title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close"></i></button>
  </div>
  <div class="modal-body">
    {{-- Pengajuan --}}
    <div class="form-group row">
      <label for="" class="col-3 text-bold">Nama Kandidat</label>
      <label for="" class="col">{{ $kandidat->nama }}</label>
    </div>
    
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
    {{-- {{ dd($wawancara->penilaian()->with('pertanyaan',function($r){$r->groupBy('kompetensi_id');})->get()) }} --}}
    @foreach ($kompetensi as $i => $k)
      <h4 class="display-5 {{ ($i == 0 ? '' : 'mt-5') }}">{{ $k->kompetensi }}</h4>
      <span class="font-size-sm">{{ $k->uraian }}</span>
      <div class="separator separator-dashed separator-border-3 mt-3 mb-5"></div>
      @foreach ($wawancara->penilaian()->whereHas('pertanyaan',function($q)use($k){$q->where('kompetensi_id',$k->id);})->get() as $i => $p)
        <div class="form-group row">
          <label for="" class="col-6">{{ $p->pertanyaan->pertanyaan }}</label>
          <div class="col col-form-label parent-group">
            <div class="radio-inline">
              <label class="radio radio-success">
                <input type="radio" disabled {{ $p->value == 1 ? 'checked' : '' }} value="1" />
                <span></span>
                1
              </label>
              <label class="radio radio-success">
                <input type="radio" disabled {{ $p->value == 2 ? 'checked' : '' }} value="2" />
                <span></span>
                2
              </label>
              <label class="radio radio-success">
                <input type="radio" disabled {{ $p->value == 3 ? 'checked' : '' }} value="3" />
                <span></span>
                3
              </label>
              <label class="radio radio-success">
                <input type="radio" disabled {{ $p->value == 4 ? 'checked' : '' }} value="4" />
                <span></span>
                4
              </label>
              <label class="radio radio-success">
                <input type="radio" disabled {{ $p->value == 5 ? 'checked' : '' }} value="5" />
                <span></span>
                5
              </label>
            </div>
          </div>
        </div>
      @endforeach
    @endforeach
    <div class="separator separator-dashed separator-border-3 mt-3 mb-5"></div>
    <div class="form-group row">
      <label for="" class="col-2 text-bold">Kesimpulan</label>
      <div class="col-2 parent-group">
        {!! $wawancara->kesimpulanRaw() !!}
      </div>
    </div>
    <div class="form-group row">
      <label for="" class="col-2 text-bold">Saran</label>
      <div class="col parent-group">
        {{ nl2br($wawancara->saran) }}
      </div>
    </div>
  </div>