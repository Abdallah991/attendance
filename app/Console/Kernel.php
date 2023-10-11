<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
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
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
