<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sirkulasi extends Model
{
    use HasFactory;

    /**
     * Table name for this model.
     */
    protected $table = 'sirkulasis';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_buku',
        'id_user',
        'tgl_pinjam',
        'tgl_kembali',
        'tgl_pengembalian',
        'status',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }


    /**
     * Scope untuk filter berdasarkan status.
     */
    public function scopeFilterByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk mencari berdasarkan tanggal peminjaman.
     */
    public function scopeFilterByTanggalPinjam($query, $startDate, $endDate)
    {
        return $query->whereBetween('tgl_pinjam', [$startDate, $endDate]);
    }
}
