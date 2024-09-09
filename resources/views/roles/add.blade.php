@extends('layouts.app')

@section ('content')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Kategori</h4>
        <!-- <hr class="my-5" /> -->
        <!-- Bordered Table -->
        <div class="card">
            <div class="row">
                <div class="col">
                    <div class="card-header">
                        <h2 class="tmbh-data-title">Bordered Table</h2>
                    </div>

                </div>
                <div class="col">
                    <div class="d-flex container-tmbh-data">
                        <a class="btn btn-primary" href="{{route('roles.create')}}" role="button">Tambah Role</a>
                    </div>
                </div>
            </div>
            <hr class="" />
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <form action="{{ route ('roles.store')}}" method="POST">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="input_role_name" class="font-weight-bold">
                                            Role name
                                        </label>
                                        <input id="input_role_name" value="{{old('name')}}" name="name" type="text" 
                                        class="form-control @error('name') is-invalid @enderror"  />
                                        @error('name')
                                        <span class="invalid-feedback">
                                            <strong>{{$message}}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="input_role_permission" class="font-weight-bold">
                                            Permission
                                        </label>
                                        <div class="form-control overflow-auto h-100 @error('permissions') is-invalid @enderror" id="input_role_permission">
                                            <!-- Flex container untuk manage name -->
                                            <div class="d-flex flex-row justify-content-start">
                                                @foreach ($authorities as $manageName => $permissions)
                                                <!-- Setiap manage name dan permissions -->
                                                <div class="mx-2">
                                                    <!-- Judul Manage (manage_kategori / manage_user) -->
                                                    <div class="list-group-item bg-dark text-white text-center mb-2">
                                                        {{$manageName}}
                                                    </div>

                                                    <!-- Permissions (kategori_show, kategori_create) ditampilkan ke bawah -->
                                                    <ul class="list-group">
                                                        @foreach ($permissions as $permission)
                                                        <li class="list-group-item">
                                                            <div class="form-check">
                                                                @if(old('permissions'))
                                                                <input id="{{$permission}}" name="permissions[]" class="form-check-input" type="checkbox"
                                                                value="{{$permission}}" {{in_array($permission,old('permissions')) ? "checked" : null}}>
                                                                @else
                                                                <input id="{{$permission}}" name="permissions[]" class="form-check-input" type="checkbox"
                                                                value="{{$permission}}" >
                                                                @endif
                                                                <label for="{{$permission}}" class="form-check-label">
                                                                    {{$permission}}
                                                                </label>
                                                            </div>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @error('permissions')
                                        <span class="invalid-feedback">
                                            <strong>{{$message}}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="float-right mb-4">
                                        <a class="btn btn-warning px-4 mx-2" href="">
                                            Back
                                        </a>
                                        <button type="submit" class="btn btn-primary px-4">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--/ Bordered Table -->
    </div>
</div>

@endsection

@section('javascript')
@endsection