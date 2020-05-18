<?php
    function db_connect($UserType){
        $host = 'localhost';
        
        $database = 'eshop';
		
        if($UserType == 'read'){                 
            $username = 'root';           
            $password = '';
            
        }else if($UserType == 'admin'){
            $username = 'root';
            $password = '';
			
        }else{
			exit('unrecorize user');
		}
        $conn = mysqli_connect($host, $username, $password, $database);
		
        if($conn->connect_error){
            exit($conn->connect_error);
        }
        return $conn;
    }
?>