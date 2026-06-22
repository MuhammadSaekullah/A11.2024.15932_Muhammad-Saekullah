<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;

    function __construct()
    {
    helper('form');
    $this->userModel = new UserModel();
    }

    public function login()
    {
        if ($this->request->getPost()) {
            $rules = [
                'username' => 'required|min_length[6]',
                'password' => 'required|min_length[7]|numeric',
            ];

            if ($this->validate($rules)) {
            $username = $this->request->getVar('username' );
            $password = $this->request->getVar('password');

            $dataUser = $this->userModel ->where(['username' => $username])->first();
            //$dataUser = ['username' => 'Muhammad Saekullah', 'password' => 'fcea920f7412b5da7be0cf42b8c93759', 'role' => 'admin']; // passw masih 1234567
            if ($dataUser) {
	            if (md5($password) == $dataUser['password']) {
                session()->set([
                    'username' => $dataUser['username'],
                    'role' => $dataUser['role'],
                    'isLoggedIn' => TRUE
                ]);

                return redirect()->to(base_url('/'));
            } else {
                session()->setFlashdata('failed', 'Username & Password Salah');
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
    } else {
        return view('v_login');
    }
   }
   
   public function logout()
   {
        session()->destroy();
        return redirect()->to('login');
   }
}

