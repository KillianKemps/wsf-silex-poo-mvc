<?php
namespace Blog;

use PDO;

Class Controller
{
    public $app;
    public $data = array();

    public function __construct($app)
    {
        $this->app = $app;

        $this->data['user'] = $this->isLogged();
        $this->data['admin'] = $this->isAdmin();
    }

    /**
     * Renvoi l'user de la session si loggué, sinon false.
     * @return boolean
     */
    public function isLogged()
    {
        $user = $this->app['session']->get('user');

        return empty($user) ? false : $user;
    }

    /**
     * Vérifie si un user est admin
     * @return boolean
     */
    public function isAdmin()
    {
        $user = $this->isLogged();

        $sql = 'SELECT admin FROM users WHERE id= :id';
        $arg = array(
            ':id' => $user['id']
        );
        $result = $this->app['sql']
                       ->prepareExec($sql, $arg)
                       ->fetch(PDO::FETCH_ASSOC);
       // $result = $result;
       //
        return $result['admin'];
    }

    public function redirect($route)
    {
        return $this->app->redirect(
            $this->app['url_generator']->generate($route)
        );
    }
}
