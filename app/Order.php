<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Support\Facades\Hash,
    App\Utilities\Functions\Functions,
    App\Utilities\ContainerTransforms,
    App\Utilities\TransformableContainer,
    App\Utilities\ContainerAPI,
    App\Utilities\ContainerID;
    use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model implements TransformableContainer, ContainerAPI
{
    use SoftDeletes, ContainerTransforms, ContainerID;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function createNew(
        $user, $total, $content = [], 
        $comments = [], string $status = '', 
        bool $retObj = false
    ) {
        /*
            $table->integer('user_id')->unsigned();
            $table->decimal('total', 12, 2)->nullable();
            $table->string('status', 255);
            $table->mediumText('content');
            $table->mediumText('comments');
            $table->string('verihash', 255)->nullable();
        */
        $data = new self;
        $uid = User::getUserId($user);
        $data->user_id = $uid;
        $data->total = is_float($total) || is_int($total) ? $total : 0;
        $data->uuid = Uuid::generate(5, '/orders/' . $email, Uuid::NS_URL);
        $cnTmp = base64_encode(serialize(Functions::getVar($content, [])));
        $data->content = $cnTmp;
        $cmTmp = base64_encode(serialize(Functions::getVar($comments, [])));
        $data->comments = $cmTmp;
        $data->verihash = Hash::make($cnTmp . $cmTmp);
        if ($data->save()) {
            return $retObj
            ? $data
            : $data->id;
        } else {
            return null;
        }
    }

    static public function createNewFrom(array $array, bool $retObj = false)
    {
        return self::createNew(
            $array['user'], $array['total'], $array['content'], 
            $array['comments'], $array['status'], $retObj
        );
    }

    public function validate()
    {
        //
    }

    ///  the Eloquent Relationship methods:

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /// end of the Eloquent Relationship methods.
}
