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
                    <h3 class="mb-4">Register</h3>
                  </div>
                 
                  <!-- <div class="w-100">
                                        <p class="social-media d-flex justify-content-end">
                                            <a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-facebook"></span></a>
                                            <a href="#" class="social-icon d-flex align-items-center justify-content-center"><span class="fa fa-twitter"></span></a>
                                        </p>
                                    </div> -->
                </div>

                <form method="POST" action="{{ route('register') }}" class="signin-form">
                  @csrf
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group @error('name') is-invalid @enderror">
                        <label class="label">Nama</label>
                        <input class="form-control" type="text" name="name">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group @error('no_hp') is-invalid @enderror">
                        <label class="label">No Hp</label>
                        <input class="form-control" type="text" name="no_hp">
                        @error('no_hp')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                      </div>
                    </div>
                  </div>
                  <div class="form-group mb-3 @error('username') is-invalid @enderror">
                    <label class="label" for="name">Username</label>
                    <input type="text" class="form-control" placeholder="username" name="username" required>
                    @error('username')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="form-group mb-3 @error('username') is-invalid @enderror">
                    <label class="label" for="password">Password</label>
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="form-group mb-3">
                    <label for="password-confirm" class="label">{{ __('Confirm Password') }}</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                  </div>
                  <div class="form-group">
                    <button type="submit" class="form-control btn btn-primary rounded submit px-3">Daftar</button>
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