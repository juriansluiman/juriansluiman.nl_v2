<?php

// Inject $app in view so we can create urls
$app->hook('slim.before.router', function () use ($app) {
    $app->view()->setData('app', $app);
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
