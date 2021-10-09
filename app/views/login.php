<?php $this->layout('master', ['title' => $title]) ?>
<div id="box-login">
    <?php if (logged(LOGGED_SESSION)) : ?>
        <h3>Você já está logado !</h3>
        <a href="/logout">Logout</a>
    <?php else : ?>
        <h2>Login</h2>

        <?php echo getFlash('message', 'color:white'); ?>
        <form action="/login/store" method="POST">
            <input type="text" name="email" placeholder="Digite seu email" value="casimir15@reichert.com">
            <input type="password" name="password" placeholder="Digite sua senha" value="123">
            <button type="submit" class="button-green">Login</button>
        </form>
        <small><a href="/forget/password">Esqueci a senha</a></small>
    <?php endif ?>
</div>
