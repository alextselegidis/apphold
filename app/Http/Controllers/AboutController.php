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

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class AboutController extends Controller
{
    /**
     * Feed of the official Apphold blog.
     */
    private const FEED_URL = 'https://apphold.org/rss.xml';

    /**
     * How long a successfully fetched feed is kept.
     */
    private const CACHE_TTL_HOURS = 24;

    /**
     * Number of posts shown on the page.
     */
    private const POST_LIMIT = 5;

    public function index()
    {
        return view('pages.about', [
            'posts' => $this->posts(),
        ]);
    }

    /**
     * Latest blog posts, cached for a day.
     *
     * The feed lives on an external site, so installations without internet access simply
     * get an empty list and the blog section is left out of the page. Failures are not
     * cached, otherwise a single hiccup would hide the posts for the whole day.
     */
    private function posts(): array
    {
        $key = 'about.blog_posts';

        $posts = Cache::get($key);

        if ($posts !== null) {
            return $posts;
        }

        $posts = $this->fetchPosts();

        if (!empty($posts)) {
            Cache::put($key, $posts, now()->addHours(self::CACHE_TTL_HOURS));
        }

        return $posts;
    }

    private function fetchPosts(): array
    {
        try {
            $response = Http::timeout(5)->get(self::FEED_URL);

            if (!$response->successful()) {
                return [];
            }

            $feed = simplexml_load_string($response->body());

            if ($feed === false) {
                return [];
            }

            $posts = [];

            foreach ($feed->channel->item as $item) {
                $posts[] = [
                    'title' => trim((string) $item->title),
                    'link' => trim((string) $item->link),
                    'description' => trim((string) $item->description),
                    'published_at' => Carbon::parse((string) $item->pubDate),
                ];
            }

            usort($posts, fn(array $a, array $b) => $b['published_at'] <=> $a['published_at']);

            return array_slice($posts, 0, self::POST_LIMIT);
        } catch (Throwable $exception) {
            Log::warning('Could not load the Apphold blog feed: ' . $exception->getMessage());

            return [];
        }
    }
}
