<?php

namespace App\Controller;

use App\Entity\Book;

use App\Form\BookEditorType;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class BooksEditController extends AbstractController{
    /**
     * @Route("/edit/{id}", requirements={"id"="\d+"})
     */
     public function edit_book(int $id = 1, Request $request) : Response{
         $repository = $this->getDoctrine()->getRepository(Book::class);
         $book = $repository->findOneById($id);

         $header_book_title = $book->getBookTitle(); // displayed in <h1> tag even if book title was changed

         if($book === NULL){
             return $this->redirectToRoute('app_bookslist_show_books_list', ['page'=>1]);
         }

         $form = $this->createForm(BookEditorType::class, $book);

         $form->handleRequest($request);
         // Updating the entity if form is valid, and redirecting back to the edit page
         if ($form->isSubmitted() && $form->isValid()){
                $book = $form->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($book);
                $entityManager->flush();

                $this->addFlash('success', 'Изменения сохранены!');

                return $this->redirectToRoute('app_booksedit_edit_book', ['id'=>$id]);
         }
         //  Otherwise adding errors to be displayed if those occured
         if(count($form->getErrors(true)) > 0){
             $this->addFlash('danger', 'Некоторые поля заполнены неверно. Проверьте правильность заполнения полей.');
         }
         // ...and rendering the template
         return $this->render('books_edit/books_edit.html.twig',
                             ['book'       => $book,
                              'form'       => $form->createView(),
                              'page_title' => 'Редактировать книгу "'.$header_book_title.'"']);
     }
 }

 ?>
