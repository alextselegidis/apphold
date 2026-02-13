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
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Observer::query();

        $q = $request->query("q");
        $length = $request->query("length", 25);
        $showInactive = $request->query("show_inactive", false);
        $tagId = $request->query("tag_id");

        if (!$showInactive) {
            $query->where("is_active", true);
        }

        if ($q) {
            $query->where("title", "like", "%" . $q . "%");
        }

        if ($tagId) {
            $query->whereHas("tags", fn($q) => $q->where("tags.id", $tagId));
        }

        $sort = $request->query("sort", "created_at");
        $direction = $request->query("direction", "desc");

        if ($sort && $direction) {
            $query->orderBy($sort, $direction);
        }

        $query->where("user_id", $request->user()->id);

        $total = $query->count();

        $observers = $query->cursorPaginate($length);

        $tags = $request->user()->tags()->orderBy("name")->get();

        return view("pages.dashboard", [
            "observers" => $observers,
            "q" => $q,
            "length" => $total > $length ? $length : null,
            "showInactive" => $showInactive,
            "tags" => $tags,
            "selectedTagId" => $tagId,
        ]);
    }
}
