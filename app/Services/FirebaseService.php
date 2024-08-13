<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FirebaseService
{
    private $database;

    public function __construct()
    {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/../../path/to/firebase_credentials.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://your-database-name.firebaseio.com/')
            ->create();

        $this->database = $firebase->getDatabase();
    }

    public function getDatabase()
    {
        return $this->database;
    }
}
