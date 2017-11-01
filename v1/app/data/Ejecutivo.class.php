<?php
// include 'app/model/tbl_medico.class.php';

class Ejecutivo
{
	var $Return_Type;
	var $conn;

	var $usu_id;
	var $emp_id;
	var $eje_id;
	var $ese_id;
	var $key;

	var $idEjecutivo;
	var $eje_nombre;
	var $eje_email;
	var $eje_telefono;
	var $eje_celular;

	var $idArea;
	var $estatus;

	public function __construct( $Class_Properties = array() ) {
		$this->Assign_Properties_Values($Class_Properties);
		$this->conn = new Connection();
		$this->Return_Type = 'json';
	}

	public function getEjecutivos(){
		$_response['success'] = false;
		if( empty( $this->emp_id ) ){
			$_response['msg']     	= 'No se ha especificado la empresa a la que desea consultar.';	
		}
		else if( $this->eje_id == '' ){
			$_response['msg']     	= 'Proporciona el Id del Ajente.';	
		}
		else{
			$params = array(
					'emp_id' => array( 'value' => $this->emp_id, 'type' => 'INT' ),
					'eje_id' => array( 'value' => $this->eje_id, 'type' => 'INT' )
				);

			$_result = $this->conn->Query( "EJE_GET_ALL_SP", $params );

			if( !empty( $_result ) ){
				$_response['success'] 	= true;
				$_response['msg']     	= 'Registros encontrados: ' . count( $_result );

				foreach ($_result as $key => $value) {
					$params_2 = array(
						'eje_id' => array( 'value' => $value['eje_id'], 'type' => 'INT' )
					);

					$_result_2 = $this->conn->Query( "EJE_AREA_EJECUTIVO_SP", $params_2 );
					$_result[ $key ]['Areas'] = $_result_2;
				}

				$_response['data'] 		= $_result;
			}
			else{
				$_response['msg']     	= 'No se encontraron resultados para tu solicitud.';
			}			
		}
		
		return $this->Request( $_response );
	}

	public function getByAreas(){
		$_response['success'] = false;
		if( empty( $this->emp_id ) ){
			$_response['msg']     	= 'No se ha especificado la empresa a la que desea consultar.';	
		}
		else if( $this->idArea == '' ){
			$_response['msg']     	= 'Proporciona el Id de las Áreas.';	
		}
		else{
			$params = array(
					'idEmpresa' => array( 'value' => $this->idArea, 'type' => 'STRING' ),
					'idArea' => array( 'value' => $this->emp_id, 'type' => 'INT' )
				);

			$_result = $this->conn->Query( "EJE_BY_AREAS_SP", $params );


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

	public function getByAreaAndCustomer(){ 
		$_response['success'] = false;
		if( empty( $this->emp_id ) ){
			$_response['msg']     	= 'No se ha especificado la empresa a la que desea consultar.';	
		}
		else if( $this->idArea == '' ){
			$_response['msg']     	= 'Proporciona el Id de las Áreas.';	
		}
		else if( $this->idEjecutivo == '' ){
			$_response['msg']     	= 'Proporciona el Id del Ejecutivo.';	
		}
		else{
			$params = array(
					'idEmpresa' => array( 'value' => $this->idArea, 'type' => 'STRING' ),
					'idArea' => array( 'value' => $this->emp_id, 'type' => 'INT' ),
					'idEjecutivo' => array( 'value' => $this->idEjecutivo, 'type' => 'STRING' )
				);

			$_result = $this->conn->Query( "EJE_BY_AREAS_AND_CUSTOMER_SP", $params );


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

	public function getEjecutivoByKey(){
		$_response['success'] = false;
		if( empty( $this->key ) ){
			$_response['msg']     	= 'No se ha especificado la clave del Ejecutivo.';	
		}
		else{
			$params = array(
					'key' => array( 'value' => $this->key, 'type' => 'STRING' )
				);

			$_result = $this->conn->Query( "EJE_GET_BY_KEY_SP", $params );

			if( !empty( $_result ) ){
				$_response['success'] 	= true;
				$_response['msg']     	= 'Registros encontrados: ' . count( $_result );

				foreach ($_result as $key => $value) {
					// Inclusion de Areas configuradas para el ejecutivo
					$params_2 = array(
						'eje_id' => array( 'value' => $value['eje_id'], 'type' => 'INT' )
					);

					$_areas = $this->conn->Query( "EJE_AREA_EJECUTIVO_SP", $params_2 );
					$_result[ $key ]['Areas'] = $_areas;

					// Inclusion de clientes asignados al ejecutivo
					$_clientes = $this->conn->Query( "CLI_POR_EJECUTIVO_SP", $params_2 );
					foreach( $_clientes as $llave => $valor ){
						// Inclusion de areas asignados a los clientes
						$params_3 = array(
							'cli_id' => array( 'value' => $valor['cli_id'], 'type' => 'INT' )
						);
						$areasCliente = $this->conn->Query( "CLI_POR_EMPRESA_AREAS_SP", $params_3 );
						$_clientes[ $llave ]['Areas'] = $areasCliente;
					}
					$_result[ $key ]['Clientes'] = $_clientes;

					// Asignacion de las areas que los clientes se les ha configurado
					$areasClientesTodos = $this->conn->Query( "ARE_CLIENTES_TODOS_BY_EJE_SP", $params_2 );
					$_result[ $key ]['AreasClientes'] = $areasClientesTodos;
				}

				$_response['data'] 		= $_result;
			}
			else{
				$_response['msg']     	= 'No se encontraron resultados para tu solicitud.';	
			}			
		}
		
		return $this->Request( $_response );
	}

	public function getEjecutivoById(){
		$_response['success'] = false;
		if( empty( $this->idEjecutivo ) ){
			$_response['msg']     	= 'No se ha especificado el Id del Ejecutivo.';	
		}
		else{
			$params = array(
					'idEjecutivo' => array( 'value' => $this->idEjecutivo, 'type' => 'INT' )
				);

			$_result = $this->conn->Query( "EJE_GET_BY_ID_SP", $params );

			if( !empty( $_result ) ){
				$_response['success'] 	= true;
				$_response['msg']     	= 'Registros encontrados: ' . count( $_result );

				foreach ($_result as $key => $value) {
					// Inclusion de Areas configuradas para el ejecutivo
					$params_2 = array(
						'eje_id' => array( 'value' => $value['eje_id'], 'type' => 'INT' )
					);

					$_areas = $this->conn->Query( "EJE_AREA_EJECUTIVO_SP", $params_2 );
					$_result[ $key ]['Areas'] = $_areas;

					// Inclusion de clientes asignados al ejecutivo
					$_clientes = $this->conn->Query( "CLI_POR_EJECUTIVO_SP", $params_2 );
					foreach( $_clientes as $llave => $valor ){
						// Inclusion de areas asignados a los clientes
						$params_3 = array(
							'cli_id' => array( 'value' => $valor['cli_id'], 'type' => 'INT' )
						);
						$areasCliente = $this->conn->Query( "CLI_POR_EMPRESA_AREAS_SP", $params_3 );
						$_clientes[ $llave ]['Areas'] = $areasCliente;
					}
					$_result[ $key ]['Clientes'] = $_clientes;

					// Asignacion de las areas que los clientes se les ha configurado
					$areasClientesTodos = $this->conn->Query( "ARE_CLIENTES_TODOS_BY_EJE_SP", $params_2 );
					$_result[ $key ]['AreasClientes'] = $areasClientesTodos;
				}

				$_response['data'] 		= $_result;
			}
			else{
				$_response['msg']     	= 'No se encontraron resultados para tu solicitud.';	
			}			
		}
		
		return $this->Request( $_response );
	}

	public function cambioEstatus(){
		$_response['success'] = false;
		if( empty( $this->eje_id ) ){
			$_response['msg']     	= 'No se ha especificado la clave del Ejecutivo.';
		}
		else if( empty( $this->ese_id ) ){
			$_response['msg']     	= 'No se ha especificado el estatus al que se cambiara.';
		}
		else{
			$params = array(
				'idEje' => array( 'value' => $this->eje_id, 'type' => 'INT' ),
				'idEstatus' => array( 'value' => $this->ese_id, 'type' => 'INT' )
			);

			$_result = $this->conn->Query( "EJE_CAMBIO_ESTATUS_SP", $params );
			$_response['success'] = true;
			$_response['msg']     = 'Estatus actualizado correctamente.';
			$_response['data']	  = $_result;
		}
		
		return $this->Request( $_response );
	}

	public function updateInfo(){
		$_response['success'] = false;
		if( empty( $this->idEjecutivo ) ){
			$_response['msg']     	= 'No se ha especificado la clave del ejecutivo.';
		}
		else if( empty( $this->eje_nombre ) ){
			$_response['msg']     	= 'Se debera especificar el nombre del ejecutivo.';
		}
		else if( empty( $this->eje_email ) ){
			$_response['msg']     	= 'Se debe proporcionar el email del ejecutivo.';
		}
		else if(!filter_var($this->eje_email, FILTER_VALIDATE_EMAIL)){
			$_response['msg']     	= 'El email proporcionado no cuenta con el formato requerido.';
		}
		else if( empty( $this->eje_celular ) ){
			$_response['msg']     	= 'El télefono celular es un campo necesario para la operación.';
		}
		else{
			$params = array(
				'idEjecutivo'  => array( 'value' => $this->idEjecutivo, 'type' => 'INT' ),
				'eje_nombre'   => array( 'value' => $this->eje_nombre, 'type' => 'STRING' ),
				'eje_email'    => array( 'value' => $this->eje_email, 'type' => 'STRING' ),
				'eje_telefono' => array( 'value' => $this->eje_telefono, 'type' => 'STRING' ),
				'eje_celular'  => array( 'value' => $this->eje_celular, 'type' => 'STRING' )
			);

			$_result = $this->conn->Query( "EJE_ACTUALIZAR_INFO_SP", $params );
			$_response['success'] = true;
			$_response['msg']     = 'Los datos del ejecutivo se han guardado.';
			$_response['data']	  = $_result;
		}
		
		return $this->Request( $_response );
	}

	public function asignarArea(){
		$_response['success'] = false;
		if( empty( $this->idEjecutivo ) ){
			$_response['msg']     	= 'No se ha especificado la clave del ejecutivo.';
		}
		else if( empty( $this->idArea ) ){
			$_response['msg']     	= 'Se debera especificar el área a asignar.';
		}
		else if( empty( $this->estatus ) ){
			$_response['msg']     	= 'Se debe especificar el estatus del área asignada.';
		}
		else{
			$params = array(
				'idEjecutivo' => array( 'value' => $this->idEjecutivo, 'type' => 'INT' ),
				'idArea'      => array( 'value' => $this->idArea, 	   'type' => 'INT' ),
				'estatus'     => array( 'value' => $this->estatus,	   'type' => 'INT' )
			);


			$_result = $this->conn->Query( "EJE_ASIGNAR_AREA_SP", $params );
			// print_r($_result);
			$_response['success'] = true;
			$_response['msg']     = 'Area asignada.';
			$_response['data']	  = $_result;
		}
		
		return $this->Request( $_response );
	}

	public function nuevoEjecutivo(){
		$_response['success'] = false;
		if( empty( $this->emp_id ) ){
			$_response['msg']     	= 'No se ha especificado el Id de la empresa.';
		}
		else if( empty( $this->eje_nombre ) ){
			$_response['msg']     	= 'No se ha especificado el nombre del ejecutivo.';
		}
		else if( empty( $this->eje_celular ) ){
			$_response['msg']     	= 'Se debe especificar el número de celular, para esta operación este campo es obligatorio.';
		}
		else if(!filter_var($this->eje_email, FILTER_VALIDATE_EMAIL)){
			$_response['msg']     	= 'El email proporcionado no cuenta con el formato requerido.';
		}
		else{
			$key = md5( date("Y-m-d H:i:s") );
			$params = array(
				'_nombre' 	=> array( 'value' => $this->eje_nombre,   'type' => 'STRING' ),
				'_telefono' => array( 'value' => $this->eje_telefono, 'type' => 'STRING' ),
				'_celular'  => array( 'value' => $this->eje_celular,  'type' => 'STRING' ),
				'_email'    => array( 'value' => $this->eje_email,	  'type' => 'STRING' ),
				'_idEmp'    => array( 'value' => $this->emp_id,	      'type' => 'INT' ),
				'_key'      => array( 'value' => $key,				  'type' => 'STRING' )
			);

			$_result = $this->conn->Query( "EJE_INSERTAR_NUEVO_SP", $params );

			$_response = $_result[0];
			$password = $_response['pass'];

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