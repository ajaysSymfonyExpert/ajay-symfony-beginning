<?php
namespace AppBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;



class SumTransactionsCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                // the name of the command (the part after "bin/console")
                ->setName('bank:sum:transactions')

                // the short description shown while running "php bin/console list"
                ->setDescription('sum all transactions by last date')

                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp('This command allows you check contact and send mail ')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $currentDate=(new \DateTime('now'))->format('Y-m-d');
      $previousDate=date('Y-m-d', strtotime($currentDate . ' -1 day'));
        
         $logger =  $this->getContainer()->get('logger');
         $logger->info("process to sum amount of transaction since last day");
         $em = $this->getContainer()->get('doctrine')->getEntityManager();
         $repository = $em
                        ->getRepository('AppBundle\Entity\Transaction');

                $query = $repository->createQueryBuilder('trans')
                        ->select('sum( trans.amount)')
                        ->Where('trans.date = :date')
                        ->setParameter('date', $previousDate)
                        ->getQuery();
                       
                $transactions = $query->getSingleScalarResult();
               
        $logger->debug("total transaction of last day is ".$transactions);
        $output->writeln('total transaction by yesterday =>' . $transactions);
    }

}