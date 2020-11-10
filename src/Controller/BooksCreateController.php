<?php

namespace App\Controller;

use App\Entity\Book;

use App\Form\BookCreatorType;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class BooksCreateController extends AbstractController{
    /**
     * @Route("/new")
     */
     public function create_book(Request $request) : Response{
         $book = new Book();

         $form = $this->createForm(BookCreatorType::class, $book);

         $form->handleRequest($request);
         // Creating and storing the entity if form is valid, and redirecting to success page
         if ($form->isSubmitted() && $form->isValid()){
                $book = $form->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($book);
                $entityManager->flush();

                return $this->redirectToRoute('app_bookscreate_create_success');
         }
         // Otherwise adding errors to be displayed if those occured
         if(count($form->getErrors(true)) > 0){
                $this->addFlash('danger', 'Некоторые поля заполнены неверно. Проверьте правильность заполнения полей.');
         }
         // ...and rendering template
         return $this->render('books_new/books_new.html.twig',
                                 ['book'      => $book,
                                 'form'       => $form->createView(),
                                 'page_title' => "Добавить новую книгу"]);

     }

     /**
      * @Route("/create_success")
      */
     public function create_success() : Response{
            return $this->render('books_new/books_create_success.html.twig',
                                ['page_title' => 'Новая книга добавлена']);
     }

 }

 ?>
