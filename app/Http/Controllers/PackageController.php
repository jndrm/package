<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use GuzzleHttp\Client as HttpClient;
use App\Jobs\DownJob;

class PackageController extends Controller
{
    public function index()
    {
    }

    public function show($name, $version=null)
    {
        // check local file
        $path = "public/packages/{$name}/package.json";
        $disk = Storage::disk('local');
        if ($disk->exists($path)) {
            $res = $disk->get($path);
        } else {
            // download file
            $client = new HttpClient([
                // Base URI is used with relative requests
                'base_uri' => 'https://registry.npmjs.org/',
                // You can set any number of default request options.
                'timeout'  => 20,
            ]);
            try {
                $resp = $client->get($name);
            } catch (\Exception $e) {
                abort(500, $e->getMessage());
            }
            $res = (string)$resp->getBody();
            // save res
            $disk->put($path, $res);
        }
        // TODO: only update tarball urls
        $res = str_replace("https://registry.npmjs.org", url("packages"), $res);
        // return package
        return response()->make($res, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function tarball($name, $tar)
    {
        $path = "public/packages/{$name}/{$tar}";
        $disk = Storage::disk('local');
        // check local file
        if ($disk->exists($path)) {
            $url = asset($disk->url($path));
        } else {
            // add download task to queue
            $url = "https://registry.npmjs.org/{$name}/-/{$tar}";
            dispatch(new DownJob($url, $path));
        }
        // redirect user to downloadable address
        return redirect($url);
    }

    public function auditsQuick()
    {
    }
}
