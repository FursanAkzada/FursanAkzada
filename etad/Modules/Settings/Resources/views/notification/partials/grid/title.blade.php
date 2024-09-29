@php
    $icon = [
        'pengajuan.tad'             => 'fa fa-user-edit',
        'pengajuan.tad.form'        => 'fa fa-user-edit',
        'pengajuan.tad.quota'       => 'fa fa-user-edit',
        'pengajuan.tad.kandidat'    => 'fa fa-user-edit',
        'pengajuan.tad.wawancara'   => 'fa fa-user-edit',
        'pengajuan.tad.penerimaan'  => 'fa fa-user-edit',
        'resign_mutasi.resign'      => 'fas fa-sign-out-alt',
        'resign_mutasi.mutasi'      => 'fas fa-exchange-alt',
        //Outa
        'personil.quota'            => 'fas fa-id-card',
        // Penilaian
        'penilaian.vendor'          => 'fas fa-star',
        'penilaian.tad'             => 'fas fa-star',
        'penilaian.perpanjangan'    => 'fas fa-star',
        // Punishment & Reward
        'pu.reward'                 => 'fas fa-medal',
        'pu.punishment'             => 'fas fa-medal',
        'pu.pembinaan'              => 'fas fa-medal',
    ];
@endphp
<div class="d-flex align-items-center">
    <i class="{{ $icon[$r->data['type']] }} text-success mx-3"></i>
    <a href="{{ $r->data['link'] }}" class="text-dark-65">
        <span class="text-bold">{{ $r->data['title'] }}</span><br>
        <span class="font-size-sm">{{ $r->data['message'] }}</span>
    </a>
</div>
