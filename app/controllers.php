<?php

/*
 * Blog routes
 */
$app->get('/', function () use ($app) {
    $articles = $app->repository->fetchRecent(10);
    $app->render('recent.phtml', ['articles' => $articles]);
})->name('home');

$app->get('/article/:id(/:slug)', function ($id, $slug) use ($app) {
    // We have the article already from the pre-dispatch hook
    $app->render('article.phtml', ['article' => $app->article]);
})->name('article');

$app->get('/archive(/:page)', function ($page = 1) use ($app) {
    $app->render('archive.phtml');
});

$app->get('/import', function () use ($app) {
    $app->repository->import();
    $app->redirect($app->urlFor('home'));
});

/*
 * Static routes
 */
$app->get('/about', function () use ($app) {
    $app->render('static/about.phtml');
})->name('about');

$app->get('/contact', function () use ($app) {
    $app->render('static/contact.phtml');
})->name('contact');

/*
 * Error handling routes
 */
$app->error(function (Exception $e) use ($app) {
    $app->render('error/500.phtml', ['exception' => $e]);
});

$app->notFound(function () use ($app) {
    $app->render('error/404.phtml');
});
