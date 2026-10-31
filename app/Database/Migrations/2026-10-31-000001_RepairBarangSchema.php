<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RepairBarangSchema extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('brgstok', 'barang')) {
            $this->forge->addColumn('barang', [
                'brgstok' => [
                    'type' => 'int',
                    'unsigned' => true,
                    'default' => 0,
                ],
            ]);
        } else {
            $this->forge->modifyColumn('barang', [
                'brgstok' => [
                    'type' => 'int',
                    'unsigned' => true,
                    'default' => 0,
                ],
            ]);
        }

        if ($this->db->fieldExists('brgharga', 'barang')) {
            $this->forge->modifyColumn('barang', [
                'brgharga' => [
                    'type' => 'decimal',
                    'constraint' => '12,2',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('brgharga', 'barang')) {
            $this->forge->modifyColumn('barang', [
                'brgharga' => [
                    'type' => 'double',
                ],
            ]);
        }
        if ($this->db->fieldExists('brgstok', 'barang')) {
            $this->forge->modifyColumn('barang', [
                'brgstok' => [
                    'type' => 'int',
                ],
            ]);
        }
    }
}
