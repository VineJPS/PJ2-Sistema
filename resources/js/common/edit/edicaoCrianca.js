/**
 * edicaoCrianca.js
 *
 * Lógica de edição compartilhada entre as páginas Matrícula, Responsáveis e Saúde.
 * Cada página pode ter uma ou várias "seções" editáveis independentes
 * (ex.: matrícula tem Dados Pessoais, Betel e Escolares; saúde tem uma ficha só).
 *
 * Uso dentro de um Alpine.data(...):
 *
 *   Alpine.data('matriculaCriancaComponent', (dadosIniciais) => ({
 *       ...CriancaEdicao.criarEstado(dadosIniciais),
 *       camposPessoais:  ['dataNascimento', 'cpf', 'rg', 'endereco'],
 *       camposEscolares: ['escola', 'periodo', 'serie'],
 *       camposBetel:     ['escolaBetel', 'periodoBetel', 'classeBetel'],
 *   }));
 *
 * No HTML:
 *   x-show="!editando.pessoais"        -> modo leitura
 *   x-show="editando.pessoais"         -> modo edição
 *   @click="iniciarEdicao('pessoais')"
 *   @click="salvarSecao('pessoais', camposPessoais)"
 *   @click="cancelarSecao('pessoais', camposPessoais)"
 *
 * Página com uma seção só (ex.: Saúde) pode usar sempre o mesmo nome, ex. 'ficha':
 *   @click="iniciarEdicao('ficha')"
 *   @click="salvarSecao('ficha', camposFicha)"
 */

window.CriancaEdicao = {

    /**
     * Cria o estado reativo base a partir dos dados iniciais vindos do Blade/seeder.
     * @param {Object} dadosIniciais
     * @returns {Object} propriedades e métodos para espalhar (...) dentro do Alpine.data
     */
    criarEstado(dadosIniciais) {
        return {
            // Cópia de trabalho (o que aparece nos inputs)
            form: this._clone(dadosIniciais || {}),

            // Cópia de segurança para permitir "Cancelar"
            backup: this._clone(dadosIniciais || {}),

            // Estado de edição por seção, ex.: { pessoais: false, escolares: false }
            editando: {},

            /**
             * Liga o modo edição de uma seção.
             * @param {string} secao - nome livre, ex. 'pessoais', 'ficha'
             */
            iniciarEdicao(secao) {
                this.editando[secao] = true;
            },

            /**
             * Confirma a edição: grava os campos da seção no backup e sai do modo edição.
             * @param {string} secao
             * @param {string[]} campos - chaves de form/backup que pertencem a essa seção
             * @param {Function} [aoSalvar] - callback opcional (ex.: enviar ao backend)
             */
            salvarSecao(secao, campos, aoSalvar) {
                campos.forEach((campo) => {
                    this.backup[campo] = this._clone(this.form[campo]);
                });
                this.editando[secao] = false;
                if (typeof aoSalvar === 'function') {
                    aoSalvar(this._extrairCampos(this.form, campos));
                }
            },

            /**
             * Cancela a edição: restaura os campos da seção a partir do backup.
             * @param {string} secao
             * @param {string[]} campos
             */
            cancelarSecao(secao, campos) {
                campos.forEach((campo) => {
                    this.form[campo] = this._clone(this.backup[campo]);
                });
                this.editando[secao] = false;
            },

            /**
             * Ajuda para campos Sim/Não que mudam de estilo (ex. labelCampoAlerta).
             * @param {string} valor
             * @returns {boolean}
             */
            isSim(valor) {
                return !!valor && String(valor).trim().toLowerCase() === 'sim';
            },

            /** Clona um valor (objeto, array ou primitivo) com segurança. */
            _clone(valor) {
                return valor === undefined ? valor : JSON.parse(JSON.stringify(valor));
            },

    /** Monta um objeto só com os campos informados, a partir de um form. */
            _extrairCampos(form, campos) {
                const saida = {};
                campos.forEach((c) => { saida[c] = form[c]; });
                return saida;
            },
        };
    },

    /**
     * Cria o estado reativo para páginas de LISTA, onde há vários cards do
     * mesmo tipo e apenas um pode ser editado por vez (ex.: Responsáveis).
     *
     * Uso:
     *   Alpine.data('responsaveisCriancaComponent', (dadosIniciais) => ({
     *       ...CriancaEdicao.criarEstadoLista(dadosIniciais),
     *   }));
     *
     * No HTML:
     *   <template x-for="(item, index) in lista" :key="index">
     *     x-if="editingIndex === index"   -> modo edição (usar tempData)
     *     x-if="editingIndex !== index"   -> modo leitura (usar item)
     *     @click="iniciarEdicaoItem(index)"
     *     @click="salvarEdicaoItem(index)"
     *     @click="cancelarEdicaoItem()"
     *
     * @param {Array} itensIniciais
     */
    criarEstadoLista(itensIniciais) {
        return {
            lista: this._clone(itensIniciais || []),

            // Índice do card em edição (null = nenhum)
            editingIndex: null,

            // Cópia temporária com as alterações do card em edição
            tempData: {},

            /** @param {number} index */
            iniciarEdicaoItem(index) {
                this.editingIndex = index;
                this.tempData = this._clone(this.lista[index]);
            },

            cancelarEdicaoItem() {
                this.editingIndex = null;
                this.tempData = {};
            },

            /**
             * @param {number} index
             * @param {Function} [aoSalvar] 
             */
            salvarEdicaoItem(index, aoSalvar) {
                this.lista[index] = this._clone(this.tempData);
                this.editingIndex = null;
                this.tempData = {};
                if (typeof aoSalvar === 'function') {
                    aoSalvar(this.lista[index]);
                }
            },

            isSim(valor) {
                return !!valor && String(valor).trim().toLowerCase() === 'sim';
            },

            _clone(valor) {
                return valor === undefined ? valor : JSON.parse(JSON.stringify(valor));
            },
        };
    },
};