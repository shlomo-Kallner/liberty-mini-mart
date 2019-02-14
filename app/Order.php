<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Support\Facades\Hash,
    App\Utilities\Functions\Functions,
    App\Utilities\NoOpEvent,
    App\Utilities\ContainerTransforms,
    App\Utilities\TransformableContainer,
    App\Utilities\ContainerAPI,
    App\Utilities\ContainerID;
use Darryldecode\Cart\Cart as DarrylCart;
use App\User,
    App\Cart;

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
        $total = is_float($total) || is_int($total) ? floatval($total) : 0.0;
        if (Functions::testVar($u) && $total > 0 
            && Functions::testVar($content) && Functions::countHas($content)
        ) {
            $uid = User::getIdFrom($u, false, 0);
            $data = new self;
            $data->user_id = $uid;
            $data->total = round($total, 2, PHP_ROUND_HALF_UP);
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

    static protected function genImage()
    {
        return Image::createImageArray(
            'edit-1105049_640.png', 'A Custumers order', 
            'images/site', 'A Custumers order', 0
        );
    }

    public function getImageArray()
    {
        return self::genImage();
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

    public function getContentAsCartArray(
        string $currencyIcon = 'fa-usd', $default = null
    ) {
        /*
                Cart::cartToArray(
                    DarrylCartCart $dcart = null, array $acart = null,
                    bool $asUrl = true, bool $asArray = true,
                    string $currencyIcon = 'fa-usd'
                )
             */
        if ($this->validate()) {
            $content = $this->getContent();
            $cart = new DarrylCart(
                collect($content), new NoOpEvent, '', 
                'order', config('shopping_cart')
            );
            return Cart::cartToArray(
                $cart, null, true, true, $currencyIcon
            );
        }
        return $default;
    }

    public function toContentArrayPlus(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false, bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        $url = $this->getFullUrl($baseUrl, $fullUrl);
        $name = $this->getPubName();
        $img = $this->getImageArray();
        $title = $this->getPubTitle();
        $dates = $this->getDatesArray();
        $article = [];
        $status = $this->status;
        $content = [];
        $comments = [];
        $total = round($this->total, 2, PHP_ROUND_HALF_UP);
        if ($useBaseMaker) {
            $contentArray = self::makeBaseContentArray(
                $name, $url, $img, $article, 
                $title, $dates, 
                [], [], false, true, ''
            );
            $contentArray['value']['content'] = $content;
            $contentArray['value']['comments'] = $comments;
            $contentArray['value']['status'] = $status;
            $contentArray['value']['total'] = $total;
            return $contentArray;
        } else {
            /
        }
    }

    static public function getChildrenFor(
        $args, string $baseUrl = 'store', $transform = null, 
        bool $useTitle = true, int $version = 1, 
        bool $withTrashed = true, bool $fullUrl = false, 
        $default = [], bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        return $default;
    }

    static public function getSelf(
        string $baseUrl = 'store', bool $withTrashed = true,
        bool $fullUrl = false, $children = [], 
        $paginator = null, string $pagingFor = ''
    );

    ///  the Eloquent Relationship methods:

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /// end of the Eloquent Relationship methods.
}
