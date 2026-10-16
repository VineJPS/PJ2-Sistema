<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Redefinição de Senha - Recanto Betel</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="100%" max-width="600" style="max-width: 600px; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #1e40af; padding: 32px 40px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">Recanto Betel</h1>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 700; color: #0f172a;">Sua senha foi redefinida</h2>
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #475569;">
                                Olá{{ $nome ? ' ' . $nome : '' }}, uma nova senha temporária foi gerada pelo administrador do <strong>Recanto Betel</strong> para o seu usuário.
                            </p>

                            <!-- Password Box -->
                            <div style="background-color: #f1f5f9; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px; text-align: center; margin-bottom: 32px;">
                                <span style="display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 8px;">Nova senha temporária:</span>
                                <code style="font-size: 22px; font-weight: 700; color: #1e40af; letter-spacing: 1px; font-family: monospace;">{{ $novaSenha }}</code>
                            </div>

                            <!-- Button -->
                            <div style="text-align: center; margin-bottom: 32px;">
                                <a href="{{ route('login', ['redefinir' => 1]) }}" style="display: inline-block; background-color: #2563eb; color: #ffffff; font-weight: 700; font-size: 15px; padding: 14px 32px; border-radius: 12px; text-decoration: none; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);">
                                    Fazer Login
                                </a>
                            </div>

                            <p style="margin: 0; font-size: 13px; line-height: 1.5; color: #94a3b8; text-align: center;">
                                Se você não solicitou a redefinição de senha, entre em contato imediatamente com a administração.
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px 40px; text-align: center; border-top: 1px solid #f1f5f9;">
                            <p style="margin: 0; font-size: 12px; color: #94a3b8;">
                                &copy; {{ date('Y') }} Recanto Betel. Todos os direitos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
