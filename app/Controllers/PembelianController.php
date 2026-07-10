<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class PembelianController extends BaseController
{
    protected $transactionModel;
    protected $transactionDetailModel;

    function __construct()
    {
        helper(['form', 'number']);
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
    }

    public function index()
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to(base_url('/'));
        }

        $transactions = $this->transactionModel->findAll();
        $transactionIds = array_column($transactions, 'id');

        $products = $this->transactionDetailModel->getProductsByTransactionIds($transactionIds);

        $data = [
            'transactions' => $transactions,
            'products'     => $products
        ];

        return view('pembelian/index', $data);
    }

    public function updateStatus($id)
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to(base_url('/'));
        }

        $dataForm = [
            'status' => $this->request->getPost('status')
        ];

        $this->transactionModel->update($id, $dataForm);

        return redirect()->to(base_url('pembelian'))->with('success', 'Status Pembelian Berhasil Diubah');
    }
}