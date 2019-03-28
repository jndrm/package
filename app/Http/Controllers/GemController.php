<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use React\EventLoop\Factory as LoopFactory;
use React\HttpClient\Client;
use File;
use Storage;

class GemController extends BaseController
{
    public function versions()
    {
    }

    public function handle(Request $request, $path)
    {
        $path = substr($request->server->get('REQUEST_URI'), 5);
        $disk = Storage::disk('local');
        if ($disk->exists("public/gems/{$path}")) {
            return redirect($disk->url("public/gems/{$path}"));
        }
        return response()->stream(function() use($path) {
            $loop = LoopFactory::create();
            $client = new Client($loop);

            $url = "https://gems.ruby-china.com/{$path}";

            $request = $client->request('GET', $url);

            $tmpPath = tempnam(sys_get_temp_dir(), 'p');
            $tmpFp = fopen($tmpPath, 'w');
            $outputFp = fopen('php://output', 'w');

            $request->on('response', function ($response) use ($outputFp, $tmpFp, $tmpPath, $path) {
                $response->on('data', function ($chunk) use($outputFp, $tmpFp) {
                    fwrite($outputFp, $chunk);
                    fwrite($tmpFp, $chunk);
                });
                $response->on('end', function() use ($outputFp, $tmpFp, $tmpPath, $path) {
                    fclose($outputFp);
                    fclose($tmpFp);
                    // TODO: move temp file to destination
                    $disk = Storage::disk('local');
                    $info = pathinfo($path);
                    if (!File::isDirectory($disk->path("public/gems/{$info['dirname']}"))) {
                        $disk->makeDirectory("public/gems/{$info['dirname']}");
                        // File::makeDirectory($disk->path($info['dirname']), 0777, true);
                    }
                    File::move($tmpPath, $disk->path("public/gems/{$path}"));
                });
            });
            $request->on('error', function (\Exception $e) use ($outputFp, $tmpFp) {
                fclose($outputFp);
                fclose($tmpFp);
                logger()->error($e);
            });
            $request->end();
            $loop->run();
        }, 200, [
            'Content-Type' => 'application/octet-stream',
        ]);
    }
}
