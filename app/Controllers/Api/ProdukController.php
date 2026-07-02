<?php

namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\ProductModel;

class ProdukController extends ResourceController
{
    protected $model;  
    private $token;

    function __construct()
    { 
        $this->model = new ProductModel(); 
        $this->token = env('MY_API_KEY');
    }

    private function authenticate()
    {
        $header = $this->request->getHeaderLine('Authorization');

        if (empty($header)) {
            return false;
        }

        if (!preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return false;
        }

        return $matches[1] === $this->token;
    }

    private function unauthorized()
    {
        return $this->respond([
            'status'  => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        return $this->respond($this->model->findAll());
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
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $product = $this->model->find($id);

        if (!$product) {
            return $this->failNotFound('Produk tidak ditemukan');
        }

        return $this->respond($product);
    } 

    /**
     * Create a new resource object, from "posted" parameters.
     *
     * @return ResponseInterface
     */
    public function create()
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $data = $this->request->getJSON(true);

        $this->model->insert($data);

        return $this->respondCreated([
            'message' => 'Produk berhasil ditambahkan'
        ]);
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
        // Method ini biasanya dilewati jika hanya membuat RESTful API murni
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
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        if (!$this->model->find($id)) {
            return $this->failNotFound('Produk tidak ditemukan');
        }

        $data = $this->request->getJSON(true);

        $this->model->update($id, $data);

        return $this->respond([
            'message' => 'Produk berhasil diperbarui'
        ]);
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
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        if (!$this->model->find($id)) {
            return $this->failNotFound('Produk tidak ditemukan');
        }

        $this->model->delete($id);

        return $this->respondDeleted([
            'message' => 'Produk berhasil dihapus'
        ]);
    }
}