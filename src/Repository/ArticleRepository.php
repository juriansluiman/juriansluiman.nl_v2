<?php

namespace App\Repository;

use Redis;

class ArticleRepository
{
    protected $redis;
    protected $prefix;

    public function __construct(Redis $redis, $prefix)
    {
        $this->redis  = $redis;
        $this->prefix = $prefix;
    }

    public function getTotalCount()
    {
        return $this->redis->zcount($this->key('articles'), '-inf', '+inf');
    }

    public function fetchRecent($limit)
    {
        return $this->fetchOffset(0, $limit -1);
    }

    public function fetchArticle($id)
    {
        $key = $this->key('article:' . $id);
        $article = $this->redis->get($this->key('article:' . $id));

        if (!$article) {
            return false;
        }

        return json_decode($article, true);
    }

    public function fetchAll()
    {
        return $this->fetchOffset(0, -1);
    }

    public function fetchOffset($start, $end)
    {
        $articles = $this->redis->zRevRange($this->key('articles'), $start, $end);

        $result = [];
        foreach ($articles as $id) {
            $result[] = json_decode($this->redis->get($this->key('article:' . $id)), true);
        }

        return $result;
    }

    public function persist(array $data)
    {
        $id = $this->redis->zRevRange($this->key('articles'), 0, 1);
        $id = $id ?: 1;

        $this->update($id, $data);
        return $id;
    }

    public function update($id, array $data)
    {
        $key     = $this->key('article:' . $id);
        $article = $this->redis->set($this->key('article:' . $id), json_encode($data + ['id' => $id]));
    }

    private function key($name)
    {
        return sprintf('%s:%s', $this->prefix, $name);
    }
}
