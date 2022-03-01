@extends('app')

@section('content')

<br />
<br />

<div class="row">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <h4>Silahkan Login</h4>
                        </div>

                        <form id="login" method="post" action="{{ $action }}" enctype="multipart/form-data">
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                                </div>

                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary mb-3">Login</button>
                                    <button type="reset" class="btn btn-warning mb-3">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-3"></div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    $(document).on('submit', 'form#login', function() {
        event.preventDefault();
        let _url = $(this).attr('action');
        let _data = new FormData($(this)[0]);
        send((data, xhr = null) => {
            if (data.status == 200) {
                Swal.fire({
                    type: 'success',
                    title: "Login Sukses",
                    text: data.messages,
                    timer: 3000,
                    icon: 'success',
                    showCancelButton: false,
                    showConfirmButton: false
                }).then(function() {
                    window.location.href = data.url;
                });
            } else if (data.status == 422) {
                FailedNotif(data.messages);
            }
        }, _url, 'json', 'post', _data)
    })
</script>
@endpush

@endsection