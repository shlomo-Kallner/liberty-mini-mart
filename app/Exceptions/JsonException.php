<?php

namespace App\Exceptions;

use Exception;
use App\Utilities\Functions\Functions;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class JsonException extends Exception
{
    protected $data = [];
    //
    public function __construct(Request $request, string $id = '', ...$data)
    {
        $this->data = [
            'request' => $request,
            'dumping_from' => Functions::getVar($id, __METHOD__),
        ];
        foreach ($data as $val) {
            $this->data[] = $val;
        }
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        $request = $request === $this->data['request'] 
            ? $request 
            : $this->data['request'];
        if ($request->ajax()) {
            $tmp = [];
            $dumper = new CliDumper;
            foreach ($this->data as $key => $val) {
                $tmp[$key] = $dumper->dump((new VarCloner)->cloneVar($val), true);
            }
            return new JsonResponse($tmp);
        } else {
            $dumper = new HtmlDumper;
            $tmp = $dumper->dump((new VarCloner)->cloneVar($this->data), true);
            return new Response($tmp);
        }
    }
}
