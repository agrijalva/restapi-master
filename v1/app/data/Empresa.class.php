<?php
class Empresa
{
	var $Return_Type;
	var $conn;

	var $idEmpresa;
	var $idCliente;
	var $Tpro;

	public function __construct( $Class_Properties = array() ) {
		$this->Assign_Properties_Values($Class_Properties);
		$this->conn = new Connection();
		$this->Return_Type = 'json';
	}

	public function getAreas(){
		$_response['success'] = false;
		if( empty( $this->idEmpresa ) ){
			$_response['msg']     	= 'No se ha proporcionado el id de la empresa.';
		}
		else if( $this->idCliente == "" ){
			$_response['msg']     	= 'Proporciona el Id del cliente.';
		}
		else{
			$params = array(
				'idEmpresa' => array( 'value' => $this->idEmpresa, 'type' => 'INT' ),
				'idCliente' => array( 'value' => $this->idCliente, 'type' => 'INT' )
			);

			$_result = $this->conn->Query( "ARE_POR_EMPRESA_SP", $params );
			if( empty( $_result ) ){
				$_response['msg']     = 'La empresa no tiene configurado áreas.';	
			}
			else{
				$_response['success'] = true;
				$_response['msg']     = 'Se encontraron ' . count( $_result ) . ' resultados.';
				$_response['data']	  = $_result;				
			}
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