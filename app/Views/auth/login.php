<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Administrator</title>
</head>
<body>
    <main>
        <h1>Login Administrator</h1>
        <?php if (! $configured) : ?>
            <p role="alert">Login belum dikonfigurasi. Atur kredensial administrator di environment aplikasi.</p>
        <?php else : ?>
            <?php if ($error !== '') : ?>
                <p role="alert"><?= esc($error) ?></p>
            <?php endif; ?>
            <?= form_open('login') ?>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" autocomplete="username" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
                <button type="submit">Masuk</button>
            <?= form_close() ?>
        <?php endif; ?>
    </main>
</body>
</html>
