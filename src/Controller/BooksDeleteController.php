<?php

namespace App\Controller;

use App\Entity\Book;

use App\Form\BookDeleterType;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class BooksDeleteController extends AbstractController{
    /**
     * @Route("/delete/{id}", requirements={"id"="\d+"})
     */
     public function delete_book(int $id, Request $request) : Response{
         $repository = $this->getDoctrine()->getRepository(Book::class);
         $book = $repository->findOneById($id);

         if($book === NULL){
                return $this->redirectToRoute('app_bookslist_show_books_list', ['page'=>1]);
         }

         $form = $this->createForm(BookDeleterType::class, $book);

         $form->handleRequest($request);
         // Removing entity if form is valid, and redirecting to success page
         if ($form->isSubmitted() && $form->isValid()){
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($book);
                $entityManager->flush();

                return $this->redirectToRoute('app_booksdelete_delete_success');
         }
         // Otherwise adding errors to be displayed if those occured
         if(count($form->getErrors(true)) > 0){
                $this->addFlash('danger', 'Некоторые поля заполнены неверно. Проверьте правильность заполнения полей.');
         }
         // ... and rendering template
         return $this->render('books_delete/books_delete.html.twig',
                             ['book'       => $book,
                              'form'       => $form->createView(),
                              'page_title' => "Удалить книгу"]);

     }

     /**
      * @Route("/delete_success")
      */
     public function delete_success() : Response{
            return $this->render('books_delete/books_delete_success.html.twig',
                                 ['page_title' => 'Книга удалена']);
     }

 }

 ?>
