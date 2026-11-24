<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper('format');
    }

    public function index()
    {
        $users = $this->userModel->orderBy('name', 'ASC')->findAll();
        return view('users/index', [
            'title' => 'Manajemen Pengguna (User Management)',
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('users/form', [
            'title' => 'Tambah Pengguna Baru',
            'user'  => null,
        ]);
    }

    public function store()
    {
        $rules = [
            'name'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,kasir]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $this->userModel->insert([
            'name'      => trim($this->request->getPost('name')),
            'email'     => strtolower(trim($this->request->getPost('email'))),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'      => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') !== null ? 1 : 0,
        ]);

        return redirect()->to(base_url('users'))->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to(base_url('users'))->with('error', 'Pengguna tidak ditemukan.');
        }

        return view('users/form', [
            'title' => 'Edit Data Pengguna',
            'user'  => $user,
        ]);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to(base_url('users'))->with('error', 'Pengguna tidak ditemukan.');
        }

        $rules = [
            'name'  => 'required|min_length[3]|max_length[100]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role'  => 'required|in_list[admin,kasir]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $updateData = [
            'name'      => trim($this->request->getPost('name')),
            'email'     => strtolower(trim($this->request->getPost('email'))),
            'role'      => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') !== null ? 1 : 0,
        ];

        // Ganti password jika diisi
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            if (strlen($password) < 6) {
                return redirect()->back()->withInput()->with('error', 'Password minimal 6 karakter.');
            }
            $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->userModel->update($id, $updateData);

        return redirect()->to(base_url('users'))->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to(base_url('users'))->with('error', 'Pengguna tidak ditemukan.');
        }

        // Cegah menghapus akun sendiri
        if ((int)$id === (int)session()->get('user_id')) {
            return redirect()->to(base_url('users'))->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        $this->userModel->delete($id);
        return redirect()->to(base_url('users'))->with('success', 'Pengguna berhasil dihapus.');
    }
}
