<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    function __construct()
    {
        helper('form');
    }

    public function login()
    {
        if ($this->request->getPost()) {
            $rules = [
                'username' => 'required|min_length[6]',
                'password' => 'required|min_length[7]',
            ];
    
            if ($this->validate($rules)) {
                $username = $this->request->getVar('username');
                $password = $this->request->getVar('password');
    
                // Contoh data user dengan password hash yang benar (password: a1115368)
                $dataUser = [
                    'username' => 'Rhenonabil',
                    'password' => '$2y$10$SZeBwnBGpIao7R0vEbxpiu/hAOQya5vjNLaHlIvem2pHt/eCuOSFW', // hasil password_hash('a1115368', PASSWORD_DEFAULT)
                    'role' => 'admin'
                ];
                
                if ($username === $dataUser['username']) {
                    if (password_verify($password, $dataUser['password'])) {
                        session()->set([
                            'username' => $dataUser['username'],
                            'role' => $dataUser['role'],
                            'isLoggedIn' => TRUE
                        ]);
                        return redirect()->to(base_url('/'));
                    } else {
                        session()->setFlashdata('failed', 'Kombinasi Username & Password Salah');
                        return redirect()->back();
                    }
                } else {
                    session()->setFlashdata('failed', 'Username Tidak Ditemukan');
                    return redirect()->back();
                }
                
            } else {
                session()->setFlashdata('failed', $this->validator->listErrors());
                return redirect()->back();
            }
        }
    
        return view('v_login');
    }
    
}