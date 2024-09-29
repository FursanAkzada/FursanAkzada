<form action="{{ route($route.'.approve.store',$record->id) }}" method="post">
  @csrf
  <div class="card card-custom mt-5">
    <div class="card-header">
      <div class="card-title">
        <span class="card-icon">
          <i class="far fa-check-circle text-primary"></i>
        </span>
        <h3 class="card-label">Otorisasi Human Capital
          <small>Otorisasi Pengajuan TAD</small>
        </h3>
      </div>
    </div>
    <div class="card-body">
      <div class="form-group row">
        <label for="" class="col-1">Approval</label>
        <div class="col-4">
          <select name="status" id="" class="form-control base-plugin--select2">
            <option value=""></option>
            <option value="approved.hc">Approved</option>
            <option value="rejected.hc">Rejected</option>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-1">Deskripsi</label>
        <div class="col-6">
          <textarea name="keterangan" id="" class="form-control" cols="30" rows="3" placeholder="Deskripsi"></textarea>
        </div>
      </div>
    </div>
    <div class="card-footer p-5">
      <div class="float-right d-flex flex-row">
        <x-btn-back class="mr-2" url="{{ route($route.'.index') }}" />
        <x-btn-save via="base-form--submit-page" />
      </div>
    </div>
  </div>
</form>
