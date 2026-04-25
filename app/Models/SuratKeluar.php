<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'surat_keluar';

    protected $fillable = [
        'tanggal_keluar_surat',
        'nomor_urut',
        'alamat_penerima',
        'tanggal_surat',
        'nomor_surat',
        'perihal',
        'asal',
        'lampiran_path',
        'lampiran_nama_asli',
        'lampiran_mime',
        'lampiran_ukuran',
    ];

    public function getLampiranUrlAttribute(): ?string
    {
        return $this->lampiran_path ? asset('storage/'.$this->lampiran_path) : null;
    }

    public function getLampiranUkuranLabelAttribute(): ?string
    {
        if (! $this->lampiran_ukuran) {
            return null;
        }

        if ($this->lampiran_ukuran >= 1024 * 1024) {
            return number_format($this->lampiran_ukuran / 1024 / 1024, 1).' MB';
        }

        return number_format($this->lampiran_ukuran / 1024, 0).' KB';
    }
}
