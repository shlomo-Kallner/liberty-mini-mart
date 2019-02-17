<?php

namespace App\Http\Controllers;

use App\Page,
    App\Cart,
    App\Order,
    App\User,
    App\Article,
    App\UserSession;
use Illuminate\Http\Request,
    App\Utilities\Functions\Functions,
    App\Rules\FieldIsUniqueRule,
    App\Rules\FieldIsUniqueOrEqualRule,
    Illuminate\Support\Facades\Log,
    Illuminate\Support\Facades\Validator,
    Illuminate\Support\Str;

class OrderController extends MainController
{
    
    public function __construct($name = '', $titleNameSep = ' | ') 
    {
        parent::__construct($name, $titleNameSep);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ordersData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'ordersPanel',
            'Dir' => 'asc',
            'WithTrashed' => Functions::isAdminPath($request->path()),
            'BaseUrl' => Functions::isAdminPath($request->path()) ? 'admin' : '',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UseGetSelf' => false,
            'Transform' => Order::TO_TABLE_ARRAY_TRANSFORM
        ];
        $usePagination = false;
        if ($usePagination) {
            $pv = Order::getPagingVars(
                $request, $ordersData['PagingFor'], $ordersData['NumShown'],
                $ordersData['Dir']
            );
            if (Functions::testVar($pv)) {
                $ordersData['PageNum'] = $pv['pageNum'];
                $ordersData['ViewNum'] = $pv['viewNum'];
                if (Functions::hasPropKeyIn($pv, 'limit')) {
                    $ordersData['NumShown'] = $pv['limit'];
                }
            } 
            $orders = Order::getAllWithPagination(
                $ordersData['Transform'], $ordersData['PageNum'], 
                $ordersData['NumShown'], $ordersData['PagingFor'], 
                $ordersData['Dir'], $ordersData['WithTrashed'], 
                $ordersData['BaseUrl'], $ordersData['ListUrl'], 
                $ordersData['ViewNum'], $ordersData['FullUrl'], 
                $ordersData['UseTitle'], 1, $ordersData['Default'], 
                $ordersData['UseBaseMaker']
            );
            if ($ordersData['UseGetSelf']) {
                $children = Functions::countHas($orders) 
                    ? $orders['items'] : null;
                $paginator = Functions::countHas($orders) 
                    ? $orders['pagination'] : null;
                $orders_index = Order::getSelf(
                    $ordersData['BaseUrl'], $ordersData['WithTrashed'],
                    $ordersData['FullUrl'], $children, 
                    $paginator, $ordersData['PagingFor']
                );
            }
        } else {
            $orders = [];
            $orders['items'] = Order::getAllWithTransform(
                $ordersData['Transform'], $ordersData['Dir'], 
                $ordersData['WithTrashed'], $ordersData['BaseUrl'], 
                $ordersData['UseTitle'], $ordersData['FullUrl'], 
                1, $ordersData['Default'], $ordersData['UseBaseMaker']
            );
        }
        //dd($pages);
        if ($request->ajax()) {
            return $orders;
        } else {
            $title = 'Our Client\'s Orders';
            $bcLinks = [];
            $bcLinks[] = self::getHomeBreadcumb();
            if (Functions::isAdminPath($request->path())) {
                $bcLinks[] = CmsController::getAdminBreadcrumb();
            }
            $breadcrumbs = Page::getBreadcrumbs(
                Page::genBreadcrumb(
                    $title, 
                    Order::genUrlFragment($ordersData['BaseUrl'], $ordersData['FullUrl'])
                ),
                $bcLinks
            );
            return self::getView(
                $request, 'cms.items_table', $title, $orders, false, $breadcrumbs, null,
                Functions::isAdminPath($request->path()) 
                ? CmsController::getAdminSidebar() : null
            );
        }
        UserSession::updateAndAbort($request, 404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            return Cart::getCurrentCart($request, true);
        } else {
            return self::getView(
                $request, 'content.cart', 'Shopping Cart Details', 
                [
                    'header' => 'Shopping Cart Details'
                ], false, 
                Page::getBreadcrumbs(
                    Page::genBreadcrumb('checkout', 'checkout')
                )
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cart = Cart::getSessionCart(true);
        $user = User::getIdFromUserArray(true, null, $request);
        $path = $request->path();
        $errors = [];
        if (Functions::testVar($user)) {
            if (Functions::testVar($cart)) {
                if (!$cart->isEmpty()) {
                    $content = [
                        'order_cart_items' => $cart->getContent(),
                        'order_cart_conditions' => $cart->getConditions()
                    ];
                    $order = Order::createNew(
                        $user, $cart->getTotal(), $content,
                        [], '', true
                    );
                    if (Functions::testVar($order)) {
                        $cart->clear();
                        $cart->clearCartConditions();
                        self::addMsg('Your Order has Been Successfully Placed!');
                        $path = 'store';
                    } else {
                        $errors['error'] = 'We Are Sorry! Your Order Could Not Be Placed!';
                    }
                } else {
                    $errors['error'] = 'We Are Sorry! Your Cart is Empty!';
                }
            } else {
                $errors['error'] = 'We Are Sorry! Your Cart is Empty!';
            }
        } else {
            $errors['error'] = 'We Are Sorry! You are Not Logged in! Please Log in and try again.';
        }
        return UserSession::updateRedirect(
            $request, $path, $errors
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ordersData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'ordersPanel',
            'Dir' => 'asc',
            'WithTrashed' => Functions::isAdminPath($request->path()),
            'BaseUrl' => Functions::isAdminPath($request->path()) ? 'admin' : '',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'Version' => 1,
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UseGetSelf' => false,
            //'Transform' => Order::TO_TABLE_ARRAY_TRANSFORM
        ];
        $order = Order::getNamed($request->order, $ordersData['WithTrashed']);
        if (Functions::testVar($order)) {
            $currencyIcon = $request->session()->has('currency') 
                ? $request->session()->get('currency')
                : 'fa-usd';
            /*
                Order::toContentArrayPlusExtra(
                    string $baseUrl = 'store', int $version = 1, 
                    bool $useTitle = true, bool $withTrashed = true, 
                    bool $fullUrl = false, bool $useBaseMaker = true,
                    string $dir = 'asc', string $currencyIcon = 'fa-usd', 
                    $default = []
                )
            */
            $content = [
                'order' =>$order->toContentArrayPlusExtra(
                    $ordersData['BaseUrl'], $ordersData['Version'], 
                    $ordersData['UseTitle'], $ordersData['WithTrashed'], 
                    $ordersData['FullUrl'], $ordersData['UseBaseMaker'],
                    $ordersData['Dir'], $currencyIcon, 
                    $ordersData['Default']
                )
            ];
            if ($request->ajax()) {
                return $content;
            } else {    
                $title = 'Customer Order: ' . $order->getPubName();
                /* $article = Article::makeArticleArray(
                    string $article, string $header = '',
                    null, string $subheading = '',
                    0
                ) */
                $bcLinks = [];
                $bcLinks[] = self::getHomeBreadcumb();
                if (Functions::isAdminPath($request->path())) {
                    $bcLinks[] = CmsController::getAdminBreadcrumb();
                }
                $bcLinks[] = Page::genBreadcrumb(
                    'Our Customers\' Orders', 
                    Order::genUrlFragment($ordersData['BaseUrl'], $ordersData['FullUrl'])
                );
                $breadcrumbs = Page::getBreadcrumbs(
                    Page::genBreadcrumb(
                        $title, 
                        $order->getFullUrl($ordersData['BaseUrl'], $ordersData['FullUrl'])
                    ),
                    $bcLinks
                );
                return self::getView(
                    $request, 'cms.order', $title, 
                    $content, false, $breadcrumbs, null, 
                    Functions::isAdminPath($request->path()) 
                    ? CmsController::getAdminSidebar() : null
                );
            }
        }
        UserSession::updateAndAbort($request, 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $ordersData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'ordersPanel',
            'Dir' => 'asc',
            'WithTrashed' => Functions::isAdminPath($request->path()),
            'BaseUrl' => Functions::isAdminPath($request->path()) ? 'admin' : '',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'Version' => 1,
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UseGetSelf' => false,
            //'Transform' => Order::TO_TABLE_ARRAY_TRANSFORM
        ];
        $order = Order::getNamed($request->order, $ordersData['WithTrashed']);
        if (Functions::testVar($order)) {
            $currencyIcon = $request->session()->has('currency') 
                ? $request->session()->get('currency')
                : 'fa-usd';
            $content = [
                'order' =>$order->toContentArrayPlusExtra(
                    $ordersData['BaseUrl'], $ordersData['Version'], 
                    $ordersData['UseTitle'], $ordersData['WithTrashed'], 
                    $ordersData['FullUrl'], $ordersData['UseBaseMaker'],
                    $ordersData['Dir'], $currencyIcon, 
                    $ordersData['Default']
                ),
                'thisURL' => $order->getFullUrl($ordersData['BaseUrl'], $ordersData['FullUrl']),
                'HttpVerb' => 'PATCH',
                'cancelUrl' => Order::genUrlFragment($ordersData['BaseUrl'], $ordersData['FullUrl']),
            ];  
            $title = 'Customer Order: ' . $order->getPubName();
            $bcLinks = [];
            $bcLinks[] = self::getHomeBreadcumb();
            if (Functions::isAdminPath($request->path())) {
                $bcLinks[] = CmsController::getAdminBreadcrumb();
            }
            $bcLinks[] = Page::genBreadcrumb(
                'Our Customers\' Orders', 
                Order::genUrlFragment($ordersData['BaseUrl'], $ordersData['FullUrl'])
            );
            $breadcrumbs = Page::getBreadcrumbs(
                Page::genBreadcrumb(
                    'Administrative Order Review and Modification Form', 
                    $order->getFullUrl($ordersData['BaseUrl'], $ordersData['FullUrl'])
                ),
                $bcLinks
            );
            return self::getView(
                $request, 'cms.forms.edit.order', 'Administrative Order Review and Modification Form',
                $content, false, $breadcrumbs, null, 
                Functions::isAdminPath($request->path()) ? CmsController::getAdminSidebar()
                : null
            );
        }
        UserSession::updateAndAbort($request, 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $is_admin = Functions::isAdminPath($request->path());
        $ordersData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'ordersPanel',
            'Dir' => 'asc',
            'WithTrashed' => Functions::isAdminPath($request->path()),
            'BaseUrl' => Functions::isAdminPath($request->path()) ? 'admin' : '',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'Version' => 1,
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UseGetSelf' => false,
            //'Transform' => Order::TO_TABLE_ARRAY_TRANSFORM
        ];
        $order = Order::getNamed($request->order, $ordersData['WithTrashed']);
        if (Functions::testVar($order)) {
            $validator = Validator::make(
                $request->all(), 
                [
                    'status' => 'required|string|max:255',
                    'comments' => 'required|string',
                ]
            );
            $path = $request->path();
            $passed = $validator->passes();
            if ($passed) {
                $val = $order->updateWith($request->comments, $request->status, true);
                if (Functions::testVar($val) && $val === $order) {
                    self::addMsg('Customer Order: ' . $order->getPubName() . ' Modified Successfully!');
                    $path = 'admin/orders';
                } else {
                    self::addMsg("Uhhh, if we got here then Customer Order Modification FAILED!!!");
                    //dd($page);
                    $passed = null;
                }
            }
            $path = $passed || Str::contains($path, 'edit') ? $path : $path . '/edit';
            return UserSession::updateRedirect(
                $request, $path, $validator, 
                !$passed ? $request->all()
                : []
            );
        }
        UserSession::updateAndAbort($request, 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }

    public function showDelete(Request $request)
    {
        return UserSession::updateRedirect(
            $request, 'admin/orders'
        );
    }
}
