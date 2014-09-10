/**
 * Validador dos formulários de adição e edição de lotação.
 */

function LotacaoValidator() {}

LotacaoValidator.validate = function(form) {
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
            sigla: {
                validators: {
                    notEmpty: {
                        message: 'Sigla é um campo de preenchimento obrigatório.'
                    }
                 }
            }
        }
    });
}
