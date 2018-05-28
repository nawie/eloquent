# Stand alone database wrapper for laravel with additional support for Oracle DB driver 
  using [yajra/laravel-oci8](https://github.com/yajra/laravel-oci8)

## Laravel-OCI8

Laravel-OCI8 is an Oracle Database Driver package for [Laravel](http://laravel.com/). Laravel-OCI8 is an extension of [Illuminate/Database](https://github.com/illuminate/database) that uses [OCI8](http://php.net/oci8) extension to communicate with Oracle. Thanks to @taylorotwell.


## Quick Installation

- Add repository on composer.json

```bash
"repositories": [{
    "type": "vcs",
    "url": "http://github.com/nawie/eloquent"
}],
"require": {
    "nawie/eloquent": "dev-master"
},
```

Then

```bash
composer update
```

## Configuration

Edit file on src/Config/Connection.php, and provide your database connection there, then using in project
```php
use function Nawie\Eloquent\Config\Oracle as configOracle;
```

Or you can add specific configuration on initialization using php array


```php
'oracle' => [
    'driver'        => 'oracle',
    'tns'           => env('DB_TNS', ''),
    'host'          => env('DB_HOST', ''),
    'port'          => env('DB_PORT', '1521'),
    'database'      => env('DB_DATABASE', ''),
    'username'      => env('DB_USERNAME', ''),
    'password'      => env('DB_PASSWORD', ''),
    'charset'       => env('DB_CHARSET', 'AL32UTF8'),
    'prefix'        => env('DB_PREFIX', ''),
    'prefix_schema' => env('DB_SCHEMA_PREFIX', ''),
],
'sqlite' => [
    'driver'   => 'sqlite',
    'database' => __DIR__ . '/dummy.sqlite3',
    'prefix'   => ''
]

```

## Usage

When using multiple database connection, we can provide name of each connection. just add connection name after configuration arguments.
Default name will be named as 'default', then call bootstrap to register

```php
use Nawie\Eloquent\Instance;
use function Nawie\Eloquent\Config\Oracle as configOracle;
use function Nawie\Eloquent\Config\Sqlite as configSqlite;

$db = new Instance;
$db::addConnection(configOracle(), 'default');
$db::addConnection(configSqlite(), 'sqlites');
$db::bootstrap();
```

## Using Model
```php

use \Illuminate\Database\Eloquent\Model;

class pcode_sqlite extends Model {
    public $timestamps = false;   // disable timestamps fields {created_at, updated_at}
    public $incrementing = false; // disable auto increment id

    protected $connection = 'sqlites';
    protected $primaryKey = 'code';
    protected $table = 'error_message';
    protected $fillable = ['code', 'description'];
}

class pcode_oracle extends Model {
    public $timestamps = false;
    public $incrementing = false;

    protected $connection = 'default';
    protected $primaryKey = 'ROWID';
    protected $table = 'P_CODE';
    protected $fillable = ['CODE', 'DESCRIPTION'];
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

// insert sqlite
$insert = PCODE::create(['code'=>$last_index,'description'=>'default']);

$last_insert = array(
    'id' => $insert->getKey(),
    'columns'=>$insert->getKeyName()
);

print_r($last_insert);


// get data

print_r(pcode_sqlite::get()->toArray());
print_r(pcode_oracle::get()->toArray());


// via query
$data = $db::getConnection('sqlites')
                ->table('error_message')
                ->select('code','description')
                ->get();

print_r($data->toJson());
```

## Credits

- [yajra/laravel-oci8](https://github.com/yajra/laravel-oci8)
- All Original Contributors of yajra/laravel-oci8

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

