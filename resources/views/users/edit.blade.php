@extends('layouts.app')

@section ('content')

<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Vertical Layouts</h4>

        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Basic Layout</h5>
                        <small class="text-muted float-end">Default label</small>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first">Nama</label>
                                        <input type="text" class="form-control" placeholder="Masukan Nama Anda" value="{{ old('name', $user->name) }}" name="name">
                                    </div>
                                </div>
                                <!--  col-md-6   -->

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last">User Name</label>
                                        <input type="text" class="form-control" placeholder="Masukan Username ANda" value="{{ old('username', $user->username) }}" name=" username">
                                    </div>
                                </div>
                                <!--  col-md-6   -->
                            </div>
                            <div class="form-group">
                                <label for="last">No HandPhone</label>
                                <input type="text" class="form-control" placeholder="Masukan No HandPhone ANda" value="{{ old('nohp', $user->no_hp) }}" name=" nohp">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">Show</button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation')">Show</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Role</label>
                                <select name="roles" class="form-control">
                                    @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                <option value="approved" {{ $user->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ $user->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success  me-2">Simpan</button>
                                <a href="{{ route('users.index') }}" class="btn btn-warning">Back</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- / Content -->
</div>

@endsection

@section('javascript')
<script>
    function togglePassword(fieldId) {
        var passwordField = document.getElementById(fieldId);
        var toggleButton = passwordField.nextElementSibling.querySelector("button");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleButton.textContent = "Hide";
        } else {
            passwordField.type = "password";
            toggleButton.textContent = "Show";
        }
    }
</script>

@endsection