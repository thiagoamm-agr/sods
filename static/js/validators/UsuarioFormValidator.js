/**
 * Validador dos formulários de adição e edição de lotação.
 */

function UsuarioFormValidator(form) {
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
                lotacao: {
                	trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Lotação é um campo de preenchimento obrigatório'
                        }
                     }
                },
                cargo: {
                	trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Cargo é um campo de preenchimento obrigatório'
                        }
                    }
                },
                fone: {
                	trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Fone é um campo de preenchimento obrigatório'
                        }
                    }
                },
                email: {
                	trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'e-mail é um campo de preenchimento obrigatório'
                        }
                    }
                },
                login: {
                	trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Login é um campo de preenchimento obrigatório'
                        }
                    }
                },
                tipoUsuario: {
                	trigger: 'blur',
                	validators: {
                		notEmpty: {
                			message: 'Tipo de Usuario é um campo de preenchimento obrigatório'
                		}
                	}
                }
            }
        });
        this.data = this.form.data('bootstrapValidator');
    }
}

UsuarioFormValidator.prototype.validate = function() {
    var valid = false;
    if (this.form != null) {
        this.data.validate();
        valid = this.data.isValid();
    }
    return valid;
};

UsuarioFormValidator.prototype.resetForm = function() {
    if (this.data != null) {
        this.data.resetForm(true);
    }
};