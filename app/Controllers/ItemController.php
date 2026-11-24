<?php

namespace App\Controllers;

use App\Models\ItemModel;

class ItemController extends BaseController
{
    protected $itemModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        helper('format');
    }

    public function index()
    {
        $items = $this->itemModel->orderBy('name', 'ASC')->findAll();
        return view('items/index', [
            'title' => 'Master Jasa & Layanan',
            'items' => $items,
        ]);
    }

    public function create()
    {
        return view('items/form', [
            'title' => 'Tambah Layanan Jasa Baru',
            'item'  => null,
        ]);
    }

    public function store()
    {
        $rules = [
            'name'       => 'required|min_length[3]|max_length[150]',
            'sell_price' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $code = trim($this->request->getPost('code') ?? '');
        if (empty($code)) {
            $last = $this->itemModel->orderBy('id', 'DESC')->first();
            $nextSeq = ($last ? (int) $last['id'] : 0) + 1;
            $code = sprintf('SRV-%03d', $nextSeq);
        }

        $this->itemModel->insert([
            'code'        => $code,
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'unit'        => $this->request->getPost('unit') ?? 'transaksi',
            'sell_price'  => (float) $this->request->getPost('sell_price'),
            'is_active'   => $this->request->getPost('is_active') !== null ? 1 : 0,
        ]);

        return redirect()->to(base_url('items'))->with('success', 'Layanan jasa berhasil disimpan.');
    }

    public function edit($id)
    {
        $item = $this->itemModel->find($id);
        if (!$item) {
            return redirect()->to(base_url('items'))->with('error', 'Layanan tidak ditemukan.');
        }

        return view('items/form', [
            'title' => 'Edit Layanan Jasa',
            'item'  => $item,
        ]);
    }

    public function update($id)
    {
        $item = $this->itemModel->find($id);
        if (!$item) {
            return redirect()->to(base_url('items'))->with('error', 'Layanan tidak ditemukan.');
        }

        $rules = [
            'name'       => 'required|min_length[3]|max_length[150]',
            'sell_price' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $this->itemModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'unit'        => $this->request->getPost('unit') ?? 'transaksi',
            'sell_price'  => (float) $this->request->getPost('sell_price'),
            'is_active'   => $this->request->getPost('is_active') !== null ? 1 : 0,
        ]);

        return redirect()->to(base_url('items'))->with('success', 'Layanan jasa berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->itemModel->delete($id);
        return redirect()->to(base_url('items'))->with('success', 'Layanan jasa berhasil dihapus.');
    }

    /**
     * AJAX lookup for invoice dynamic line items
     */
    public function getPriceJson($id)
    {
        $item = $this->itemModel->find($id);
        if (!$item) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Item not found'])->setStatusCode(404);
        }

        return $this->response->setJSON([
            'status'     => 'success',
            'id'         => $item['id'],
            'code'       => $item['code'],
            'name'       => $item['name'],
            'unit'       => $item['unit'],
            'sell_price' => (float) $item['sell_price'],
        ]);
    }
}
