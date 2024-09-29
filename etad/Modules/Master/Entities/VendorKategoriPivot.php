<?php

namespace Modules\Master\Entities;

use App\Entities\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorKategoriPivot extends BaseModel
{
    use HasFactory;

    protected $table = 'ref_vendor_kategori_pivot';
    protected $fillable = [
        'vendor_id',
        'kategori_id',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriVendor::class, 'kategori_id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
