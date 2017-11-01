<?php
// include 'app/model/tbl_medico.class.php';

class Cliente
{
	var $Return_Type;
	var $conn;

	var $Ideje;
	var $Tpro;
	var $idEmpresa;

	var $emp_id;
	var $cli_nombre;
	var $cli_rfc;
	var $cli_email;
	var $cli_telefono;
	var $cli_celular;
	var $cli_razon_social;
	var $ctc_id;

	var $cli_id;
	var $ca_id;
	var $Estatus;

	var $key;

	public function __construct( $Class_Properties = array() ) {
		$this->Assign_Properties_Values($Class_Properties);
		$this->conn = new Connection();
		$this->Return_Type = 'json';
	}

	public function getCliente(){
		$_response['success'] = false;
		if( empty( $this->key ) ){
			$_response['msg']     	= 'Proporciona el Key del Cliente.';	
		}
		else{
			$params = array('_Key' => array( 'value' => $this->key, 'type' => 'STRING' ));
			$_result = $this->conn->Query( "CLI_GET_ONE_SP", $params );

			if( !empty( $_result ) ){
				$params = array('idCliente' => array( 'value' => $_result[0]['cli_id'], 'type' => 'STRING' ));
				$_resultEjecutivos   = $this->conn->Query( "EJE_GET_BY_CLIENTE_SP", $params );
				$_resultResponsables = $this->conn->Query( "RES_GET_BY_CLIENTE_SP", $params );
					
				$_result[0]['Ejecutivos'] = $_resultEjecutivos;
				$_result[0]['Representantes'] = $_resultResponsables;

				$_response['success'] 	= true;
				$_response['msg']     	= 'Registros encontrados: ' . count( $_result );
				$_response['data'] 		= $_result;
			}
			else{
				$_response['msg']     	= 'No se encontraron resultados para tu solicitud.';	
			}			
		}
		
		return $this->Request( $_response );
	}

	public function byEjecutivo(){
		$_response['success'] = false;
		if( empty( $this->Ideje ) ){
			$_response['msg']     	= 'Proporciona el Id del Ajente.';	
		}
		else{
			$params = array(
					'Ideje' => array( 'value' => $this->Ideje, 'type' => 'INT' ),
					'Tpro' => array( 'value' => $this->Tpro, 'type' => 'INT' )
				);

			$_result = $this->conn->Query( "CLI_POR_EJECUTIVO_SP", $params );

			if( !empty( $_result ) ){
				$_response['success'] 	= true;
				$_response['msg']     	= 'Registros encontrados: ' . count( $_result );
				$_response['data'] 		= $_result;
			}
			else{
				$_response['msg']     	= 'No se encontraron resultados para tu solicitud.';	
			}			
		}
		
		return $this->Request( $_response );
	}

	public function byEmpresa(){
		$_response['success'] = false;
		if( empty( $this->idEmpresa ) ){
			$_response['msg']     	= 'Proporciona el Id de la empresa.';	
		}
		else{
			$params = array(
				'idEmpresa' => array( 'value' => $this->idEmpresa, 'type' => 'INT' )
			);

			$_result = $this->conn->Query( "CLI_POR_EMPRESA_SP", $params );

			if( !empty( $_result ) ){
				foreach ($_result as $key => $value) {
					$params = array(
						'idCliente' => array( 'value' => $value['cli_id'], 'type' => 'INT' )
					);

					$_areas = $this->conn->Query( "CLI_POR_EMPRESA_AREAS_SP", $params );
					$_result[ $key ]['Areas'] = $_areas;
				}
				$_response['success'] 	= true;
				$_response['msg']     	= 'Registros encontrados: ' . count( $_result );
				$_response['data'] 		= $_result;
			}
			else{
				$_response['msg']     	= 'No se encontraron resultados para tu solicitud.';	
			}
		}
		
		return $this->Request( $_response );
	}

	public function asignarArea(){
		$_response['success'] = false;
		if( empty( $this->cli_id ) ){
			$_response['msg']     	= 'Proporcionar el Id del cliente.';	
		}
		else if( empty( $this->ca_id ) ){
			$_response['msg']     	= 'Proporciona el Id del área.';	
		}
		else if( empty( $this->Estatus ) ){
			$_response['msg']     	= 'Proporciona el estatus.';	
		}
		else{
			$params = array(
				'idCliente' => array( 'value' => $this->cli_id,  'type' => 'INT' ),
				'idArea' 	=> array( 'value' => $this->ca_id, 	 'type' => 'INT' ),
				'Estatus' 	=> array( 'value' => $this->Estatus, 'type' => 'INT' )
			);

			$_result = $this->conn->Query( "CLI_ASIGNA_AREA_SP", $params );

			if( !empty( $_result ) ){
				$_response['success'] 	= true;
				$_response['msg']     	= $_result[0];
			}
			else{
				$_response['msg']     	= 'No se encontraron resultados para tu solicitud.';	
			}
		}
		
		return $this->Request( $_response );
	}

	public function AsiganarEjecutivo(){

		$_response['success'] = false;
		if( empty( $this->idEjecutivo ) ){
			$_response['msg']     	= 'Proporciona el Id del ejecutivo.';	
		}
		else if( empty( $this->idCliente ) ){
			$_response['msg']     	= 'Proporciona el Id del cliente.';	
		}
		else if( empty( $this->Estatus ) ){
			$_response['msg']     	= 'Proporciona el estatus de la relaci&oacute;n Cliente - Ejecutivo.';	
		}
		else{
			$params = array(
				'idEjecutivo' => array( 'value' => $this->idEjecutivo, 'type' => 'INT' ),
				'idCliente'   => array( 'value' => $this->idCliente, 'type' => 'INT' ),
				'estatus' 	  => array( 'value' => $this->Estatus, 'type' => 'INT' )
			);

			$_result = $this->conn->Query( "EJE_ASIGNAR_CLIENTE_SP", $params );

			if( !empty( $_result ) ){
				$_response['success'] 	= true;
				$_response['msg']     	= 'Registros encontrados: ' . count( $_result );
				$_response['data'] 		= $_result;
			}
			else{
				$_response['msg']     	= 'No se encontraron resultados para tu solicitud.';	
			}
		}
		
		return $this->Request( $_response );
	}

	public function catalogoTipoCliente(){
		$_response['success'] = false;
		$_result = $this->conn->Query( "ADM_CTC_SP", array() );
		if( empty( $_result ) ){
			$_response['msg']     = 'No se cuenta registros en el catálogo.';
		}
		else{
			$_response['success'] = true;
			$_response['msg']     = 'Se encontraron ' . count( $_result ) . ' resultados.';
			$_response['data']	  = $_result;
		}

		return $this->Request( $_response );
	}

	public function nuevoCliente(){
		$_response['success'] = false;
		if( empty( $this->emp_id ) ){
			$_response['msg']     	= 'No se ha especificado el Id de la empresa.';
		}
		else if( empty( $this->cli_razon_social ) ){
			$_response['msg']     	= 'Se debe proporcionar la razón social del cliente.';
		}
		else if( empty( $this->cli_rfc ) ){
			$_response['msg']     	= 'Debe ingresar el RFC del cliente.';
		}
		else if(!filter_var($this->cli_email, FILTER_VALIDATE_EMAIL)){
			$_response['msg']     	= 'El email proporcionado no cuenta con el formato requerido.';
		}
		// else if( empty( $this->cli_celular ) ){
		// 	$_response['msg']     	= 'Se debe especificar el número de celular, para esta operación este campo es obligatorio.';
		// }
		else if( empty( $this->cli_nombre ) ){
			$_response['msg']     	= 'No se ha especificado el nombre del representante del cliente.';
		}
		else if( empty( $this->ctc_id ) ){
			$_response['msg']     	= 'No se ha proporcionado el tipo de cliente.';
		}
		else{
			$key = md5( date("Y-m-d H:i:s") );
			$params_cliente = array(
				'idEmpresa' => array( 'value' => $this->emp_id,   		  'type' => 'INT' ),
				'_razon' 	=> array( 'value' => $this->cli_razon_social, 'type' => 'STRING' ),
				'_rfc'  	=> array( 'value' => $this->cli_rfc,  		  'type' => 'STRING' ),
				'_email'    => array( 'value' => $this->cli_email,	  	  'type' => 'STRING' )
			);

			// print_r($params_cliente);

			$_result = $this->conn->Query( "CLI_INSERTAR_NUEVO_SP", $params_cliente );
			if( !empty( $_result ) ){
				$_key = $_result[0]['key'];
				$_LastId = $_result[0]['LastId'];

				$params_repre = array(
					'idCliente' => array( 'value' => $_result[0]['LastId'],   'type' => 'STRING' ),
					'_nombre'   => array( 'value' => $this->cli_nombre,	      'type' => 'STRING' ),
					'_email'    => array( 'value' => $this->cli_email,	  	  'type' => 'STRING' ),
					'_telefono' => array( 'value' => $this->cli_telefono,	  'type' => 'STRING' ),
					'idEmpresa' => array( 'value' => $this->emp_id,   		  'type' => 'INT' ),
					'idCtc'    	=> array( 'value' => $this->ctc_id,	      	  'type' => 'INT' )
				);

				$_responseRep = $this->conn->Query( "RES_INSERTAR_NUEVO_SP", $params_repre );

				$_response['success'] = true;
				$_response['msg'] 	  = 'Se ha guardado correctamente.';
				$_response['LastId']  = $_LastId;
				$_response['key']  	  = $_key;
				$_response['pass']    = $_responseRep[0]['pass'];
			}
			else{
				$_response['msg'] = 'Ha ocurrido un error no controlado.';
			}

		}
		
		return $this->Request( $_response );
	}

	public function actualizaCliente(){
		$_response['success'] = false;
		if( empty( $this->cli_id ) ){
			$_response['msg']     	= 'No se ha especificado el Id del cliente.';
		}
		else if( empty( $this->cli_razon_social ) ){
			$_response['msg']     	= 'Se debe proporcionar la razón social del cliente.';
		}
		else if( empty( $this->cli_rfc ) ){
			$_response['msg']     	= 'Debe ingresar el RFC del cliente.';
		}
		else if( empty( $this->cli_celular ) ){
			$_response['msg']     	= 'Se debe especificar el número de celular, para esta operación este campo es obligatorio.';
		}
		else if( empty( $this->cli_nombre ) ){
			$_response['msg']     	= 'No se ha especificado el nombre del representante del cliente.';
		}
		else if( empty( $this->ctc_id ) ){
			$_response['msg']     	= 'No se ha proporcionado el tipo de cliente.';
		}
		else{
			$params = array(
				'idCliente' => array( 'value' => $this->cli_id,   		  'type' => 'INT' ),
				'_razon' 	=> array( 'value' => $this->cli_razon_social, 'type' => 'STRING' ),
				'_rfc'  	=> array( 'value' => $this->cli_rfc,  		  'type' => 'STRING' ),
				'_telefono' => array( 'value' => $this->cli_telefono,	  'type' => 'STRING' ),
				'_celular'  => array( 'value' => $this->cli_celular,	  'type' => 'STRING' ),
				'_nombre'   => array( 'value' => $this->cli_nombre,	      'type' => 'STRING' ),
				'_ctc'    	=> array( 'value' => $this->ctc_id,	      	  'type' => 'INT' )
			);

			$_result = $this->conn->Query( "CLI_ACTUALIZAR_SP", $params );

			$_response = $_result[0];
		}
		
		return $this->Request( $_response );
	}

	public function UpdateCliente(){
		$_response['success'] = false;
		if( empty( $this->cli_rfc ) ){
			$_response['msg']     	= 'Favor de proporcionar el RFC del cliente.';
		}
		else if( empty( $this->cli_razon_social ) ){
			$_response['msg']     	= 'Favor de Proporcionar la Razón Social del cliente.';
		}
		else if( empty( $this->cli_id ) ){
			$_response['msg']     	= 'Se debe proporcionar el Id del Cliente.';
		}
		else{
			$params = array(
				'idCliente' => array( 'value' => $this->cli_id,   		  'type' => 'INT' ),
				'_razon' 	=> array( 'value' => $this->cli_razon_social, 'type' => 'STRING' ),
				'_rfc'  	=> array( 'value' => $this->cli_rfc,  		  'type' => 'STRING' )
			);

			$_result = $this->conn->Query( "CLI_UPD_EDITAR_SP", $params );

			$_response = $_result[0];
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