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

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query();

        $q = $request->query('q');

        if ($q) {
            $query->where('name', 'like', '%' . $q . '%');
        }

        $sort = $request->query('sort');
        $direction = $request->query('direction');

        if ($sort && $direction) {
            $query->orderBy($sort, $direction);
        }

        $projects = $query->cursorPaginate(25);

        return view('pages.projects', [
            'projects' => $projects,
            'q' => $q,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $payload = $request->all();

        $project = Project::create([
            'name' => $payload['name'],
        ]);

        return redirect(route('projects.edit', ['project' => $project->id]));
    }

    public function show(Request $request, Project $project)
    {
        return view('pages.projects-show', [
            'project' => $project,
        ]);
    }

    public function edit(Request $request, Project $project)
    {
        return view('pages.projects-edit', [
            'project' => $project,
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|min:2',
        ]);

        $payload = $request->input();

        $project->fill($payload);

        $project->save();

        return redirect(route('projects.show', $project->id))->with('success', __('record_saved_message'));
    }

    public function destroy(Request $request, Project $project)
    {
        $project->delete();

        return redirect()->back()->with('success', __('record_deleted_message'));
    }
}
