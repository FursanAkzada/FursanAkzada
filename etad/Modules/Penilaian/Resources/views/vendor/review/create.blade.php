<form action="{{ route('penilaian.vendor.store') }}" method="POST">
  @csrf
  <div class="modal-header">
    <h4 class="modal-title">Buat Form Penilaian {{ $title }}</h4>
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
    <div class="separator separator-dashed separator-border-3 mt-3 mb-5"></div>
    <div class="form-group row">
      <label for="" class="col-3 font-weight-bold">Divisi / Cabang</label>
      <div class="col">
        <label for="">{{ auth()->user()->user()->cabang }}</label>
      </div>
    </div>
    <div class="form-group row">
      <label for="" class="col-3 font-weight-bold">Vendor</label>
      <div class="col-5">
       <select name="vendor_id" id="" class="form-control base-plugin--select2" title="Pilih Vendor">
        <option selected disabled></option>
        @foreach ($vendor as $item)
          <option value="{{ $item->id }}">{{ $item->nama }}</option>
        @endforeach
       </select>
      </div>
    </div>
    <div class="form-group row">
      <label for="" class="col-3 font-weight-bold">Tahun</label>
      <div class="col-5">
      <select name="tahun" id="" class="form-control base-plugin--select2" title="Pilih Tahun">
        <option disabled></option>
        @for ($year = 2015; $year <= date('Y'); $year++)
          <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
        @endfor
       </select>
      </div>
    </div>
    <div class="form-group row">
      <label for="" class="col-3 font-weight-bold">Jumlah Tenaga Alih Daya</label>
      <div class="col">
        <label for="">10</label>
      </div>
    </div>
    <div class="separator separator-dashed separator-border-3 mt-3 mb-5"></div>
    @foreach ($pertanyaan as $item)
    <div class="form-group row">
      <label for="" class="col-6">{{ $item->pertanyaan }}</label>
      <div class="col col-form-label parent-group">
        <div class="radio-inline">
          <label class="radio radio-success">
            <input type="radio" name="question[{{ $item->id }}]" value="1" />
            <span></span>
            1
          </label>
          <label class="radio radio-success">
            <input type="radio" name="question[{{ $item->id }}]" value="2" />
            <span></span>
            2
          </label>
          <label class="radio radio-success">
            <input type="radio" name="question[{{ $item->id }}]" value="3" />
            <span></span>
            3
          </label>
          <label class="radio radio-success">
            <input type="radio" checked name="question[{{ $item->id }}]" value="4" />
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
          <select name="kesimpulan" id="" class="form-control base-plugin--select2" title="Pilih Kesimpulan">
            <option selected disabled></option>
            <option value="Dapat Diperpanjang">Dapat Diperpanjang</option>
            <option value="Dipertimbangkan">Dipertimbangkan</option>
            <option value="Tidak Diperpanjang">Tidak Diperpanjang</option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="" class="text-bold">Kritik Untuk Vendor Tenaga Alih Daya</label>
      <textarea name="kritik" id="" cols="30" rows="3" class="form-control" placeholder="Kritik"></textarea>
    </div>
    <div class="form-group">
      <label for="" class="text-bold">Saran Untuk Vendor Tenaga Alih Daya</label>
      <textarea name="saran" id="" cols="30" rows="3" class="form-control" placeholder="Saran"></textarea>
    </div>
  </div>
  <div class="modal-footer pt-0 border-0">
    <x-btn-save via="base-form--submit-modal" />
  </div>
</form>
