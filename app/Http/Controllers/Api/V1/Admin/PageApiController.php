<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Http\Resources\Admin\PageResource;
use App\Models\Page;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PageApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('page_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PageResource(Page::all());
    }

    public function store(StorePageRequest $request)
    {
        $page = Page::create($request->all());

        return (new PageResource($page))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Page $page)
    {
        abort_if(Gate::denies('page_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PageResource($page);
    }

    public function update(UpdatePageRequest $request, Page $page)
    {
        $page->update($request->all());

        return (new PageResource($page))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Page $page)
    {
        abort_if(Gate::denies('page_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $page->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
