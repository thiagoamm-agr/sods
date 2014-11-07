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
            fields: {
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
                            message: 'Descrição da Solicitação é um campo de preenchimento obrigatório'
                        }
                     }
                },
                tipo_solicitacao_id: {
                    trigger: 'blur change',
                    group: '.col-sm-6',
                    validators: {
                        notEmpty: {
                            message: 'Tipo de Solicitação é um campo de preenchimento obrigatório.'
                        }
                    }
                }
            }
        }).on('success.form.bv', function(event) {
            // Validação bem sucedida
            event.preventDefault();
            // Obtem o formulário (o alvo da ação)
            var f = $(event.target);
            // Obtem o id da modal
            var modal = $(f).attr('id').replace('form', '#modal');
            // Esconde a modal
            $(modal).modal('hide');
            // Limpa o formulário
            $(f).data('bootstrapValidator').resetForm(true);
        })
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

SolicitacaoFormValidator.prototype.reset = function() {
    if (this.data != null) {
        this.data.resetForm(true);
    }
};