<?php

class geko_model extends Doctrine_Record{

	public function hasMany($componentName, $options) {
    	return parent::hasMany($componentName, $options);
    }

};
