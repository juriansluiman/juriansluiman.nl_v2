<?php

// Use the slim.before.router to inject vars into all views
$app->hook('slim.before.router', function () use ($app) {
    // Inject $  in view so we can create urls
    $app->view()->setData('app', $app);

    // Inject helpers to the view for title, metadata and javascript
    $app->view()->setData('title', new App\View\Helper\Title(' &middot; '));
    $app->view()->setData('meta', new App\View\Helper\Meta);
    $app->view()->setData('script', new App\View\Helper\Script);
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
