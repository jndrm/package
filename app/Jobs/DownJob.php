<?php

namespace App\Jobs;

use File;
use Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DownJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url = null;

    public $path = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url, $path)
    {
        $this->url = $url;
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // download file to temp folder
        $tmpPath = tempnam(sys_get_temp_dir(), 'p');
        self::downloadDistantFile($this->url, $tmpPath);
        // move file to destination
        File::move($tmpPath, Storage::disk('local')->path($this->path));
    }

    /**
     * Download a large distant file to a local destination.
     *
     * This method is very memory efficient :-)
     * The file can be huge, PHP doesn't load it in memory.
     *
     * /!\ Warning, the return value is always true, you must use === to test the response type too.
     *
     * @author dalexandre
     * @see https://gist.github.com/damienalexandre/1258787
     * @param string $url
     *    The file to download
     * @param ressource $dest
     *    The local file path or ressource (file handler)
     * @return boolean true or the error message
     */
    public static function downloadDistantFile($url, $dest)
    {
        $options = array(
            CURLOPT_FILE => is_resource($dest) ? $dest : fopen($dest, 'w'),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_URL => $url,
            CURLOPT_FAILONERROR => true, // HTTP code > 400 will throw curl error
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $return = curl_exec($ch);

        if ($return === false) {
            throw new \Exception(curl_error($ch));
        }
        return true;
    }
}
