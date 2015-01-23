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

$app->get('/feed', function () use ($app) {
    $articles = $app->repository->fetchRecent(50);

    $app->response->headers->set('Content-Type', 'application/rss+xml');
    $app->render('blog/rss.phtml', ['articles' => $articles, 'layout' => false]);
})->name('feed');

/*
 * Search routes
 */
$app->get('/search', function () use ($app) {
    $query  = $app->request->get('q');
    $config = $app->config('search');

    $app->render('search/results.phtml', ['query' => $query, 'key' => $config['key'], 'cx' => $config['cx']]);
})->name('search');

$app->get('/import', function () use ($app) {
    if (!file_exists('data/articles_nl.php') || !file_exists('data/articles_en.php')) {
        echo "No files to import";
        return;
    }

    $dutch   = include 'data/articles_nl.php';
    $english = include 'data/articles_en.php';

    $articles    = [];
    $unpublished = [];
    foreach (array_merge($dutch, $english) as $entry) {
        $article = [
            'id'    => (int) $entry['id'],
            'date'  => DateTime::createFromFormat('Y-m-d H:i:s', $entry['publish_date']),
            'title' => $entry['title'],
            'lead'  => $entry['lead'],
            'body'  => $entry['body'],
            'lang'  => $entry['locale'],
        ];

        // We overwrite Dutch articles with English ones when they both exists
        if ($article['date']) {
            $articles[$article['id']] = $article;
        } else {
            $unpublished[$article['id']] = $article;
        }
    }

    usort($articles, function ($a, $b) {
        $aDate = $a['date'];
        $bDate = $b['date'];

        if ($aDate == $bDate) {
            return 0;
        }
        return ($aDate < $bDate) ? -1 : 1;
    });

    $repository = $app->repository;
    $repository->purge();

    foreach ($articles as $article) {
        if ($article['date']) {
            $article['date'] = $article['date']->format('U');
        }
        $repository->update($article['id'], $article);
        $repository->publish($article['id'], $article['date']);
    }

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

    $csrf = Zend\Math\Rand::getString(20);
    $app->session->csrf = $csrf;

    $app->render('auth/login-form.phtml', ['csrf' => $csrf]);
});

$app->post('/login', function () use ($app) {
    if ($app->session->admin === true) {
        $app->redirect($app->urlFor('admin'));
    }

    // Validate CSRF
    $csrf = $app->request->post('csrf');
    if ($app->session->csrf !== $csrf) {
        $app->redirect($app->urlFor('home'));
    }
    $app->session->offsetUnset('csrf');

    // Generate token
    $token  = Zend\Math\Rand::getString(20);
    $app->session->token = $token;

    // Get some more context for the user
    $time = new DateTime;
    $ip   = $app->request->getIp();
    try {
        $address = $app->geocoder->city($ip);
    } catch (GeoIp2\Exception\AddressNotFoundException $e) {
        // Silently ignore not found addresses
        $address = null;
    }

    // Send the login email
    $config = $app->config('auth');
    $app->email->send('auth/email.phtml', ['token' => $token, 'address' => $address, 'time' => $time], $config);

    $app->render('auth/token-sent.phtml');
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
    $app->render('admin/dashboard.phtml', ['layout' => 'admin/layout.phtml']);
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
            $app->redirect($app->urlFor('admin-blog-edit', ['id' => $id]));
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
            $app->redirect($app->urlFor('admin-blog-edit', ['id' => $id]));
        });

        $app->delete('/:id', function ($id) use ($app) {
            $app->repository->remove($id);
            $app->redirect($app->urlFor('admin-blog'));
        });
    });

    $app->group('/guide', function () use ($app) {

    });
});
