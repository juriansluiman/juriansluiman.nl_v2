<?php

/*
 * Blog routes
 */
$app->get('/', function () use ($app) {
    $articles = $app->repository->fetchRecent(10);
    $app->render('blog/recent.phtml', ['articles' => $articles]);
})->name('home');

$app->get('/article/:id(/:slug)', function ($id, $slug) use ($app) {
    // We have the article already from the pre-dispatch hook
    $app->render('blog/article.phtml', ['article' => $app->article]);
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

/*
 * Authentication
 */
$authentication = function () use ($app) {
    if (true !== $app->session->admin) {
        $app->redirect($app->urlFor('home'));
    }
};

$app->get('/login', function () use ($app) {
    if ($app->session->admin === true) {
        $app->redirect($app->urlFor('admin'));
    }

    $token  = Zend\Math\Rand::getString(20);
    $token  = str_replace(['+', '/'], '@', $token);
    $config = $app->config('auth');

    $app->session->token = $token;
    $app->email->send('auth/email.phtml', ['token' => $token], $config + ['subject' => 'Login token for juriansluiman.nl']);

    $app->render('auth/login.phtml');
})->name('login');

$app->get('/authenticate/:token', function ($token) use ($app) {
    $session = $app->session;
    if (!isset($session->token) || $token !== $app->session->token) {
        var_dump($token, $session->token);exit;
        $app->redirect($app->urlFor('home'));
    }

    $session->getManager()->regenerateId();
    $session->admin = true;
    unset($app->session->token);

    $app->redirect($app->urlFor('admin'));
})->name('authenticate');

$app->post('/logout', $authentication, function () use ($app) {
    unset($app->session->admin);
    $app->redirect($app->urlFor('home'));
})->name('logout');
