<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Database\Eloquent\SoftDeletes,
    App\Image,
    App\Utilities\Functions\Functions,
    App\Utilities\ContainerTransforms,
    App\Utilities\TransformableContainer,
    App\Utilities\ContainerAPI,
    App\Utilities\ContainerID;


class PageGroup extends Model implements TransformableContainer, ContainerAPI
{
    use SoftDeletes, ContainerTransforms, ContainerID;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_groups';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function orderAround(int $order)
    {
        $tmp = self::where('order', '>=', $order)->get();
        if (Functions::testVar($tmp) && count($tmp) > 0) {
            foreach ($tmp as $t) {
                if ($t->order >= $order) {
                    $t->order += 1;
                    $t->save();
                }
            }
        }
    }

    static public function createNew(
        string $name, int $order = -1, bool $retObj = false
    ) {
        $tmp = self::where('name', $name)->get();
        if (!Functions::testVar($tmp) || count($tmp) === 0) {
            if ($order < 1) {
                $o = self::count() + 1;
            } else {
                self::orderAround($order);
                $o = $order;
            }
            $data = new self;
            $data->name = $name;
            $data->order = $o;
            if ($data->save()) {
                return $retObj ? $data : $data->id;
            }
        }
        return null;
    }

    static public function createNewFrom(array $array, bool $retObj = false)
    {
        return self::createNew(
            $array['name'], $array['order']??-1,
            $retObj
        );
    }

    static public function getFrom($item) 
    {
        if (is_string($item) && Functions::testVar($item)) {
            $tmp = self::where('name', $item)->first();
        } elseif (is_int($item) && Functions::testVar($item)) {
            $tmp = self::where('id', $item)->first();
        } elseif ($item instanceof self) {
            $tmp = $item;
        } else {
            $tmp = null;
        }
        return $tmp;
    }

    static public function getIdFrom($pg)
    {
        if (Functions::testVar($pg)) {
            if (is_int($pg) && self::exists($pg)) {
                return $pg;
            } elseif (is_string($pg)) {
                $t = self::getFrom($pg);
                return Functions::testVar($t) ? $t->id : null;
            } elseif ($pg instanceof self) {
                return $pg->id;
            }
        }
        return null;
    }

    static public function exists($pg)
    {
        return Functions::testVar(self::getFrom($pg));
    }

    /**
     * Function getRandId - get a random ID of an existing PageGroup
     *                      if there are none create and return one 
     *                      using $newName for the new PageGroup's name.
     *                      Returns null on Failure.
     *
     * @param string $newName 
     * @return int|null
     */
    static public function getRandId(string $newName = 'Pages')
    {
        $n = self::count();
        if ($n > 0) {
            return $n !== 1 ? random_int(1, $n) : 1;
        } else {
            if (Functions::testVar($ng = self::getFrom($newName))) {
                return $ng->id;
            } elseif (Functions::testVar($newName)) {
                $tng = self::createNew($newName);
                return Functions::testVar($tng) ? $tng : null;
            }
        }
        return null;
    }

    public function pages()
    {
        return $this->hasManyThrough(
            'App\Page', 'App\PageGrouping',
            'group_id', 'id',
            'id', 'page_id'
        );
    }

    ///

    static public function getSelf(
        string $baseUrl = 'store', bool $withTrashed = true,
        bool $fullUrl = false, $children = [], 
        $paginator = null, string $pagingFor = ''
    ) {
        $title = $name = 'Menu Content';
        $article = [];
        $img = Image::createImageArray(
            'menu-3167859_1920.jpg', 'Menu Content Listing', 
            'images/site', 'Menu Content Listing'
        );
        $pagingFor = $pagingFor ?: 'menusPanel';
        return self::makeSelf(
            $name, $title, $article,
            $img, $baseUrl, $withTrashed,
            $fullUrl, $children, $paginator,
            $pagingFor, null
        );
    }

    static public function getOrderByKey()
    {
        return 'id';
    }

    static public function genUrlFragment(string $baseUrl, bool $fullUrl = false)
    {
        $url = empty($baseUrl)
            ? 'menus/' 
            : $baseUrl . '/menus/';
        return $fullUrl ? url($url) : $url;
    }

    public function getImageArray()
    {
        return Image::createImageArray(
            'app-menu-2155443_1280.png', 'Menu Listing', 'images/site',
            'Menu Listing'
        );
    }

    public function getUrl()
    {
        return $this->name;
    }

    static public function getUrlByKey()
    {
        return 'name';
    }

    static public function makeContentArray(
        int $id, string $name, int $order, array $pages = null, 
        bool $useBaseMaker = true, string $url = '', $img = null,
        array $dates = null
    ) {
        if ($useBaseMaker) {
            $content = self::makeBaseContentArray(
                $name, $url, $img, null, $name, 
                $dates, null, $pages, 
                Functions::countHas($pages), true, ''
            );
            $content['value']['order'] = $order;
        } else {
            $content = [
                'id' => $id,
                'name' => $name,
                'order' => $order,
                'pages' => $pages
            ];
        }
        return $content;
    }

    public function numChildren(bool $withTrashed = true)
    {
        return $withTrashed 
        ? $this->pages()->withTrashed()->count()
        : $this->pages()->count();
    }

    public function getChildren(
        $transform = null, bool $withTrashed = true, 
        string $dir = 'asc', string $baseUrl = 'store',
        bool $useTitle = true, bool $fullUrl = false, 
        int $version = 1, $default = [], bool $useBaseMaker = true,
        bool $done = true
    ) {
        $tmp = self::getOrderedFor(
            $this->pages(), $dir, 
            $withTrashed, 'order'
        );
        return Page::getFor(
            $tmp, $baseUrl, $transform, $useTitle,
            $version, $withTrashed, $fullUrl, $default, 
            $useBaseMaker, $done, $dir
        );
    }
    
    public function toContentArrayPlus(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false, bool $useBaseMaker = true,
        bool $done = true, string $dir = 'asc'
    ) {
        $res = $this->getChildren(
            self::TO_URL_LIST_TRANSFORM, $withTrashed,
            $dir, $baseUrl, $useTitle, $fullUrl, $version,
            [], $useBaseMaker, false
        );
        return self::makeContentArray(
            $this->id, $this->name, 
            $this->order, $res, $useBaseMaker, 
            $this->getFullUrl($baseUrl, $fullUrl),
            $this->getImageArray(), $this->getDatesArray()
        );
    }

    static public function getAllGroups(
        bool $toArray = true, string $dir = 'asc',
        string $baseUrl = '', bool $withTrashed = true, 
        bool $fullUrl = false, bool $useBaseMaker = false, 
        int $version = 1
    ) {
        $tmp = self::orderBy('order', $dir)->get();
        if (Functions::countHas($tmp)) {
            if ($toArray) {
                $res = [];
                foreach ($tmp as $g) {
                    $res[] = $g->toContentArrayPlus(
                        $baseUrl, $version, 
                        false, $withTrashed, 
                        $fullUrl, $useBaseMaker,
                        true, $dir
                    );
                }
                return $res;
            } else {
                return $tmp;
            }
        }
        return null;
    }

}
