<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailTransaksiModel extends Model
{
    protected $table = 'detail_transaksi'; // pastikan nama tabel sesuai database Anda
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'transaksi_id', 'product_id', 'jumlah', 'diskon', 'subtotal_harga', 'created_at', 'updated_at'
    ];
}