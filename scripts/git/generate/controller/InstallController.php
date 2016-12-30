<?php

namespace go1\xxxxx\controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use go1\util\DB;

class InstallController
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function get()
    {
        return DB::install($this->db, [
            function (Schema $schema) {
                if (!$schema->hasTable('xxxxx_xxxxx')) {
                    $table = $schema->createTable('xxxxx_xxxxx');
                }
            },
        ]);
    }
}
