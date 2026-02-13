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

use App\Models\Observer;
use App\Models\Tag;
use DOMDocument;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Throwable;

class ObserversController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Observer::class);

        $query = Observer::query();

        $q = $request->query('q');

        if ($q) {
            $query->where('title', 'like', '%' . $q . '%');
        }

        $sort = $request->query('sort');

        $direction = $request->query('direction');

        if ($sort && $direction) {
            $query->orderBy($sort, $direction);
        }

        $query->where('user_id', $request->user()->id);
        $observers = $query->cursorPaginate(25);

        return view('pages.observers', [
            'observers' => $observers,
            'q' => $q,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Observer::class);

        $request->validate([
            'url' => 'required|url',
        ]);

        $payload = $request->all();

        $pageInfo = $this->fetchPageInfo($payload['url']);

        $pageInfo['user_id'] = $request->user()->id;
        $observer = Observer::create($pageInfo);

        return redirect(route('observers.edit', ['observer' => $observer->id]));
    }

    public function edit(Request $request, Observer $observer)
    {
        Gate::authorize('update', $observer);

        if ($observer->user_id !== $request->user()->id) {
            abort(403);
        }

        return view('pages.observers-edit', [
            'observer' => $observer,
            'tags' => Tag::where('user_id', $request->user()->id)->get(),
        ]);
    }

    public function update(Request $request, Observer $observer)
    {
        Gate::authorize('update', $observer);

        if ($observer->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'url' => 'required|min:2',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $payload = $request->input();

        $pageInfo = $this->fetchPageInfo($payload['url']);
        // Only update favicon if we got a new one
        if (!empty($pageInfo['favicon'])) {
            $payload['favicon'] = $pageInfo['favicon'];
        }
        // Only update og_image if we got a new one
        if (!empty($pageInfo['og_image'])) {
            $payload['og_image'] = $pageInfo['og_image'];
        }

        $observer->fill($payload);

        $observer->save();
        $observer->tags()->syncWithoutDetaching(request('tags'));

        return redirect(route('observers.edit', $observer->id))->with('success', __('record_saved_message'));
    }

    public function destroy(Request $request, Observer $observer)
    {
        Gate::authorize('delete', $observer);

        if ($observer->user_id !== $request->user()->id) {
            abort(403);
        }

        $observer->delete();

        return redirect('observers')->with('success', __('record_deleted_message'));
    }

    public function toggle(Request $request, Observer $observer)
    {
        Gate::authorize('update', $observer);

        if ($observer->user_id !== $request->user()->id) {
            abort(403);
        }

        $observer->is_active = !$observer->is_active;

        $observer->save();

        return redirect()->back()->with('success', __('record_saved_message'));
    }

    function fetchPageInfo(string $url): array
    {
        $data = [
            'title' => '',
            'url' => $url,
            'interval' => 60,
            'notes' => '',
            'emails' => Auth::user()->email,
            'og_image' => null,
            'favicon' => null,
            'is_active' => true,
            'with_ssl_verification' => true,
        ];
        try {
            $response = Http::withOptions([
                'allow_redirects' => true,
            ])->get($url);
        } catch (Throwable $e) {

            return $data;
        }

        $html = $response->body();
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();

        $dom->loadHTML($html);
        libxml_clear_errors();
        // <title>
        $titleTag = $dom->getElementsByTagName('title');

        if ($titleTag->length) {
            $data['title'] = trim($titleTag->item(0)->nodeValue);
        }
        // <meta> tags
        foreach ($dom->getElementsByTagName('meta') as $meta) {
            $name = strtolower($meta->getAttribute('name') ?: $meta->getAttribute('property'));

            $content = trim($meta->getAttribute('content'));
            switch ($name) {
                case 'og:image':
                    $data['og_image'] = $content;
                    break;
            }
        }
        // <link rel="icon"> or fallback
        $faviconUrl = null;
        foreach ($dom->getElementsByTagName('link') as $link) {
            $rel = strtolower($link->getAttribute('rel'));

            if (str_contains($rel, 'icon')) {
                $href = $link->getAttribute('href');
                $faviconUrl = parse_url($href, PHP_URL_HOST) ? $href : rtrim($url, '/') . '/' . ltrim($href, '/');
                break;
            }
        }

        if (!$faviconUrl) {
            $faviconUrl = rtrim($url, '/') . '/favicon.ico';
        }
        // Download and encode favicon
        try {
            $faviconResponse = Http::withOptions(['stream' => true])->get($faviconUrl);

            if ($faviconResponse->successful()) {
                $faviconBinary = $faviconResponse->body();
                $data['favicon'] = base64_encode($faviconBinary);
            }
        } catch (Exception $e) {
            // leave favicon as null
        }
        // Download and encode og:image
        if (!empty($data['og_image'])) {
            try {
                $ogImageUrl = $data['og_image'];
                // Handle relative URLs
                if (!parse_url($ogImageUrl, PHP_URL_HOST)) {
                    $ogImageUrl = rtrim($url, '/') . '/' . ltrim($ogImageUrl, '/');
                }

                $ogImageResponse = Http::withOptions(['stream' => true])->get($ogImageUrl);

                if ($ogImageResponse->successful()) {
                    $ogImageBinary = $ogImageResponse->body();

                    if (strlen($ogImageBinary) <= 512 * 1024) {
                        $data['og_image'] = base64_encode($ogImageBinary);
                    } else {
                        $data['og_image'] = '';
                    }
                } else {
                    $data['og_image'] = '';
                }
            } catch (Exception $e) {
                $data['og_image'] = '';
            }
        }

        return $data;
    }
}
