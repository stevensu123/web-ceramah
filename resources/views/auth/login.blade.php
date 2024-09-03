<!doctype html>
<html lang="en">

<head>
  <title>Login 04</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="{{asset ('assets/login/css/style.css')}}">

</head>

<body>
  <section class="ftco-section" style="background-image: url(assets/login/images/bg.jpg)">
    <div class="container">
      <!-- <div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">Login #04</h2>
				</div>
			</div> -->
      <div class="card-container">

        <div class="row justify-content-center">
          <div class="col-md-12 col-lg-10">
            <div class="wrap d-md-flex">
              <div class="img" style="background-image: url(assets/login/images/bg.jpg);">
              </div>
              <div class="login-wrap p-4 p-md-5">
                <div class="d-flex">
                  <div class="w-100">
                    <h3 class="mb-4">Sign In</h3>
                  </div>
                  @if (session('status'))
                  <div class="alert alert-info" role="alert">
                    {{ session('status') }}
                  </div>
                  @endif
                  <!-- <div class="w-100">
                                        <p class="social-media d-flex justify-content-end">
                                            <a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-facebook"></span></a>
                                            <a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-twitter"></span></a>
                                        </p>
                                    </div> -->
                  <div class="w-100">
                    <a href="{{route('register') }}" class="big btn btn-primary"><i class="fa fa-plus"></i> Register</a>
                  </div>

                </div>

                <form method="POST" action="{{ route('login') }}" class="signin-form">
                  @csrf
                  <div class="form-group mb-3">
                    <label class="label" for="name">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="username" required>
                  </div>
                  <div class="form-group mb-3">
                    <label class="label" for="password">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="password" required>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="form-control btn btn-primary rounded submit px-3">Sign In</button>
                  </div>
                  <div class="form-group d-md-flex">
                    <div class="w-50 text-left">
                      <label class="checkbox-wrap checkbox-primary mb-0">Remember Me
                        <input type="checkbox" checked>
                        <span class="checkmark"></span>
                      </label>
                    </div>
                    <div class="w-50 text-md-right">
                      <a href="#">Forgot Password</a>
                    </div>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="{{asset ('assets/login/js/jquery.min.js')}}"></script>
  <script src="js/popper.js"></script>
  <script src="{{asset ('assets/login/js/bootstrap.min.js')}}"></script>
  <script src="{{asset ('assets/login/js/main.js')}}"></script>

</body>

</html>