<?php $this->layout('master', ['title' => $title]) ?>

<h2>Edit User</h2>

<?php echo getFlash('edit_success', 'background-color: green;color: white;padding:5px'); ?>
<?php echo getFlash('edit_error', 'background-color: red;color: white;padding:5px'); ?>

<form action="/user/<?php echo $user->id; ?>" method="post">
    <input type="text" name="firstName" placeholder="Seu nome" value="<?php echo $user->firstName; ?>">
    <?php echo getFlash('firstName'); ?>
    <br>
    <input type="text" name="lastName" placeholder="Seu sobrenome" value="<?php echo $user->lastName; ?>">
    <?php echo getFlash('lastName'); ?>
    <br>
    <input type="text" name="email" placeholder="Seu email"value="<?php echo $user->email; ?>">
    <?php echo getFlash('email'); ?>
    <br>
    <button type="submit">Update</button>
</form>

<hr>

<img src="/<?php echo $user->path; ?>" alt="">

<form action="/user/image/update" method="post" enctype="multipart/form-data">
    <input type="file" name="file" id="">
    <button type="submit">Upload</button>
</form>