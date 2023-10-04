<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;



class PlatformController extends Controller
{


    // * for testing, uncomment when needed
    // public function fetchToken(Request $request)
    // {

    //     $apiToken =  config('app.GRAPHQL_TOKEN');
    //     $platformToken = config('app.PLATFORM_TOKEN');
    //     $environment = App::environment();
    //     $path = base_path('.env');
    //     // $test = file_get_contents($path);


    //     return [
    //         'api_token' => $apiToken,
    //         'platform_token' => $platformToken,
    //         'environment' => $environment,
    //         'path' => $path,
    //         // 'test' => $test
    //     ];
    // }


    //* Get the GQL token using platform token and save it in .env file
    public function getPlatformToken(Request $request)
    {
        // get platform token, api token, path, file contents
        $platformToken = config('app.PLATFORM_TOKEN');
        $apiToken =  config('app.GRAPHQL_TOKEN');
        $path = base_path('.env');
        $fileContents = file_get_contents($path);
        // api call to get the new token
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->get('https://learn.reboot01.com/api/auth/token?token=' . $platformToken);
        // if the file exist
        if (file_exists($path)) {
            // replace graph ql token content
            file_put_contents($path, str_replace('GRAPHQL_TOKEN="' . $apiToken . '"', 'GRAPHQL_TOKEN=' . $response, $fileContents));
        }
        Artisan::call('optimize', ['--quiet' => true]);

        return 'success';
    }
}
