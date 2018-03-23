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

class GetTransactionByFilterRestController extends FOSRestController {

    public function TransactionAction($customerId, $amount, $date, $offset, $limit) {
        /**
         * calling service of logger
         */
        $logger = $this->get('logger');
        $view = $this->view(null, 200);
        try {

            $logger->info("GetTransactionByIdRestController:getTransactionAction-> process to make new transaction");
            $logger->debug("GetTransactionByIdRestController:getTransactionAction-> request are in process with transactionId" . $date . 'and customerId' . $customerId);
            #calling Entity Manager (Doctrine)
            $em = $this->getDoctrine()->getManager();
            
            
            if ($customerId) {
                $logger->debug("GetTransactionByIdRestController:getTransactionAction-> trying to find transaction by transactionId" . $date . " and customer id" . $customerId);
                $repository = $em
                        ->getRepository('AppBundle\Entity\Transaction');

                $query = $repository->createQueryBuilder('trans')
                        ->select('trans')
                        ->AndWhere('trans.customerId=:customerid')
                        ->andWhere('trans.date = :date')
                        ->AndWhere('trans.amount=:amount')
                        ->setParameter('customerid', $customerId)
                        ->setParameter('date', $date)
                        ->setParameter('amount', $amount)
                        ->getQuery()
                        ->setFirstResult($offset)
                        ->setMaxResults($limit);
                $transactions = $query->getResult();
                 $serializer = $this->get('serializer');
               
                $data = $serializer->serialize($transactions, 'json', \JMS\Serializer\SerializationContext::create()
                            );
             $logger->info("GetTransactionByIdRestController:getTransactionAction-> adding new transation ");
                $logger->debug("GetTransactionByIdRestController:getTransactionAction-> New transaction has been made successfully :Details are :   " . print_r($transactions, true));
            return new Response($data, 200, array('Content-Type' => 'application/json'));
                }
        } catch (\Exception $e) {

            $logger->error("GetTransactionByIdRestController:getTransactionAction :  Error while making new  transaction :" . $e->getMessage());
            $logger->error("GetTransactionByIdRestController:getTransactionAction : stacktrace:" . $e->getTraceAsString());
        }  
    }

}
