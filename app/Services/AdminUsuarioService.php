<?php

namespace App\Services;

use App\Mail\ConviteUsuarioMail;
use App\Mail\RedefinirSenhaMail;
use App\Models\Nivel;
use App\Models\Usuario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminUsuarioService
{
    public function listarPaginado(?string $search = null, ?string $tab = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Usuario::with('nivel');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($tab === 'administradores') {
            $query->where(function ($q) {
                $q->where('role', 'administrador')
                  ->orWhereHas('nivel', fn ($n) => $n->whereRaw('LOWER(nome) = ?', ['administrador']));
            });
        } elseif ($tab === 'colaboradores') {
            $query->where(function ($q) {
                $q->where('role', 'colaborador')
                  ->orWhereHas('nivel', fn ($n) => $n->whereRaw('LOWER(nome) = ?', ['colaborador']));
            });
        }

        return $query->orderBy('id', 'desc')->paginate($perPage)->withQueryString();
    }

    public function convidar(string $email, string $role = 'colaborador', ?string $nome = null): array
    {
        $roleNormalized = strtolower($role) === 'administrador' ? 'administrador' : 'colaborador';
        $nivelNome = $roleNormalized === 'administrador' ? 'Administrador' : 'Colaborador';

        $nivel = Nivel::firstOrCreate(
            ['nome' => $nivelNome],
            ['descricao' => $roleNormalized === 'administrador' ? 'Acesso total ao sistema' : 'Acesso básico ao painel']
        );

        $tempPassword = Str::random(10);
        $nomeFinal = $nome ?: ucfirst(explode('@', $email)[0]);

        $usuario = Usuario::create([
            'nome' => $nomeFinal,
            'email' => $email,
            'role' => $roleNormalized,
            'status' => 'pendente',
            'FK_id_nivel' => $nivel->id,
            'senha' => Hash::make($tempPassword),
        ]);

        $remetenteEmail = Auth::user()?->email ?? 'admin@recantobetel.com.br';
        $inviteUrl = route('convite.confirmar', ['id' => $usuario->id, 'remetente' => $remetenteEmail]);

        try {
            Mail::to($usuario->email)->send(new ConviteUsuarioMail($tempPassword, $usuario->nome, $usuario, $remetenteEmail, $inviteUrl));
        } catch (\Throwable $e) {
            Log::error('Erro ao enviar e-mail de convite: ' . $e->getMessage());
        }

        return [
            'usuario' => $usuario,
            'tempPassword' => $tempPassword,
            'inviteUrl' => $inviteUrl,
        ];
    }

    public function tornarAdmin(int $id): Usuario
    {
        $usuario = Usuario::findOrFail($id);

        $nivelAdmin = Nivel::firstOrCreate(
            ['nome' => 'Administrador'],
            ['descricao' => 'Acesso total ao sistema']
        );

        $usuario->update([
            'role' => 'administrador',
            'FK_id_nivel' => $nivelAdmin->id,
        ]);

        return $usuario->fresh(['nivel']);
    }

    public function removerAdmin(int $id): Usuario
    {
        $usuario = Usuario::findOrFail($id);

        $nivelColaborador = Nivel::firstOrCreate(
            ['nome' => 'Colaborador'],
            ['descricao' => 'Acesso básico ao painel']
        );

        $usuario->update([
            'role' => 'colaborador',
            'FK_id_nivel' => $nivelColaborador->id,
        ]);

        return $usuario->fresh(['nivel']);
    }

    public function desativar(int $id): Usuario
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update(['status' => 'inativo']);

        return $usuario->fresh(['nivel']);
    }

    public function reativar(int $id): Usuario
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update(['status' => 'ativo']);

        return $usuario->fresh(['nivel']);
    }

    public function redefinirSenha(int $id): array
    {
        $usuario = Usuario::findOrFail($id);
        $novaSenha = Str::random(10);

        $usuario->update([
            'senha' => Hash::make($novaSenha),
        ]);

        try {
            Mail::to($usuario->email)->send(new RedefinirSenhaMail($novaSenha, $usuario->nome));
        } catch (\Throwable $e) {
            Log::error('Erro ao enviar e-mail de redefinição de senha: ' . $e->getMessage());
        }

        return [
            'usuario' => $usuario,
            'novaSenha' => $novaSenha,
        ];
    }

    public function atualizar(int $id, array $dados): Usuario
    {
        $usuario = Usuario::findOrFail($id);

        if (isset($dados['senha']) && !empty($dados['senha'])) {
            $dados['senha'] = Hash::make($dados['senha']);
        } else {
            unset($dados['senha']);
        }

        if (isset($dados['role'])) {
            $roleNormalized = strtolower($dados['role']) === 'administrador' ? 'administrador' : 'colaborador';
            $nivelNome = $roleNormalized === 'administrador' ? 'Administrador' : 'Colaborador';
            $nivel = Nivel::firstOrCreate(
                ['nome' => $nivelNome],
                ['descricao' => $roleNormalized === 'administrador' ? 'Acesso total ao sistema' : 'Acesso básico ao painel']
            );
            $dados['role'] = $roleNormalized;
            $dados['FK_id_nivel'] = $nivel->id;
        }

        $usuario->update($dados);

        return $usuario->fresh(['nivel']);
    }

    public function deletar(int $id): bool
    {
        $usuario = Usuario::findOrFail($id);

        return (bool) $usuario->delete();
    }
}
