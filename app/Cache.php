<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Cache extends Model {

    protected $table = 'caches';

    protected $fillable = ['identifier', 'data', 'username'];

    protected $primaryKey = 'identifier';

    static function createOrUpdate($data, $keys) {
        $record = self::where($keys)->first();
        if (is_null($record)) {
            return self::create($data);
        } else {
            return self::where($keys)->update($data);
        }
    }

}
