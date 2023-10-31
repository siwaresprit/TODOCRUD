<?php

namespace App\Controller;
use App\Form\BookType;
use App\Entity\Book;
use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }




    #[Route('/add', name: 'addbook')]
    public function addProduct(ManagerRegistry $mr,Request $req, AuthorRepository $repauth): Response
    {
        $b=new Book();
        $form=$this->createForm(BookType::class,$b);
        $form->handleRequest($req);
                if ($form->isSubmitted() && $form->isValid()) { 
                    $em=$mr->getManager();
                    $authorBooks = $form->get('author')->getData();
                    $auth=$repauth->findOneById($authorBooks);
                    if ($authorBooks !== null) {
                    $res=$auth->getNbbooks(); 
                    $auth->setNbbooks($res+1); 
                    }
                    if ($form->isSubmitted() && $form->isValid()) {
                    $em->persist($b);
                    $em->flush();
                    }
                     $em->flush();
        }
      

        return $this->renderForm('book/add.html.twig',[
            'book' => $b,
            'f'=>$form,
        ]);

    }




    #[Route('/show', name: 'showlist')]
    public function Show(BookRepository $repo,ManagerRegistry $mr){  
    $books=$mr->getRepository(Book::class); 
    $Publishedbooks= $books->findBy(['published' => true]);
    $publishedcount = $repo->countPublishedBooks();
    $unpublishedcount = $repo->countUnpublishedBooks();
    return $this->render('book/show.html.twig', [
        'b' => $Publishedbooks,
        'pc' => $publishedcount,
        'upc' => $unpublishedcount
    ]);




    }





    private EntityManagerInterface $entityManager;  // Inject the EntityManager

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/edit/{id}', name: 'edit_book')]
    public function editBook(Request $request, int $id): Response
    {
        $book = $this->entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('showlist'); // Replace with your list route
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/delete/{id}', name: 'delete_book')]
    public function deleteBook($id, ManagerRegistry $mr,BookRepository $repo ){
        $em=$mr->getManager(); 
        $b=$repo->find($id);
        $em->remove($b);
        $em->flush();
        return new Response('removed');
       
    }
     

    #[Route('/book/{id}', name: 'showd_book')]
    public function showDetails(BookRepository $bookRepository, int $id): Response
    {
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        return $this->render('book/show_book.html.twig', [
            'book' => $book,
        ]);
    }






   

}
