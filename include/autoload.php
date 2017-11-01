<?php
spl_autoload_register(function ($classname) {
    $file_lib 		= LIB_PATH . '/' . $classname . '.class.php';
	$file_model 	= APP_PATH . '/model/' . $classname . '.class.php';
	$file_data 		= APP_PATH . '/data/' . $classname . '.class.php';
	
	if( file_exists( $file_lib ) ){
		require( $file_lib );
	}
	else if( file_exists( $file_model ) ){
		require( $file_model );
	}
	else if( file_exists( $file_data ) ){
		require( $file_data );
	}
});
?>