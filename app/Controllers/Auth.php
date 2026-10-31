<?php

namespace App\Controllers;

use Config\Auth as AuthConfig;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('admin_logged_in') === true) {
            return redirect()->to(site_url('/'));
        }

        $auth = config(AuthConfig::class);
        $data = ['error' => '', 'configured' => $auth->isConfigured()];
        if (! $data['configured']) {
            $this->response->setStatusCode(503);
        }

        return view('auth/login', $data);
    }

    public function attempt()
    {
        $auth = config(AuthConfig::class);
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (! $auth->isConfigured()) {
            $this->response->setStatusCode(503);
            return view('auth/login', [
                'error' => '',
                'configured' => false,
            ]);
        }
        if (! is_string($username) || ! is_string($password)) {
            return view('auth/login', [
                'error' => 'Username atau password tidak sesuai.',
                'configured' => true,
            ]);
        }

        $validPassword = password_verify($password, $auth->adminPasswordHash);
        if (! hash_equals($auth->adminUsername, $username) || ! $validPassword) {
            return view('auth/login', [
                'error' => 'Username atau password tidak sesuai.',
                'configured' => true,
            ]);
        }

        session()->regenerate(true);
        session()->set('admin_logged_in', true);

        return redirect()->to(site_url('/'));
    }

    public function logout()
    {
        session()->remove('admin_logged_in');
        session()->regenerate(true);

        return redirect()->to(site_url('login'));
    }
}
