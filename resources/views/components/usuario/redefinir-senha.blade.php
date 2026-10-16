<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>Recanto Betel - Redefinir Senha</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#e8edfa] min-h-screen flex flex-col items-center justify-center font-sans antialiased p-4">

    <!-- Título Principal -->
    <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-br from-[#2f6fe0] to-[#173f8a] tracking-tight mb-8 text-center">
        Recanto Betel
    </h1>

    <!-- Card -->
    <div class="w-full max-w-[550px] bg-white rounded-[20px] shadow-[0px_20px_45px_-20px_#1c2d5a38] p-8 md:p-12 flex flex-col items-center">

        <!-- Cabeçalho -->
        <h2 class="text-2xl md:text-[35px] font-bold text-[#1c2536] tracking-tight text-center mb-3">
            Redefinir senha
        </h2>
        <p class="text-[#7e8aa1] text-base md:text-xl text-center leading-relaxed mb-8">
            Informe seu e-mail cadastrado e enviaremos<br class="hidden sm:inline"/> uma nova senha temporária.
        </p>

        <!-- Formulário -->
        <form method="POST" action="{{ route('senha.solicitar') }}" class="w-full max-w-[450px] flex flex-col gap-5">
            @csrf

            <!-- Feedback de sucesso / informação -->
            @if(session('info'))
                <div class="p-3 bg-blue-50 border border-blue-200 text-blue-700 text-sm rounded-xl text-center">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Feedback de erro geral -->
            @error('email')
                <div class="p-3 bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl text-center">
                    {{ $message }}
                </div>
            @enderror

            <!-- Campo E-mail -->
            <div class="flex flex-col gap-1">
                <div class="w-full h-[70px] bg-[#fafbfd] rounded-[15px] border-[1.5px] border-solid @error('email') border-red-400 @else border-[#e9ecf3] @enderror px-6 flex items-center focus-within:border-[#2f6fe0] transition-colors">
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="Digite seu e-mail"
                        class="w-full bg-transparent outline-none text-[#1c2536] placeholder-[#acacad] text-lg md:text-xl font-normal"
                    />
                </div>
            </div>

            <!-- Botão Enviar -->
            <button
                type="submit"
                class="w-full h-[70px] rounded-[15px] bg-gradient-to-tr text-white font-bold text-xl tracking-tight shadow-md hover:opacity-95 transition-opacity flex items-center justify-center mt-2 cursor-pointer"
                style="background-image: linear-gradient(130deg, rgba(77,139,240,1) 0%, rgba(28,78,168,1) 100%);"
            >
                Enviar nova senha
            </button>
        </form>

        <!-- Rodapé -->
        <div class="flex items-center justify-center gap-2 text-base md:text-xl mt-8 text-center">
            <a href="{{ route('login') }}" class="text-[#2255b0] font-semibold hover:underline">
                ← Voltar para o login
            </a>
        </div>

    </div>

</body>
</html>
