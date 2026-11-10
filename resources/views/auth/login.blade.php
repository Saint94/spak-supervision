<!DOCTYPE html>
<html lang="fr" class="loading">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="description" content="Apex admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Apex admin template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="PIXINVENT">
        <title>Spak Management | Connexion</title>

        <link rel="apple-touch-icon" sizes="152x152" href="{{ url('assets/img/favicon/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ url('assets/img/favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ url('assets/img/favicon/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ url('assets/img/favicon/site.webmanifest') }}">
        <link rel="mask-icon" href="{{ url('assets/img/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-touch-fullscreen" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700,900|Montserrat:300,400,500,600,700,800,900" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ url('assets/fonts/feather/style.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('assets/fonts/simple-line-icons/style.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('assets/fonts/font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('assets/vendors/css/perfect-scrollbar.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('assets/vendors/css/prism.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('assets/css/app.css') }}">
    </head>

    <body data-col="1-column" class=" 1-column  blank-page">
        <div class="wrapper nav-collapsed menu-collapsed">
            <div class="main-panel">
                <div class="main-content">
                    <div class="content-wrapper">
                        <section id="login">
                            <div class="container-fluid">
                                <div class="row full-height-vh m-0">
                                    <div class="col-12 d-flex align-items-center justify-content-center">
                                        <div class="card">
                                            <div class="card-content">
                                                <div class="card-body login-img">
                                                    <div class="row m-0">
                                                        <div class="col-lg-6 d-lg-block d-none py-2 text-center align-middle">
                                                            <img src="{{ url('assets/img/gallery/login.png') }}" alt="" class="img-fluid mt-5" width="400" height="230">
                                                        </div>

                                                        <div class="col-lg-6 col-md-12 bg-white px-4 pt-3">

                                                        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                                                            @csrf

                                                            <h4 class="mb-2 card-title">SE CONNECTER</h4>
                                                            <p class="card-text mb-3">
                                                                Connectez-vous avec votre compte habituel.
                                                            </p>

                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                    <input type="text" class="form-control mb-1" id="username" name="username" value="{{ old('username') }}" placeholder="Nom d'utilisateur" required />
                                                                    <div class="invalid-feedback">
                                                                        Ce champ est obligatoire.
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                    <input type="password" class="form-control mb-1" id="password" name="password" autocomplete="current-password" placeholder="Mot de passe" required/>
                                                                    <div class="invalid-feedback">
                                                                        Ce champ est obligatoire.
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex justify-content-between mt-2">
                                                                <div class="remember-me">
                                                                    <div class="custom-control custom-checkbox custom-control-inline mb-3">
                                                                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} class="custom-control-input" />
                                                                        <label class="custom-control-label" for="remember">
                                                                            Se rappeler de moi
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="forgot-password-option">
                                                                <a href="#" class="text-decoration-none text-primary">Mot de passe oublié
                                                                    ?</a>
                                                                </div>
                                                            </div>
                                                            <div class="fg-actions d-flex justify-content-between">
                                                                <div class="recover-pass">
                                                                    <button type="submit" class="btn btn-primary" class="text-decoration-none text-white">
                                                                        Se connecter
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <hr class="m-0">

                                                            <p><a style="text-align: right;" href="https://diff-itc.com/" target="_blank">www.diff-itc.net</a></p>
                                                        </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ url('assets/vendors/js/core/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
        <script src="{{ url('assets/vendors/js/core/popper.min.js') }}" type="text/javascript"></script>
        <script src="{{ url('assets/vendors/js/core/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ url('assets/vendors/js/perfect-scrollbar.jquery.min.js') }}" type="text/javascript"></script>
        <script src="{{ url('assets/vendors/js/prism.min.js') }}" type="text/javascript"></script>
        <script src="{{ url('assets/vendors/js/jquery.matchHeight-min.js') }}" type="text/javascript"></script>
        <script src="{{ url('assets/vendors/js/screenfull.min.js') }}" type="text/javascript"></script>
        <script src="{{ url('assets/vendors/js/pace/pace.min.js') }}" type="text/javascript"></script>
        <script src="{{ url('assets/js/app-sidebar.js') }}" type="text/javascript"></script>
        <script src="{{ url('assets/js/notification-sidebar.js') }}" type="text/javascript"></script>
        <script src="{{ url('assets/js/customizer.js') }}" type="text/javascript"></script>

        <script>
            // Example starter JavaScript for disabling form submissions if there are invalid fields
                (function () {
                'use strict'

                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.querySelectorAll('.needs-validation')

                // Loop over them and prevent submission
                Array.prototype.slice.call(forms)
                    .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                    })
                })()
        </script>
    </body>
</html>
