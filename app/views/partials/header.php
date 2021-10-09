<ul id="menu_list">
      <li><a href="/">Home</a></li>
      <li><a href="/contact">Contact</a></li>
    <?php if (!logged(LOGGED_SESSION)) : ?>
      <li><a href="/login">Login</a></li>
      <li><a href="/user/create">Cadastrar</a></li>
    <?php endif; ?>
</ul>

<div id="status_login">
    Bem vindo,
    <?php if (logged(LOGGED_SESSION)) : ?>
        <img src="/<?php echo sessionData(LOGGED_SESSION)->path; ?>" alt="">
        <?php echo sessionData(LOGGED_SESSION)->firstName; ?> | <a href="/logout">Logout</a> | <a href="/user/<?php echo sessionData(LOGGED_SESSION)->id; ?>/edit">Editar meus dados</a>
    <?php else : ?>
        Visitante
    <?php endif; ?>
</div>
