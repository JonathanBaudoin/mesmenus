<?php
/**
 * Created by PhpStorm.
 * User: john
 * Date: 15/10/17
 * Time: 22:16
 */

namespace AppAdminBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController;

/**
 * Class UserController
 * @package AppAdminBundle\Controller
 */
class UserController extends AdminController
{
    public function createNewUserEntity()
    {
        return $this->get('fos_user.user_manager')->createUser();
    }

    public function prePersistUserEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
    }

    public function preUpdateUserEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
    }
}
