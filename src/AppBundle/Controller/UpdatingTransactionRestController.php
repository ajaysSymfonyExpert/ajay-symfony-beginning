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

class UpdatingTransactionRestController extends FOSRestController {

    /**
     * Updating Transaction
     * request:  TransactionId , amount
     * @Route("/updates/transactions")
     * @Method({"POST"})
     * return transactionId,amount,date,customerId
     */
    public function postUpdateTransactionAction(Request $request) {
        /**
         * calling service of logger
         */
        $logger = $this->get('logger');
        try {

            $logger->info("UpdatingTransactionRestController:postUpdatingTransactionAction-> process to make new transaction");
            $view = $this->view(null, 200);
            $json = $request->request->all();
            $logger->debug("UpdatingTransactionRestController:postUpdatingTransactionAction-> request are in process" . print_r($json, true));
            #calling Entity Manager (Doctrine)
            $em = $this->getDoctrine()->getManager();
            if (isset($json['transactionId']) && isset($json['amount'])) {
                $logger->debug("UpdatingTransactionRestController:postUpdatingTransactionAction-> trying to find transaction by id from database " . $json['transactionId']);
                $transaction = $em->getRepository('AppBundle\Entity\Transaction')->findOneByid($json['transactionId']);
                $logger->info("UpdatingTransactionRestController:postUpdatingTransactionAction-> updating transaction");
                $transaction->setamount($json['amount']);
                $transaction->setdate(new \DateTime('now'));
                $em->persist($transaction);
                $em->flush();
                $result = array();
                $result['transactionId'] = $transaction->getid();
                $result['customerId'] = $transaction->getcustomer()->getid();
                $result['amount'] = $transaction->getamount();
                $result['date'] = $transaction->getdate();
                $view = $this->view($result, 200);
                $logger->debug("UpdatingTransactionRestController:postUpdatingTransactionAction->  transaction has been  successfully updated :Details are :   " . print_r($result, true));
            } else {
                $logger->debug("UpdatingTransactionRestController:postUpdatingTransactionAction-> Invalid request received " . print_r($json, true));
                $view = $this->view('Invalid request received', 200);
            }
        } catch (\Exception $e) {

            $logger->error("UpdatingTransactionRestController:postUpdatingTransactionAction : Error while updating transaction :" . $e->getMessage());
            $logger->error("UpdatingTransactionRestController:postUpdatingTransactionAction : stacktrace:" . $e->getTraceAsString());
            $view = $this->view($e->getMessage(), 200);
        } finally {
            return $this->handleView($view);
        }
    }

}
