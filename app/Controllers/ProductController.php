<?php
namespace App\Controllers;

use App\Models\ProductModel;
use Dompdf\Dompdf;

class ProductController extends BaseController
{
    protected $product; 

    function __construct()
    {
        $this->product = new ProductModel();
    }

    public function index()
    {
        $product = $this->product->findAll();
        $data['product'] = $product;

        return view('v_produk', $data);
    }

    public function create()
    {
        $dataFoto = $this->request->getFile('foto');

        $dataForm = [
            'nama' => $this->request->getPost('nama'),
            'harga' => $this->request->getPost('harga'),
            'jumlah' => $this->request->getPost('jumlah'),
            'created_at' => date("Y-m-d H:i:s")
        ];

        // Validasi dan upload foto
        if ($dataFoto && $dataFoto->isValid() && !$dataFoto->hasMoved()) {
            $fileName = $dataFoto->getRandomName();
            $dataForm['foto'] = $fileName;
            
            // Pastikan direktori img/ exists
            if (!is_dir('img/')) {
                mkdir('img/', 0755, true);
            }
            
            $dataFoto->move('img/', $fileName);
        }

        $this->product->insert($dataForm);

        return redirect()->to('produk')->with('success', 'Data Berhasil Ditambah');
    } 
    
    public function edit($id)
    {
        $dataProduk = $this->product->find($id);
        
        if (!$dataProduk) {
            return redirect()->to('produk')->with('error', 'Data tidak ditemukan');
        }

        $dataForm = [
            'nama' => $this->request->getPost('nama'),
            'harga' => $this->request->getPost('harga'),
            'jumlah' => $this->request->getPost('jumlah'),
            'updated_at' => date("Y-m-d H:i:s")
        ];

        // Cek apakah ada request untuk mengubah foto
        if ($this->request->getPost('check') == 1) {
            // Hapus foto lama jika ada
            if (!empty($dataProduk['foto']) && file_exists("img/" . $dataProduk['foto'])) {
                unlink("img/" . $dataProduk['foto']);
            }

            $dataFoto = $this->request->getFile('foto');

            // Upload foto baru
            if ($dataFoto && $dataFoto->isValid() && !$dataFoto->hasMoved()) {
                $fileName = $dataFoto->getRandomName();
                
                // Pastikan direktori img/ exists
                if (!is_dir('img/')) {
                    mkdir('img/', 0755, true);
                }
                
                $dataFoto->move('img/', $fileName);
                $dataForm['foto'] = $fileName;
            }
        }

        $this->product->update($id, $dataForm);

        return redirect()->to('produk')->with('success', 'Data Berhasil Diubah');
    }

    public function delete($id)
    {
        $dataProduk = $this->product->find($id);
        
        if (!$dataProduk) {
            return redirect()->to('produk')->with('error', 'Data tidak ditemukan');
        }

        // Hapus foto jika ada
        if (!empty($dataProduk['foto']) && file_exists("img/" . $dataProduk['foto'])) {
            unlink("img/" . $dataProduk['foto']);
        }

        $this->product->delete($id);

        return redirect()->to('produk')->with('success', 'Data Berhasil Dihapus');
    }
   
    public function download()
    {
        $products = $this->product->findAll();
        $dompdf = new \Dompdf\Dompdf();
        $html = view('v_produkPDF', ['product' => $products]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Download PDF
        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="produk.pdf"')
            ->setBody($dompdf->output());
    }
}