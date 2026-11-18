<?php
/**
 * Vista: Login del Panel de Administración (/admin/login).
 */

use App\Core\View;
use App\Core\Csrf;
use App\Core\Session;

$err = Session::flash('error');
?>
<!DOCTYPE html>
<html lang="es-UY">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Acceso Administrador · Carnelli Studio</title>
    <link rel="icon" type="image/svg+xml" href="<?= View::asset('assets/brand/carnelli-favicon.svg', false) ?>">
    <link rel="stylesheet" href="<?= View::asset('assets/css/base.css') ?>">
    <link rel="stylesheet" href="<?= View::asset('assets/css/components.css') ?>">
    <link rel="stylesheet" href="<?= View::asset('assets/css/admin.css') ?>">
</head>
<body class="admin-login-body">
    <div class="admin-login-card">
        <div class="admin-login-card__header">
            <img src="<?= View::asset('assets/brand/carnelli-imagotipo-color-transparente.svg') ?>" alt="Carnelli Studio" width="210" height="54">
            <h1>Panel de Administración</h1>
            <p>Ingresá tus credenciales para administrar el sitio y los leads.</p>
        </div>

        <?php if ($err): ?>
            <div class="alert alert--error"><?= View::e($err) ?></div>
        <?php endif; ?>

        <form action="<?= View::url('admin/login') ?>" method="POST">
            <?= Csrf::field() ?>
            <div class="form-group">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" id="email" name="email" class="form-input" value="carnellistudio2026@gmail.com" required autofocus>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>
            <button type="submit" class="btn btn--primary btn--full btn--lg">Ingresar al panel</button>
        </form>

        <div class="admin-login-card__footer">
            <a href="<?= View::url('') ?>">← Volver al sitio público</a>
        </div>
    </div>
</body>
</html>

