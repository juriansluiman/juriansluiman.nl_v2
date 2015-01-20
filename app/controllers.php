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
})->conditions(['id' => '\d+'])->name('article');

$app->get('/archive(/:page)', function ($page = 1) use ($app) {
    $page     = (int) $page;
    $count    = $app->repository->getTotalCount();
    $perPage  = 25;
    $from     = ($page-1) * $perPage;
    $to       = ($page * $perPage) - 1;
    $pages    = (int) ceil($count/$perPage);
    $articles = $app->repository->fetchOffset($from, $to);

    $app->render('blog/archive.phtml', ['articles' => $articles, 'pages' => $pages, 'page' => $page]);
})->conditions(['page' => '\d+'])->name('archive');

$app->get('/import', function () use ($app) {
    $app->repository->import();
    $app->redirect($app->urlFor('home'));
});

/*
 * Search routes
 */
$app->get('/search', function () use ($app) {
    $query  = $app->request->get('q');
    $config = $app->config('search');

    $app->render('search/results.phtml', ['query' => $query, 'key' => $config['key'], 'cx' => $config['cx']]);
})->name('search');

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

/*
 * Admin routes
 */
$app->get('/admin', $authentication, function () use ($app) {
    $app->render('admin/dashboard.phtml');
})->name('admin');

$app->group('/admin', $authentication, function () use ($app) {
    $app->get('/blog', function () use ($app) {
        $articles = $app->repository->fetchAll();
        $app->render('admin/blog/index.phtml', ['articles' => $articles, 'layout' => 'admin/layout.phtml']);
    })->name('admin-blog');

    $app->group('/blog', function () use ($app) {
        $app->get('/create', function () use ($app) {
            $app->render('admin/blog/new.phtml', ['layout' => 'admin/layout.phtml']);
        })->name('admin-blog-create');

        $app->post('/create', function () use ($app) {
            // Validate?

            $data = $app->request->post();
            $id   = $app->repository->persist($data);

            $app->flash('success', 'A new blog post has been created!');
            $app->redirect($app->urlFor('admin-blog-edit'), ['id' => $id]);
        });

        $app->get('/:id', function ($id) use ($app) {
            $article = $app->repository->fetchArticle($id);
            $app->render('admin/blog/edit.phtml', ['article' => $article, 'layout' => 'admin/layout.phtml']);
        })->name('admin-blog-edit');

        $app->put('/:id', function ($id) use ($app) {
            // Validate?

            $data = $app->request->post();
            $id   = $app->repository->update($id, $data);

            $app->flash('success', 'Your blog post has been saved!');
            $app->redirect($app->urlFor('admin-blog-edit'), ['id' => $id]);
        });

        $app->delete('/:id', function ($id) use ($app) {
            $app->repository->remove($id);
            $app->redirect($app->urlFor('admin-blog'));
        });
    });

    $app->group('/guide', function () use ($app) {

    });
});
