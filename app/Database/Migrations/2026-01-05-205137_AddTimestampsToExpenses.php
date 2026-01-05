<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTimestampsToExpenses extends Migration
{
    public function up()
    {
        $fields = [
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'created_at'
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at'
            ]
        ];
        $this->forge->addColumn('expenses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('expenses', ['updated_at', 'deleted_at']);
    }
}
