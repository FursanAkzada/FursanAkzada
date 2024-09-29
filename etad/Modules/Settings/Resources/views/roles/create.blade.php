<form action="{{ route($route . '.store') }}" autocomplete="off" method="POST">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title">Hak Akses</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                class="ki ki-close"></i></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label for="name">Hak Akses</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="@lang('Name')">
        </div>
        {{-- <div class="form-group">
      <label for="name">@lang('Selected Users')</label>
      <select name="users[]" class="form-control base-plugin--select2 show-tick" multiple title="~ @lang('Select Users') ~">
        @foreach ($users as $user)
        <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
      </select>
    </div> --}}
    </div>
    <div class="modal-footer pt-0 border-0">
        <x-btn-save via="base-form--submit-modal" confirm="0"/>
    </div>
</form>
