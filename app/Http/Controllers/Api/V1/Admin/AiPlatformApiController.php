<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAiPlatformRequest;
use App\Http\Requests\UpdateAiPlatformRequest;
use App\Http\Resources\Admin\AiPlatformResource;
use App\Models\AiPlatform;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AiPlatformApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('ai_platform_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AiPlatformResource(AiPlatform::all());
    }

    public function store(StoreAiPlatformRequest $request)
    {
        $aiPlatform = AiPlatform::create($request->all());

        return (new AiPlatformResource($aiPlatform))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AiPlatform $aiPlatform)
    {
        abort_if(Gate::denies('ai_platform_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AiPlatformResource($aiPlatform);
    }

    public function update(UpdateAiPlatformRequest $request, AiPlatform $aiPlatform)
    {
        $aiPlatform->update($request->all());

        return (new AiPlatformResource($aiPlatform))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AiPlatform $aiPlatform)
    {
        abort_if(Gate::denies('ai_platform_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $aiPlatform->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
