<?php
$pageTitle = $pageTitle ?? 'Descubra seu Signo';
$extraCss = $extraCss ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <?php foreach ($extraCss as $css): ?>
        <link
            rel="stylesheet"
            href="<?= htmlspecialchars($css, ENT_QUOTES, 'UTF-8') ?>"
        >
    <?php endforeach; ?>
</head>
<body>