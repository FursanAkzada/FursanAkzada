
<form action="{{ route($route . '.riwayatKerja.store', $record->id) }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">Informasi Riwayat Kerja</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group row">
            <label for="" class="col-md-2 col-form-label text-bold">Title</label>
            <div class="col-md-10 parent-group">
                <input type="text" class="form-control" name="title" placeholder="Ex : Direktur Utama">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="" class="col-md-4 col-form-label text-bold">Tipe Pekerjaan</label>
                    <div class="col-md-8 parent-group">
                        <select name="employment_type" id=""
                            class="form-control base-plugin--select2">
                            <option value="disabled">{{ __('Pilih Salah Satu') }}</option>      
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Contract">Contract</option>
                            <option value="Internship">Internship</option>
                            <option value="Freelance">Freelance</option>
                            <option value="Apprenticeship">Apprenticeship</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="" class="col-md-4 col-form-label text-bold">Sistem Kerja</label>
                    <div class="col-md-8 parent-group">
                        <select name="system_working" id=""
                            class="form-control base-plugin--select2">
                            <option value="disabled">{{ __('Pilih Salah Satu') }}</option>      
                            <option value="On-site">On-site</option>
                            <option value="Remote">Remote</option>
                            <option value="Hybrid">Hybrid</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-md-2 col-form-label text-bold">Company</label>
            <div class="col-md-10 parent-group">
                <input type="text" class="form-control" name="company" placeholder="Company">
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-md-2 col-form-label text-bold">Lokasi Company</label>
            <div class="col-md-10 parent-group">
                <textarea name="location_company" class="form-control" id="" cols="30" rows="3"
                    placeholder="Lokasi Company"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="" class="col-md-4 col-form-label text-bold">Tgl Mulai</label>
                    <div class="col-md-8 parent-group">
                        <input type="text" class="form-control base-plugin--datepicker" data-language="in"
                        data-format="dd/mm/yyyy"
                        data-options='@json(['endDate' => ''])' name="start_date"
                        placeholder="Tgl Mulai">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label for="" class="col-md-4 col-form-label text-bold">Tgl Akhir</label>
                    <div class="col-md-8 parent-group">
                        <input type="text" class="form-control base-plugin--datepicker" data-language="in"
                        data-format="dd/mm/yyyy"
                        data-options='@json(['endDate' => ''])' name="end_date"
                        placeholder="Tgl Akhir">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-md-2 col-form-label text-bold">Deskripsi</label>
            <div class="col-md-10 parent-group">
                <textarea name="description" class="form-control" id="" cols="30" rows="3"
                    placeholder="Deskripsi"></textarea>
            </div>
        </div>
    </div>
    <div class="pt-0 border-0 modal-footer">
        <button type="submit" data-swal-confirm="false" data-rusmen="true"
            class="btn btn-info d-flex align-items-center base-form--submit-page">
            <i class="fas fa-save mr-2"></i>Simpan
        </button>
    </div>
</form>

