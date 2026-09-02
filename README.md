# Projeto Integrador 2

## Tecnologias
* **Backend:** Laravel
* **Frontend:** Tailwind CSS
* **Infraestrutura:** Docker & Docker Compose
* **Banco de Dados:** SQL 

## Equipe
* **Vinicius** (VineJPS)
* **Renan** (RenanHB1)
* **Rebeca** (Rebeca123558)
* **Sthefani** (Sthee2004)

---

## Iniciando o Projeto

| Etapa / Ação | Comando | O que faz? |
| :--- | :--- | :--- |
| **Subir o ambiente** | `make start` | Inicia os containers do projeto (executa o build na primeira vez). |
| **Derrubar o ambiente** | `make stop` | Derruba e interrompe os containers do projeto. |

### 💡 Observações Importantes
* **Dúvidas com os comandos?** Consulte o arquivo `Makefile` na raiz do projeto para verificar todos os alvos disponíveis.
* **Problemas no Linux?** Caso encontre erros ao tentar executar o comando `make`, certifique-se de ter a ferramenta instalada em seu sistema operacional executando:
  ```bash
  sudo apt update && sudo apt install -y make
### Erros no docker?
| Etapa / Ação | Comando | O que faz? |
| :--- | :--- | :--- |
| **Derrubar o ambiente** | `make stop` | Derruba e interrompe os containers do projeto. |
| **Reiniciar o Docker** | `sudo systemctl restart docker` | da restart no docker. |
| **Ajustando permissões do docker** | `sudo chown -R $USER:$USER . chmod -R 775 storage bootstrap/cache` | Da  ao docker permissão ao storage . |
| **Subir o ambiente** | `make start` | Inicia os containers do projeto (executa o build na primeira vez). |


## Abrindo PR
Crie uma nova Branch (Atualizada) a partir da main com os prefixos "feature" criação de algo novo ou "fix" correção de bugs. EX feature/adicao-zoom || fix/correcao-zoom
