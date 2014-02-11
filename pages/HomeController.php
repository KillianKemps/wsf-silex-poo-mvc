<?php

use Blog\Controller;

Class HomeController extends Controller
{

    /**
     * Affiche la home page
     */
    public function getIndex($idTag = null)
    {
        $this->data['user'] = $this->isLogged();

        $article = new Article($this->app);
        $this->data['articles'] = $article->getAllArticles($idTag);

        return $this->app['twig']->render('home.twig', $this->data);
    }

    public function getArticle($idArticle)
    {
        $article = new Article($this->app);
        $this->data['article'] = $article->getArticle($idArticle);

        $tag = new Tag($this->app);
        $tags = $tag->getTagsByArticle($idArticle);

        $twitter = new Twitter('WYqjWHdW9xdCNLAJEtPdEQ',
                               'ppEZC7m0ibOGtWxKNzg9FBoUBkvMQKiGsCKFJ9UsQ' ,
                               '75681733-AU5dmSas9YwUK0UHRRZZsvqeVHpxPTRHIWd057XFu' ,
                               'HVGWhy6fs2yenEuyleXPce1t4RSd7OmnkCkZHEn5zSKeJ' );

        foreach ($tags as $tag) {
            $result = $twitter->search('#'.$tag['name']);
            $m = new MongoClient();
            $tweets = $m->selectCollection('blog', 'tweets');
            foreach ($result as $tweet) {
                $tweets->insert($tweet);
            }
            $results[] = $result;

        }

        $this->data['tweets'] = $results;

        return $this->app['twig']->render('article.twig', $this->data);
    }

}
