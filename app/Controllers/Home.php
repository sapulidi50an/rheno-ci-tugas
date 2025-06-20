<?php

namespace App\Controllers;

class Home extends BaseController
{
   protected $productModel;
   protected $transaksi;
   protected $detail_transaksi;
   
   public function __construct()
   {
     helper("form");
     helper("number");
     $this->productModel = model('App\Models\ProductModel');
     $this->transaksi = model('App\Models\TransaksiModel'); // pastikan $table = 'transaction'
     $this->detail_transaksi = model('App\Models\DetailTransaksiModel'); // pastikan $table = 'transaction_detail'
   }

   public function index()
   {
        $product = $this->productModel->findAll();
        $data['product'] = $product;
        return view('v_home', $data);
   }

   public function profile()
{
    $username = session()->get('username');
    $data['username'] = $username;

    $buy = $this->transaksi->where('username', $username)->findAll();
    $data['buy'] = $buy;

    $product = [];

    if (!empty($buy)) {
        foreach ($buy as $item) {
            $detail = $this->detail_transaksi
    ->select('detail_transaksi.*, product.nama, product.harga, product.foto')
    ->join('product', 'detail_transaksi.product_id = product.id')
    ->where('transaksi_id', $item['id'])
    ->findAll();

            if (!empty($detail)) {
                $product[$item['id']] = $detail;
            }
            // The following check is not needed here; handle product details in the view
            // if (isset($product[$item['id']]) && !empty($product[$item['id']])) {   foreach ($product[$item['id']] as $index2 => $item2) :}
        }
    }

    $data['product'] = $product;

    return view('v_profile', $data);
}

   public function about()
   {
        return view('v_about');
   }

   public function contact()
   {
        return view('v_contact');
   }



}



