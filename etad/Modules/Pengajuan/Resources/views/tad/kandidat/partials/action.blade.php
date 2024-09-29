<div class="dropdown dropleft">
    <div class="btn-group dropup">
        <button type="button" class="btn btn-light-primary btn-icon btn-sm"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i
                class="ki ki-bold-more-hor"></i></button>
        <div class="dropdown-menu dropdown-menu-right">
            <form action="{{ route($route . '.kandidatDestroy', $r->id) }}" method="POST">
                @method('DELETE')
                @csrf
                <button type="submit" class="dropdown-item align-items-center" style="display:unset;">
                    <i class="mr-1 fa fa-trash text-danger"></i>
                    {{ __('Hapus') }}
                    <span class=""></span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.base-form--postByUrl', function (e) {
        e.preventDefault();
        BaseForm.postByUrl(this);
    });
</script>
