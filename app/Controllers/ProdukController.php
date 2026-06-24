<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ProductModel;
    
class ProdukController extends BaseController
{
    protected $productModel; 

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        return view('produk/index', [
            'products' => $this->productModel->findAll()
        ]);
    }

    public function create()
    {
        $dataFoto = $this->request->getFile('foto');

        $dataForm = [
            'nama'   => $this->request->getPost('nama'),
            'harga'  => $this->request->getPost('harga'),
            'jumlah' => $this->request->getPost('jumlah')
            // Kolom waktu (created_at & updated_at) otomatis diisi oleh Model
        ];

        if ($dataFoto && $dataFoto->isValid()) {
            $fileName = $dataFoto->getRandomName(); 
            $dataFoto->move('img/', $fileName);
            
            $dataForm['foto'] = $fileName;
        }

        $this->productModel->insert($dataForm);

        return redirect()->to('produk')->with('success', 'Data Berhasil Ditambah');
    }     

    public function edit($id)
    {
        $dataProduk = $this->productModel->find($id);

        $dataForm = [
            'nama'   => $this->request->getPost('nama'),
            'harga'  => $this->request->getPost('harga'),
            'jumlah' => $this->request->getPost('jumlah')
            // Kolom updated_at otomatis diperbarui oleh Model
        ];

        if ($this->request->getPost('check') == 1) {
            if ($dataProduk['foto'] != '' && file_exists("img/" . $dataProduk['foto'])) {
                unlink("img/" . $dataProduk['foto']);
            }

            $dataFoto = $this->request->getFile('foto');

            if ($dataFoto && $dataFoto->isValid()) {
                $fileName = $dataFoto->getRandomName();
                $dataFoto->move('img/', $fileName);
                
                $dataForm['foto'] = $fileName;
            }
        }

        $this->productModel->update($id, $dataForm);

        return redirect()->to('produk')->with('success', 'Data Berhasil Diubah');
    }

    public function delete($id)
    {
        // Panggilan delete() ini otomatis akan mengisi kolom deleted_at di database Anda
        $this->productModel->delete($id);

        return redirect()->to('produk')->with('success', 'Data Berhasil Dihapus');
    }
}