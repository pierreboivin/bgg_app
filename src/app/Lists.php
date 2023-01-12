<?php namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Lists extends Model
{
    use Sluggable, SluggableScopeHelpers;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'data', 'user_id'];

    public static $rules = array(
        'name' => 'required',
        'data' => 'required'
    );

    public static function findAllOnlyAccess()
    {
        if (Auth::user()->type == 'admin') {
            return self::all();
        } else {
            return self::where('user_id', Auth::user()->id)->get();
        }
    }

    public static function findOnlyAccess($id)
    {
        $list = self::find($id);
        if (Auth::user()->type == 'admin' || ($list && $list->user_id == Auth::user()->id)) {
            return $list;
        }
        return null;
    }

    /**
     * @return array
     */
    public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

}
