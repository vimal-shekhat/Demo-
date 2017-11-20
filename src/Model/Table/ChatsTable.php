<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Validation\Validator;

class ChatsTable extends Table {
	public function initialize(array $config) {
		$this->belongsTo('Sender', ['foreignKey' => 'fromid','className' => 'Users']);
		$this->belongsTo('Receiver', ['foreignKey' => 'toid','className' => 'Users']);
	}
}
