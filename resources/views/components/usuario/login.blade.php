<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta charset="utf-8" />
    <title>Recanto Betel - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#e8edfa] min-h-screen flex flex-col items-center justify-center font-sans antialiased p-4">

    <!-- Título Principal -->
    <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-br from-[#2f6fe0] to-[#173f8a] tracking-tight mb-8 text-center">
        Recanto Betel
    </h1>

    <!-- Card de Login -->
    <div class="w-full max-w-[550px] bg-white rounded-[20px] shadow-[0px_20px_45px_-20px_#1c2d5a38] p-8 md:p-12 flex flex-col items-center">
        
        <!-- Cabeçalho do Card -->
        <h2 class="text-2xl md:text-[35px] font-bold text-[#1c2536] tracking-tight text-center mb-3">
            Bem-vindo de volta!
        </h2>
        <p class="text-[#7e8aa1] text-base md:text-xl text-center leading-relaxed mb-8">
            Informe suas credenciais para acessar <br class="hidden sm:inline"/> o sistema.
        </p>

        <!-- Formulário -->
        <form class="w-full max-w-[450px] flex flex-col gap-5">
            <!-- Campo E-mail -->
            <div class="w-full h-[70px] bg-[#fafbfd] rounded-[15px] border-[1.5px] border-solid border-[#e9ecf3] px-6 flex items-center focus-within:border-[#2f6fe0] transition-colors">
                <input 
                    type="email" 
                    placeholder="Digite seu e-mail" 
                    class="w-full bg-transparent outline-none text-[#1c2536] placeholder-[#acacad] text-lg md:text-xl font-normal"
                />
            </div>

            <!-- Campo Senha -->
            <div class="w-full h-[70px] bg-[#fafbfd] rounded-[15px] border-[1.5px] border-solid border-[#e9ecf3] px-6 flex items-center focus-within:border-[#2f6fe0] transition-colors">
                <input 
                    type="password" 
                    placeholder="Digite sua senha" 
                    class="w-full bg-transparent outline-none text-[#1c2536] placeholder-[#acacad] text-lg md:text-xl font-normal"
                />
            </div>

            <!-- Botão Entrar -->
            <button 
                type="submit" 
                class="w-full h-[70px] rounded-[15px] bg-gradient-to-tr from-[#4df8f4] via-[#4d8bf0] to-[#1c4ee8] text-white font-bold text-xl tracking-tight shadow-md hover:opacity-95 transition-opacity flex items-center justify-center mt-2"
                style="background-image: linear-gradient(130deg, rgba(77,139,240,1) 0%, rgba(28,78,168,1) 100%);"
            >
                Entrar
            </button>
        </form>

        <!-- Rodapé do Card (Esqueceu a senha) -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2 text-base md:text-xl mt-8 text-center">
            <span class="text-[#7e8aa1] font-normal">Esqueceu sua senha?</span>
            <a href="#" class="text-[#2255b0] font-semibold hover:underline">Redefinir senha</a>
        </div>

    </div>

</body>
</html>