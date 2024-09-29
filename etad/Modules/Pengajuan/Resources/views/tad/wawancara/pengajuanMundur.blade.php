
<form action="{{ route($route . '.handlePengajuanMundur', $record->id) }}" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">{{ $title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('ID Pengajuan') }}</label>
                    <div class="col-md-8 parent-group">
                        <input class="form-control" type="text" placeholder="{{ __('ID Pengajuan') }}"
                            value="{{$record->kandidat->summary->pengajuan->no_tiket}}" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Unit Kerja') }}</label>
                    <div class="col-md-8 parent-group">
                        <input class="form-control" type="text" placeholder="{{ __('Unit Kerja') }}"
                            value="{{ $record->kandidat->summary->pengajuan->so->name ?? '' }}" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Posisi TAD') }}</label>
                    <div class="col-md-8 parent-group">
                        <input class="form-control" type="text" placeholder="{{ __('Posisi TAD') }}"
                            value="{{ $record->kandidat->summary->requirement->jabatan->NM_UNIT . ' ( ' . $record->kandidat->summary->requirement->jumlah . ' posisi ' . ')'}}" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Nama Kandidat') }}</label>
                    <div class="col-md-8 parent-group">
                        <input class="form-control" type="text" placeholder="{{ __('Nama Kandidat') }}"
                            value="{{ $record->kandidat->tad->nama }}" disabled>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Tgl Pengajuan') }}</label>
                    <div class="col-md-8 parent-group">
                        <input class="form-control" type="text" placeholder="{{ __('Tgl Pengajuan') }}"
                            value="{{ $record->kandidat->summary->pengajuan->tgl_pengajuan->translatedFormat('d/m/Y') }}" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Periode') }}</label>
                    <div class="col-md-8 parent-group">
                        <input class="form-control" type="text" placeholder="{{ __('Periode') }}"
                            value="{{ 'Tahun ' . $record->kandidat->summary->pengajuan->year . ' Semester ' . $record->kandidat->summary->pengajuan->semester }}" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">{{ __('Vendor') }}</label>
                    <div class="col-md-8 parent-group">
                        <input class="form-control" type="text" placeholder="{{ __('Vendor') }}" value="{{$record->kandidat->summary->requirement->vendor->nama}}" disabled>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 col-form-label">{{ __('Curiculum Vitae') }}</label>
            <div class="col-md-10 parent-group">
                @foreach ($record->kandidat->tad->files->where('flag', 'cv') as $file)
                <div class="progress-container w-100" data-uid="{{ $file->id }}">
                    <div class="alert alert-custom alert-light fade show py-2 px-3 mb-0 mt-2 success-uploaded" role="alert">
                        <div class="alert-icon">
                            <i class="{{ $file->file_icon }}"></i>
                        </div>
                        <div class="alert-text text-left">
                            <input type="hidden" name="uploads_cv[files_ids][]" value="{{ $file->id }}">
                            <div>Uploaded File:</div>
                            <a href="{{ $file->file_url }}" target="_blank" class="text-primary">
                                {{ $file->file_name }}
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <hr class="my-8">
        <div class="form-group row">
            <label class="col-md-2 col-form-label">{{ __('Alasan') }}</label>
            <div class="col-md-10 parent-group">
                <textarea name="alasan_pengunduran" class="form-control" rows="5"
                    placeholder="{{ __('Alasan') }}">{{ $record->alasan_pengunduran }}</textarea>
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

<script>
    $('.modal-dialog').removeClass('modal-md').addClass('modal-lg');
</script>
