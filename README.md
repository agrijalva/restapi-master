Este es un pequeño backend utilizando la libreria Slim

# restAPI

Para su funcionameinto se debera considerar las siguientes correcciones:

## Config

Primero inicializar con la configuracion de las rutas de las carpetas de los controles
que se encuentra en la siguiente ruta: __include/config.php__

    define('APP_PATH', $_SERVER['DOCUMENT_ROOT'] . '/pfiscal/restapi/v1/app');
    define('LIB_PATH', $_SERVER['DOCUMENT_ROOT'] . '/pfiscal/restapi/libs');
    
Estas direcciones se ven reflejadas en el archivo de inclución de librerias en __Autoload__

## Conexión a mySQL

Este API establece una conexión con mySQL como manejador de base de datos y para ello es necesario
realizar la configuracion en la siguiente archivo __v1/app/model/Connection.class.php__ en el se
debera sustituir los datos por los da cada servidor de las siguientes variables:

    $this->BaseDatos = "asesoria";
    $this->Servidor  = "localhost";
    $this->Usuario 	 = "root";
    $this->Clave	 = "";
    
## Estructura de una clase

Para poder realizar las reglas de negocio, se debera utilizar la siguiente estructura en una clase:

    <?php
      class Cliente
      {
        var $Return_Type;
        var $conn;

        var $mi_variable;

        public function __construct( $Class_Properties = array() ) {
          $this->Assign_Properties_Values($Class_Properties);
          $this->conn = new Connection();
          $this->Return_Type = 'json';
        }

        public function MiFuncion(){
          $_response['success'] = false;
          if( empty( $this->mi_variable ) ){
            $_response["msg"] = "Favor de proporcionar el valor de la variable.";
          }
          else{
            $params = array(
              "mi_variable" => array( "value" => $this->mi_variable, "type" => "INT" )
            );

            $_result = $this->conn->Query( "CLI_UPD_EDITAR_SP", $params );
            $_response = $_result;
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
    
Las funciones __Assign_Properties_Values__ y __Request__ son necesarias para el funcionamiento de la aplicación 
para la recepción de datos y para la respuesta en JSON.
