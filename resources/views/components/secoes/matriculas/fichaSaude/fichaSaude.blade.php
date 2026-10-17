<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de saúde - Recanto Betel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/cadastroCrianca.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cadastroEnderecoGastos.css') }}">
</head>
<body class="cadastro-body">
    <div class="cadastro-layout">
        <x-sidebar />

        <main class="cadastro-main">
            <div class="cadastro-wrap">
                <form class="cadastro-form" method="POST" action="{{ route('matricula.saude.salvar') }}">
                    @csrf
                    <p>futura ficha aqui</p>

                    <div class="cadastro-actions cadastro-actions-split">
                        <a class="button button-secondary" href="{{ route('matricula.endereco') }}">Voltar</a>
                        <button class="button button-primary" type="submit">Próxima</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
