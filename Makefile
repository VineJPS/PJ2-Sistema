# Makefile para gerenciamento do Laravel Sail com Docker

.PHONY: start stop build logs restart status bash shell

# Sobe os containers em segundo plano (detached mode)
start:
	@echo "Subindo os containers do projeto..."
	./vendor/bin/sail up -d

# Derruba os containers mantendo os volumes intactos
stop:
	@echo "Derrubando os containers..."
	./vendor/bin/sail stop

# Reconstrói os containers do zero, sem cache
build:
	@echo "Reconstruindo os containers do zero (sem cache)..."
	./vendor/bin/sail build --no-cache

# Exibe os logs dos containers em tempo real
logs:
	./vendor/bin/sail logs -f

# Reinicia os containers
restart:
	@echo "Reiniciando os containers..."
	./vendor/bin/sail restart

# Exibe o status atual dos containers
status:
	./vendor/bin/sail ps

# Entra no terminal interativo do container da aplicação
bash:
	./vendor/bin/sail bash

# Atalho alternativo caso prefira digitar "make shell"
shell:
	./vendor/bin/sail bash