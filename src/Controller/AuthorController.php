<?php

namespace App\Controller;
use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/author')]
class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/show', name: 'showlistauthor')]
   public function showAuthor(AuthorRepository $repo){
   return $this->render('author/show.html.twig', [
   'a' => $repo->findAll()
   ]);









}




#[Route('/delete0/{id}', name: 'delete0_author')]
public function deleteAuthorsWithZeroBooks(int $id, EntityManagerInterface $em, AuthorRepository $repo)
{
    $author = $repo->find($id);

    if ($author !== null) {
        $em->remove($author);
        $em->flush();

        return new Response('Author with ID ' . $id . ' removed.');
    } else {
        return new Response('Author with ID ' . $id . ' not found.');
    }
}

}


