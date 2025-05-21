<?php

class Conexion{

	static public function conectar(){
		//CONEXION LOCAL
		$host = 'colegio_db'; // El nombre del servicio definido en docker-compose
		$dbname = 'colegio';
		$username = 'user';
		$password = 'userpass';

		try {
			$link = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
			$link->exec("set names utf8");
			return $link;
		} catch (PDOException $e) {
			die("Error de conexión: " . $e->getMessage());
		}
		
	}

	static public  function conectarEnlace(){
		
		try {

			//CONEXION DE BACKUP EN LA WEB  --ORIGINAL--
			// $link = new PDO("mysql:host=66.97.41.81;dbname=colegioe_enlace01",
			// "colegioe_01",
			// "yohthouM9io6");
			//$link = new PDO("mysql:host=66.97.41.81;dbname=colegioe_enlace01",
			  //	            "colegioe_01",
			 	//            "yohthouM9io6");

			//CONEXION DE BACKUP EN LA WEB  --PRUEBA--
			$link = new PDO("mysql:host=66.97.41.81;dbname=colegioe_enlace01prueba",
				            "root",
				            "Bgtoner123456"); 

			$link->exec("set names utf8");

			$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $link;

    
    
		} catch (PDOException $e) {
			
			echo '<script>window.location = "iniciosinconexion";</script>';
			

		}

	}
	static public function conectarWs(){

		//CONEXION DE BACKUP EN LA WEB  --ORIGINAL--
		//  $link = new PDO("mysql:host=66.97.41.81;dbname=webservice",
		 		            // "root",
		 		            // "Bgtoner123456");
		//CONEXION PARA WEBSERVICE  --PRUEBA--
		$link = new PDO("mysql:host=66.97.41.81;dbname=webservice_prueba",
				            "root",
				            "Bgtoner123456"); 

		

			$link->exec("set names utf8");

			$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $link;
		
	}
	static public function conectarEscribanos() {
        try {
            $link = new PDO("mysql:host=localhost;dbname=escribano", "root", "");
            $link->exec("set names utf8");
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $link;
        } catch (PDOException $e) {
            echo "Error al conectar a la base de datos escribanos: " . $e->getMessage();
            exit;
        }
    }

}

   