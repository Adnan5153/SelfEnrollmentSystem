

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>

<style>
    body {
        background-image: url("{{ asset('images/logologin.png') }}");
        background-position: center;
        background-size: 40%;
        background-repeat: no-repeat;
    }
</style>




<!-- =================================== Error Handling =================================== -->



<!-- =================================== Error Handling =================================== -->

<div style="display: flex; justify-content: center; align-items: center; height: 80vh;">
    <div class="card ms-auto me-auto" style="width: 800px; background-color: rgba(255, 255, 255, 0.5);">
        <div class="card-body">
            <!-- =================================================== Form Section =================================================== -->
            <form action="{{ route('teacher.login') }}" method="POST" class="ms-auto me-auto mt-auto" style="width: 500px;">
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label" style="font-weight: bolder;">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="example@mail.com" aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label" style="font-weight: bolder;">Password</label>
                    <input type="password" class="form-control" placeholder="Password" id="exampleInputPassword1" name="password">
                </div>
                <button type="submit" class="btn btn-primary" href="dashboard.blade.php">Submit</button>
            </form>
        </div>
    </div>
</div>









