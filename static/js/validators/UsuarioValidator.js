/**
 * Validador dos formulários de adição e edição de lotação.
 */

function UsuarioValidator(form) {
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
                lotacao: {
                    validators: {
                        notEmpty: {
                            message: 'Lotação é um campo de preenchimento obrigatório'
                        }
                     }
                },
                cargo: {
                    validators: {
                        notEmpty: {
                            message: 'Cargo é um campo de preenchimento obrigatório.'
                        }
                    }
                },
                fone: {
                    validators: {
                        notEmpty: {
                            message: 'Fone é um campo de preenchimento obrigatório.'
                        }
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: 'e-mail é um campo de preenchimento obrigatório.'
                        }
                    }
                },
                login: {
                    validators: {
                        notEmpty: {
                            message: 'Tipo de Usuario é um campo de preenchimento obrigatório.'
                        }
                    }
                },
            }
        });
        this.data = this.form.data('bootstrapValidator')
    }
}

UsuarioValidator.prototype.validate = function() {
    var valid = false;
    if (this.form != null) {
        this.data.validate();
        valid = this.data.isValid();
    }
    return valid;
}

UsuarioValidator.prototype.resetForm = function() {
    if (this.data != null) {
        this.data.resetForm(true);
    }
};