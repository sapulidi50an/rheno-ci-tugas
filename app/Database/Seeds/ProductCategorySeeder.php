<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductCategoriesSeeder extends Seeder
{
    public function run()
    {
        $currentTimestamp = date('Y-m-d H:i:s'); // Store the current timestamp

        $data = [
            [
                'nama_kategori' => 'Laptop',
                'deskripsi' => 'Kategori untuk produk laptop',
                'status' => 'aktif',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
            [
                'nama_kategori' => 'Keyboard', // Capitalized 'K'
                'deskripsi' => 'Kategori untuk produk keyboard',
                'status' => 'aktif',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
            [
                'nama_kategori' => 'Headset', // Corrected spelling
                'deskripsi' => 'Kategori untuk produk headset',
                'status' => 'aktif',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ],
            [
                'nama_kategori' => 'Monitor',
                'deskripsi' => 'Kategori untuk produk monitor',
                'status' => 'aktif',
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
            ]
        ];

        // Insert data using the correct table name
        if ($this->db->table('product_categories')->insertBatch($data)) {
            echo "ProductCategorySeeder berhasil dijalankan. " . count($data) . " data kategori telah ditambahkan.\n";
        } else {
            echo "Terjadi kesalahan saat menambahkan data kategori.\n";
        }
    }
}
