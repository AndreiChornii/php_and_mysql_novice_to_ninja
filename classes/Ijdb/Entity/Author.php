<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Ijdb\Entity;

class Author {

    public $id;
    public $name;
    public $email;
    public $password;
    private $jokesTable;

    public function __construct(\Ninja\DatabaseTable
    $jokesTable) {
        $this->jokesTable = $jokesTable;
    }

    public function getJokes() {
        return $this->jokesTable->find('authorid', $this->id);
    }

    public function addJoke($joke) {
        $joke['authorid'] = $this->id;
        $this->jokesTable->save($joke);
    }

}
