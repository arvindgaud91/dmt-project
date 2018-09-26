<?php

class DmtVendor extends \Eloquent {
	protected $fillable = ['user_id', 'bc_agent', 'type', 'session_token', 'timeout', 'balance', 'parent_id','asm_id'];
	protected $table = 'dmt_vendors';

	public static function type ($type)
	{
		$dict = [
        1 => [
            'id' => 1,
            'type' => 'agent',
            'parent_type_id' => 2,
            'parent' => 'distributor'
        ],
        2 => [
            'id' => 2,
            'type' => 'distributor',
            'parent_type_id' => 3,
            'parent' => 'super distributor',
						'child_type_id' => 1,
						'child' => 'agent'
        ],
        3 => [
            'id' => 3,
            'type' => 'super distributor',
						'child_type_id' => 2,
						'child' => 'distributor'
        ],
        4 => [
            'id'            => 4,
            'type'          => 'sales executive',
            'parent_type_id'=> 5,
            'parent'        => 'area sales officer'
        ],
        5 => [
            'id'            => 5,
            'type'          => 'area sales officer',
            'parent_type_id'=> 6,
            'parent'        => 'area sales manager',
            'child_type_id' => 4,
            'child'         => 'sales executive'
        ],
        6 => [
            'id'            => 6,
            'type'          => 'area sales manager',
            'parent_type_id'=> 7,
            'parent'        => 'cluster head',
            'child_type_id' => 5,
            'child'         => 'area sales officer'
        ],
        7 => [
            'id'            => 7,
            'type'          => 'cluster head',
            'parent_type_id'=> 10,
            'parent'        => 'state head',
            'child_type_id' => 6,
            'child'         => 'area sales manager'
        ],
        10 => [
            'id'            => 10,
            'type'          => 'state head',
            'child_type_id' => 7,
            'parent_type_id'=> 11,
            'parent'        => 'regional head',
            'child'         => 'cluster head'
        ],
        11 => [
            'id'            => 11,
            'type'          => 'regional head',
            'child_type_id' => 10,
            'child'         => 'state head' 
        ]
      ];
      return $dict[$type];
	}

	public function user ()
	{
		return $this->belongsTo('User');
	}

}


