<?php

/* ----------------------------------------------------------------------------
 * Apphold - Online Software Telemetry
 *
 * @package     Apphold
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://apphold.org
 * ---------------------------------------------------------------------------- */

namespace App\Console\Commands;

use App\Models\Observer;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ObserversPingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'observers:ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ping a set of URLs to check if they return HTTP 200 OK';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $observers = Observer::where('is_active', true)->get();

        $failedObservers = [];

        foreach ($observers as $observer) {
            $url = $observer->url;

            try {
                $response = Http::timeout(10)
                    ->withOptions([
                        'allow_redirects' => true, // follow redirects
                    ])
                    ->get($url);

                if ($response->status() !== 200) {
                    $failedObservers[] = $observer;
                }
            } catch (Exception $e) {
                $this->error("⚠️ Error pinging {$url}: " . $e->getMessage());
                $failedObservers[] = $observer;
            }
        }

        if (!empty($failedObservers)) {
            $urls = collect($failedObservers)->pluck('url')->toArray();

            $emails = User::pluck('email')->toArray();

            $text = 'The following URLs failed to respond successfully: ' . PHP_EOL . PHP_EOL . implode(PHP_EOL, $urls);

            Mail::raw($text, function ($message) use ($emails) {
                $message->to($emails)->subject('Apphold - Observer Failure');
            });

            $this->error('⚠️ Some observers failed');
        }

        $this->info('✅ Done!');
    }
}
