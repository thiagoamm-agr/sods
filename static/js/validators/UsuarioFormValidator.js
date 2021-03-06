/**
 * Validador dos formulários de adição e edição de lotação.
 */

function UsuarioFormValidator(form) {
    this.form = form;
    this.data = null;
    this.valid = false;
    if (form != null) {
        form.bootstrapValidator({
            submitButtons: 'button[type="submit"]',
            live: 'enabled',
            excluded: [':disabled', ':hidden'],
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
                            message: 'Lotação é um campo de preenchimento obrigatório.'
                        }
                     }
                },
                funcao: {
                    trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Função é um campo de preenchimento obrigatório.'
                        }
                    }
                },
                fone: {
                    trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Telefone é um campo de preenchimento obrigatório.'
                        }
                    }
                },
                email: {
                    trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'E-mail é um campo de preenchimento obrigatório.'
                        },
                        emailAddress: {
                            message: 'Preencha um endereço de e-mail válido.'
                        }
                    }
                },
                login: {
                    trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Login é um campo de preenchimento obrigatório.'
                        },
                        remote: {
                            enabled: false,
                            message: 'Esse login já está sendo utilizado!',
                            type: 'post',
                            url: '/sods/admin/usuarios/',
                            name: 'login',
                            data: function(validator) {
                                console.log(validator);
                                return { 
                                    action: 'check_login',
                                    login: validator.getFieldElements('login').val(),
                                    login_antigo: validator.getFieldElements('login_antigo').val()
                                };
                            }
                        }
                    }
                },
                senha: {
                    trigger: 'blur',
                    validators: {
                        regexp: {
                            regexp: /\w{8,}/i,
                            message: 'A senha deve conter no mínimo 8 caracteres entre letras e números.'
                        },
                        identical: {
                            field: 'confirma_senha',
                            message: 'As senhas não correspondem.'
                        },
                        different: {
                            field: 'login',
                            message: 'A senha não pode ser igual ao login.'
                        }
                    }
                },
                confirma_senha: {
                    trigger: 'blur',
                    validators: {
                        regexp: {
                            regexp: /\w{8,}/i,
                            message: 'A senha deve conter no mínimo 8 caracteres entre letras e números.'
                        },
                        identical: {
                            field: 'senha',
                            message: 'As senhas não correspondem.'
                        },
                        different: {
                            field: 'login',
                            message: 'A senha não pode ser igual ao login.'
                        }
                    }
                },
                perfil: {
                    
                },
                status: {
                    
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
        }).on('blur', '[name="login"]', function() {
            $(form).bootstrapValidator('enableFieldValidators', 'login', true, 'remote');
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
    this.valid=valid;
    return valid;
};

UsuarioFormValidator.prototype.reset = function() {
    if (this.data != null) {
        this.data.resetForm(true);
    }
};