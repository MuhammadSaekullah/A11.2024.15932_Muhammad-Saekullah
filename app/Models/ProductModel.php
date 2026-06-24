<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'product'; 
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // AKTIFKAN SOFT DELETES: Produk tidak terhapus permanen dari phpMyAdmin
    protected $useSoftDeletes   = true; 
    
    protected $protectFields    = true;
    
    // Semua nama kolom wajib didaftarkan di sini agar diizinkan masuk ke database
    protected $allowedFields    = ['nama', 'harga', 'jumlah', 'foto', 'created_at', 'updated_at', 'deleted_at']; 

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates (Pengaturan Waktu Otomatis)
    // AKTIFKAN TRUE: CodeIgniter otomatis yang akan mengisi kolom waktu Anda
    protected $useTimestamps = true; 
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = true; 
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}