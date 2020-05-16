<?php

namespace Ijdb\Controllers;

use \Ninja\DatabaseTable;
use \Ninja\Authentication;

class Joke {

    private $authorsTable;
    private $jokesTable;
    private $categoriesTable;
    private $authentication;

    public function __construct(DatabaseTable $jokesTable, DatabaseTable $authorsTable, DatabaseTable $categoriesTable, Authentication $authentication) {
        $this->authorsTable = $authorsTable;
        $this->jokesTable = $jokesTable;
        $this->categoriesTable = $categoriesTable;
        $this->authentication = $authentication;
    }

    public function list() {
        $jokes = $this->jokesTable->findAll();
//        $jokes = [];
//        foreach ($result as $joke) {
//            $author = $this->authorsTable->findById($joke->authorid);
//
//            $jokes[] = [
//                'id' => $joke->id,
//                'joketext' => $joke->joketext,
//                'rate' => $joke->rate,
//                'jokedate' => $joke->jokedate,
//                'name' => $author->name,
//                'email' => $author->email,
//                'authorId' => $author->id
//            ];
//        }

        
        $title = 'Joke list';

        $totalJokes = $this->jokesTable->total();

        $author = $this->authentication->getUser();

        return ['template' => 'jokes.html.php',
            'title' => $title,
            'variables' => [
                'totalJokes' => $totalJokes,
                'jokes' => $jokes,
                'userId' => $author->id ?? null
            ]
        ];
    }

    public function home() {
        $title = 'Internet Joke Database';

        return ['template' => 'home.html.php', 'title' => $title];
    }

    public function delete() {
        $author = $this->authentication->getUser();
        $joke = $this->jokesTable->findById($_POST['id']);

        if ($joke->authorid != $author->id) {
            return;
        }

        $this->jokesTable->delete($_POST['id']);

        header('location: /joke/list');
    }

    public function saveEdit() {
        $author = $this->authentication->getUser();

//        $authorObject = new \Ijdb\Entity\Author($this->jokesTable);
//        
//        $authorObject->id = $author['id'];
//        $authorObject->name = $author['name'];
//        $authorObject->email = $author['email'];
//        $authorObject->password = $author['password'];

//        if (isset($_GET['id'])) {
//            $joke = $this->jokesTable->findById($_GET['id']);
//
//            if ($joke['authorid'] != $author['id']) {
//                return;
//            }
//        }

        $joke = $_POST['joke'];
        $joke['jokedate'] = new \DateTime();
//        $joke['authorid'] = $author['id'];

//        $this->jokesTable->save($joke);
        $author->addJoke($joke);

        header('location: /joke/list');
    }

    public function edit() {
        $author = $this->authentication->getUser();

        if (isset($_GET['id'])) {
            $joke = $this->jokesTable->findById($_GET['id']);
        }

        $title = 'Edit joke';

        return ['template' => 'editjoke.html.php',
            'title' => $title,
            'variables' => [
                'joke' => $joke ?? null,
//                'userId' => $author['id'] ?? null
                'userId' => $author->id ?? null
            ]
        ];
    }

    public function rate() {
        if (isset($_GET['id'])) {
            $joke = $this->jokesTable->findById($_GET['id']);
        }
        
        $joke->rate ++;
//        for ($i = 0; $i <= 4; $i++) {
//            unset($joke[$i]);
//        }
        $author = $this->authentication->getUser();
        
        $joke_arr = [];
        $joke_arr['id'] = $joke->id;
        $joke_arr['authorid'] = $joke->authorid;
        $joke_arr['jokedate'] = $joke->jokedate;
        $joke_arr['joketext'] = $joke->joketext;
        $joke_arr['rate'] = $joke->rate;
        
        $author->addJoke($joke_arr);
//        var_dump((array)$joke);
//        $this->jokesTable->save((array)$joke);

        header('location: /joke/list');
    }

}
