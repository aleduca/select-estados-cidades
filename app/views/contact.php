<?php $this->layout('master', ['title' => $title]) ?>

<h2>Contact</h2>
<form action="/contact" method="post">
    <input type="text" name="name" placeholder="nome" value="<?php echo $this->e(getOld('name')); ?>"> <?php echo getFlash('name'); ?> <br>
    <input type="text" name="email" placeholder="email" value="<?php echo $this->e(getOld('email')); ?>"> <?php echo getFlash('email'); ?> <br>
    <input type="text" name="subject" placeholder="subject" value="<?php echo getOld('subject'); ?>"> <?php echo getFlash('subject'); ?> <br>
    <textarea name="message" id="" cols="30" rows="10" placeholder="message"><?php echo getOld('message'); ?></textarea> <?php echo getFlash('message'); ?> <br>
    <button type="submit">Send</button>
</form>
