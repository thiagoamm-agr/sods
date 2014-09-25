/**
 * Validador dos formulários de adição e edição de Solicitação.
 */

function SolicitacaoValidator(form) {
    this.form = form;
    this.data = null;
    if (form != null) {
        form.bootstrapValidator({
            live: 'enabled',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                nome: {
                    validators: {
                        notEmpty: {
                            message: 'Nome é um campo de preenchimento obrigatório.'
                        }
                    }
                },
                titulo: {
                	validators: {
                		notEmpty: {
                			message: 'Título é um campo de preenchimento obrigatório.'
                		}
                	}
                },
                detalhamento: {
                    validators: {
                        notEmpty: {
                            message: 'Descrição é um campo de preenchimento obrigatório'
                        }
                     }
                },
                infoAdicionais: {
                    validators: {
                        notEmpty: {
                            message: 'Informações adicionais é um campo de preenchimento obrigatório.'
                        }
                    }
                },
                observacoes: {
                    validators: {
                        notEmpty: {
                        	message: 'Observação é um campo de preenchimento obrigatório.'
                        }
                    }
                }
            }
        });
        this.data = this.form.data('bootstrapValidator');
    }
}

SolicitacaoValidator.prototype.validate = function() {
    var valid = false;
    if (this.form != null) {
        this.data.validate();
        valid = this.data.isValid();
    }
    return valid;
};

SolicitacaoValidador.prototype.resetForm = function() {
    if (this.data != null) {
        this.data.resetForm(true);
    }
};