<?php
declare(strict_types=1);

date_default_timezone_set('America/Sao_Paulo');

const ARQUIVO_SIGNOS = __DIR__ . '/signos.xml';
const ANO_MINIMO     = 1900; // mesmo limite do campo date em index.php

function e(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
}


function validarDataNascimento(mixed $valor): array
{
    if (!is_string($valor) || trim($valor) === '') {
        return [null, 'Informe sua data de nascimento para continuar.'];
    }

    $valor = trim($valor);
    $msgInvalida = 'A data informada é inválida. Escolha uma data de nascimento existente.';

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
        return [null, $msgInvalida];
    }

    $data   = DateTimeImmutable::createFromFormat('!Y-m-d', $valor);
    $erros  = DateTimeImmutable::getLastErrors();
    $temErro = $erros !== false && ($erros['warning_count'] > 0 || $erros['error_count'] > 0);

    if ($data === false || $temErro || $data->format('Y-m-d') !== $valor) {
        return [null, $msgInvalida];
    }

    if ((int) $data->format('Y') < ANO_MINIMO) {
        return [null, 'Informe uma data a partir do ano ' . ANO_MINIMO . '.'];
    }
    if ($data > new DateTimeImmutable('today')) {
        return [null, 'A data de nascimento não pode estar no futuro.'];
    }

    return [$data, null];
}

function converterDiaMes(string $texto): ?int
{
    if (!preg_match('/^(\d{1,2})\/(\d{1,2})$/', trim($texto), $m)) {
        return null;
    }
    $dia = (int) $m[1];
    $mes = (int) $m[2];

    if (!checkdate($mes, $dia, 2024)) {
        return null;
    }
    return $mes * 100 + $dia;
}

function carregarSignos(string $arquivo): array
{
    if (!is_readable($arquivo)) {
        throw new RuntimeException('Arquivo signos.xml não encontrado ou sem permissão de leitura.');
    }

    libxml_use_internal_errors(true); 
    $xml = simplexml_load_file($arquivo, SimpleXMLElement::class, LIBXML_NONET);

    if ($xml === false) {
        $mensagens = array_map(
            static fn(LibXMLError $erro): string => trim($erro->message),
            libxml_get_errors()
        );
        libxml_clear_errors();
        throw new RuntimeException('XML inválido: ' . implode(' | ', $mensagens));
    }

    $itens = $xml->xpath('//signo');
    if (empty($itens)) {
        throw new RuntimeException('Nenhum elemento <signo> encontrado em signos.xml.');
    }

    $signos = [];
    foreach ($itens as $item) {
        $nome      = trim((string) $item->signoNome);
        $inicioTxt = trim((string) $item->dataInicio);
        $fimTxt    = trim((string) $item->dataFim);
        $inicio    = converterDiaMes($inicioTxt);
        $fim       = converterDiaMes($fimTxt);

        if ($nome === '' || $inicio === null || $fim === null) {
            continue;
        }

        $signos[] = [
            'nome'      => $nome,
            'inicioTxt' => $inicioTxt,
            'fimTxt'    => $fimTxt,
            'inicio'    => $inicio,
            'fim'       => $fim,
            'descricao' => trim((string) $item->descricao),
        ];
    }

    if (empty($signos)) {
        throw new RuntimeException('Nenhum signo válido encontrado em signos.xml.');
    }
    return $signos;
}

function estaNoPeriodo(int $diaMes, int $inicio, int $fim): bool
{
    if ($inicio <= $fim) {
        return $diaMes >= $inicio && $diaMes <= $fim;
    }
    return $diaMes >= $inicio || $diaMes <= $fim;
}

function encontrarSigno(array $signos, int $diaMes): ?array
{
    foreach ($signos as $signo) {
        if (estaNoPeriodo($diaMes, $signo['inicio'], $signo['fim'])) {
            return $signo;
        }
    }
    return null;
}

$status   = 'erro_tecnico'; 
$mensagem = '';
$signo    = null;
$dataNasc = null;

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    
    http_response_code(405);
    header('Allow: POST');
    $status   = 'metodo';
    $mensagem = 'Para descobrir seu signo, informe sua data de nascimento no formulário.';
} else {
    [$dataNasc, $erroValidacao] = validarDataNascimento($_POST['data_nascimento'] ?? null);

    if ($erroValidacao !== null) {
        http_response_code(400);
        $status   = 'validacao';
        $mensagem = $erroValidacao;
    } else {
        try {
            $signos = carregarSignos(ARQUIVO_SIGNOS);
            $diaMes = (int) $dataNasc->format('n') * 100 + (int) $dataNasc->format('j');
            $signo  = encontrarSigno($signos, $diaMes);

            if ($signo === null) {
                $status   = 'nao_encontrado';
                $mensagem = 'Não encontramos um signo para esta data. Tente novamente com outra data.';
            } else {
                $status = 'ok';
            }
        } catch (RuntimeException $ex) {
            
            error_log('[Descubra seu Signo] ' . $ex->getMessage());
            http_response_code(500);
            $status   = 'erro_tecnico';
            $mensagem = 'Não foi possível consultar os signos agora. Tente novamente em instantes.';
        }
    }
}


$pageTitle = $status === 'ok' ? 'Seu signo: ' . $signo['nome'] : 'Descubra seu Signo';
$extraCss  = ['assets/css/style.css'];

require_once __DIR__ . '/layouts/header.php';
?>

<main class="signo-page d-flex align-items-center min-vh-100 py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">

                <section class="signo-card p-4 p-md-5" aria-live="polite">

                <?php if ($status === 'ok'): ?>

                    <p class="signo-texto text-center mb-2">
                        Nascimento em <?= e($dataNasc->format('d/m/Y')) ?>
                    </p>
                    <h1 class="signo-titulo text-center mb-3"><?= e($signo['nome']) ?></h1>

                    <p class="text-center mb-4">
                        <span class="signo-label">Período:</span>
                        <span class="signo-texto">
                            <?= e($signo['inicioTxt']) ?> a <?= e($signo['fimTxt']) ?>
                        </span>
                    </p>

                    <?php if ($signo['descricao'] !== ''): ?>
                        <p class="signo-texto mb-4"><?= e($signo['descricao']) ?></p>
                    <?php endif; ?>

                <?php else: ?>

                    <h1 class="signo-titulo text-center mb-3">Descubra seu Signo</h1>
                    <div class="alert <?= $status === 'nao_encontrado' || $status === 'metodo' ? 'alert-info' : 'alert-warning' ?> mb-4" role="alert">
                        <?= e($mensagem) ?>
                    </div>

                <?php endif; ?>

                    <a href="index.php" class="btn btn-lg w-100 btn-signo">
                        <?= $status === 'ok' ? 'Consultar outra data' : 'Voltar ao início' ?>
                    </a>

                </section>

            </div>
        </div>
    </div>
</main>

</body>
</html>