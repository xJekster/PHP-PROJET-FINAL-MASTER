<?php

//Il s'agit du controller Security qui se trouve dans le dosser controller
namespace App\Controller;
class Security{
    public function login(){

    }
}
//-------------------------------------------------
//Il s'agit du utils Security qui se trouve dans le dosser utils
namespace App\Utils;
class Security{
    public function cryptPwd($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }
}


//-------------------------------------------------

namespace App;

use App\Utils\Security as  Secu;
use App\Controller\Security;

new Secu();