<?php echo getFlash('message_forget', 'color:red'); ?>

<form action="/forget/password" method="post">
    <input type="text" name="email" id="">
    <button type="submit">Forget my password</button>
</form>
