<?php

namespace App\Controller;

use App\Entity\Book;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BooksListController extends AbstractController{
    /**
     * @Route("/books/{page}", requirements={"page"="[1-9]\d*"})
     */
    public function show_books_list(int $page = 1) : Response{
        $repository = $this->getDoctrine()->getRepository(Book::class);

        // Retrieving current page books & setting pagination prerequisites
        $books_per_page = $this->getParameter('books_per_page');

        $books          = $repository->getAllBooks($page, $books_per_page);
        $books_iterator = $books->getIterator();

        $total_books    = $books->count();
        $total_pages    = ceil($total_books / $books_per_page);
        // Preventing page number from being out of range
        // and infinite redirect if no books were added
        if ($total_pages > 0 && $page > $total_pages) {
            return $this->redirectToRoute('app_bookslist_show_books_list', ['page'=>1]);
        }
        // Rendering template
        return $this->render('books_list/books_viewall.html.twig',
                            ['books'        => $books_iterator,
                             'current_page' => $page,
                             'total_pages'  => $total_pages,
                             'page_title'   => "Список книг - Страница $page из $total_pages"]);
     }
}

?>
