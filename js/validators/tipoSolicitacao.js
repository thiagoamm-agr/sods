/**
 * Validadores de modais de tipo de solicitação
 */

//Modais de Tipo de Solicitação
function tipo_sol($form) {
	$($form).bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            nome: {
	            validators: {
		            notEmpty: {
			            message: 'Este campo precisa ser preenchido'
		            }
	            }
            }
        }
    });
}