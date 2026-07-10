<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\DiscountModel;

class DiskonController extends BaseController
{
    protected $discountModel;

    function __construct()
    {
        helper(['form', 'number']);
        $this->discountModel = new DiscountModel();
    }

    public function index()
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to(base_url('/'));
        }

        return view('diskon/index', [
            'discounts' => $this->discountModel->findAll()
        ]);
    }

    public function create()
{
    if (session()->get('role') != 'admin') {
        return redirect()->to(base_url('/'));
    }

    $rules = [
        'tanggal' => 'required|is_unique[discount.tanggal]',
        'nominal' => 'required|numeric'
    ];

    if (!$this->validate($rules)) {
        return redirect()->to(base_url('diskon'))->with('failed', 'Tanggal diskon sudah ada');
    }

    $dataForm = [
        'tanggal' => $this->request->getPost('tanggal'),
        'nominal' => $this->request->getPost('nominal')
    ];

    if (!$this->discountModel->insert($dataForm)) {
        return redirect()->to(base_url('diskon'))->with('failed', 'Data Gagal Ditambah');
    }

    return redirect()->to(base_url('diskon'))->with('success', 'Data Berhasil Ditambah');
}

    public function edit($id)
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to(base_url('/'));
        }

        $rules = [
            'nominal' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('diskon'))->with('failed', 'Data Gagal Diubah');
        }

        $dataForm = [
            'nominal' => $this->request->getPost('nominal')
        ];

        $this->discountModel->update($id, $dataForm);

        return redirect()->to(base_url('diskon'))->with('success', 'Data Berhasil Diubah');
    }

    public function delete($id)
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to(base_url('/'));
        }

        $this->discountModel->delete($id);

        return redirect()->to(base_url('diskon'))->with('success', 'Data Berhasil Dihapus');
    }
}