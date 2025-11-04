<?php

require_once 'QueryBuilder.php';

class ApiController {

    protected QueryBuilder $queryBuilder;

    public function __construct() {

        $this->queryBuilder = new QueryBuilder();
    }

    /**
     * Retrieve all users from the database
    */
    public function getUsers(): array {

        return $this->queryBuilder->table('Users')
            ->select(['*'])
            ->get();

    }

    /*
    * Add more methods as needed
    * Example: insertUser(), getUserById(), updateUser(), deleteUser(), etc.
    */

}