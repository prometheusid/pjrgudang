<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Auth extends BaseConfig
{
    public $adminUsername = '';
    public $adminPasswordHash = '';

    public function __construct()
    {
        parent::__construct();

        $this->adminUsername = (string) env('auth.adminUsername', '');
        $this->adminPasswordHash = (string) env('auth.adminPasswordHash', '');
    }

    public function isConfigured(): bool
    {
        return $this->adminUsername !== '' && $this->adminPasswordHash !== '';
    }
}
