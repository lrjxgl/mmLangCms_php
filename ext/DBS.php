<?php

namespace ext;

class DBS{

    

	public static function MM($module,$table){

    	$model="\app\\".$module."\model\\".ucwords($table)."Model";

    	return  new $model();

    }

}