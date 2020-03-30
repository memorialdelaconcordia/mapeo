<?php

    function db_connect() {
        //Tomado de: https://www.binpress.com/tutorial/using-php-with-mysql-the-right-way/17

        // Define connection as a static variable, to avoid connecting more than once 
        static $connection;

        // Try and connect to the database, if a connection has not been established yet
        if(!isset($connection)) {
            $host="localhost"; 				// Host 
            $username="DB_USERNAME"; 		// Mysql username
            $password="DB_PASSWORD-"; 			// Mysql password
            $db_name="DB_NAME"; 				// Database			
            
            $connection = mysqli_connect($host, $username, $password, $db_name);
        }

        // If connection was not successful, handle the error
        if($connection === false) {
            die("Connection error: " . mysqli_connect_error());
        }
        
        mysqli_set_charset($connection,"utf8");

        return $connection;
    }	

    function db_query($query){
        
        // Connect to the database
        $connection = db_connect();
        
        $result = mysqli_query($connection, $query);
        
        if(!$result){
            //TODO
            echo "Error for ".$query."<br>".mysqli_error($connection)."<br>";
        }
        return $result;
    }

?>
