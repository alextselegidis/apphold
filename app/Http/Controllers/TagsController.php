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

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TagsController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Tag::class);

        $query = Tag::query();

        $q = $request->query('q');

        if ($q) {
            $query->where('name', 'like', '%' . $q . '%');
        }

        $sort = $request->query('sort');

        $direction = $request->query('direction');

        if ($sort && $direction) {
            $query->orderBy($sort, $direction);
        }

        $query->where('user_id', $request->user()->id);
        $tags = $query->cursorPaginate(25);

        return view('pages.tags', [
            'tags' => $tags,
            'q' => $q,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Tag::class);

        $request->validate([
            'name' => 'required',
        ]);

        $payload = $request->all();

        $tag = Tag::create([
            'name' => $payload['name'],
            'user_id' => $request->user()->id,
        ]);

        return redirect(route('tags.edit', ['tag' => $tag->id]));
    }

    public function edit(Request $request, Tag $tag)
    {
        Gate::authorize('update', $tag);

        return view('pages.tags-edit', [
            'tag' => $tag,
        ]);
    }

    public function update(Request $request, Tag $tag)
    {
        Gate::authorize('update', $tag);

        $request->validate([
            'name' => 'required|min:2',
        ]);

        $payload = $request->input();

        $tag->fill($payload);

        $tag->save();

        return redirect(route('tags.edit', $tag->id))->with('success', __('record_saved_message'));
    }

    public function destroy(Request $request, Tag $tag)
    {
        Gate::authorize('delete', $tag);

        $tag->delete();

        return redirect('tags')->with('success', __('record_deleted_message'));
    }

    public function observers(Request $request, Tag $tag)
    {
        Gate::authorize('update', $tag);

        return view('pages.tags-edit-observers', [
            'tag' => $tag,
            'observers' => $tag->observers,
        ]);
    }
}
