{{-- {{ $confirm }} --}}
{{-- {{ gettype($confirm) }} --}}
<button type="submit" class="btn btn-info d-flex align-items-center {{ $class }} {{ $via }}" data-swal-confirm="{{ $confirm == 1 ?'true':'false' }}">
    <i class="{{ $icon }} mr-2"></i>{{ $label }}
</button>
