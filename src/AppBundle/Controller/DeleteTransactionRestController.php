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

class DeleteTransactionRestController extends FOSRestController {

    /**
     * deleting transaction by id
     * request:  TransactionId 
     * @Route("/transactions/{$id}")
     * @Method({"DELETE"})
     * return transactionId,amount,date,customerId
     */
    public function deleteTransactionAction($transactionsId) {
        /**
         * calling service of logger
         */
        $logger = $this->get('logger');
         $view = $this->view(null, 200);
        try {

            $logger->info("DeleteTransactionRestController:deleteTransactionAction-> process to deleting particular transaction");
           
             $logger->debug("DeleteTransactionRestController:deleteTransactionAction-> transaction is in process" . $transactionsId);
            #calling Entity Manager (Doctrine)
            $em = $this->getDoctrine()->getManager();
            if ($transactionsId) {
                $logger->debug("DeleteTransactionRestController:deleteTransactionAction-> trying to find transaction by id from database " . $transactionsId);
                $transaction = $em->getRepository('AppBundle\Entity\Transaction')->findOneByid($transactionsId); 
                $logger->info("DeleteTransactionRestController:deleteTransactionAction-> deleting transaction");
               
                $em->remove($transaction);
                $em->flush();
               
                $view = $this->view('Success', 200);
                $logger->debug("DeleteTransactionRestController:deleteTransactionAction->  transaction has been  successfully deleted :id :   " . $transactionsId);
            } else {
                $logger->debug("DeleteTransactionRestController:deleteTransactionAction-> Invalid request received " . $transactionsId);
                $view = $this->view('fail', 200);
            }
        } catch (\Exception $e) {

            $logger->warning("DeleteTransactionRestController:deleteTransactionAction : Error while deleting transaction :" . $e->getMessage());
            $logger->debug("DeleteTransactionRestController:deleteTransactionAction: stacktrace:" . $e->getTraceAsString());
            $view = $this->view('fail', 200);
        } finally {
            return $this->handleView($view);
        }
    }

}
