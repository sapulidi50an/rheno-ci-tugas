<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

use App\Models\UserModel;
use App\Models\TransaksiModel;
use App\Models\DetailTransaksiModel;

class ApiController extends ResourceController
{
    protected $apiKey = '45HdbCNX4242c4b481425130xFedFYOr';
    protected $user;
    protected $transaction;
    protected $transaction_detail;

    public function __construct()
    {
        $this->user = new UserModel();
        $this->transaction = new TransaksiModel();
        $this->transaction_detail = new DetailTransaksiModel();
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data = [ 
            'results' => [],
            'status' => ["code" => 401, "description" => "Unauthorized"]
        ];

        $headers = $this->request->headers(); 

        array_walk($headers, function (&$value, $key) {
            $value = $value->getValue();
        });

        if(array_key_exists("Key", $headers)){
            if ($headers["Key"] == $this->apiKey) {
                $penjualan = $this->transaction->findAll();
                
                foreach ($penjualan as &$pj) {
                    $pj['details'] = $this->transaction_detail->where('transaksi_id', $pj['id'])->findAll();
                }

                $data['status'] = ["code" => 200, "description" => "OK"];
                $data['results'] = $penjualan;

            }
        } 

        return $this->respond($data);
    }
    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties.
     *
     * @return ResponseInterface
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        //
    }
}
