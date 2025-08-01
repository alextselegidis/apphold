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
use App\Models\Project;
use App\Models\Tag;
use DOMDocument;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ObserversController extends Controller
{
    public function index(Request $request)
    {
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
        $request->validate([
            'url' => 'required|url',
        ]);

        $payload = $request->all();

        $pageInfo = $this->fetchPageInfo($payload['url']);

        $pageInfo['user_id'] = $request->user()->id;

        $observer = Observer::create($pageInfo);

        return redirect(route('observers.edit', ['observer' => $observer->id]));
    }

    public function show(Request $request, Observer $observer)
    {
        if ($observer->user_id !== $request->user()->id) {
            abort(403);
        }

        return view('pages.observers-show', [
            'observer' => $observer,
        ]);
    }

    public function edit(Request $request, Observer $observer)
    {
        if ($observer->user_id !== $request->user()->id) {
            abort(403);
        }

        return view('pages.observers-edit', [
            'observer' => $observer,
            'projectOptions' => Project::toOptions(),
            'tagOptions' => Tag::toOptions(),
        ]);
    }

    public function update(Request $request, Observer $observer)
    {
        if ($observer->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'url' => 'required|min:2',
            'tags' => 'array',
            'tags.*' => 'exists:projects,id',
        ]);

        $payload = $request->input();

        $pageInfo = $this->fetchPageInfo($payload['url']);

        $payload['user_id'] = $request->user()->id;
        $payload['favicon'] = $pageInfo['favicon'];
        $payload['og_image'] = $pageInfo['og_image'];

        $observer->fill($payload);

        $observer->save();

        $observer->tags()->syncWithoutDetaching(request('tags'));

        return redirect(route('observers.show', $observer->id))->with('success', __('record_saved_message'));
    }

    public function destroy(Request $request, Observer $observer)
    {
        if ($observer->user_id !== $request->user()->id) {
            abort(403);
        }

        $observer->delete();

        return redirect()->back()->with('success', __('record_deleted_message'));
    }

    public function toggle(Request $request, Observer $observer)
    {
        if ($observer->user_id !== $request->user()->id) {
            abort(403);
        }

        $observer->is_active = !$observer->is_active;
        $observer->save();
        return redirect()->back()->with('success', __('record_saved_message'));
    }

    function fetchPageInfo(string $url): array
    {
        // Fetch HTML
        $response = Http::get($url);
        $html = $response->body();

        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        libxml_clear_errors();

        $data = [
            'title' => '',
            'url' => $url,
            'interval' => 60,
            'notes' => '',
            'emails' => Auth::user()->email,
            'og_image' => null, // base64-encoded favicon content
            'favicon' => null, // base64-encoded favicon content
            'is_active' => true,
            'with_ssl_verification' => true,
        ];

        // <title>
        $titleTag = $dom->getElementsByTagName('title');
        if ($titleTag->length) {
            $data['title'] = $titleTag->item(0)->nodeValue;
        }

        // <meta> tags
        foreach ($dom->getElementsByTagName('meta') as $meta) {
            $name = strtolower($meta->getAttribute('name') ?: $meta->getAttribute('property'));
            $content = $meta->getAttribute('content');

            switch ($name) {
                case 'description':
                    $data['meta_description'] = $content;
                    break;
                case 'author':
                    $data['meta_author'] = $content;
                    break;
                case 'keywords':
                    $data['meta_keyword'] = $content;
                    break;
                case 'theme-color':
                    $data['theme_color'] = $content;
                    break;
                case 'og:title':
                    $data['og_title'] = $content;
                    break;
                case 'og:description':
                    $data['og_description'] = $content;
                    break;
                case 'og:type':
                    $data['og_type'] = $content;
                    break;
                case 'og:url':
                    $data['og_url'] = $content;
                    break;
                case 'og:image':
                    $data['og_image'] = $content;
                    break;
                case 'og:site_name':
                    $data['og_site_name'] = $content;
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
            $faviconUrl = rtrim($url, '/') . '/favicon.ico'; // fallback
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
                        // 512KB
                        $data['og_image'] = base64_encode($ogImageBinary);
                    } else {
                        $data['og_image'] = null;
                    }
                } else {
                    $data['og_image'] = null;
                }
            } catch (Exception $e) {
                $data['og_image'] = null;
            }
        } else {
            $data['og_image'] = null;
        }

        return $data;
    }
}
