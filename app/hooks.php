<?php

// Use the slim.before.router to inject vars into all views
$app->hook('slim.before.router', function () use ($app) {
    // Inject $  in view so we can create urls
    $app->view()->setData('app', $app);

    // Inject helpers to the view for title, metadata and javascript
    $app->view()->setData('title', function ($title = null, $prepend = true) {
        static $result;
        $result = $result ?: [];

        if ($title) {
            if ($prepend) {
                array_unshift($result, $title);
            } else {
                array_push($result, $title);
            }
        }

        return implode(' &middot; ', $result);
    });

    $app->view()->setData('meta', function ($name = null, $content = null) {
        static $result;
        $result = $result ?: '';

        if ($name && $content) {
            $result .= sprintf('<meta name="%s" content="%s">' . PHP_EOL, $name, $content);
        }

        return $result;
    });

    $app->view->setData('script', function ($src = null, $script = null) {
        static $result;
        $result = $result ?: '';

        if ($src) {
            $result .= sprintf('<script type="text/javascript" src="%s"></script>' . PHP_EOL, $src);
        } elseif ($script) {
            $result .= sprintf('<script type="text/javascript">%s</script>'. PHP_EOL, $script);
        }

        return $result;
    });
});

// If /article/:id/:slug is requested, check the slug and redirect if required
$app->hook('slim.before.dispatch', function () use ($app) {
    $route = $app->router()->getCurrentRoute();
    if ('article' !== $route->getName()) {
        return;
    }

    $params  = $route->getParams();
    $article = $app->repository->fetchArticle($params['id']);

    if (!$article) {
        $app->notFound();
        return;
    }

    $slug = $app->slugifier->slugify($article['title']);
    if (!isset($params['slug']) || $slug !== $params['slug']) {
        $app->redirect($app->urlFor('article', ['id' => $params['id'], 'slug' => $slug]), 301);
        return;
    }

    $app->article = $article;
});
