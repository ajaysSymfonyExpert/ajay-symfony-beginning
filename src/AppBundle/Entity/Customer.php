<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Events;
use AppBundle\Business\Common;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="customer")
 * @ORM\HasLifecycleCallbacks
 */
class Customer {

    public function __construct() {
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * name
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string" ,nullable=true)
     */
    protected $name;

    /**
     * name
     *
     * @var string
     *
     * @ORM\Column(name="cnp", type="string" ,nullable=true)
     */
    protected $cnp;

    # making getter and setter of attribute
       #id
    public function setid($id) {
        $this->id = $id;
    }

    public function getid() {
        return $this->id;
    }
    #name
    public function setname($name) {
        $this->name = $name;
    }

    public function getname() {
        return $this->name;
    }
    
    #cnp
    public function setcnp($cnp) {
        $this->cnp = $cnp;
    }

    public function getcnp() {
        return $this->cnp;
    }

}
