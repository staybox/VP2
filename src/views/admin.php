<?php
include_once ("header.php");
?>

<div class="container">

    <div class="form-container">
        <form enctype="multipart/form-data" class="form-horizontal" action="/admin/add" method="post">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Логин</label>
                <div class="col-sm-10">
                    <input type="text" name="login" class="form-control" id="inputEmail3" placeholder="Логин">
                </div>
            </div>
            <div class="form-group">
                <label for="Email_Parse" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
                    <input type="text" name="Email_Parse" class="form-control" id="Email_Parse" placeholder="Email">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">Пароль</label>
                <div class="col-sm-10">
                    <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Пароль">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword4" class="col-sm-2 control-label">Пароль (Повтор)</label>
                <div class="col-sm-10">
                    <input type="password" name="confirm_password" class="form-control" id="inputPassword4" placeholder="Пароль">
                </div>
            </div>
            <div class="form-group">
                <label for="inputName" class="col-sm-2 control-label">Имя</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" id="inputname" placeholder="Имя">
                </div>
            </div>
            <div class="form-group">
                <label for="inputAge" class="col-sm-2 control-label">Возраст</label>
                <div class="col-sm-10">
                    <input type="text" name="age" class="form-control" id="inputAge" placeholder="Возраст">
                </div>
            </div>
            <div class="form-group">
                <label for="inputDescription" class="col-sm-2 control-label">Описание</label>
                <div class="col-sm-10">
                    <input type="text" name="desc" class="form-control" id="inputDescription" placeholder="Описание">
                </div>
            </div>
            <div class="form-group">
                <label for="inputDescription" class="col-sm-2 control-label">Загрузка файла</label>
                <div class="col-sm-10">
                    <input name="userfile" type="file" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">Добавить пользователя</button>
                    <br><br>
                    Зарегистрированы? <a href="/">Авторизируйтесь</a>
                </div>
            </div>
        </form>
    </div>

</div><!-- /.container -->

<div class="container">
    <h1>Пользователи из базы данных</h1>
    <h2>Данные для редактирования</h2>
    <table class="table table-bordered">
        <tr>
            <th>Пользователь(логин)</th>
            <th>Email</th>
            <th>Имя</th>
            <th>возраст</th>
            <th>описание</th>
            <th>Фотография</th>
            <th>Действия</th>
        </tr>
        <? foreach ($users as $key=>$value): ?>
            <tr>
                <td><?=$value['login'];?></td>
                <td><?=$value['email'];?></td>
                <td><?=$value['name'];?></td>
                <td><?=$value['age'];?></td>
                <td><?=$value['description'];?></td>
                <td><img src="<?=$value['photo'];?>" alt=""></td>
                <td>
                    <a href="?remove_user_id=<?=$value['user_id'];?>">Удалить пользователя</a>
                </td>
            </tr>
        <? endforeach; ?>
    </table>
</div><!-- /.container -->
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="../../public/js/main.js"></script>
<script src="../../public/js/bootstrap.min.js"></script>

</body>
</html>
