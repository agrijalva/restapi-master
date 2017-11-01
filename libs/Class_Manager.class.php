<?php

class Class_Manager {
	public $Class_Name;
	public $Class_Method;
	public $Arguments;
	
	public function __construct($Class_Name, $Class_Method, $Arguments) {
		$this->Class_Name = $Class_Name;
		$this->Class_Method = $Class_Method;
		
		$Arguments = $this->Clear_Variables($Arguments, "Class_Name");
		$Arguments = $this->Clear_Variables($Arguments, "Class_Method");
		
		$this->Arguments = $Arguments;
		
		$this->Instance_Class();
	}
	
	private function Instance_Class(){
		if(!class_exists($this->Class_Name)) 
			return false;
		
		$Class_Instance = new $this->Class_Name($this->Arguments);
		
		$Class_Method = $this->Class_Method;
		
		if(!method_exists($Class_Instance, $Class_Method))
			return false;
			
		$Class_Instance->$Class_Method();
	}
	
	private function Clear_Variables($Array, $Key){
		unset($Array[$Key]);
		
		return $Array;
	}
}
?>