<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Auth;



class Letter
{

    public static function year(){
        return date('Y');
    }

    public static function getLetter_PKS()
    {
        return DB::table('req_letter_number_pks as a')
        ->where('letter_number', 'LIKE', '%'.Letter::year().'%');
    }

    public static function getMyLetter_PKS()
    {
        return DB::table('req_letter_number_pks')
        ->where('created_by','=',Auth::user()->uuid);

    }

    public static function getReqLetterPKS($id)
    {
        return DB::table('req_letter_number_pks as a')
            ->where('a.id', '=', $id)
            ->select('a.*')
            ->first();
    }    
    
    public static function getLetter_OPS()
    {
        return DB::table('req_letter_number_memo_ops')
        ->where('letter_number', 'LIKE', '%'.Letter::year().'%');
    }

    public static function getMyLetter_OPS()
    {
        return DB::table('req_letter_number_memo_ops')
        ->where('created_by','=',Auth::user()->uuid);
    }

    public static function getReqLetterOPS($id)
    {
        return DB::table('req_letter_number_memo_ops as a')
            ->where('a.id', '=', $id)
            ->select('a.*')
            ->first();

    }    

    public static function getLetter_mrkt()
    {
        return DB::table('req_letter_number_memo_mrkt')
        ->where('letter_number', 'LIKE', '%'.Letter::year().'%');
    }

    public static function getMyLetter_mrkt()
    {
        return DB::table('req_letter_number_memo_mrkt')
        ->where('created_by','=',Auth::user()->uuid);
    }

    public static function getReqLetter_mrkt($id)
    {
        return DB::table('req_letter_number_memo_mrkt as a')
            ->where('a.id', '=', $id)
            ->select('a.*')
            ->first();
    }   
    
    public static function getLetter_it()
    {
        return DB::table('req_letter_number_memo_it')
        ->where('letter_number', 'LIKE', '%'.Letter::year().'%');
    }

    public static function getMyLetter_it()
    {
        return DB::table('req_letter_number_memo_it')
        ->where('created_by','=',Auth::user()->uuid);
    }

    public static function getReqLetter_it($id)
    {
        return DB::table('req_letter_number_memo_it as a')
            ->where('a.id', '=', $id)
            ->select('a.*')
            ->first();
    }  
    
    public static function getLetter_hr()
    {
        return DB::table('req_letter_number_memo_hr')
        ->where('letter_number', 'LIKE', '%'.Letter::year().'%');
    }

    public static function getMyLetter_hr()
    {
        return DB::table('req_letter_number_memo_hr')
        ->where('created_by','=',Auth::user()->uuid);
    }

    public static function getReqLetter_hr($id)
    {
        return DB::table('req_letter_number_memo_hr as a')
            ->where('a.id', '=', $id)
            ->select('a.*')
            ->first();
    }    


    public static function getLetter_sales()
    {
        return DB::table('req_letter_number_sales')
        ->where('letter_number', 'LIKE', '%'.Letter::year().'%');
    }

    public static function getMyLetter_sales()
    {
        return DB::table('req_letter_number_sales')
        ->where('created_by','=',Auth::user()->uuid);
    }

    public static function getReqLetter_sales($id)
    {
        return DB::table('req_letter_number_sales as a')
            ->where('a.id', '=', $id)
            ->select('a.*')
            ->first();
    }    

    public static function getLetter_out()
    {
        return DB::table('req_outgoing_mail')
        ->where('letter_number', 'LIKE', '%'.Letter::year().'%');
    }

    public static function getMyLetter_out()
    {
        return DB::table('req_outgoing_mail')
        ->where('created_by','=',Auth::user()->uuid);
    }
    
    public static function getReqLetter_out($id)
    {
        return DB::table('req_outgoing_mail as a')
            ->where('a.id', '=', $id)
            ->select('a.*')
            ->first();
    }    

    public static function getLetter_in()
    {
        return DB::table('incoming_mail');
    }

    public static function getMyLetter_in()
    {
        return DB::table('incoming_mail')
        ->where('created_by','=',Auth::user()->uuid);
    }

    public static function getReqLetter_in($id)
    {
        return DB::table('incoming_mail as a')
            ->where('a.id', '=', $id)
            ->select('a.*')
            ->first();
    }    

    public static function insert_pks($data)
    {
        $db = DB::connection();
        $id = $db->table('req_letter_number_pks')
            ->insert($data);          
    
    }

    public static function update_pks($data,$id)
    {
        $db = DB::connection();
        $id = $db->table('req_letter_number_pks')
        ->where('id','=', $id)
            ->update($data);          
    
    }

    public static function insert_ops($data)
    {
        $db = DB::connection();
        $id = $db->table('req_letter_number_memo_ops')
            ->insert($data);            
    }

    public static function update_ops($data,$id)
    {
        $db = DB::connection();
        $id = $db->table('req_letter_number_memo_ops')
        ->where('id','=', $id)
            ->update($data);          
    
    }

    public static function insert_mrkt($data)
    {
        $db = DB::connection();
        $id = $db->table('req_letter_number_memo_mrkt')
            ->insert($data);            
    }

    public static function update_mrkt($data,$id)
    {
        $db = DB::connection();
        $id = $db->table('req_letter_number_memo_mrkt')
        ->where('id','=', $id)
            ->update($data);          
    
    }

    public static function insert_it($data)
    {
        $db = DB::connection();
        $id = $db->table('req_letter_number_memo_it')
            ->insert($data);            
    }


    public static function update_it($data,$id)
    {
        $db = DB::connection();
        $id = $db->table('req_letter_number_memo_it')
        ->where('id','=', $id)
            ->update($data);          
    
    }
   
    public static function insert_hr($data)
    {
        $db = DB::connection();
        $id = $db->table('req_letter_number_memo_hr')
            ->insert($data);            
    }

    public static function update_hr($data,$id)
    {
        $db = DB::connection();
        $id = $db->table('req_letter_number_memo_hr')
        ->where('id','=', $id)
            ->update($data);          
    
    }

    public static function insert_sales($data)
    {
        $db = DB::connection();
        $id = $db->table('req_letter_number_sales')
            ->insert($data);            
    }

    public static function update_sales($data,$id)
    {
        $db = DB::connection();
        $id = $db->table('req_letter_number_sales')
        ->where('id','=', $id)
            ->update($data);          
    
    }

    public static function insert_out($data)
    {
        $db = DB::connection();
        $id = $db->table('req_outgoing_mail')
                ->insertGetId($data['out']);

        if($data['in']['incoming_mail'] != 0){
        $db->table('incoming_outgoing')
            ->insert(['outgoing_id' => $id, 'incoming_id' => $data['in']['incoming_mail']]);
        }
    }

    public static function update_out($data,$id)
    {
        $db = DB::connection();
        $id = $db->table('req_outgoing_mail')
        ->where('id','=', $id)
            ->update($data);          
    
    }

    public static function insert_in($data)
    {
        $db = DB::connection();
        $id = $db->table('incoming_mail')
            ->insert($data);            
    }

    public static function update_in($data,$id)
    {
        $db = DB::connection();
        $id = $db->table('incoming_mail')
        ->where('id','=', $id)
            ->update($data);          
    
    }

    public static function get_last_pks_no(){
        return DB::table('req_letter_number_pks')
		->where("letter_number","<>","")
		->orderBy("id","desc")
        ->limit(1,null)
		->select("*", DB::raw('YEAR(created_at) year'))
        ->get();
    }
    
    public static function get_last_ops_no(){
        return DB::table('req_letter_number_memo_ops')
		->where("letter_number","<>","")
		->orderBy("id","desc")
        ->limit(1,null)
        // ->select("*", DB::raw('YEAR(created_at) year'))
        ->select("*", DB::raw('MONTH(created_at) month'))
        ->get();
    }

    public static function get_last_mrkt_no(){
        return DB::table('req_letter_number_memo_mrkt')
		->where("letter_number","<>","")
		->orderBy("id","desc")
        ->limit(1,null)
		->select("*", DB::raw('YEAR(created_at) year'))
        ->get();
    }

    public static function get_last_it_no(){
        return DB::table('req_letter_number_memo_it')
		->where("letter_number","<>","")
		->orderBy("id","desc")
        ->limit(1,null)
		->select("*", DB::raw('YEAR(created_at) year'))
        ->get();
    }

    public static function get_last_hr_no(){
        return DB::table('req_letter_number_memo_hr')
		->where("letter_number","<>","")
		->orderBy("id","desc")
        ->limit(1,null)
		->select("*", DB::raw('YEAR(created_at) year'))
        ->get();
    }

    public static function get_last_sales_no(){
        return DB::table('req_letter_number_sales')
		->where("letter_number","<>","")
		->orderBy("id","desc")
        ->limit(1,null)
		->select("*", DB::raw('YEAR(created_at) year'))
        ->get();
    }

    public static function get_last_out_no(){
        return DB::table('req_outgoing_mail')
		->where("letter_number","<>","")
		->orderBy("id","desc")
        ->limit(1,null)
		->select("*", DB::raw('YEAR(created_at) year'))
        ->get();
    }

    public static function get_client_letter_number(){
        return DB::table('incoming_mail')
        ->where('client_letter_number',"<>","")
        ->orderBy("client_letter_number","asc")
		->select("*")
        ->get();
    }
}