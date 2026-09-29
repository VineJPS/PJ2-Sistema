/**
 * Módulo Unificado de Edição de Criança
 * 
 * Fornece 3 factories que retornam componentes Alpine.data
 * Todos usam a lógica compartilhada do CriancaEdicao
 */

const criancaEdit = {
    /**
     * Componente para Matrícula (3 seções independentes)
     * 
     * Seções:
     * - pessoais: dataNascimento, cpf, rg, endereco
     * - betel: escolaBetel, periodoBetel, classeBetel  
     * - escolares: escola, periodo, serie
     * 
     * @param {Object} dadosIniciais
     * @returns {Object} Componente Alpine.data
     */
    matricula: function(dadosIniciais) {
        return {
            ...window.CriancaEdicao.criarEstado(dadosIniciais || {}),

            camposPessoais: ['dataNascimento', 'cpf', 'rg', 'endereco'],
            camposBetel: ['escolaBetel', 'periodoBetel', 'classeBetel'],
            camposEscolares: ['escola', 'periodo', 'serie'],

            // Atalhos de compatibilidade
            get isEditingPessoais() { return !!this.editando['pessoais']; },
            set isEditingPessoais(v) { this.editando['pessoais'] = v; },

            get isEditingBetel() { return !!this.editando['betel']; },
            set isEditingBetel(v) { this.editando['betel'] = v; },

            get isEditingEscolares() { return !!this.editando['escolares']; },
            set isEditingEscolares(v) { this.editando['escolares'] = v; },

            salvarPessoais() { this.salvarSecao('pessoais', this.camposPessoais); },
            cancelarPessoais() { this.cancelarSecao('pessoais', this.camposPessoais); },

            salvarBetel() { this.salvarSecao('betel', this.camposBetel); },
            cancelarBetel() { this.cancelarSecao('betel', this.camposBetel); },

            salvarEscolares() { this.salvarSecao('escolares', this.camposEscolares); },
            cancelarEscolares() { this.cancelarSecao('escolares', this.camposEscolares); },

            get formData() { return this.form; },
        };
    },

    /**
     * Componente para Responsáveis (lista de cards)
     * 
     * Um responsável por vez pode ser editado.
     * 
     * @param {Array} dadosIniciais - Array de responsáveis
     * @returns {Object} Componente Alpine.data
     */
    responsaveis: function(dadosIniciais) {
        return {
            ...window.CriancaEdicao.criarEstadoLista(dadosIniciais || []),

            camposResponsavel: ['nome', 'telefone', 'enderecoResidencial', 'enderecoComercial'],

            iniciarEdicao(index) { this.iniciarEdicaoItem(index); },
            salvarEdicao(index) { this.salvarEdicaoItem(index); },
            cancelarEdicao() { this.cancelarEdicaoItem(); },

            get listaResponsaveis() { return this.lista; },
        };
    },

    /**
     * Componente para Saúde (uma única seção com múltiplos campos)
     * 
     * Campos: queixaPrincipal, pergunta1-3, medicacao, cirurgias,
     * doencasEspecificas, cicatrizacao, anestesia, hemorragia,
     * habitos, antecedentes, outrasInformacoes
     * 
     * @param {Object} dadosIniciais
     * @returns {Object} Componente Alpine.data
     */
    saude: function(dadosIniciais) {
        return {
            ...window.CriancaEdicao.criarEstado(dadosIniciais || {}),

            camposFicha: [
                'queixaPrincipal', 'pergunta1', 'pergunta2', 'pergunta3',
                'medicacao', 'cirurgias', 'doencasEspecificas',
                'cicatrizacao', 'anestesia', 'hemorragia',
                'habitos', 'antecedentes', 'outrasInformacoes'
            ],

            get isEditing() { return !!this.editando['ficha']; },
            set isEditing(v) { this.editando['ficha'] = v; },

            iniciarEdicao() { this.editando['ficha'] = true; },
            salvar() { this.salvarSecao('ficha', this.camposFicha); },
            cancelar() { this.cancelarSecao('ficha', this.camposFicha); },
        };
    }
};

export default criancaEdit;
