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

    public function import()
    {
        $keys = $this->redis->keys($this->prefix . ':*');
        foreach ($keys as $key) {
            $this->redis->del($key);
        }

        $articles = [
            ['title' => 'Foo', 'lead' => '<p>Test Test</p>', 'body' => '<p>Test Test Test Test</p>'],
            ['title' => 'Bar', 'lead' => '<p>Tasd Tasd</p>', 'body' => '<p>Tasd Tasd Tasd Tasd</p>'],
        ];

        $id = 1;
        foreach ($articles as $article) {
            $this->redis->lpush($this->key('articles'), $id);
            $this->redis->set($this->key('article:' . $id), json_encode($article + ['id' => $id]));
            $id++;
        }
    }

    public function fetchRecent($limit)
    {
        $articles = $this->redis->lRange($this->key('articles'), 0, $limit - 1);

        $result = [];
        foreach ($articles as $id) {
            $result[] = json_decode($this->redis->get($this->key('article:' . $id)), true);
        }

        return $result;
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

    private function key($name)
    {
        return sprintf('%s:%s', $this->prefix, $name);
    }
}
