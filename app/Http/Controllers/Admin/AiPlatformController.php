<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAiPlatformRequest;
use App\Http\Requests\StoreAiPlatformRequest;
use App\Http\Requests\UpdateAiPlatformRequest;
use App\Models\AiPlatform;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AiPlatformController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('ai_platform_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $aiPlatforms = AiPlatform::all();

        return view('admin.aiPlatforms.index', compact('aiPlatforms'));
    }

    public function create()
    {
        abort_if(Gate::denies('ai_platform_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.aiPlatforms.create');
    }

    public function store(StoreAiPlatformRequest $request)
    {
        $aiPlatform = AiPlatform::create($request->all());

        return redirect()->route('admin.ai-platforms.index');
    }

    public function edit(AiPlatform $aiPlatform)
    {
        abort_if(Gate::denies('ai_platform_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.aiPlatforms.edit', compact('aiPlatform'));
    }

    public function update(UpdateAiPlatformRequest $request, AiPlatform $aiPlatform)
    {
        $aiPlatform->update($request->all());

        return redirect()->route('admin.ai-platforms.index');
    }

    public function show(AiPlatform $aiPlatform)
    {
        abort_if(Gate::denies('ai_platform_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $aiPlatform->load('aiPlatformPrompts');

        return view('admin.aiPlatforms.show', compact('aiPlatform'));
    }

    public function destroy(AiPlatform $aiPlatform)
    {
        abort_if(Gate::denies('ai_platform_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $aiPlatform->delete();

        return back();
    }

    public function massDestroy(MassDestroyAiPlatformRequest $request)
    {
        $aiPlatforms = AiPlatform::find(request('ids'));

        foreach ($aiPlatforms as $aiPlatform) {
            $aiPlatform->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
