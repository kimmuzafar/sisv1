<?php 
namespace Wit\UserBundle\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
     /**
      * @SecurityAssert\UserPassword(
      *     message = "Current password does't match!'"
      * )
      */
     protected $oldPassword;

     /**
      * @Assert\Length(
      *     min = 6,
      *     minMessage = "Password should by at least 6 chars long"
      * )
      */
     protected $newPassword;

    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }

    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }

    public function getNewPassword()
    {
        return $this->newPassword;
    }
}