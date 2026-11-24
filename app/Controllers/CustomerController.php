<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class CustomerController extends BaseController
{
    protected $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        helper('format');
    }

    public function index()
    {
        $customers = $this->customerModel->orderBy('name', 'ASC')->findAll();
        return view('customers/index', [
            'title'     => 'Master Data Pelanggan',
            'customers' => $customers,
        ]);
    }

    public function create()
    {
        return view('customers/form', [
            'title'    => 'Tambah Pelanggan Baru',
            'customer' => null,
        ]);
    }

    public function store()
    {
        $rules = [
            'name'  => 'required|min_length[3]|max_length[150]',
            'phone' => 'permit_empty|max_length[30]',
            'email' => 'permit_empty|valid_email|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $code = trim($this->request->getPost('code') ?? '');
        if (empty($code)) {
            $last = $this->customerModel->orderBy('id', 'DESC')->first();
            $nextSeq = ($last ? (int) $last['id'] : 0) + 1;
            $code = sprintf('CUST-%03d', $nextSeq);
        }

        $this->customerModel->insert([
            'code'      => $code,
            'name'      => $this->request->getPost('name'),
            'phone'     => $this->request->getPost('phone'),
            'email'     => $this->request->getPost('email'),
            'address'   => $this->request->getPost('address'),
            'is_active' => $this->request->getPost('is_active') !== null ? 1 : 0,
        ]);

        return redirect()->to(base_url('customers'))->with('success', 'Data pelanggan berhasil disimpan.');
    }

    public function edit($id)
    {
        $customer = $this->customerModel->find($id);
        if (!$customer) {
            return redirect()->to(base_url('customers'))->with('error', 'Pelanggan tidak ditemukan.');
        }

        return view('customers/form', [
            'title'    => 'Edit Data Pelanggan',
            'customer' => $customer,
        ]);
    }

    public function update($id)
    {
        $customer = $this->customerModel->find($id);
        if (!$customer) {
            return redirect()->to(base_url('customers'))->with('error', 'Pelanggan tidak ditemukan.');
        }

        $rules = [
            'name'  => 'required|min_length[3]|max_length[150]',
            'phone' => 'permit_empty|max_length[30]',
            'email' => 'permit_empty|valid_email|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $this->customerModel->update($id, [
            'name'      => $this->request->getPost('name'),
            'phone'     => $this->request->getPost('phone'),
            'email'     => $this->request->getPost('email'),
            'address'   => $this->request->getPost('address'),
            'is_active' => $this->request->getPost('is_active') !== null ? 1 : 0,
        ]);

        return redirect()->to(base_url('customers'))->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->customerModel->delete($id);
        return redirect()->to(base_url('customers'))->with('success', 'Data pelanggan berhasil dihapus.');
    }
}
