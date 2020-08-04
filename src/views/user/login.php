<?php include __DIR__ . '/../layout/header.php'; ?>

    <header class="masthead">
        <div class="container">
            <div class="text-center mt-4 col-sm-4">
                <h2>Вход</h2>
                <form action="/auth/login" method="POST">
                    <div class="form-group">
                        <small class="form-text text-danger"><?php echo flash('error') ?? "&nbsp;" ?></small>
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Введите email адрес">
                        <small class="form-text text-danger"><?php echo flash('email') ?? "&nbsp;" ?></small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Пароль</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Пароль">
                        <small class="form-text text-danger"><?php echo flash('password') ?? "&nbsp;" ?></small>
                    </div>
                    <button type="submit" class="btn btn-primary">Войти</button>
                </form>
            </div>
        </div>
    </header>
<?php var_dump($_SESSION);?>
<?php include __DIR__ . '/../layout/footer.php'; ?>