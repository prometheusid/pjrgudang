<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransaksi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'transaksi_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'transaksi_jenis' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'transaksi_customer' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'transaksi_total' => [
                'type' => 'DECIMAL',
                'constraint' => '14,2',
                'default' => 0,
            ],
            'transaksi_tanggal' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('transaksi_id', true);
        $this->forge->createTable('transaksi');

        $this->forge->addField([
            'detail_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'transaksi_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
            ],
            'barang_kode' => [
                'type' => 'CHAR',
                'constraint' => 10,
            ],
            'jumlah' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'harga_satuan' => [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
            ],
            'subtotal' => [
                'type' => 'DECIMAL',
                'constraint' => '14,2',
            ],
        ]);
        $this->forge->addKey('detail_id', true);
        $this->forge->addForeignKey('transaksi_id', 'transaksi', 'transaksi_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('barang_kode', 'barang', 'brgkode', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('transaksi_detail');
    }

    public function down()
    {
        $this->forge->dropTable('transaksi_detail');
        $this->forge->dropTable('transaksi');
    }
}
