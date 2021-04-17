@extends('admin.layouts.master')

@section('subtitle')
Manajemen Akun
@endsection

@section('css')
<link href="{{ asset('highdmin/libs/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('highdmin/libs/datatables/buttons.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('highdmin/libs/datatables/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('highdmin/libs/custombox/custombox.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card-box table-responsive">
            <h4 class="header-title pb-2">DATA AKUN ANDA</h4>
            <style>
                @media only screen and (max-width: 600px) {
                    table {
                        text-align: justify !important;
                    }
                }

                @media only screen and (min-width: 600px) {
                    table {
                        text-align: justify !important;
                    }
                }

                @media only screen and (min-width: 992px) {
                    table {
                        text-align: center !important;
                    }
                }

                #datatable-buttons-auth th {
                    border: 1px solid #f0f0f0 !important;
                }

                #datatable-buttons-auth td {
                    background: #ffff !important;
                    border: 1px solid #f0f0f0 !important;
                }
            </style>
            <table id="datatable-buttons-auth"
                class="table table-striped table-bordered dt-responsive nowrap thead-dark"
                style="border-radius:30px; width: 100%;">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ Auth::user()->name }}</td>
                        <td>{{ Auth::user()->username }}</td>
                        <td>{{ Auth::user()->email }}</td>
                        <td><span class="badge badge-success font-15">{{ Auth::user()->role }}</span></td>
                        <td>
                            <div class="form-group">
                                <button type="button" data-role="{{ Auth::user()->role }}" id="{{ Auth::user()->role }}"
                                    class="btn btn-info btn-edit waves-effect waves-light editButton mr-2"
                                    data-toggle="modal" data-target=".editModal"
                                    onclick="setEditData({{ Auth::user() }})">Edit
                                </button>
                                <a href="{{ route('users.edit', Auth::user()->id) }}"
                                    class="btn btn-dark waves-effect waves-light" id="update-password">Ubah
                                    Password</a>
                            </div>
                            <div class="form-group">

                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-box table-responsive">
            <h4 class="header-title">Data Users</h4>
            <p class="sub-header">
                @if(Auth::user()->role == 'admin')
                <div class="button-list">
                    <button type="button" id="createButton"
                        class="btn btn-primary btn-sm btn-create waves-light waves-effect" data-toggle="modal"
                        data-animation="slide" data-overlaySpeed="200" data-overlayColor="#36404a"
                        data-target=".createModal">Tambah</button>
                    <button type="button" class="btn btn-danger btn-sm waves-light waves-effect"
                        id="bersihkan-semua-data">Bersihkan
                    </button>
                </div>
                @endif

            </p>

            <style>
                @media only screen and (max-width: 600px) {
                    table {
                        text-align: justify !important;
                    }
                }

                @media only screen and (min-width: 600px) {
                    table {
                        text-align: justify !important;
                    }
                }

                @media only screen and (min-width: 992px) {
                    table {
                        text-align: center !important;
                    }
                }
            </style>

            <table id="datatable-user" class="table table-striped table-bordered dt-responsive nowrap"
                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        @if(Auth::user()->role == 'admin')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>

                <tbody>

                    @php
                    $increment = 1;
                    @endphp

                    @foreach($users as $user)
                    <tr>
                        <td>{{ $increment++ }} </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge badge-success font-15">{{ $user->role }}</span></td>
                        @if(Auth::user()->role == 'admin')
                        <td>
                            <div class="form-group">
                                <button type="button" data-role="{{ $user->role }}" id="{{ $user->role }}"
                                    class="btn btn-warning rounded btn-edit waves-effect waves-light editButton"
                                    data-toggle="modal" data-target=".editModal" onclick="setEditData({{ $user }})">Edit
                                </button>
                                <form style="display: inline" action="{{ route('users.destroy', $user) }}"
                                    method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="button"
                                        class="btn btn-danger rounded waves-light waves-effect buttonHapus"
                                        onclick="deleteAlert(this)">Hapus
                                    </button>
                                </form>

                            </div>
                            <div class="form-group">
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="btn btn-dark waves-effect waves-light" id="update-password">Ubah
                                    Password</a>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Create modal -->
<style>
    .add-modal {
        overflow-y: scroll !important;
    }

    textarea {
        border: 1px solid #e3eaef !important;
        border-radius: 5px;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out, -webkit-box-shadow .15s ease-in-out;
    }
</style>

<!-- create modal pop up -->
<div class="modal fade createModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mySmallModalLabel">Tambah User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ route('users.store') }}" method="post">
                    @csrf

                    <div class="form-group">
                        <div class="col-12">
                            <label for="election">Nama Lengkap</label>
                            <input class="form-control mb-1 @error('name') is-invalid @enderror" type="text" id="name"
                                placeholder="Contoh: Briana White" name="name" value="{{ old('name') }}" required>

                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12">
                            <label for="period">Username</label>
                            <input class="form-control mb-1 @error('username') is-invalid @enderror" type="text"
                                id="username" placeholder="Contoh: briana67" name="username"
                                value="{{ old('username') }}" required>
                            @error('username')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12">
                            <label for="period">Email</label>
                            <input class="form-control mb-1 @error('email') is-invalid @enderror" minlength="3"
                                maxlength="35" type="email" id="email" placeholder="Contoh: briana67@gmail.com"
                                name="email" value="{{ old('email') }}" required>
                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12">
                            <label for="period">Password</label>
                            <div class="input-group mb-1">
                                <input class="form-control @error('password') is-invalid @enderror" type="password"
                                    name="password" id="passwordId" placeholder="Masukan Password Anda">
                                <div class="input-group-append">
                                    <button
                                        class="btn btn-secondary fas fa-eye toggle-password @error('password') btn-danger @enderror"
                                        type="button"></button>
                                </div>
                            </div>
                            @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12">
                            <label for="period">Password Confirmation</label>
                            <div class="input-group mb-1">
                                <input class="form-control @error('password') is-invalid @enderror" type="password"
                                    name="password_confirmation" id="passwordConfirm"
                                    placeholder="Masukan Konfirmasi Password Anda">
                                <div class="input-group-append">
                                    <button
                                        class="btn btn-secondary fas fa-eye toggle-password-confirm @error('password') btn-danger @enderror"
                                        type="button"></button>
                                </div>
                            </div>
                            @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12">
                            <label for="period">Role</label>
                            <select class="form-control" name="role" id="role" required>
                                <option value="admin">Admin</option>
                                <option value="panitia">Panitia</option>
                            </select>

                            @error('role')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group account-btn text-center mt-2">
                        <div class="col-12">
                            <button class="btn width-lg btn-rounded btn-primary waves-effect waves-light"
                                type="submit">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- edit modal pop up -->
<div class="modal fade editModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mySmallModalLabel">Edit User</h4> <span
                    class="badge badge-light font-15 ">@if(Auth::user()->role == 'admin')akses admin @else akses panitia
                    @endif</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ route('users.update', '') }}" id="edit-form" method="POST">
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <div class="col-12">
                            <label for="name">Nama Lengkap</label>
                            <input class="form-control mb-1 @error('edit_name') is-invalid @enderror" type="text"
                                id="edit_name" placeholder="Contoh: Briana White" name="edit_name"
                                value="{{ old('edit_name') }}" required>

                            @error('edit_name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12">
                            <label for="username">Username</label>
                            <input class="form-control mb-1 @error('edit_username') is-invalid @enderror" type="text"
                                id="edit_username" placeholder="Contoh: briana67" name="edit_username"
                                value="{{ old('edit_username') }}" required>
                            @error('edit_username')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12">
                            <label for="email">Email</label>
                            <input class="form-control mb-1 @error('edit_email') is-invalid @enderror" minlength="3"
                                maxlength="35" type="email" id="edit_email" placeholder="Contoh: briana67@gmail.com"
                                name="edit_email" value="{{ old('edit_email') }}" required>
                            @error('edit_email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if(Auth::user()->role == 'admin')
                    <div class="form-group">
                        <div class="col-12">
                            <label for="role">Role</label>
                            <select class="form-control mb-1 @error('edit_role') is-invalid @enderror" name="edit_role"
                                id="edit_role" required>
                                <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="panitia">Panitia</option>
                            </select>

                            @error('role')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @else
                    <div class="form-group">
                        <div class="col-12">
                            <label for="role">Role</label>
                            <select class="form-control mb-1 @error('edit_role') is-invalid @enderror" name="edit_role"
                                id="edit_role" required>
                                <option value="">Pilih Role</option>
                                <option value="panitia">Panitia</option>
                            </select>

                            @error('role')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <div class="col-12">

                        </div>
                    </div>
                    <div class="form-group account-btn text-center mt-2">
                        <div class="col-12">
                            <button class="btn width-lg btn-rounded btn-primary waves-effect waves-light"
                                type="submit">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- delete row modal pop up -->
<div class="modal fade deleteUser" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mySmallModalLabel">Hapus User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ route('users.destroy', '') }}" method="POST" id="hapusUser">
                    @csrf
                    @method('delete')
                    <div class="form-group account-btn text-center  ">
                        <div class="col-12">
                            <h3>Apakah anda yakin?</h3>
                            <p>Data akan dihapus secara permanen!</p>
                            <div class="btn-group py-2" role="group" aria-label="Basic example">
                                <button type="submit" class="btn btn-danger rounded py-1 mr-3">Hapus</button>
                                <button type="button" class="btn btn-secondary rounded" data-dismiss="modal"
                                    aria-label="Close">Kembali</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('js')
<!-- Required datatable js -->
<script src="{{ asset('highdmin/libs/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('highdmin/libs/datatables/dataTables.bootstrap4.min.js') }}"></script>
<!-- Buttons examples -->
<script src="{{ asset('highdmin/libs/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('highdmin/libs/datatables/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('highdmin/libs/datatables/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('highdmin/libs/datatables/dataTables.select.min.js') }}"></script>
<script src="{{ asset('highdmin/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('highdmin/libs/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('highdmin/libs/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('highdmin/libs/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('highdmin/libs/datatables/buttons.print.min.js') }}"></script>
<!-- Responsive examples -->
<script src="{{ asset('highdmin/libs/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('highdmin/libs/datatables/responsive.bootstrap4.min.js') }}"></script>
<!-- Datatables init -->
<script src="{{ asset('highdmin/js/pages/datatables.init.js') }}"></script>
<!-- Custombox modal -->
<script src="{{ asset('highdmin/libs/custombox/custombox.min.js') }}"></script>

<!-- keep the modal pop up if errors occur -->
@if ($errors->has('name') || $errors->has('username') || $errors->has('email') || $errors->has('password'))
<script>
    function showCreateModal() {
        $('.createModal').modal('show');
    }
    showCreateModal();
</script>
@endif

@if ($errors->has('edit_name') || $errors->has('edit_username') || $errors->has('edit_email'))
<script>
    function showCreateModal() {
        $('.editModal').modal('show');
    }
    showCreateModal();
</script>
@endif



<script>
    //  passing data to edit modal pop up 
    const updateLink = $('#edit-form').attr('action');

    function setEditData(user) {
        $('#edit-form').attr('action', `${updateLink}/${user.id}`);
        $('[name="edit_name"]').val(user.name);
        $('[name="edit_username"]').val(user.username);
        $('[name="edit_email"]').val(user.email);
        $('[name="edit_role"]').val(user.role);
    }
    
    // passing data to select option tag
    $(".editButton").click(function() {
        var idAsValue = $(this).attr('id');
        $("#edit_role").val(idAsValue);
    });

    // password show/hide toggle
    $(".toggle-password").click(function() {
        $(this).toggleClass("far fa-eye-slash");
        var password = document.getElementById("passwordId");
        if (password.type === "password") {
            password.type = "text";
        } else {
            password.type = "password";
        }

    });

    // password confirm show/hide toggle
    $(".toggle-password-confirm").click(function() {
        $(this).toggleClass("far fa-eye-slash");
        var passwordConfirm = document.getElementById("passwordConfirm");

        if (passwordConfirm.type === "password") {
            passwordConfirm.type = "text";

        } else {
            passwordConfirm.type = "password";
        }

    });

    // edit password show/hide toggle
    $(".toggle-edit-password").click(function() {
        $(this).toggleClass("far fa-eye-slash");
        var editPassword = document.getElementById("editPassword");

        if (editPassword.type === "password") {
            editPassword.type = "text";

        } else {
            editPassword.type = "password";
        }

    });
    
    // edit password confirm show/hide toggle
    $(".toggle-edit-password-confirm").click(function() {
        $(this).toggleClass("far fa-eye-slash");
        var editPasswordConfirm = document.getElementById("editPasswordConfirm");

        if (editPasswordConfirm.type === "password") {
            editPasswordConfirm.type = "text";

        } else {
            editPasswordConfirm.type = "password";
        }

    });
    
    // sweetalert
    $("#bersihkan-semua-data").click(function () {
        Swal.fire({
            title: "Bersihkan data user?",
            text: `Seluruh data terkait user akan ikut terhapus. Anda tidak akan dapat mengembalikan aksi
            ini!`,
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then(function (f) {
            if (f.value) {
                window.location.href = "{{ route('users.clear') }}"
            }
        })
    });

    function deleteAlert(e) {
        Swal.fire({
            title: "Hapus user?",
            text: `Seluruh data terkait user akan terhapus. Anda tidak akan dapat mengembalikan aksi
            ini!`,
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then(function (t) {
            if (t.value) {
                e.parentNode.submit()
            }
        })
    }
</script>
@endsection