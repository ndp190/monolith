<?php

namespace andytruong\odb\domain;

use andytruong\odb\App;
use Doctrine\DBAL\Connection;
use PDO;

class BreadRepository
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function load($id)
    {
        $bread = $this->db->executeQuery('SELECT * FROM odb_bread WHERE id = ?', [$id])->fetch(PDO::FETCH_OBJ);
        if (!$bread) {
            return false;
        }

        $relationship = $this->db->executeQuery('SELECT type, target_id, data FROM odb_edge WHERE source_id = ? ORDER BY weight', [$id]);
        while ($edge = $relationship->fetch(PDO::FETCH_OBJ)) {
            switch ($edge->type) {
                case App::HAS_IMAGE:
                    $bread->image = $edge->data;
                    break;

                case App::HAS_AUDIO:
                    $bread->audio = $edge->data;
                    break;

                case App::HAS_SCRIPTURE:
                    $bread->scripture = $edge->data;
                    break;

                case App::HAS_PLAN:
                    $bread->plan[] = $edge->data;
                    break;

                case App::HAS_POEM:
                    $bread->poem = $edge->data;
                    break;

                case App::HAS_THOUGHT:
                    $bread->thought = $edge->data;
                    break;

                case App::HAS_TAG:
                    $bread->tags[] = $edge->data;
                    break;

                case App::HAS_AUTHOR:
                    $bread->author = $edge->data;
                    break;
            }
        }

        if ($body = json_decode($bread->body)) {
            $bread->body = $body;
        }

        return $bread;
    }

    public function save($title, $description, $body, $created = null)
    {
        $this
            ->db
            ->insert('odb_bread', [
                'title'       => $title,
                'description' => $description,
                'body'        => is_scalar($body) ? $body : json_encode($body),
                'created'     => $created ?: time(),
            ]);

        return $this->db->lastInsertId('odb_bread');
    }

    private function link($type, $sourceId, $targetId = null, $weight = 0, $data = null)
    {
        $targetId = !is_null($targetId) ? $targetId : (1 + $this
            ->db
            ->fetchColumn(
                'SELECT max(target_id) FROM odb_edge WHERE type = ? AND source_id = ?',
                [$type, $sourceId]
            ) ?: 0);

        $this
            ->db
            ->insert('odb_edge', [
                'type'      => $type,
                'source_id' => $sourceId,
                'target_id' => $targetId,
                'weight'    => $weight,
                'data'      => is_null($data) ? null : (is_scalar($data) ? $data : json_encode($data)),
            ]);

        return $this->db->lastInsertId('odb_edge');
    }

    public function linkImage($id, $url)
    {
        $this->link(App::HAS_IMAGE, $id, null, 0, $url);

        return $this;
    }

    public function linkAudio($id, $url)
    {
        $this->link(App::HAS_AUDIO, $id, null, 0, $url);

        return $this;
    }

    public function linkScripture($id, $scripture)
    {
        $this->link(App::HAS_SCRIPTURE, $id, null, 0, $scripture);

        return $this;
    }

    public function linkPlan($id, array $plans)
    {
        foreach ($plans as $weight => $section) {
            $this->link(App::HAS_PLAN, $id, null, $weight, $section);
        }

        return $this;
    }

    public function linkAuthor($id, $author)
    {
        $this->link(App::HAS_AUTHOR, $id, null, 0, $author);

        return $this;
    }

    public function linkTags($id, array $tags)
    {
        foreach ($tags as $weight => $tag) {
            $this->link(App::HAS_TAG, $id, null, $weight, $tag);
        }

        return $this;
    }
}
