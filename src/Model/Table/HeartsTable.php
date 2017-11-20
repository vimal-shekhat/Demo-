<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Validation\Validator;

class HeartsTable extends Table {

    public function initialize(array $config) {
		$this->belongsTo('Users',['foreignKey' => 'fromid']);
    }
}
