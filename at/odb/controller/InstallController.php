<?php

namespace andytruong\odb\controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\TableExistsException;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\HttpFoundation\JsonResponse;

class InstallController
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function get()
    {
        $schema = $this->db->getSchemaManager()->createSchema();
        if ($schema->hasTable('me_app')) {
            return new JsonResponse(['message' => 'Already installed.'], 400);
        }

        !$schema->hasTable('odb_bread') && $this->createBreadTable($schema);

        foreach ($schema->toSql($this->db->getDatabasePlatform()) as $sql) {
            try {
                $this->db->executeQuery($sql);
            }
            catch (TableExistsException $e) {
            }
        }

        return new JsonResponse([], 200);
    }

    private function createBreadTable(Schema $schema)
    {
        $bread = $schema->createTable('odb_bread');
        $bread->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
        $bread->addColumn('language', 'string', ['default' => 'en']);
        $bread->addColumn('title', 'string');
        $bread->addColumn('description', 'text');
        $bread->addColumn('body', 'blob');
        $bread->addColumn('created', 'datetime');
        $bread->setPrimaryKey(['id']);
        $bread->addIndex(['created']);

        $edge = $schema->createTable('odb_edge');
        $edge->addColumn('id', 'integer', ['unsigned' => true, 'autoincrement' => true]);
        $edge->addColumn('type', 'integer', ['unsigned' => true]);
        $edge->addColumn('source_id', 'integer', ['unsigned' => true]);
        $edge->addColumn('target_id', 'integer', ['unsigned' => true]);
        $edge->addColumn('data', 'text', ['notnull' => false]);
        $edge->addColumn('weight', 'integer', ['unsigned' => true]);
        $edge->setPrimaryKey(['id']);
        $edge->addUniqueIndex(['type', 'source_id', 'target_id']);
        $edge->addIndex(['type', 'source_id', 'weight']);
        $edge->addIndex(['type', 'target_id', 'weight']);
    }
}
