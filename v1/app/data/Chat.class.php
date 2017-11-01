<?php

class Chat

{

	var $Return_Type;

	var $conn;



	var $Mensaje;

	var $idUsuario;

	var $idCliente;

	var $idEjecutivo;

	var $Tipo;

	var $idEmpresa;

	var $LastId;



	public function __construct( $Class_Properties = array() ) {

		$this->Assign_Properties_Values($Class_Properties);

		$this->conn = new Connection();

		$this->Return_Type = 'json';

	}



	public function Mensajea(){		

		$params = array(

			'Mensaje' 		=> array( 'value' => $this->Mensaje, 'type' => 'STRING' ),

			'idCliente' 	=> array( 'value' => $this->idCliente, 'type' => 'INT' ),

			'idEjecutivo' 	=> array( 'value' => $this->idEjecutivo, 'type' => 'INT' ),

			'Tipo' 			=> array( 'value' => $this->Tipo, 'type' => 'INT' ),

			'idEmpresa' 	=> array( 'value' => $this->idEmpresa, 'type' => 'INT' )

		);



		$_result = $this->conn->Query( "CHAT_GUARDA_MENSAJE_SP", $params );

		if( empty( $_result ) ){

			$_response['msg']     = 'Mensaje no guardado';	

		}

		else{

			$_response['success'] = true;

			// $_response['msg']     = 'Se encontraron ' . count( $_result ) . ' resultados.';

			$_response['data']	  = $_result[0];				

		}



		return $this->Request( $_response );

	}



	public function GetByEjecutivo(){
		$params = array(
			'idEjecutivo' 	=> array( 'value' => $this->idEjecutivo, 'type' => 'INT' ),
			'LastId' 		=> array( 'value' => $this->LastId, 'type' => 'INT' )
		);

		$_result = $this->conn->Query( "CHAT_GET_EJECUTIVO_SP", $params );
		if( empty( $_result ) ){
			$_response['msg']     = 'No hay mensajes nuevos';	
		}
		else{
			$_response['success'] = true;
			$_response['msg']     = 'Se encontraron ' . count( $_result ) . ' resultados.';
			$_response['data']	  = $_result;				
		}

		return $this->Request( $_response );
	}



	public function GetByCliente(){		

		$params = array(

			'idCliente' 	=> array( 'value' => $this->idCliente, 'type' => 'INT' ),

			'idEjecutivo' 	=> array( 'value' => $this->idEjecutivo, 'type' => 'INT' ),

			'LastId' 		=> array( 'value' => $this->LastId, 'type' => 'INT' )

		);



		$_result = $this->conn->Query( "CHAT_GET_CLIENTE_SP", $params );

		if( empty( $_result ) ){

			$_response['msg']     = 'No hay mensajes nuevos';	

		}

		else{

			$_response['success'] = true;

			$_response['msg']     = 'Se encontraron ' . count( $_result ) . ' resultados.';

			$_response['data']	  = $_result;				

		}



		return $this->Request( $_response );

	}

	public function GetByClienteInit(){
		$params = array(
			'idCliente' 	=> array( 'value' => $this->idCliente, 'type' => 'INT' ),
			'idEjecutivo' 	=> array( 'value' => $this->idEjecutivo, 'type' => 'INT' )
		);

		$_result = $this->conn->Query( "CHAT_GET_CLIENTE_INIT_SP", $params );
		if( empty( $_result ) ){
			$_response['msg']     = 'No hay mensajes nuevos';	
		}
		else{
			$_response['success'] = true;
			$_response['msg']     = 'Se encontraron ' . count( $_result ) . ' resultados.';
			$_response['data']	  = $_result;				
		}

		return $this->Request( $_response );
	}


	private function Assign_Properties_Values($Properties_Array){

		if (is_array($Properties_Array)) {

			foreach($Properties_Array as $Property_Name => $Property_Value)  {

				$this->{$Property_Name} = trim(htmlentities($Property_Value, ENT_QUOTES, 'UTF-8'));

			}

		}

	}



	private function Request( $_array ){

		if( empty( $this->Return_Type ) ){

			return $_array;			

		}

		else if( $this->Return_Type == 'json'  || $this->Return_Type == 'JSON' ){

			print_r( json_encode( $_array ) );

		}

	}

}

?>