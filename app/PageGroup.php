<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    App\Image,
    App\Page, 
    Webpatser\Uuid\Uuid,
    App\Utilities\Functions\Functions,
    App\Utilities\ContainerTransforms,
    App\Utilities\TransformableContainer;


class PageGroup extends Model implements TransformableContainer
{
    use ContainerTransforms {
        ContainerTransforms::toTableArray as private traitToTableArray;
    }
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_groups';

    static public function createNew(
        string $name, int $order = -1, bool $retObj = false
    ) {
        $tmp = self::where('name', $name)->get();
        if (!Functions::testVar($tmp) || !Functions::countHas($tmp)) {
            if ($order < 1) {
                $o = self::getCount() + 1;
            } else {
                self::orderAround($order);
                $o = $order;
            }
            $data = new self;
            $data->name = $name;
            $data->order = $o;
            $data->uuid = Uuid::generate(5, '/menus/' . $name, Uuid::NS_URL);
            if ($data->save()) {
                return $retObj ? $data : $data->id;
            }
        }
        return null;
    }

    public function updateWith(
        string $name, int $order = -1, bool $retObj = false
    ) {
        $tmp = self::where(
            [
                ['name', '=', $name],
                ['order', '=', $order],
                ['id', '<>', $this->id],
            ]
        )->get();
        if (!Functions::testVar($tmp) || !Functions::countHas($tmp)) {
            if ($order !== $this->order && $order >= 1) { 
                self::orderAround($order);
                $this->order = $order;
            }
            if (Functions::testVar($name) && $name !== $this->name) {
                $this->name = $name;
            }
            //$data->uuid = Uuid::generate(5, '/menus/' . $name, Uuid::NS_URL);
            if ($this->save()) {
                return $retObj ? $this : $this->id;
            }
        }
        return null;
    }

    public function toTableArray(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true,
        bool $fullUrl = false
    ) {
        $content = $this->traitToTableArray(
            $baseUrl, $version, $useTitle, $withTrashed,
            $fullUrl
        );
        $content['order'] = $this->order;
        return $content;
    }

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

    static public function getAllGroups(
        bool $toArray = true, string $dir = 'asc',
        string $baseUrl = '', bool $withTrashed = true, 
        bool $fullUrl = false, bool $useBaseMaker = false, 
        int $version = 1, bool $useTitle = true
    ) {
        $tmp = self::orderBy('order', $dir)->get();
        if (Functions::countHas($tmp)) {
            if ($toArray) {
                $res = [];
                foreach ($tmp as $g) {
                    $res[] = $g->toContentArrayPlus(
                        $baseUrl, $version, 
                        $useTitle, $withTrashed, 
                        $fullUrl, $useBaseMaker,
                        $dir
                    );
                }
                return $res;
            } else {
                return $tmp;
            }
        }
        return null;
    }

    /**
     * Function getRandId - get a random ID of an existing PageGroup
     *                      if there are none create and return one 
     *                      using $newName for the new PageGroup's name.
     *                      Returns null on Failure.
     *
     * @param string $newName - the name to be used for the newly created 
     *                          Group, if one had to be created.
     * @param bool $withTrashed - if to use the soft deleted Groups in the 
     *                            search for a random id to return.
     * 
     * @return int|null
     */
    static public function getRandId(
        string $newName = 'Pages', bool $withTrashed = false
    ) {
        $n = self::getCount($withTrashed);
        if ($n > 0) {
            return $n !== 1 ? random_int(1, $n) : 1;
        } else {
            $ng = self::getFrom($newName);
            if (Functions::testVar($ng)) {
                return $ng->id;
            } elseif (Functions::testVar($newName)) {
                $tng = self::createNew($newName, -1, true);
                if (Functions::testVar($tng)) {
                    return $tng->id;
                }
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

    /**
     * @deprecated any
     *
     * @param [type] $page
     * @param string $dir
     * @param boolean $withTrashed
     * @return void
     */
    private function pageInGroup($page, string $dir = 'asc', bool $withTrashed = false)
    {
        return Page::getOrderedByFor(
            $this->pages(), $dir, $withTrashed, null
        )->where('id', '<>', Page::getIdFrom($page, false, null))->get();
    }

    /// trait overides and interface implementations..

    static public function createNewFrom(array $array, bool $retObj = false)
    {
        return self::createNew(
            $array['name'], $array['order']??-1,
            $retObj
        );
    }

    public function getChildrenQuery()
    {
        return $this->pages();
    }

    static public function getChildrenFor(
        $args, string $baseUrl = 'store', $transform = null, 
        bool $useTitle = true, int $version = 1, 
        bool $withTrashed = true, bool $fullUrl = false, 
        $default = [], bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        return Page::getFor(
            $args, $baseUrl, $transform, 
            $useTitle, $version, $withTrashed, 
            $fullUrl, $default, $useBaseMaker,
            $dir
        );
    }

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
        return 'order';
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
        return $this->uuid;
    }

    static public function getUrlByKey()
    {
        return 'uuid';
    }

    static public function getChildrenOrderByKey()
    {
        return Page::getOrderByKey();
    }
    
    public function toContentArrayPlus(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false, bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        $res = $this->getChildren(
            self::TO_URL_LIST_TRANSFORM, $withTrashed,
            $dir, $baseUrl, $useTitle, $fullUrl, $version,
            [], $useBaseMaker
        );
        return self::makeContentArray(
            $this->id, $this->name, 
            $this->order, $res, $useBaseMaker, 
            $this->getFullUrl($baseUrl, $fullUrl),
            $this->getImageArray(), $this->getDatesArray()
        );
    }

}
