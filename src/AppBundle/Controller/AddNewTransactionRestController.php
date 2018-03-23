<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



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

class AddNewTransactionRestController extends FOSRestController {

    /**
     * making new transaction
     * request:  customerId , amount
     * @Route("/addings/transactions")
     * @Method({"POST"})
     * return transactionId,amount,date,customerId
     */
    public function postAddingTransactionAction(Request $request) {
        /**
         * calling service of logger
         */
        $logger = $this->get('logger');
        try {

            $logger->info("AddNewTransactionRestController:postAddingTransactionAction-> process to make new transaction");
            $view = $this->view(null, 200);
            $json = $request->request->all();
            $logger->debug("AddNewTransactionRestController:postAddingTransactionAction-> request are in process" . print_r($json, true));
            #calling Entity Manager (Doctrine)
            $em = $this->getDoctrine()->getManager();
            if (isset($json['customerId']) && isset($json['amount'])) {
                $logger->debug("AddNewTransactionRestController:postAddingTransactionAction-> trying to find customer by id from database ".$json['customerId']);
                $customer= $em->getRepository('AppBundle\Entity\Customer')->findOneByid($json['customerId']);
                $transaction = new \AppBundle\Entity\Transaction();
                 $logger->info("AddNewTransactionRestController:postAddingTransactionAction-> adding new transation ");
                $transaction->setamount($json['amount']);
                $transaction->setcustomer($customer);
                $transaction->setdate(new \DateTime('now'));
                $em->persist($transaction);
                $em->flush();
                $result = array();
                $result['transactionId']= $transaction->getid();
                $result['customerId'] = $customer->getid();
                $result['amount'] = $transaction->getamount();
                $result['date'] = $transaction->getdate();
                $view = $this->view($result, 200);
                $logger->debug("AddNewTransactionRestController:postAddingTransactionAction-> New transaction has been made successfully :Details are :   " .  print_r($result, true));
            }else{
              $logger->debug("AddNewTransactionRestController:postAddingTransactionAction-> Invalid request received " . print_r($json, true));  
              $view = $this->view('Invalid request received', 200);
            }
        } catch (\Exception $e) {

            $logger->error("AddNewTransactionRestController:postAddingTransactionAction :  Error while making new  transaction :" . $e->getMessage());
            $logger->error("AddNewTransactionRestController:postAddingTransactionAction : stacktrace:" . $e->getTraceAsString());
            $view = $this->view($e->getMessage(), 200);
        } finally {
            return $this->handleView($view);
        }
    }

    
    
}
