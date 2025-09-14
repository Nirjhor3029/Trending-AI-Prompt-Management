@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.aiPlatform.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ai-platforms.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.aiPlatform.fields.id') }}
                        </th>
                        <td>
                            {{ $aiPlatform->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.aiPlatform.fields.name') }}
                        </th>
                        <td>
                            {{ $aiPlatform->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.aiPlatform.fields.url') }}
                        </th>
                        <td>
                            {{ $aiPlatform->url }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ai-platforms.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#ai_platform_prompts" role="tab" data-toggle="tab">
                {{ trans('cruds.prompt.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="ai_platform_prompts">
            @includeIf('admin.aiPlatforms.relationships.aiPlatformPrompts', ['prompts' => $aiPlatform->aiPlatformPrompts])
        </div>
    </div>
</div>

@endsection