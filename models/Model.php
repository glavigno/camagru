<?php

abstract class Model
{
    private static $_db;

    // establish connection with the database
    private static function setDb()
    {
        require_once('config/database.php');
        self::$_db = new PDO($DB_DSN.$DB_NAME, $DB_USER, $DB_PASSWORD);
        self::$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    // get connection identifier
    protected function getDb()
    {
        if ( self::$_db == null )
            self::setDb();
        return self::$_db;
    }
}