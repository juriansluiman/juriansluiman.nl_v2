<?php

namespace App\Repository;

use Redis;

class ArticleRepository
{
    const KEY_ARTICLES_PUBLISHED = 'articles:published';
    const KEY_ARTICLES_CONCEPT   = 'articles:concept';
    const KEY_ARTICLE            = 'article';
    const KEY_ARTICLE_NEXT_ID    = 'article:next_id';

    protected $redis;
    protected $prefix;

    public function __construct(Redis $redis, $prefix = '')
    {
        $this->redis  = $redis;
        $this->prefix = $prefix;
    }

    public function getTotalCount()
    {
        return $this->redis->zcount($this->key(self::KEY_ARTICLES_PUBLISHED), '-inf', '+inf');
    }

    public function fetchRecent($limit)
    {
        return $this->fetchOffset(0, $limit -1);
    }

    public function fetchArticle($id)
    {
        $article = $this->redis->get($this->key(self::KEY_ARTICLE . ':' . $id));

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
        $articles = $this->redis->zRevRange($this->key(self::KEY_ARTICLES_PUBLISHED), $start, $end);

        $result = [];
        foreach ($articles as $id) {
            $result[] = json_decode($this->redis->get($this->key(self::KEY_ARTICLE . ':'. $id)), true);
        }

        return $result;
    }

    public function purge()
    {
        $keys = $this->redis->keys($this->prefix . ':*');
        foreach ($keys as $key) {
            $this->redis->del($key);
        }
    }

    public function persist(array $data)
    {
        $id = (int) $this->redis->incr($this->key(self::KEY_ARTICLE_NEXT_ID));
        $id = $id ?: 1;

        $this->update($id, $data);
        return $id;
    }

    public function update($id, array $data)
    {
        $key     = $this->key('article:' . $id);
        $article = $this->redis->set($this->key(self::KEY_ARTICLE . ':' . $id), json_encode($data + ['id' => $id]));
    }

    public function publish($id, $time = null)
    {
        if (null === $time) {
            $time = time();
        }

        $this->redis->zAdd($this->key(self::KEY_ARTICLES_PUBLISHED), $time, $id);
    }

    public function remove($id)
    {
        $this->redis->zRem($this->key(self::KEY_ARTICLES_PUBLISHED), $id);
        $this->redis->del($this->key(self::KEY_ARTICLE . ':' . $id));
    }

    private function key($name)
    {
        return sprintf('%s:%s', $this->prefix, $name);
    }
}
