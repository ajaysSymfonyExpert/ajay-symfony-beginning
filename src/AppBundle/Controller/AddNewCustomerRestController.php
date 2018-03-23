<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class AddNewCustomerRestController extends FOSRestController {

    /**
     * adding customer
     * request:  name , cnp
     * @Route("/addings/customers")
     * @Method({"POST"})
     * return customerId
     */
    public function postAddingCustomerAction(Request $request) {
        /**
         * calling service of logger
         */
        $logger = $this->get('logger');
        try {
       $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $logger->info("BankRestController:postAddingCustomerAction-> process to create new customer");
            $view = $this->view(null, 200);
            $json = $request->request->all();
            $logger->debug("BankRestController:postAddingCustomerAction-> request are in process" . print_r($json, true));
            #calling Entity Manager (Doctrine)
            $em = $this->getDoctrine()->getManager();
            if (isset($json['name']) && isset($json['cnp'])) {
                $customer = new \AppBundle\Entity\Customer();
                $customer->setname($json['name']);
                $customer->setcnp($json['cnp']);
                $em->persist($customer);
                $em->flush();
                $result = array();
                $result['customerId'] = $customer->getid();
                $view = $this->view($result, 200);
                $logger->debug("BankRestController:postAddingCustomerAction-> New Customer has been successfully created: customerId is : " . $customer->getid());
            }else{
              $logger->debug("BankRestController:postAddingCustomerAction-> Invalid request received " . print_r($json, true));  
              $view = $this->view('Invalid request received', 200);
            }
        } catch (\Exception $e) {

            $logger->error("BankRestController:postAddingCustomerAction :  Error while adding new  customer :" . $e->getMessage());
            $logger->debug("BankRestController:postAddingCustomerAction : stacktrace:" . $e->getTraceAsString());
            $view = $this->view($e->getMessage(), 200);
        } finally {
            return $this->handleView($view);
        }
    }

    
    
}
