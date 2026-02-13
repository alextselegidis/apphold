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

use App\Models\Incident;
use App\Models\IncidentComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class IncidentsController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Incident::class);

        $query = Incident::query()->with('observer');

        $q = $request->query('q');

        if ($q) {
            $query->whereHas('observer', function ($q2) use ($q) {
                $q2->where('title', 'like', '%' . $q . '%')
                   ->orWhere('url', 'like', '%' . $q . '%');
            });
        }

        $type = $request->query('type');

        if ($type) {
            $query->where('type', $type);
        }

        $status = $request->query('status');

        if ($status) {
            $query->where('status', $status);
        }

        $sort = $request->query('sort', 'created_at');
        $direction = $request->query('direction', 'desc');

        $query->orderBy($sort, $direction);

        $query->where('user_id', $request->user()->id);
        $incidents = $query->cursorPaginate(25);

        $types = Incident::where('user_id', $request->user()->id)
            ->distinct()
            ->pluck('type');

        $statuses = ['new', 'ignored', 'fixing', 'fixed'];

        return view('pages.incidents', [
            'incidents' => $incidents,
            'types' => $types,
            'statuses' => $statuses,
            'q' => $q,
            'type' => $type,
            'status' => $status,
        ]);
    }

    public function edit(Request $request, Incident $incident)
    {
        Gate::authorize('update', $incident);

        if ($incident->user_id !== $request->user()->id) {
            abort(403);
        }

        $users = User::where('is_active', true)->get();

        return view('pages.incidents-edit', [
            'incident' => $incident,
            'users' => $users,
        ]);
    }

    public function update(Request $request, Incident $incident)
    {
        Gate::authorize('update', $incident);

        if ($incident->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:new,ignored,fixing,fixed',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        $incident->fill($request->only(['status', 'assigned_user_id']));
        $incident->save();

        return redirect(route('incidents.edit', $incident->id))->with('success', __('record_saved_message'));
    }

    public function comments(Request $request, Incident $incident)
    {
        Gate::authorize('update', $incident);

        if ($incident->user_id !== $request->user()->id) {
            abort(403);
        }

        $comments = $incident->comments()->with('user')->orderBy('created_at', 'desc')->get();

        return view('pages.incidents-edit-comments', [
            'incident' => $incident,
            'comments' => $comments,
        ]);
    }

    public function storeComment(Request $request, Incident $incident)
    {
        Gate::authorize('update', $incident);

        if ($incident->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|min:1',
        ]);

        IncidentComment::create([
            'incident_id' => $incident->id,
            'user_id' => $request->user()->id,
            'content' => $request->input('content'),
        ]);

        return redirect(route('incidents.comments', $incident->id))->with('success', __('record_saved_message'));
    }

    public function destroyComment(Request $request, Incident $incident, IncidentComment $comment)
    {
        Gate::authorize('update', $incident);

        if ($incident->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($comment->incident_id !== $incident->id) {
            abort(403);
        }

        $comment->delete();

        return redirect(route('incidents.comments', $incident->id))->with('success', __('record_deleted_message'));
    }

    public function destroy(Request $request, Incident $incident)
    {
        Gate::authorize('delete', $incident);

        if ($incident->user_id !== $request->user()->id) {
            abort(403);
        }

        $incident->delete();

        return redirect('incidents')->with('success', __('record_deleted_message'));
    }
}
