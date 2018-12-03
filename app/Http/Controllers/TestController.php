<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User,
    App\Utilities\Functions\Functions,
    App\PageGroup,
    App\PageGrouping,
    App\Page;
use Webpatser\Uuid\Uuid;

class TestController extends MainController
{
    public function __construct($name = '', $titleNameSep = ' | ')
    {
        ///
    }

    public function index(Request $request, $method = null)
    {
        if (is_null($method)) {
            /**
             * Route::get(
             *   '/', function () {
             *       return view('welcome');
             *   }
             * );
             */
            return view('welcome');
        } else {
            switch ($variable) {
            case 'url':
                return $this->doUrl($request);
                break;
        
            case 'dump':
                return $this->doDump($request);
                break;
        
            case 'info':
                phpinfo();
                return '';
                break;
        
            case 'template':
                return view('master_themewagon');
                break;
            
            default:
                return view('welcome');
                break;
            }
        }
    }

    protected function doUrl(Request $request) 
    {
        $num = 44;
        $n = Functions::int2url_encode($num);
        $k = Functions::url2int_decode($n);
        $j = pack('V', $num);
        $l = bin2hex($j);
        $u = Uuid::generate(5,'test', Uuid::NS_URL);
        $array = [
            'num' => $num, 
            'url_encoded' => $n, 
            'url_decode' => $k, 
            'packed' => $j,
            'bin2hed' => $l,
            'uuid' => $u,
        ];
        if (!$request->ajax()) {
            dd($array);
        } else {
            return $array;
        }
    }

    protected function doDump(Request $request) 
    {
        $dump = true;
        $tmp = [
            'user' => User::getUserArray($request),
            'session' => $request->session()->all(),
        ];
        if ($dump) {
            dd($tmp);
        } else {
            return $tmp;
        }
    }
}
