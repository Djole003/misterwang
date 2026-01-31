@extends('admin.layouts.admin')

@section('content')
<div class="container mt-5">
    <h2>Lista korisnika</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <thead>
   
</thead>
<tbody>
    @foreach($users as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->role }}</td>

        <td>
            @if ($user->active)
                <span class="badge bg-success">Aktivan</span>
            @else
                <span class="badge bg-secondary">Deaktiviran</span>
            @endif
        </td>

        <td>
            <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="d-flex">
                @csrf
                <select name="role" class="form-select me-2" required>
                    <option value="user" @if($user->role == 'user') selected @endif>User</option>
                    <option value="editor" @if($user->role == 'editor') selected @endif>Editor</option>
                    <option value="admin" @if($user->role == 'admin') selected @endif>Admin</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm">Sačuvaj</button>
            </form>
        </td>

        <td>
            <form method="POST" action="{{ route('admin.users.toggleActive', $user->id) }}">
                @csrf
                <button type="submit" class="btn btn-sm {{ $user->active ? 'btn-warning' : 'btn-success' }}">
                    {{ $user->active ? 'Deaktiviraj' : 'Aktiviraj' }}
                </button>
            </form>
        </td>
    </tr>
    @endforeach
</tbody>

    </table>
</div>
@endsection
