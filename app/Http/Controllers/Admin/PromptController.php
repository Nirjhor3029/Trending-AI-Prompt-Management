<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPromptRequest;
use App\Http\Requests\StorePromptRequest;
use App\Http\Requests\UpdatePromptRequest;
use App\Models\AiPlatform;
use App\Models\Prompt;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PromptController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('prompt_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prompts = Prompt::with(['ai_platforms', 'media'])->get();

        return view('admin.prompts.index', compact('prompts'));
    }

    public function create()
    {
        abort_if(Gate::denies('prompt_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ai_platforms = AiPlatform::pluck('name', 'id');

        return view('admin.prompts.create', compact('ai_platforms'));
    }

    public function store(StorePromptRequest $request)
    {
        $prompt = Prompt::create($request->all());
        $prompt->ai_platforms()->sync($request->input('ai_platforms', []));
        foreach ($request->input('images', []) as $file) {
            $prompt->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('images');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $prompt->id]);
        }

        return redirect()->route('admin.prompts.index');
    }

    public function edit(Prompt $prompt)
    {
        abort_if(Gate::denies('prompt_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ai_platforms = AiPlatform::pluck('name', 'id');

        $prompt->load('ai_platforms');

        return view('admin.prompts.edit', compact('ai_platforms', 'prompt'));
    }

    public function update(UpdatePromptRequest $request, Prompt $prompt)
    {
        $prompt->update($request->all());
        $prompt->ai_platforms()->sync($request->input('ai_platforms', []));
        if (count($prompt->images) > 0) {
            foreach ($prompt->images as $media) {
                if (! in_array($media->file_name, $request->input('images', []))) {
                    $media->delete();
                }
            }
        }
        $media = $prompt->images->pluck('file_name')->toArray();
        foreach ($request->input('images', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $prompt->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('images');
            }
        }

        return redirect()->route('admin.prompts.index');
    }

    public function show(Prompt $prompt)
    {
        abort_if(Gate::denies('prompt_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prompt->load('ai_platforms');

        return view('admin.prompts.show', compact('prompt'));
    }

    public function destroy(Prompt $prompt)
    {
        abort_if(Gate::denies('prompt_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prompt->delete();

        return back();
    }

    public function massDestroy(MassDestroyPromptRequest $request)
    {
        $prompts = Prompt::find(request('ids'));

        foreach ($prompts as $prompt) {
            $prompt->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('prompt_create') && Gate::denies('prompt_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Prompt();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
