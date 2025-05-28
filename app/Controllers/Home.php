<?php

namespace App\Controllers;

class Home extends BaseController
{
   protected $productModel;
   
   public function __construct()
   {
     helper("form");
     helper("number");
     $this->productModel = model('App\Models\ProductModel');
   }

   public function index()
   {
        $product = $this->productModel->findAll();
        $data['product'] = $product; // perbaiki key di sini
        return view('v_home', $data);
   }
}
