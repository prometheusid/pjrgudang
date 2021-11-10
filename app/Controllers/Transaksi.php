<?php

namespace App\Controllers;

class Transaksi extends BaseController
{
    //index transaction page
    public function index()
    {
        return view('transaksi/viewtransaksi');
    }
}
