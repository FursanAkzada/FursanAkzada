<div class="btn-group dropup d-flex align-items-center">
    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="far fa-save mr-2"></i>{{ $label }}
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        <button type="submit" class="dropdown-item {{ $via }}" data-submit="0">
            <i class="mr-2 fas fa-list text-primary"></i>{{ $label_draft ?? 'Simpan Sebagai Draft' }}
        </button>
        <button type="submit"
            class="dropdown-item {{ $via }} {{ isset($submit) && $submit == 'disabled' ? 'disabled' : '' }}"
            data-submit="1" {{ isset($submit) && $submit == 'disabled' ? 'disabled' : '' }}
            data-swal-confirm="{{ (string)$confirm }}"
            id="submitBtn">
            <i class="mr-2 far fa-save text-success"></i>{{ $label_save ?? 'Simpan & Kirim' }}
        </button>
    </div>
</div>
