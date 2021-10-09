<?php $this->layout('master', ['title' => $title]) ?>

<h2>Create User</h2>
<?php echo getFlash('message_store_error', 'background-color: crimson;color: white;padding:5px'); ?>
<form action="/user/store" method="post">
    <?php echo getCsrf(); ?>
    <input type="text" name="firstName" placeholder="Seu nome" value="Alexandre">
    <?php echo getFlash('firstName'); ?>
    <br>
    <input type="text" name="lastName" placeholder="Seu sobrenome" value="Cardoso">
    <?php echo getFlash('lastName'); ?>
    <br>
    <input type="text" name="email" placeholder="Seu email" value="xandecar@hotmail.com">
    <?php echo getFlash('email'); ?>
    <br>
    <input type="password" name="password" placeholder="Sua senha" value="123">
    <?php echo getFlash('password'); ?>
    <br>
    <button type="submit">Create</button>
</form>
