<?php

namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\DiscountModel;

class DiskonController extends ResourceController
{
    protected $model;
    private $token;

    function __construct()
    {
        $this->model = new DiscountModel();
$this->token = trim((string) env('MY_API_KEY'));    }

    private function authenticate()
    {
        $header = $this->request->getHeaderLine('Authorization');

        if (empty($header)) {
            return false;
        }

        if (!preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return false;
        }

        return trim($matches[1]) === $this->token;
    }

    private function unauthorized()
    {
        return $this->respond([
            'status'  => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    public function index()
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = (int) ($this->request->getGet('per_page') ?? 10);

        $discounts = $this->model->paginate($perPage, 'default', $page);

        return $this->respond([
            'data' => $discounts,
            'pagination' => [
                'current_page' => $page,
                'per_page'     => $perPage,
                'last_page'    => $this->model->pager->getPageCount(),
                'total_data'   => $this->model->pager->getTotal(),
                'has_next'     => $page < $this->model->pager->getPageCount(),
                'has_prev'     => $page > 1,
            ]
        ]);
    }

    public function show($id = null)
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $discount = $this->model->find($id);

        if (!$discount) {
            return $this->failNotFound('Diskon tidak ditemukan');
        }

        return $this->respond($discount);
    }

    public function new()
    {
        //
    }

    public function create()
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->failValidationErrors('Data tidak valid');
        }

        if (empty($data['tanggal']) || empty($data['nominal'])) {
            return $this->failValidationErrors('Tanggal dan nominal wajib diisi');
        }

        $existing = $this->model->where('tanggal', $data['tanggal'])->first();

        if ($existing) {
            return $this->failValidationErrors('Tanggal diskon sudah ada');
        }

        $this->model->insert([
            'tanggal' => $data['tanggal'],
            'nominal' => $data['nominal']
        ]);

        return $this->respondCreated([
            'message' => 'Diskon berhasil ditambahkan'
        ]);
    }

    public function edit($id = null)
    {
        //
    }

    public function update($id = null)
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $discount = $this->model->find($id);

        if (!$discount) {
            return $this->failNotFound('Diskon tidak ditemukan');
        }

        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->failValidationErrors('Data tidak valid');
        }

        if (isset($data['tanggal'])) {
            $existing = $this->model
                ->where('tanggal', $data['tanggal'])
                ->where('id !=', $id)
                ->first();

            if ($existing) {
                return $this->failValidationErrors('Tanggal diskon sudah ada');
            }
        }

        $updateData = [];

        if (isset($data['tanggal'])) {
            $updateData['tanggal'] = $data['tanggal'];
        }

        if (isset($data['nominal'])) {
            $updateData['nominal'] = $data['nominal'];
        }

        $this->model->update($id, $updateData);

        return $this->respond([
            'message' => 'Diskon berhasil diperbarui'
        ]);
    }

    public function delete($id = null)
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        if (!$this->model->find($id)) {
            return $this->failNotFound('Diskon tidak ditemukan');
        }

        $this->model->delete($id);

        return $this->respondDeleted([
            'message' => 'Diskon berhasil dihapus'
        ]);
    }
}