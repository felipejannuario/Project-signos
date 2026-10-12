<?php
$pageTitle = 'Descubra seu Signo';
$extraCss  = ['assets/css/style.css'];

$hoje = date('Y-m-d');

require_once __DIR__ . '/layouts/header.php';
?>

<main class="signo-page d-flex align-items-center min-vh-100 py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">

                <section class="signo-card p-4 p-md-5" aria-labelledby="titulo-pagina">

                    <div class="signo-simbolos text-center mb-3" aria-hidden="true">
                        &#9800;&#xFE0E; &#9801;&#xFE0E; &#9802;&#xFE0E; &#9803;&#xFE0E;
                        &#9804;&#xFE0E; &#9805;&#xFE0E; &#9806;&#xFE0E; &#9807;&#xFE0E;
                        &#9808;&#xFE0E; &#9809;&#xFE0E; &#9810;&#xFE0E; &#9811;&#xFE0E;
                    </div>

                    <h1 id="titulo-pagina" class="signo-titulo text-center mb-3">Descubra seu Signo</h1>

                    <p class="signo-texto text-center mb-4">
                        As estrelas guardam o segredo do dia em que você nasceu.
                        Informe sua data de nascimento e descubra qual signo do zodíaco
                        faz parte da sua história.
                    </p>

            
                    <form action="show_zodiac_sign.php" method="POST">
                        <div class="mb-4">
                            <label for="data_nascimento" class="form-label signo-label">
                                Data de nascimento
                            </label>
                            <input
                                type="date"
                                class="form-control form-control-lg signo-input"
                                id="data_nascimento"
                                name="data_nascimento"
                                min="1900-01-01"
                                max="<?= $hoje ?>"
                                required
                            >
                            <div class="form-text signo-ajuda">
                                Escolha o dia, o mês e o ano em que você nasceu.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-lg w-100 btn-signo">
                            Descobrir meu signo
                        </button>
                    </form>

                </section>

            </div>
        </div>
    </div>
</main>

</body>
</html>