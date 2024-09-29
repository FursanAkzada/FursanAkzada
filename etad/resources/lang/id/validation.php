<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'harus diterima.',
    'active_url'           => 'bukan URL yang sah.',
    'after'                => 'tidak valid.',
    'after_or_equal'       => 'tidak valid.',
    'alpha'                => 'hanya boleh berisi huruf.',
    'alpha_dash'           => 'hanya boleh berisi huruf, angka, dan strip.',
    'alpha_num'            => 'hanya boleh berisi huruf dan angka.',
    'array'                => 'harus berupa sebuah array.',
    'before'               => 'tidak valid.',
    'before_or_equal'      => 'tidak valid.',
    'between'              => [
        'numeric' => 'harus antara :min dan :max.',
        'file'    => 'harus antara :min dan :max kilobytes.',
        'string'  => 'harus antara :min dan :max karakter.',
        'array'   => 'harus antara :min dan :max item.',
    ],
    'boolean'              => 'harus berupa true atau false',
    'confirmed'            => 'Konfirmasi tidak cocok.',
    'date'                 => 'bukan tanggal yang valid.',
    'date_equals'          => 'tidak valid.',
    'date_format'          => 'tidak cocok dengan format :format.',
    'different'            => ':attribute dan :other harus berbeda.',
    'digits'               => 'harus :digits digit.',
    'digits_between'       => 'harus antara :min sampai :max digit.',
    'dimensions'           => 'harus merupakan dimensi gambar yang sah.',
    'distinct'             => 'memiliki nilai yang duplikat.',
    'email'                => 'harus berupa alamat surel yang valid.',
    'ends_with'            => 'harus diakhiri dengan salah satu dari: :values.',
    'exists'               => 'yang dipilih tidak valid.',
    'file'                 => 'harus berupa file.',
    'filled'               => 'wajib diisi.',
    'gt' => [
        'numeric' => 'harus lebih besar dari :value.',
        'file' => 'harus lebih besar dari :value kilobyte.',
        'string' => 'harus lebih besar dari :value karakter.',
        'array' => 'harus memiliki lebih dari :value item.',
    ],
    'gte' => [
        'numeric' => 'harus lebih besar dari atau sama :value.',
        'file' => 'harus lebih besar dari atau sama :value kilobyte.',
        'string' => 'harus lebih besar dari atau sama :value karakter.',
        'array' => 'harus memiliki :value item atau lebih.',
    ],
    'image'                => 'harus berupa gambar.',
    'in'                   => 'yang dipilih tidak valid.',
    'in_array'             => 'tidak terdapat dalam :other.',
    'integer'              => 'harus merupakan bilangan bulat.',
    'ip'                   => 'harus berupa alamat IP yang valid.',
    'ipv4'                 => 'harus alamat IPv4 yang valid.',
    'ipv6'                 => 'harus alamat IPv6 yang valid.',
    'json'                 => 'harus berupa string JSON yang valid.',
    'lt' => [
        'numeric' => 'harus kurang dari :value.',
        'file' => 'harus kurang dari :value kilobyte.',
        'string' => 'harus kurang dari :value karakter.',
        'array' => 'harus memiliki kurang dari :value item.',
    ],
    'lte' => [
        'numeric' => 'harus kurang dari atau sama dengan :value.',
        'file' => 'harus kurang dari atau sama dengan :value kilobytes.',
        'string' => 'harus kurang dari atau sama dengan :value karakter.',
        'array' => 'tidak boleh lebih dari :value item.',
    ],
    'max'                  => [
        'numeric' => 'seharusnya tidak lebih dari :max.',
        'file'    => 'seharusnya tidak lebih dari :max kilobytes.',
        'string'  => 'seharusnya tidak lebih dari :max karakter.',
        'array'   => 'seharusnya tidak lebih dari :max item.',
    ],
    'mimes'                => 'harus dokumen berjenis : :values.',
    'mimetypes'            => 'harus dokumen berjenis : :values.',
    'min'                  => [
        'numeric' => 'harus minimal :min.',
        'file'    => 'harus minimal :min kilobytes.',
        'string'  => 'harus minimal :min karakter.',
        'array'   => 'harus minimal :min item.',
    ],
    'not_in'               => 'yang dipilih tidak valid.',
    'not_regex' => 'Format isian tidak valid.',
    'numeric'              => 'harus berupa angka.',
    'password'             => 'Password Anda salah.',
    'present'              => 'wajib ada.',
    'regex'                => 'Format isian tidak valid.',
    'required'             => 'tidak boleh kosong.',
    // 'required_if'          => 'tidak boleh kosong bila :other adalah :value.',
    'required_if'          => 'tidak boleh kosong',
    'required_unless'      => 'tidak boleh kosong kecuali :other memiliki nilai :values.',
    'required_with'        => 'tidak boleh kosong bila terdapat :values.',
    'required_with_all'    => 'tidak boleh kosong bila terdapat :values.',
    'required_without'     => 'tidak boleh kosong',
    'required_without_all' => 'tidak boleh kosong bila tidak terdapat ada :values.',
    'same'                 => 'dan :other harus sama.',
    'size'                 => [
        'numeric' => 'harus berukuran :size.',
        'file'    => 'harus berukuran :size kilobyte.',
        'string'  => 'harus berukuran :size karakter.',
        'array'   => 'harus mengandung :size item.',
    ],
    'starts_with' => 'harus dimulai dengan salah satu dari: :values.',
    'string'               => 'harus berupa string.',
    'timezone'             => 'harus berupa zona waktu yang valid.',
    'unique'               => 'sudah ada sebelumnya.',
    'unique_with'          => 'Kombinasi sudah ada.',
    'uploaded'             => 'gagal terupload.',
    'url'                  => 'Format isian tidak valid.',
    'uuid'                 => 'harus berupa UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'Nama',
        'email' => 'Email',
        'password' => 'Password',
        // Global Fields
        'nama' => 'Nama',
        'description' => 'Deskripsi',
        'deskripsi' => 'Deskripsi',
        'code' => 'Kode',
        'kode' => 'Kode',
    ],

];
