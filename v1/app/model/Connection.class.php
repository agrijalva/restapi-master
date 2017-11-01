<?php
	class Connection{
		// Datos de conexión de MySQL Server
		var $mysqli;
		var $BaseDatos;
		var $Servidor;
		var $Usuario;
		var $Clave;
			
		var $conf;
		function Connection(){
			// $this->BaseDatos = "db_intestino_limpo";
			// $this->BaseDatos = "informacionspf";

			// $this->Servidor  = "localhost";
			// $this->BaseDatos = "loladise_asesoria";
			// $this->Usuario 	 = "loladise_asesor";
			// $this->Clave	 = "AseSor1*";

			$this->BaseDatos = "asesoria";
			$this->Servidor  = "localhost";
			$this->Usuario 	 = "root";
			$this->Clave	 = "";

			// $this->BaseDatos = "nutrici9_asesoria";
			// $this->Servidor  = "localhost";
			// $this->Usuario 	 = "nutrici9_aseso";
			// $this->Clave	 = "amorOdio1*";
		}

		 function conectar() {
			$mysqli = new mysqli( $this->Servidor, $this->Usuario, $this->Clave, $this->BaseDatos);
			if ( $mysqli->connect_errno ) {
			    echo "Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
				$this->mysqli = false;
			    dead();
			}
			else{
				$this->mysqli = $mysqli;
			}

			return true;
		}

		function Query( $sp, $params ){
			if( $this->conectar() ){
				$str_parametros = $this->FormatParams( $params );
				$stored = "CALL " . $sp . "(" . $str_parametros . ");";
				// echo $stored;
				$datos = $this->mysqli->query( $stored );
				// $datos = $this->mysqli->query( 'SELECT * FROM usuario WHERE usu_id = 2;' );

				if (!$datos) {
				    printf("Errormessage: %s\n", $this->mysqli->error);
				    $registros = false;
				}
				else{
					$registros = array();
					while ($fila = $datos->fetch_assoc()) {
						$registros[] = $fila;
					}

					if (empty($registros)){
						$registros = array();
					}
				}

				return $registros;
			}
		}

		private function FormatParams(  $params ){
			$aux = array();
			if( !empty( $params ) ){
				foreach ($params as $key => $item) {
					if( $item['type'] == 'INT' ){
						$aux[] = $item['value'];
					}
					else{
						$aux[] = "'". $item['value'] ."'";	
					}
				}

				return implode(",", $aux);
			}
			else{
				return '';
			}
		}
	}
?>