<?php 

namespace App\Security\Voter;

use App\Entity\Products;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ProductsVoter extends Voter
{
    const EDIT = 'PRODUCT_EDIT';
    const DELETE = 'PRODUCT_DELET';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attriute, $product): bool
    {
        if(!in_array($attriute, [self::EDIT, self::DELETE])){
            return false;
        }
        if(!$product instanceof Products){
            return false;
        }
        return true;
        
    }

    protected function voteOnAttribute($attriute, $product, TokenInterface $token): bool
    {
        // On recupère l'utilisateur a partir du user
        $user = $token->getUser();

        if(!$user instanceof UserInterface) return false;

        // On vérifie si l'utilisateur est admin
        if($this->security->isGranted('ROLE_ADMIN')) return true;

        // On vérifie les permissions
        switch($attriute){
            case self::EDIT:
                // On vérifie si l'utilisateur peut editer
                return $this->canEdit();
                break;
            case self::DELETE:
                // On vérifie si l'utililisateur peut supprimer
                return $this->canDelete();
                break;
        }
    }

    private function canEdit(){
        // On vérifie si l'utilisateur a le role admin
        return $this->security->isGranted('ROLE_PRODUCT_ADMIN');
    }

    private function canDelete(){
        // On vérifie si l'utilisateur a le role admin
        return $this->security->isGranted('ROLE_PRODUCT_ADMIN');
    }
}