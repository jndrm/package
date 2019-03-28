<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComposerController extends Controller
{
    // https://packagist.phpcomposer.com/p/provider-2013$421d09842613d5c5cc20bd117040f73f66740f333cecb89c06771601bb8c71f7.json
    // https://packagist.phpcomposer.com/p/zweifisch/match$10dc7b7618009112266a95103f7742fc2e45cb62289c7ee66f81b59ca8ad2fd2.json
    // https://packagist.phpcomposer.com/p/jndrm/phpwoo$dc25671234b8c23befb654c67f5e8417ba26ebb65fda15b111f349a55e080209.json
    public function index(Request $request)
    {
        return [
            "packages" => [],
            "includes": {
                "include/all$4a1229aefe01501798a5a95824f7baa3764333d1.json": {
                    "sha1": "4a1229aefe01501798a5a95824f7baa3764333d1"
                }
            },
            "notify" => "https://packagist.org/downloads/%package%",
            "notify-batch" => "https://packagist.org/downloads/",
            "providers-url" => "/p/%package%$%hash%.json",
            "search" => "https://packagist.org/search.json?q=%query%&type=%type%",
            "provider-includes" => [
                "p/provider-2013$%hash%.json" => ["sha256" => "421d09842613d5c5cc20bd117040f73f66740f333cecb89c06771601bb8c71f7"],
                "p/provider-2014$%hash%.json" => ["sha256" => "4eb528ecf388b3c780ac8992ca72fd223217a3a3bde6862b36326151e51bf340"],
                "p/provider-2015$%hash%.json" => ["sha256" => "9d0f4345d01618bcfa369a276c6f2dc5bceb656e2bca081b3941c9b8ad908293"],
                "p/provider-2016$%hash%.json" => ["sha256" => "ae19026fd0d5a3d5733e5a46202305c96ff2ae1ccd7988bb88c5fa2a7e490f7d"],
                "p/provider-2017$%hash%.json" => ["sha256" => "2cde6ea7013405cfcb255d563180ad866feeff9ad85fb8062ddf6a02a1403527"],
                "p/provider-2017-10$%hash%.json" => ["sha256" => "6adcc765fe5b787ea1d50581c624fbf305f397fc858ad5f1359f756cfe1801bc"],
                "p/provider-2018-01$%hash%.json" => ["sha256" => "51cb774c62ade10c3598fa67bd96216b639c3a8f7d3c42890aafcbc96071023a"],
                "p/provider-2018-04$%hash%.json" => ["sha256" => "8b7e5418d1600b5fafe146ee99af0b2c65056bfc77ca4862d361930c2b900812"],
                "p/provider-2018-07$%hash%.json" => ["sha256" => "b932bd34baa179fe2c1109b77fb54d87ab70687c92cdba89884f1bf46b5c6210"],
                "p/provider-archived$%hash%.json" => ["sha256" => "584bc6f8d1dda3e4575d3b121d0b9a686210689dd620693119c4517b807f3ff7"],
                "p/provider-latest$%hash%.json" => ["sha256" => "cc52846a143c09b445e5fffa317822cc38775fa72bc0065093b46352f702ad22"],
            ],
            "sync-time" => "2018-07-17T14:51:36+00:00",
            "how-to-use-this-packagist-mirror" => "https://pkg.phpcomposer.com/",
            "total-cached-packages" => 200410,
            "total-cached-zips" => "millions"
        ];
    }
}
