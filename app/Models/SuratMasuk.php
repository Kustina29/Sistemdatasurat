<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'surat_masuk';

    protected $fillable = [
        'tanggal_masuk_surat',
        'nomor_urut',
        'alamat_pengirim',
        'tanggal_surat',
        'nomor_surat',
        'perihal',
        'tujuan_disposisi',
    ];
}
