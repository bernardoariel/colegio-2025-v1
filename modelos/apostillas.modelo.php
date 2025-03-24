<?php

require_once "conexion.php";

class ModeloApostillas{

	/*=============================================
	MOSTRAR APOSTILLAS
	=============================================*/

	static public function mdlMostrarApostillas($tabla, $item, $valor){
		
		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_INT);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		

	}
	/*=============================================
	MOSTRAR TODAS LAS APOSTILLAS de un venta
	=============================================*/

	static public function mdlMostrarTodasApostillasVenta($tabla, $item, $valor){
	

		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE idventa = :idventa");

		$stmt->bindParam(":idventa", $valor, PDO::PARAM_INT);

		$stmt -> execute();

		return $stmt -> fetchAll();


	}
	
	static public function mdlMostrarJsonApostilla($tabla, $item, $valor){
	
		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

		$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
		$stmt -> execute();

		return $stmt -> fetch();


	}
	

	/*=============================================
	AGREGAR DATOS APOSTILLA
	=============================================*/

	static public function mdlGuardarDatosApostilla($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
												descripcion = :descripcion,
												nombre = :nombre,
												importe = :importe,
												intervino = :intervino,
												haya = :haya 
												WHERE id = :id");

		$stmt -> bindParam(":id", $datos["id"], PDO::PARAM_INT);
		$stmt -> bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt -> bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt -> bindParam(":importe", $datos["importe"], PDO::PARAM_STR);
		$stmt -> bindParam(":intervino", $datos["intervino"], PDO::PARAM_STR);
		$stmt -> bindParam(":haya", $datos["haya"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

	}


	
}
