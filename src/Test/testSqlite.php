<?php

namespace Nawie\Eloquent\Test;

require_once __DIR__ . DIRECTORY_SEPARATOR . '../../vendor/autoload.php';

use Nawie\Eloquent\Instance;
use function Nawie\Eloquent\Config\Sqlite as configSqlite;

$db = new Instance;
$db::addConnection(configSqlite(), 'sqlites');
$db::bootstrap();

/**
 * implementation of model
 */

use \Illuminate\Database\Eloquent\Model;

class PCODE extends Model {
    public $timestamps    = false;   // disable timestamps fields {created_at, updated_at}
    public $incrementing  = false; // disable auto increment id

    protected $connection = 'sqlites';
    protected $primaryKey = 'code';
    protected $table      = 'error_message';
    protected $fillable   = ['code', 'description'];
}

$last_index = 10;

// raw query
$conn = $db::getConnection('sqlites');
$last_records = $conn->select( $conn->raw("select code from error_message order by code desc limit :limit"), array(
    'limit'=>2
) );

if(!empty($last_records)){
    $last_index = $last_records[0]->code + 1; 
}

print_r($last_records);

// insert
$insert = PCODE::create(['code'=>$last_index,'description'=>'default']);

$last_insert = array(
    'id' => $insert->getKey(),
    'columns'=>$insert->getKeyName()
); 

print_r($last_insert);

// get data
print_r(PCODE::get()->toArray());

// via query
$data = $db::getConnection('sqlites')
                ->table('error_message')
                ->select('code','description')
                ->get();

print_r($data->toJson());