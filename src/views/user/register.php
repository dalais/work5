<?php include __DIR__ . '/../layout/header.php'; ?>

    <header class="masthead">
        <div class="container">
            <div class="text-center mt-4 col-sm-4">
                <form action="/auth/register" method="POST">
                    <h2>Регистрация</h2>
                    <div class="form-group">
                        <small id="emailHelp" class="form-text text-danger"><?php echo flash('error') ?? "&nbsp;" ?></small>
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Введите email адрес">
                        <small id="emailHelp" class="form-text text-danger"><?php echo flash('email') ?? "&nbsp;" ?></small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Пароль</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Пароль">
                        <small id="emailHelp" class="form-text text-danger"><?php echo flash('password') ?? "&nbsp;" ?></small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Подтверждение пароля</label>
                        <input type="password" name="confirm" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Подтверждение">
                        <small id="emailHelp" class="form-text text-danger"><?php echo flash('confirm') ?? "&nbsp;" ?></small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Имя</label>
                        <input type="text" name="firstname" class="form-control" id="exampleInputPassword1" placeholder="Имя">
                        <small id="emailHelp" class="form-text text-danger"><?php echo flash('firstname') ?? "&nbsp;" ?></small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Фамилия</label>
                        <input type="text" name="lastname" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Фамилия">
                        <small class="form-text text-danger"><?php echo flash('lastname') ?? "&nbsp;" ?></small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Отчество</label>
                        <input type="text" name="middlename" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Отчество">
                        <small class="form-text text-danger"><?php echo flash('middlename') ?? "&nbsp;" ?></small>
                    </div>
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </form>
            </div>
        </div>
    </header>

<?php include __DIR__ . '/../layout/footer.php'; ?>