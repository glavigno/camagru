#!/usr/bin/php
<?php
    require_once('database.php');
    $dbConnection = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    if (!$dbConnection)
        die();
    $sqlQueries = file_get_contents('camagru_db.sql');
    try 
    {
        $dbConnection->exec($sqlQueries);
    }
    catch (PDOException $e)
    {
        echo "Error " . $e->getMessage() . "\n";
        die();
    }