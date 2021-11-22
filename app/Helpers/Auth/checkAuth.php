<?php

function checkAuth(array $privilegies = []) {

    $id = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;
    $user = null;

    if ($id) {

        $user = R::findOne('users', 'id = ?', [$id]);

        if (!$user) {
            unset($_SESSION['userid']);
            return false;
        }

        if (!empty($privilegies)) {
            if (in_array($user->groups->id, $privilegies))
                return true;

            return false;
        }
    }

    return false;
    
}