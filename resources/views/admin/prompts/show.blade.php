@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.prompt.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.prompts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.prompt.fields.id') }}
                        </th>
                        <td>
                            {{ $prompt->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.prompt.fields.title') }}
                        </th>
                        <td>
                            {{ $prompt->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.prompt.fields.text') }}
                        </th>
                        <td>
                            {{ $prompt->text }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.prompt.fields.images') }}
                        </th>
                        <td>
                            @foreach($prompt->images as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.prompt.fields.ai_platform') }}
                        </th>
                        <td>
                            @foreach($prompt->ai_platforms as $key => $ai_platform)
                                <span class="label label-info">{{ $ai_platform->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.prompts.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection