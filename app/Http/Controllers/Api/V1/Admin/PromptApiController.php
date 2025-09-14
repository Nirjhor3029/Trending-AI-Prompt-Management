<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePromptRequest;
use App\Http\Requests\UpdatePromptRequest;
use App\Http\Resources\Admin\PromptResource;
use App\Models\Prompt;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PromptApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('prompt_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PromptResource(Prompt::with(['ai_platforms'])->get());
    }

    public function store(StorePromptRequest $request)
    {
        $prompt = Prompt::create($request->all());
        $prompt->ai_platforms()->sync($request->input('ai_platforms', []));
        foreach ($request->input('images', []) as $file) {
            $prompt->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('images');
        }

        return (new PromptResource($prompt))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Prompt $prompt)
    {
        abort_if(Gate::denies('prompt_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PromptResource($prompt->load(['ai_platforms']));
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

        return (new PromptResource($prompt))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Prompt $prompt)
    {
        abort_if(Gate::denies('prompt_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $prompt->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
