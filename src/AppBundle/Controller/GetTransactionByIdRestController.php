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

class GetTransactionByIdRestController extends FOSRestController {

    public function TransactionAction($customerId, $transactionId) {
        /**
         * calling service of logger
         */
        $logger = $this->get('logger');

        try {
            $result = array();
            $logger->info("GetTransactionByIdRestController:getTransactionAction-> process to make new transaction");
            $logger->debug("GetTransactionByIdRestController:getTransactionAction-> request are in process with transactionId" . $transactionId . 'and customerId' . $customerId);
            #calling Entity Manager (Doctrine)
            $em = $this->getDoctrine()->getManager();
            if ($customerId && $transactionId) {
                $logger->debug("GetTransactionByIdRestController:getTransactionAction-> trying to find transaction by transactionId" . $transactionId . " and customer id" . $customerId);
                $transaction = $em->getRepository('AppBundle\Entity\Transaction')->findOneBy(array("id" => $transactionId, "customerId" => $customerId));

                $logger->info("GetTransactionByIdRestController:getTransactionAction-> adding new transation ");


                if ($transaction) {

                    $result['transactionId'] = $transaction->getid();
                    $result['amount'] = $transaction->getamount();
                    $result['date'] = $transaction->getdate()->format('Y-m-d H:i:s');
                    
                }
                $logger->debug("GetTransactionByIdRestController:getTransactionAction-> New transaction has been made successfully :Details are :  ".  print_r($result,true));
            } else {
                $logger->debug("GetTransactionByIdRestController:getTransactionAction-> Invalid request received ");

                $result[] = "Invalid request received";
            }
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } catch (\Exception $e) {

            $logger->error("GetTransactionByIdRestController:getTransactionAction :  Error while making new  transaction :" . $e->getMessage());
            $logger->error("GetTransactionByIdRestController:getTransactionAction : stacktrace:" . $e->getTraceAsString());
        }
    }

}
