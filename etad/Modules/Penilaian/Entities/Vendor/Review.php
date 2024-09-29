<?php

namespace Modules\Penilaian\Entities\Vendor;

use App\Entities\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Master\Entities\Penilaian\PertanyaanVendor;
use Modules\Penilaian\Entities\Penilaian;
use App\Entities\User;

class Review extends BaseModel
{
  use HasFactory;

  protected $table = 'trans_penilaian_vendor_review';
  protected $fillable = [
    'penilaian_vendor_id',
    'sign_by',
    'sign_at'
  ];

  /**
   * Get the user that owns the Review
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user(): BelongsTo
  {
      return $this->belongsTo(User::class, 'sign_by');
  }

}
