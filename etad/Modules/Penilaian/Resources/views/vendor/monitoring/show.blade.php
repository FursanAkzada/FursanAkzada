<form action="" method="POST">
  @csrf
  <div class="modal-header">
    <h4 class="modal-title">Detail Form Penilaian {{ $title }}</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close"></i></button>
  </div>
  <div class="modal-body">
    <div class="alert alert-success mb-5 p-5" role="alert">
      <h4 class="alert-heading">Keterangan Nilai (Memberikan Centang Pada Lingkaran Angka Nilai)</h4>
      <div class="row">
        <div class="col">
          <p>1. Kurang</p>
          <p>2. Cukup</p>
        </div>
        <div class="col">
          <p>3. Baik</p>
          <p>4. Baik Sekali</p>
        </div>
      </div>
    </div>
    @foreach ($pertanyaan as $item)
    <div class="form-group row">
      <label for="" class="col-6">{{ $item->pertanyaan }}</label>
      <div class="col col-form-label parent-group">
        <div class="radio-inline">
          <label class="radio radio-success">
            <input type="radio" {{ !empty($item->jawaban->value) && $item->jawaban->value == '1' ? 'checked' : '' }} name="question[{{ $item->id }}]" value="1" disabled />
            <span></span>
            1
          </label>
          <label class="radio radio-success">
            <input type="radio" {{ !empty($item->jawaban->value) && $item->jawaban->value == '2' ? 'checked' : '' }} name="question[{{ $item->id }}]" value="2" disabled />
            <span></span>
            2
          </label>
          <label class="radio radio-success">
            <input type="radio" {{ !empty($item->jawaban->value) && $item->jawaban->value == '3' ? 'checked' : '' }} name="question[{{ $item->id }}]" value="3" disabled />
            <span></span>
            3
          </label>
          <label class="radio radio-success">
            <input type="radio" {{ !empty($item->jawaban->value) && $item->jawaban->value == '4' ? 'checked' : '' }} name="question[{{ $item->id }}]" value="4" disabled />
            <span></span>
            4
          </label>
        </div>
      </div>
    </div>
    @endforeach
    <div class="separator separator-dashed separator-border-3 mt-3 mb-5"></div>
    <div class="form-group">  
      <label for="" class="text-bold">Kesimpulan</label>
      <div class="row">
        <div class="col-5 parent-group">
          <p>{{ $record->kesimpulan }}</p>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="" class="text-bold">Kritik Untuk Vendor Tenaga Alih Daya</label>
      <p>{{ $record->kritik }}</p>
    </div>
    <div class="form-group">
      <label for="" class="text-bold">Saran Untuk Vendor Tenaga Alih Daya</label>
      <p>{{ $record->saran }}</p>
    </div>
  </div>
</form>