<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\DiscardBook;
use App\Entity\Reader;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\DiscardBookRepository;
use App\Repository\ReaderRepository;
use Exception;

class Library
{
    private const BOOK_VALID = [
        'name',
        'yearPublication',
        'ISBN',
        'count',
        'authors',
    ];

    private const AUTHOR_VALID = [
        'name',
        'surname',
        'patronymic',
    ];

    private const GIVE_BOOK_VALID = [
        'ISBN',
        'reader'
    ];

    private const READER_VALID = [
        'name',
        'surname',
        'patronymic',
    ];

    private const DISCARD_BOOK = [
        'ISBN',
        'reason'
    ];

    public function __construct(
        private readonly BookRepository   $bookRepository,
        private readonly AuthorRepository $authorRepository,
        private readonly ReaderRepository $readerRepository,
        private readonly DiscardBookRepository $discardBookRepository
    )
    {
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function validatorAddBook(array $data): void
    {
        foreach (self::BOOK_VALID as $value) {
            if (!isset($data[$value])) {
                throw new Exception('В книги не указан параметр '.$value);
            }
        }

        if (count($data['authors']) == 0) {
            throw new Exception('Не указан(ы) автор(ы)');
        }

        foreach ($data['authors'] as $author) {
            foreach (self::AUTHOR_VALID as $value) {
                if (!isset($author[$value])) {
                    throw new Exception('В авторе не указан параметр '.$value);
                }
            }
        }
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function validatorGiveBook(array $data): void
    {
        foreach (self::GIVE_BOOK_VALID as $value) {
            if (!isset($data[$value])) {
                throw new Exception('В книги не указан параметр '.$value);
            }
        }

        foreach (self::READER_VALID as $value) {
            if (!isset($data['reader'][$value])) {
                throw new Exception('В авторе не указан параметр '.$value);
            }
        }
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function validatorDiscardBook(array $data): void
    {
        foreach (self::DISCARD_BOOK as $value) {
            if (!isset($data[$value])) {
                throw new Exception('При списании книги не указан параметр '.$value);
            }
        }
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    private function validatorSearchBookByAuthor(array $data): void
    {
        if (!isset($data['authors'])) {
            throw new Exception('Не указан параметр authors');
        }

        foreach ($data['authors'] as $author) {
            foreach (self::AUTHOR_VALID as $value) {
                if (!isset($author[$value])) {
                    throw new Exception('В авторе не указан параметр '.$value);
                }
            }
        }
    }

    public function allBooks(): array
    {
        $books = $this->bookRepository->allBooks();

        if ($books === null) {
            return ['allBooks' => 'Книги в библиотеке не найдены'];
        }

        return $books;
    }

    /**
     * @param array $data
     * @return string[]
     * @throws Exception
     */
    public function searchBookByAuthor(array $data): array
    {
        $this->validatorSearchBookByAuthor($data);

        $books = $this->bookRepository->searchBookByAuthor($data);

        if ($books === null) {
            return ['searchBookByAuthor' => 'Книги в библиотеке не найдены'];
        }

        return $books;
    }

    /**
     * @param array $data
     * @return true[]
     * @throws Exception
     */
    public function addBook(array $data): array
    {
        $this->validatorAddBook($data);

        $book = $this->bookRepository->findOneBy(['ISBN' => $data['ISBN']]);

        if ($book === null) {
            $newBook = (new Book())
                ->setName($data['name'])
                ->setYearPublication($data['yearPublication'])
                ->setISBN($data['ISBN'])
                ->setCount($data['count']);
            $this->bookRepository->add($newBook, false);

            foreach ($data['authors'] as $author) {

                $oldAuthor = null;
                if (isset($author['id'])) {
                    $oldAuthor = $this->authorRepository->find($author['id']);
                    if ($oldAuthor === null) {
                        throw new Exception('Автор книги не найден');
                    }
                }

                if ($oldAuthor === null) {
                    $newAuthor = (new Author())
                        ->setName($author['name'])
                        ->setSurname($author['surname'])
                        ->setPatronymic($author['patronymic'])
                        ->addBook($newBook);
                    $this->authorRepository->add($newAuthor, false);
                } else {
                    $oldAuthor->addBook($newBook);
                }
            }
        } else {
            $book->setCount($book->getCount() + $data['count']);
        }

        $this->bookRepository->flush();

        return ['addBook' => true];
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function giveBook(array $data): array
    {
        $this->validatorGiveBook($data);

        $book = $this->bookRepository->findOneBy(['ISBN' => $data['ISBN']]);

        if ($book === null || $book->getCount() == 0) {
            throw new Exception('Запрошенной книги нет в библиотеке');
        }

        $reader = null;
        if (isset($data['reader']['id'])) {
            $reader = $this->readerRepository->find($data['reader']['id']);
            if ($reader === null) {
                throw new Exception('Запрошенный читатель не найден');
            }
            if ($reader->getBooks()->contains($book)) {
                throw new Exception('Данная книга уже выдана читателю');
            }
        }

        if ($reader === null) {
            $reader = (new Reader())
                ->setName($data['reader']['name'])
                ->setSurname($data['reader']['surname'])
                ->setPatronymic($data['reader']['patronymic']);
            $this->readerRepository->add($reader);
        }

        $book->setCount($book->getCount() - 1);

        $reader->addBook($book);
        $this->readerRepository->flush();

        return [
            'readerId' => $reader->getId(),
            'bookId' => $book->getId()
        ];
    }

    /**
     * @throws Exception
     */
    public function discardBook(array $data): array
    {
        $this->validatorDiscardBook($data);

        $book = $this->bookRepository->findOneBy(['ISBN' => $data['ISBN']]);

        if ($book === null) {
            throw new Exception('Запрошенной книги нет в библиотеке');
        }

        $discard = (new DiscardBook())
            ->setBook($book)
            ->setReason($data['reason'])
            ;

        $book->setCount($book->getCount() - 1);
        $this->discardBookRepository->add($discard);

        return [
            'discard' => true,
            'discardId' => $discard->getId()
        ];
    }
}