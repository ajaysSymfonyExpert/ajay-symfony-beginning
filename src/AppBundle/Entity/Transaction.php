<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Events;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="transaction")
 * @ORM\HasLifecycleCallbacks
 */
class Transaction {

    public function __construct() {
        // $this->emails = new   ArrayCollection();
    }

   

    /**
     * @ORM\Id
     * @ORM\Column(name="id",type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    
    /**
     * amount
     *
     * @var float
     *
     * @ORM\Column(name="amount", type="float"  ,nullable=true)
     */
    protected $amount; 

    /**
     * Date
     *
     * @var date
     *
     * @ORM\Column(name="date", type="date" ,nullable=true)
     */
    protected $date;
    
    /**
     * partner
     *
     * @ORM\ManyToOne(targetEntity="Customer")
     * @ORM\JoinColumn(name="customerId" , referencedColumnName="id" )
     * 
     */
    protected $customerId;

    # making method getter and setter of attribute
    # 
    #amount
    public function setamount($amount) {
        $this->amount = $amount;
    }

    public function getamount() {
        return $this->amount;
    }
    
    #customerId

    public function getcustomer() {
        return $this->customerId;
    }

    public function setcustomer($customerId) {
        $this->customerId = $customerId;

        return $this;
    }
    #date
    public function getdate() {
        return $this->date;
    }

    public function setdate($date) {
        $this->date = $date;

        return $this;
    }
      #id
    public function setid($id) {
        $this->id = $id;
    }

    public function getid() {
        return $this->id;
    }
}

