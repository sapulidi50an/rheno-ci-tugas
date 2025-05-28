<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductCategoryModel; // Assumes you have this model

class ProductCategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new ProductCategoryModel();
    }

    // Show list of categories
    public function index()
    
    {
        $data['category'] = $this->categoryModel->findAll();
        return view('v_ProductCategory', $data);
    }

    // Handle creating a new category
    public function create()
    {
        helper(['form']);

        $validation = $this->validate([
            'nama_kategori' => 'required|string|max_length[255]',
            'deskripsi'     => 'required|string|max_length[1000]',
            'foto'          => 'permit_empty|is_image[foto]|max_size[foto,2048]'
        ]);

        if (!$validation) {
            return redirect()->to('/produk_category')->with('failed', $this->validator->listErrors());
        }

        $fileName = null;
        $fotoFile = $this->request->getFile('foto');
        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            $fileName = $fotoFile->getRandomName();
            $fotoFile->move('img', $fileName);
        }

        $this->categoryModel->insert([
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'foto'          => $fileName
        ]);

        session()->setFlashdata('success', 'Kategori berhasil ditambahkan.');
        return redirect()->to('/produk_category');
    }

    // Show edit form is in the modal, so here just handle the submission
    public function edit($id)
    {
        helper(['form']);

        // Validate post input (foto optional on edit)
        $rules = [
            'nama_kategori' => 'required|string|max_length[255]',
            'deskripsi'     => 'required|string|max_length[1000]',
        ];

        // Check if user checked to replace photo
        if ($this->request->getPost('check') == '1') {
            $rules['foto'] = 'uploaded[foto]|max_size[foto,2048]|is_image[foto]';
        }

        if (!$this->validate($rules)) {
            return redirect()->to('/produk_category')->with('failed', $this->validator->listErrors());
        }

        $dataToUpdate = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
        ];

        // Handle photo replacement
        if ($this->request->getPost('check') == '1') {
            $fotoFile = $this->request->getFile('foto');
            if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
                // Delete old photo if exists
                $oldData = $this->categoryModel->find($id);
                if ($oldData && isset($oldData['foto']) && !empty($oldData['foto'])) {
                    $oldFilePath = WRITEPATH . '../public/img/' . $oldData['foto'];
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
                $fileName = $fotoFile->getRandomName();
                $fotoFile->move('img', $fileName);
                $dataToUpdate['foto'] = $fileName;
            }
        }

        if (empty($dataToUpdate)) {
            session()->setFlashdata('failed', 'Tidak ada data yang diubah.');
            return redirect()->to('/produk_category');
        }
        $this->categoryModel->update($id, $dataToUpdate);

        session()->setFlashdata('success', 'Kategori berhasil diubah.');
        return redirect()->to('/produk_category');
    }

    // Handle deleting a category
    public function delete($id)
    {
        // Find the record to delete photo if exists
        $category = $this->categoryModel->find($id);
        if ($category) {
            if (isset($category['foto']) && !empty($category['foto'])) {
                $filePath = WRITEPATH . '../public/img/' . $category['foto'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $this->categoryModel->delete($id);
            session()->setFlashdata('success', 'Kategori berhasil dihapus.');
        } else {
            session()->setFlashdata('failed', 'Kategori tidak ditemukan.');
        }

        return redirect()->to('/produk_category');
    }
}

