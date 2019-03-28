<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected function saveAndDown($folder, $path)
    {
        $disk = Storage::disk('local');
        if ($disk->exists("{$folder}/{$path}")) {
            return redirect($disk->url("{$folder}/{$path}"));
        }
        return response()->stream(function() use($path) {
            $loop = LoopFactory::create();
            $client = new Client($loop);

            $url = "https://gems.ruby-china.com/{$path}";

            $request = $client->request('GET', $url);

            $tmpPath = tempnam(sys_get_temp_dir(), 'p');
            $tmpFp = fopen($tmpPath, 'w');
            $outputFp = fopen('php://output', 'w');

            $request->on('response', function ($response) use ($outputFp, $tmpFp, $tmpPath, $folder, $path) {
                $response->on('data', function ($chunk) use($outputFp, $tmpFp) {
                    fwrite($outputFp, $chunk);
                    fwrite($tmpFp, $chunk);
                });
                $response->on('end', function() use ($outputFp, $tmpFp, $tmpPath, $folder, $path) {
                    fclose($outputFp);
                    fclose($tmpFp);
                    // TODO: move temp file to destination
                    $disk = Storage::disk('local');
                    $info = pathinfo($path);
                    if (!File::isDirectory($disk->path("{$folder}/{$info['dirname']}"))) {
                        $disk->makeDirectory("{$folder}/{$info['dirname']}");
                        // File::makeDirectory($disk->path($info['dirname']), 0777, true);
                    }
                    File::move($tmpPath, $disk->path("{$folder}/{$path}"));
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
