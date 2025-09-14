@extends('layouts.admin')
@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            {{ trans('global.show') }} {{ trans('cruds.user.title') }}
        </div>

        <div class="card-body">
            <div class="mb-3">
                <a class="btn btn-secondary" href="{{ route('admin.users.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>

            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>{{ trans('cruds.user.fields.id') }}</th>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.user.fields.name') }}</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.user.fields.email') }}</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.user.fields.email_verified_at') }}</th>
                        <td>{{ $user->email_verified_at }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.user.fields.roles') }}</th>
                        <td>
                            @foreach ($user->roles as $roles)
                                <span class="badge bg-info text-dark">{{ $roles->title }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Generate Token Section -->
            <div class="mt-4">
                <button class="btn btn-success" id="generate-token-btn">
                    <i class="fas fa-key"></i> Generate Token
                </button>

                <div class="d-none" id="token-box">
                    <div class="mt-3 p-3 border rounded " style="background-color: #f8f9fa;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span id="generated-token" class="text-break"></span>
                            <button class="btn btn-outline-secondary btn-sm" id="copy-token-btn" title="Copy to clipboard">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>

                    </div>
                    <p style="color: red; ">
                        Copy & save this, because this is only available once.
                    </p>
                </div>

            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('generate-token-btn').addEventListener('click', function() {
            fetch("{{ route('admin.users.generate-token', $user->id) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    const tokenBox = document.getElementById('token-box');
                    const tokenSpan = document.getElementById('generated-token');
                    tokenSpan.textContent = data.token;
                    tokenBox.classList.remove('d-none');
                })
                .catch(err => alert('Something went wrong!'));
        });

        document.getElementById('copy-token-btn').addEventListener('click', function() {
            const token = document.getElementById('generated-token').textContent;
            navigator.clipboard.writeText(token).then(() => {
                alert('Token copied to clipboard!');
            });
        });
    </script>
@endsection
