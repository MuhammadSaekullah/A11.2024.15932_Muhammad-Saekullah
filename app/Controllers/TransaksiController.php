<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\RajaOngkirService;
use App\Models\TransactionModel; 
use App\Models\TransactionDetailModel;

class TransaksiController extends BaseController
{
    protected $cart;
    protected $transactionModel;
    protected $transactionDetailModel;

    public function __construct()
    {
        // Memuat helper bawaan CodeIgniter 4 yang dibutuhkan
        helper(['number', 'form', 'url']);
        
        // Menginisialisasi library shopping cart
        $this->cart = service('cart');

        // Menginisialisasi model yang dibutuhkan untuk proses transaksi dan riwayat
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
    }

    /**
     * Menampilkan halaman daftar keranjang belanja
     */
    public function index()
    {  
        $data = [
            'items' => $this->cart->contents(),
            'total' => $this->cart->total()  
        ];

        return view('v_keranjang', $data);
    }

    /**
     * MENANGANI POST: keranjang/add
     * Menambahkan produk ke dalam data keranjang belanja
     */
    public function cart_add()
    {
        $this->cart->insert([
            'id'      => $this->request->getPost('id'),
            'qty'     => 1,
            'price'   => $this->request->getPost('harga'),
            'name'    => $this->request->getPost('nama'),
            'options' => [
                'foto' => $this->request->getPost('foto')
            ]
        ]);
        
        session()->setFlashdata('success', 'Produk berhasil ditambahkan ke keranjang.');
        return redirect()->to(base_url('/'));
    }

    /**
     * MENANGANI POST: keranjang/edit
     * Memperbarui kuantitas (qty) barang di dalam keranjang
     */
    public function cart_edit()
    {
        $rowid = $this->request->getPost('rowid');
        $qty   = $this->request->getPost('qty');

        if (!empty($rowid) && is_array($rowid)) {
            foreach ($rowid as $i => $id) {
                $this->cart->update([
                    'rowid' => $id,
                    'qty'   => $qty[$i]
                ]);
            }
            session()->setFlashdata('success', 'Keranjang belanja berhasil diperbarui.');
        }

        return redirect()->to(base_url('keranjang'));
    }

    /**
     * MENANGANI GET: keranjang/delete/(:any)
     * Menghapus salah satu item produk dari keranjang
     */
    public function cart_delete($rowid)
    {
        $this->cart->remove($rowid);
        session()->setFlashdata('success', 'Produk berhasil dihapus dari keranjang.');
        return redirect()->to(base_url('keranjang'));
    } 

    /**
     * MENANGANI GET: keranjang/clear
     * Mengosongkan seluruh isi data keranjang belanja
     */
    public function cart_clear()
    {
        $this->cart->destroy();
        session()->setFlashdata('success', 'Seluruh isi keranjang belanja berhasil dikosongkan.');
        return redirect()->to(base_url('keranjang'));
    }

    /**
     * Menampilkan halaman checkout belanja
     */
    public function checkout()
    {  
        $service = new RajaOngkirService();
        $response = $service->getDestination('semarang');

        $data = [
            'title'    => 'Checkout', 
            'items'    => $this->cart->contents(),
            'total'    => $this->cart->total(),
            'response' => $response,
        ];

        return view('v_checkout', $data);
    }

    /**
     * MENANGANI AJAX: ajax/costs
     * Membaca flat array respon murni dari API Komerce
     */
    public function costs()
    {
        $origin = '64999'; // Statis: Pedurungan Tengah
        
        // Menangkap data secara fleksibel baik melalui query string GET maupun POST
        $destination = $this->request->getGet('destination') ?? $this->request->getPost('destination');
        
        // Fallback: Jika data dikirimkan lewat JSON payload murni
        if (empty($destination)) {
            $json = $this->request->getJSON();
            $destination = $json->destination ?? null;
        }
        
        $weight = 1000;  
        $courier = 'jne';  

        $service = new RajaOngkirService();
        $response = $service->getCost($origin, $destination, $weight, $courier);

        $results = [];
        
        $costsData = isset($response['data']) ? $response['data'] : $response;

        if (!empty($costsData) && is_array($costsData)) {
            foreach ($costsData as $item) {
                $costValue = is_numeric($item['cost'] ?? null) ? $item['cost'] : ($item['cost'][0]['value'] ?? 0);
                $etdValue  = $item['etd'] ?? ($item['cost'][0]['etd'] ?? '-');

                $results[] = [
                    'service'     => $item['service'] ?? '',
                    'description' => $item['description'] ?? '',
                    'cost'        => (int)$costValue, 
                    'etd'         => $etdValue   
                ];
            }
        }

        return $this->response->setJSON($results);
    }

    /**
     * MENANGANI POST: buy
     * Menyimpan data transaksi induk dan detail ke dalam database
     */
    public function buy()
    { 
        $cartItems = $this->cart->contents();

        if (empty($cartItems)) {
            return redirect()->back();
        }

        $db = \Config\Database::connect();
        $db->transStart(); 

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['qty'] * $item['price'];
        }

        $ongkir = (int) $this->request->getPost('ongkir');

        $transaction = [
            'username'    => $this->request->getPost('username'),
            'alamat'      => $this->request->getPost('alamat'),
            'ongkir'      => $ongkir,
            'total_harga' => $subtotal + $ongkir,
            'status'      => 0, 
        ];

        // Insert ke tabel transaksi induk
        if (!$this->transactionModel->insert($transaction)) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal membuat transaksi');
        }

        $transactionId = $this->transactionModel->getInsertID();

        // Insert ke tabel detail transaksi per item barang
        foreach ($cartItems as $item) {
            $this->transactionDetailModel->insert([
                'transaction_id' => $transactionId,
                'product_id'     => $item['id'],
                'jumlah'         => $item['qty'],
                'diskon'         => 0,
                'subtotal_harga' => $item['qty'] * $item['price'] 
            ]);
        }

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->with('error', 'Gagal membuat transaksi');
        }

        // Hapus session keranjang belanja setelah checkout sukses
        $this->cart->destroy();
        return redirect()->to(base_url());
    }

    /**
     * MENANGANI GET: history
     * Menampilkan riwayat transaksi belanja milik user yang sedang aktif
     */
    public function history()
    {
        $username = session()->get('username'); 
     
        $transactions = $this->transactionModel->where('username', $username)->findAll();
        $transactionIds = array_column($transactions, 'id');

        $products = $this->transactionDetailModel->getProductsByTransactionIds($transactionIds);

        $data = [
            'title'        => 'History Transaksi',
            'username'      => $username,
            'transactions'  => $transactions,
            'products'      => $products
        ]; 

        return view('v_history', $data);
    }
}