<?php

class User extends Controller
{

    public function viewUser($user_id)
    {
        // if we have an id of a song that should be edited
        if (isset($user_id)) 
        {
            // do getSong() in model/model.php
            $user = $this->models->user->getUserById($user_id);

            // load views. within the views we can echo out $song easily
            require APP . 'view/_templates/header.php';
            require APP . 'view/user/view.php';
            require APP . 'view/_templates/footer.php';
        } 
        else 
        {
            header('location: ' . URL . 'index');
        }
    }
}
