<?php

use Blog\Controller;

Class UserController extends Controller
{

    /**
     * [getLogin description]
     * @return [type] [description]
     */
    public function getLogin()
    {

        return $this->app['twig']->render('user/login.twig', $this->data);
    }

    public function postLogin()
    {
        $email = $this->app['request']->get('email');
        $password = $this->app['request']->get('password');


        $sql = "SELECT * FROM users WHERE email = :email";
        $arguments = array(
            ':email' => $email
        );

        $statement = $this->app['sql']->prepareExec($sql, $arguments);
        $user = $statement->fetch();

        //Test if user exist
        if ($user === false) {
            $this->data['errors'] = 'Login or password incorrect';
        }

        //test if password is correct
        if (sha1($password.$user['salt']) !== $user['password']) {
            $this->data['errors'] = 'Login or password incorrect';
        }

        if ($this->data['errors'])
            return $this->getLogin();

        $user = array(
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        );
        $this->app['session']->set('user', $user);

        return $this->redirect('home');
    }

    /**
     * Deco
     * @return [type] [description]
     */
    public function getLogout()
    {
        $this->app['session']->set('user', null);

        return $this->redirect('home');
    }

    /**
     * [getRegister description]
     * @return [type] [description]
     */
    public function getRegister()
    {

        return $this->app['twig']->render('user/register.twig', $this->data);
    }

    /**
     * [postRegister description]
     * @return [type] [description]
     */
    public function postRegister()
    {

        $email = $this->app['request']->get('email');
        $password = $this->app['request']->get('password');
        $password_confirmation = $this->app['request']->get('password_confirmation');
        $name = $this->app['request']->get('name');

        //Verification du password
        if ($password !== $password_confirmation) {
            $this->data['errors'][] = 'Password doesn\'t match';
        }

        //Verification de l'email
        $sql = "SELECT email FROM users WHERE email = :email";
        $arguments = array(
            ':email' => $email
        );
        $emailVerif = $this->app['sql']->prepareExec($sql, $arguments);

        if ($emailVerif->fetch() !== false) {
            $this->data['errors'][] = 'This email already exist.';
        }

        //Si erreurs réafficher le forumluaire.
        if (!empty($this->data['errors'])) {
            return $this->getRegister();
        }

        //Insertion dans la base de donnés
        $salt = uniqid();
        $password = sha1($password.$salt);

        $sql = "INSERT INTO  users (
                    email ,
                    password ,
                    name ,
                    salt
                )
                VALUES (
                    :email, :password, :name, :salt
                )";
        $arguments = array(
            ':email' => $email,
            ':password' => $password,
            ':name' => $name,
            ':salt' => $salt
        );

        $this->app['sql']->prepareExec($sql, $arguments);

        return $this->app['twig']->render('user/register-success.twig', $this->data);
    }

}
