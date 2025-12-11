<?php if(isset($error)): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>
<form method="post" action="../index.php?action=login">
<input type="email" name="email" required>
<input type="password" name="password" required>
<button type="submit">Se connecter</button>

