<?php
namespace App\Controllers;
date_default_timezone_set('Asia/Jakarta');
use App\Models\TransaksiModel;
use App\Models\DetailTransaksiModel;

class TransaksiController extends BaseController
{
    protected $cart;
    protected $client;
    protected $apiKey;
    protected $transaksiModel;
    protected $detailTransaksiModel;
    protected $db;

    function __construct()
    {
        helper('number');
        helper('form');
        $this->cart = \Config\Services::cart();
        $this->client = new \GuzzleHttp\Client();
        $this->apiKey = env('COST_API_KEY', 'BsXYd5ew8b00f598096a1d19pzpVoyke');
        $this->transaksiModel = new TransaksiModel();
        $this->detailTransaksiModel = new DetailTransaksiModel();
         $this->db = \Config\Database::connect(); // Tambahkan barisini
    }

    public function index()
    {
        $data['items'] = $this->cart->contents();
        $data['total'] = $this->cart->total();
        return view('v_keranjang', $data);
    }

    public function cart_add()
    {
        $this->cart->insert([
            'id'        => $this->request->getPost('id'),
            'qty'       => 1,
            'price'     => $this->request->getPost('harga'),
            'name'      => $this->request->getPost('nama'),
            'options'   => ['foto' => $this->request->getPost('foto')]
        ]);
        session()->setFlashdata('success', 'Produk berhasil ditambahkan ke keranjang. (<a href="' . base_url() . 'keranjang">Lihat</a>)');
        return redirect()->to(base_url('/'));
    }

    public function cart_clear()
    {
        $this->cart->destroy();
        session()->setFlashdata('success', 'Keranjang Berhasil Dikosongkan');
        return redirect()->to(base_url('keranjang'));
    }

    public function cart_edit()
    {
        $i = 1;
        foreach ($this->cart->contents() as $value) {
            $this->cart->update([
                'rowid' => $value['rowid'],
                'qty'   => $this->request->getPost('qty' . $i++)
            ]);
        }

        session()->setFlashdata('success', 'Keranjang Berhasil Diedit');
        return redirect()->to(base_url('keranjang'));
    }

    public function cart_delete($rowid)
    {
        $this->cart->remove($rowid);
        session()->setFlashdata('success', 'Keranjang Berhasil Dihapus');
        return redirect()->to(base_url('keranjang'));
    }

    public function checkout()
    {
        $data['items'] = $this->cart->contents();
        $data['total'] = $this->cart->total();
        $data['ongkir'] = 0; // atau nilai default ongkir

        return view('v_checkout', $data);
    }

    // Tetap gunakan getLocation versi lama
    public function getLocation()
    {
        $search = $this->request->getGet('search');

        $response = $this->client->request(
            'GET', 
            'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search='.$search.'&limit=50', [
                'headers' => [
                    'accept' => 'application/json',
                    'key' => $this->apiKey,
                ],
            ]
        );

        $body = json_decode($response->getBody(), true); 
        return $this->response->setJSON($body['data']);
    }

    public function getCost()
    {
        $origin = $this->request->getGet('origin') ?? '64999';
        $destination = $this->request->getGet('destination');
        $weight = $this->request->getGet('weight') ?? 1000;
        $courier = $this->request->getGet('courier') ?? 'jne';

        $client = \Config\Services::curlrequest();
        $response = $client->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            'form_params' => [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier
            ],
            'headers' => [
                'key' => $this->apiKey
            ]
        ]);
        $result = json_decode($response->getBody(), true);

        if (isset($result['data']) && is_array($result['data'])) {
            return $this->response->setJSON($result['data']);
        } else {
            return $this->response->setJSON([]);
        }
    }

    public function buy()
    {
        if ($this->request->getPost()) { 
            $dataForm = [
                'username' => $this->request->getPost('username'),
                'total_harga' => $this->request->getPost('total_harga'),
                'alamat' => $this->request->getPost('alamat'),
                'ongkir' => $this->request->getPost('ongkir'),
                'status' => 0,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ];

            $this->transaksiModel->insert($dataForm);

            $last_insert_id = $this->transaksiModel->getInsertID();

            foreach ($this->cart->contents() as $value) {
                $dataFormDetail = [
                    'transaksi_id' => $last_insert_id,
                    'product_id' => $value['id'],
                    'jumlah' => $value['qty'],
                    'diskon' => 0,
                    'subtotal_harga' => $value['qty'] * $value['price'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ];

                $this->detailTransaksiModel->insert($dataFormDetail);
            }

            $this->cart->destroy();
     
            session()->setFlashdata('success', 'Pesanan berhasil dibuat!');
            return redirect()->to('/checkout'); // atau ke halaman sukses lain
        }
    }

    // Tambahkan fitur edit nama penerima transaksi
    public function editNama($id)
    {
        $transaksi = $this->transaksiModel->find($id);

        if (!$transaksi) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan');
        }

        if ($this->request->getMethod() === 'post') {
            $newName = $this->request->getPost('nama_penerima');
            $this->transaksiModel->update($id, [
                'nama_penerima' => $newName,
                'updated_at' => date("Y-m-d H:i:s")
            ]);
            session()->setFlashdata('success', 'Nama penerima berhasil diubah.');
            return redirect()->to(base_url('transaksi/detail/' . $id));
        }

        return view('v_edit_nama', ['transaksi' => $transaksi]);
    }
}