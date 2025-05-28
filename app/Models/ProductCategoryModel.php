<?php namespace App\Models;

use CodeIgniter\Model;

class ProductCategoryModel extends Model
{
    protected $table = 'product_categories'; // pastikan nama tabel sesuai migrasi
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_kategori', 'deskripsi', 'foto', 'created_at', 'updated_at'];
    protected $returnType = 'array';

    protected $validationRules = [
        'nama_kategori' => 'required|min_length[3]|max_length[255]',
        'deskripsi'     => 'required|min_length[3]|max_length[1000]'
    ];
}