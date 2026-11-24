<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('auth/login');
    }

    public function processLogin()
    {
        $email    = trim($this->request->getPost('email') ?? '');
        $password = trim($this->request->getPost('password') ?? '');

        if (empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Silakan isi email dan password.');
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        if ((int)$user['is_active'] !== 1) {
            return redirect()->back()->withInput()->with('error', 'Akun Anda dinonaktifkan. Hubungi administrator.');
        }

        session()->set([
            'is_logged_in' => true,
            'user_id'      => $user['id'],
            'user_name'    => $user['name'],
            'user_email'   => $user['email'],
            'user_role'    => $user['role'],
        ]);

        return redirect()->to(base_url('dashboard'))->with('success', "Selamat datang kembali, {$user['name']}!");
    }

    public function quickLogin()
    {
        $role = $this->request->getGet('role') ?? 'admin';
        $email = ($role === 'kasir') ? 'kasir@umkm.id' : 'admin@umkm.id';

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user) {
            session()->set([
                'is_logged_in' => true,
                'user_id'      => $user['id'],
                'user_name'    => $user['name'],
                'user_email'   => $user['email'],
                'user_role'    => $user['role'],
            ]);
            return redirect()->to(base_url('dashboard'))->with('success', "Selamat datang, {$user['name']}!");
        }

        return redirect()->to(base_url('login'));
    }

    public function register()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to(base_url('dashboard'));
        }

        return view('auth/register');
    }

    public function processRegister()
    {
        $name     = trim($this->request->getPost('name') ?? '');
        $email    = trim($this->request->getPost('email') ?? '');
        $password = trim($this->request->getPost('password') ?? '');
        $confirm  = trim($this->request->getPost('password_confirm') ?? '');

        if (empty($name) || empty($email) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Semua kolom wajib diisi.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->withInput()->with('error', 'Format email tidak valid.');
        }

        if (strlen($password) < 6) {
            return redirect()->back()->withInput()->with('error', 'Password minimal 6 karakter.');
        }

        if ($password !== $confirm) {
            return redirect()->back()->withInput()->with('error', 'Konfirmasi password tidak cocok.');
        }

        $userModel = new UserModel();
        $existing = $userModel->where('email', $email)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Email sudah terdaftar. Silakan gunakan email lain atau login.');
        }

        $userModel->insert([
            'name'      => $name,
            'email'     => $email,
            'password'  => password_hash($password, PASSWORD_BCRYPT),
            'role'      => 'kasir',
            'is_active' => 1,
        ]);

        return redirect()->to(base_url('login'))->with('success', 'Registrasi berhasil! Akun Kasir Anda telah aktif. Silakan masuk.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'Anda telah berhasil logout.');
    }
}
