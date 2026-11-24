<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (!$session->get('is_logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $userRole = $session->get('user_role');
        $allowedRoles = $arguments ?? ['admin'];

        if (!in_array($userRole, $allowedRoles)) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Akses ditolak: Anda tidak memiliki wewenang untuk membuka halaman ini.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
