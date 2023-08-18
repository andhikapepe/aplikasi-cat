    <div class="card">
        <article class="card-body">
            <div class="text-center">
                <img class="mb-4" src="<?= base_url('assets/img/logo.png') ?>" alt="logo" width="72" height="72">
                <h4 class="card-title mt-1 mb-4">Login CAT</h4>
                <h6 class="card-subtitle mb-2 text-muted">Computer Assisted Test</h6>
            </div>
            <hr>
            <div class="form-group">
                <label>Username</label>
                <input name="username" id="uname" class="form-control" type="text" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input name="password" id="pwd" class="form-control" type="password" required>
            </div>
            <div class="form-group">
                <button id="login" class="btn btn-primary btn-block"> Proses </button>
            </div>

            <script>
                document.getElementById("uname")
                    .addEventListener("keyup", function(event) {
                        event.preventDefault();
                        if (event.keyCode === 13) {
                            document.getElementById("login").click();
                        }
                    });

                document.getElementById("pwd")
                    .addEventListener("keyup", function(event) {
                        event.preventDefault();
                        if (event.keyCode === 13) {
                            document.getElementById("login").click();
                        }
                    });

                document.getElementById("login").onclick = function() {
                    let uname = document.getElementById('uname').value
                    let pwd = document.getElementById('pwd').value
                    if (uname == '' || pwd == '') {
                        toastr.warning('kolom username/password tidak boleh kosong!')
                    } else {
                        proses_login(uname, pwd)
                    }
                }

                function proses_login(uname, pwd) {
                    let url = "<?= site_url('proses-login') ?>"
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            'uname': uname,
                            'pwd': pwd
                        },
                        dataType: 'Json',
                        cache: false,
                        success: function(data) {
                            if (data.status == false) {
                                toastr.error(data.pesan_gagal)
                            } else {
                                toastr.success(data.pesan_sukses)
                                if (data.is_admin == true) {
                                    window.setTimeout(function() {
                                        window.location = "<?= base_url('dashboard-admin') ?>";
                                    }, 3000)
                                } else {
                                    window.setTimeout(function() {
                                        window.location = "<?= base_url('ujian') ?>";
                                    }, 3000)
                                }
                            }
                        }
                    })
                }
            </script>
        </article>
    </div>