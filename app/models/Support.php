<?php

class Support extends \Eloquent{
	protected $fillable = ['ticket_id','user_id', 'type', 'status', 'message', 'created_at', 'response', 'response_date'];
	protected $table = 'support';
    
    public function insert($data)
    {
            return DB::table('support')->insert($data);
    }
    
    public function show_data()
    {
            return DB::table('support')->paginate(5);
    }
}
