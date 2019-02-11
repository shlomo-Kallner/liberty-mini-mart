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

class Order extends Model implements TransformableContainer
{
    use ContainerTransforms;

    static public function createNew(
        $user, $total, $content = [], 
        $comments = [], string $status = '', 
        bool $retObj = false
    ) {
        /*
            $table->integer('user_id')->unsigned();
            $table->decimal('total', 12, 2)->nullable();
            $table->mediumText('content');
            $table->string('verihash', 255)->nullable();
            $table->uuid('uuid');
            $table->string('status', 255);
            $table->mediumText('comments');
        */
        $u = User::getFrom($user, false);
        $total = is_float($total) || is_int($total) ? $total : 0;
        if (Functions::testVar($u) && $total > 0 
            && Functions::testVar($content) && Functions::countHas($content)
        ) {
            $uid = User::getIdFrom($u, false, 0);
            $data = new self;
            $data->user_id = $uid;
            $data->total = $total;
            $uuid = Uuid::generate(5, '/orders/' . $u->getUrl(), Uuid::NS_URL);
            $data->uuid = $uuid;
            $cnTmp = base64_encode(serialize(Functions::getVar($content, [])));
            $data->content = $cnTmp;
            $data->verihash = Hash::make($uid . $total . $uuid . $cnTmp);
            $data->status = $status;
            $data->comments = base64_encode(serialize(Functions::getVar($comments, [])));
            if ($data->save()) {
                return $retObj ? $data : $data->id;
            } 
        }
        return null;
    }

    public function updateWith(
        $comments = [], string $status = '', bool $retObj = false
    ) {
        if (Functions::testVar($comments) && Functions::countHas($comments)) {
            $this->comments = base64_encode(serialize($comments));
        }
        if (Functions::testVar($status)) {
            $this->status = $status;
        }
        if ($this->save()) {
            return $retObj ? $this : $this->id;
        }
        return null;
    }

    public function getComments()
    {
        return unserialize(base64_decode($this->comments));
    }

    public function getContent()
    {
        return unserialize(base64_decode($this->content));
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
        // $data->verihash = Hash::make($uid . $total . $uuid . $cnTmp);
        $plain = $this->user_id . $this->total . $this->uuid . $this->content;
        return Hash::check($plain, $this->verihash);
    }

    public function getPrice()
    {
        return $this->total;
    }

    public function getImageArray()
    {
        //
    }

    public function getParentUrl(string $baseUrl, bool $fullUrl = false)
    {
        return $this->user->getFullUrl($baseUrl, $fullUrl);
    }

    static public function genUrlFragment(string $baseUrl, bool $fullUrl = false)
    {
        $surl = empty($baseUrl) ? 'orders/' : $baseUrl . '/orders/';
        return $fullUrl ? url($surl) : $surl;
    }

    static public function getOrderByKey()
    {
        return 'created_at';
    }

    static public function getNamedByKey()
    {
        return 'uuid';
    }

    static public function getUrlByKey()
    {
        return 'uuid';
    }

    public function getPubId()
    {
        return $this->uuid;
    }

    public function getPubName()
    {
        return $this->uuid;
    }

    public function getUrl()
    {
        return $this->uuid;
    }

    ///  the Eloquent Relationship methods:

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /// end of the Eloquent Relationship methods.
}
