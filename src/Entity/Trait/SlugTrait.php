<?php
namespace App\Entity\Trait;

use Doctrine\ORM\Mapping AS ORM;

trait SlugTrait
{
        // default CYRRENT_TIMESTAMP pour me donner la date d'inscription automaticement par defaut
        #[ORM\Column(type:'string',length:255)]
        private $slug;
        
        public function getSlug(): ?string
        {
            return $this->slug;
        }
    
        public function setSlug(string $slug): self
        {
            $this->slug = $slug;
    
            return $this;
        }
}