<?php

use Blog\Model;

Class Tweet extends Model
{

    private $twitter;
    private $mongo;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->twitter =  new Twitter(
            $app['config-silex']['consumerKey'],
            $app['config-silex']['consumerSecret'],
            $app['config-silex']['accessToken'],
            $app['config-silex']['accessTokenSecret']
        );

        $this->mongo = new MongoClient();

    }

    public function getTweetByApi($tag)
    {
        return $this->twitter->search('#'.$tag);
    }

    public function saveTweet($tweetArray)
    {
        $tweets = $this->mongo->selectCollection('blog', 'tweets');
        foreach ($tweetArray as $tweet) {
            $tweets->insert($tweet);
        }
    }
}
