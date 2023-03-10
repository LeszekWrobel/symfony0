<?php

namespace App\Controller;

use App\Entity\Ennouncement;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\EnnouncementRepository;

class AnnouncementController extends AbstractController
{
    
    #[Route('/announcement/{id}', name: 'product_show')]
    
    public function show(int $id, EnnouncementRepository $ennouncementRepository): Response
    {
        $announcement = $ennouncementRepository->find($id);
       # if ($announcement->getName($id) !== '' && $announcement->getName($id) !== null) { 
        if (isset($announcement)) {
            return new Response('Read product with id '.$announcement->getId().'+'.$announcement->getName($id));
        } else {
            return new Response('Product with id '.$id.' not exist');
        }
    }

    #[Route('/announcement', name: 'app_announcement')]
   
    public function createAnnouncement(ValidatorInterface $validator, EntityManagerInterface $entityManager): Response 
    {
        $announcement = new Ennouncement();
        $name = '';
        $price = 1999;
        $size = 36;
        $phone = null;
        $description = '';
        $update_add = new DateTime('now');
        $created_add = new DateTime('now');
        
        $announcement->setName($name);
        $announcement->setPrice($price);
        $announcement->setSize($size);
        $announcement->setPhone($phone);
        $announcement->setDescription($description);
        $announcement->setUpdateAdd($update_add);
        $announcement->setCreatedAdd($created_add);

        if ('' == $name && null == $name) {
            // validation will fail
            return new Response('valid stop '.$announcement->getId());
            exit;
        }

        $errors = $validator->validate($announcement);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        } else {
            // tell Doctrine you want to (eventually) save the Announcement (no queries yet)
            $entityManager->persist($announcement);
            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return new Response('Saved new product with id '.$announcement->getId());
        }
    }

    public function index(): Response
    {
        return $this->render('announcement/index.html.twig', [
            'controller_name' => 'AnnouncementController',
        ]);
    }
}
