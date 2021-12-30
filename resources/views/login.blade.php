<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>登入-{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <script src="{{ asset('js/all.min.js') }}"></script>
</head>
<body class="bg-primary">
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header"><h3 class="text-center font-weight-light my-4">彰化 GSuite 登入</h3></div>
                            <div class="card-body">
                                <form action="{{ route('g_auth') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputUsername">帳號@chc.edu.tw</label>
                                        <input class="form-control py-4" id="inputUsername" type="text" name="username" placeholder="彰化 GSuite 帳號" required autofocus value="{{ old('username') }}" />
                                    </div>
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputPassword">密碼</label>
                                        <input class="form-control py-4" id="inputPassword" type="password" name="password" placeholder="請輸入密碼" required />
                                    </div>
                                    @include('layouts.errors')
                                    <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                                        <a class="btn btn-secondary" href="{{ route('index') }}">返回</a>
                                        <button class="btn btn-primary">登入</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center">
                                <div class="small">
                                    <img src="{{ asset('images/gsuite_logo.png') }}" height="40">
                                    <a href="https://gsuite.chc.edu.tw/" target="_blank">彰化 GSuite 帳號申請</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--
                    <div class="col-lg-5">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                                        <div class="card-header"><h3 class="text-center font-weight-light my-4">彰化 OpenID 登入</h3></div>
                                        <div class="card-body">
                                            <a class="btn btn-primary" href="{{ url('/openid/authcontrol.php') }}">授權</a>
                                        </div>
                                        <div class="card-footer text-center" style="background-color: #ffffff">
                                            <div class="small">
                                                <img src="{{ asset('images/openid_logo.png') }}" height="40">
                                                <a href="http://openid.chc.edu.tw/" target="_blank">彰化 OpenID</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                                        <div class="card-header"><h3 class="text-center font-weight-light my-4">彰化 cloudschool 登入</h3></div>
                                        <div class="card-body">
                                            <div class="container">
                                                <form action="{{ route('cloudschool_auth') }}" method="post">
                                                <div class="row">
                                                        <div class="col-8">
                                                                @csrf
                                                                <select class="form-control" name="school_code" required>
                                                                    <option value="">
                                                                        --請選擇學校--
                                                                    </option>
                                                                    @foreach($select_school as $k=>$v)
                                                                        <option value="{{ $k }}">
                                                                            {{ $k }} - {{ $schools[$k] }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                        </div>
                                                        <div class="col-4">
                                                            <button class="btn btn-primary">授權</button>
                                                        </div>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="card-footer text-center" style="background-color: #0073b7">
                                            <div class="small">
                                                <img src="{{ asset('images/cloudschool_logo.png') }}" height="40">
                                                <a href="https://cloudschool.chc.edu.tw/" target="_blank"><span style="color: white">彰化 cloudschool 校務系統</span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    -->
                </div>
            </div>
        </main>
    </div>
    <div id="layoutAuthentication_footer">
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Your Website 2020</div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
<script src="{{ asset('js/jquery-3.5.1.slim.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
</body>
</html>
