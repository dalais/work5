<?php include __DIR__ . '/../layout/header.php'; ?>

    <header class="masthead">
        <div class="container">
            <div class="text-center mt-4 col-sm-4">
                <form action="/profile/update" method="POST">
                    <h2>Изменить данные</h2>
                    <?php if (flash('error')) : ?>
                        <small class="form-text text-danger"><?php echo flash('error') ?></small>
                    <?php elseif (flash('success')) : ?>
                        <small class="form-text text-success"><?php echo flash('success') ?></small>
                    <?php else : ?>
                        <small class="form-text text-white">&nbsp;</small>
                    <?php endif ?>
                    <div class="form-group">
                        <label for="inputFirstname">Имя</label>
                        <input type="text"
                               name="firstname"
                               class="form-control"
                               id="inputFirstname"
                               placeholder="Имя"
                               value="<?php echo isset($user['firstname']) ? $user['firstname'] : null ?>">
                        <small id="emailHelp"
                               class="form-text text-danger"><?php echo flash('firstname') ?? "&nbsp;" ?></small>
                    </div>
                    <div class="form-group">
                        <label for="inputLastname">Фамилия</label>
                        <input type="text"
                               name="lastname"
                               class="form-control"
                               id="inputLastname"
                               placeholder="Фамилия"
                               value="<?php echo isset($user['lastname']) ? $user['lastname'] : null ?>">
                        <small class="form-text text-danger"><?php echo flash('lastname') ?? "&nbsp;" ?></small>
                    </div>
                    <div class="form-group">
                        <label for="inputMiddlename">Отчество</label>
                        <input type="text"
                               name="middlename"
                               class="form-control"
                               id="inputMiddlename"
                               placeholder="Отчество"
                               value="<?php echo isset($user['middlename']) ? $user['middlename'] : null ?>">
                        <small class="form-text text-danger"><?php echo flash('middlename') ?? "&nbsp;" ?></small>
                    </div>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </form>
            </div>
            <hr>
            <div class="text-center mt-4 col-sm-4">
                <form action="/profile/psw_change" method="POST">
                    <h2>Сменить пароль</h2>
                    <?php if (flash('error')) : ?>
                        <small class="form-text text-danger"><?php echo flash('error') ?></small>
                    <?php elseif (flash('success')) : ?>
                        <small class="form-text text-success"><?php echo flash('success') ?></small>
                    <?php else : ?>
                        <small class="form-text text-white">&nbsp;</small>
                    <?php endif ?>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Пароль">
                        <small class="form-text text-danger"><?php echo flash('password') ?? "&nbsp;" ?></small>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Подтверждение пароля</label>
                        <input type="password" name="confirm" class="form-control" id="confirmPassword"
                               aria-describedby="emailHelp" placeholder="Подтверждение">
                        <small class="form-text text-danger"><?php echo flash('confirm') ?? "&nbsp;" ?></small>
                    </div>
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </form>
            </div>
        </div>
    </header>

<?php include __DIR__ . '/../layout/footer.php'; ?>