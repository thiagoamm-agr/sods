/**
 * Validador dos formulários de adição e edição de Solicitação.
 */

function SolicitacaoFormValidator(form) {
	this.form = form;
    this.data = null;
    this.valid = false;
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
                	trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Nome é um campo de preenchimento obrigatório.'
                        }
                    }
                },
                titulo: {
                	trigger: 'blur',
                	validators: {
                		notEmpty: {
                			message: 'Título é um campo de preenchimento obrigatório.'
                		}
                	}
                },
                detalhamento: {
                	trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Descrição é um campo de preenchimento obrigatório'
                        }
                     }
                },
                infoAdicionais: {
                	trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Informações adicionais é um campo de preenchimento obrigatório.'
                        }
                    }
                },
                observacoes: {
                	trigger: 'blur',
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

SolicitacaoFormValidator.prototype.validate = function() {
    var valid = false;
    if (this.form != null) {
        this.data.validate();
        valid = this.data.isValid();
    }
    return valid;
};

SolicitacaoFormValidador.prototype.resetForm = function() {
    if (this.data != null) {
        this.data.resetForm(true);
    }
};