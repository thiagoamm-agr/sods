/**
 * Validadores de modais de tipo de solicitação
 */

//Modais de Tipo de Solicitação
function tipo_sol($form) {
	$($form).bootstrapValidator({
        fields: {
            nome: {
	            validators: {
		            notEmpty: {
			            message: ' '
		            }
	            }
            }
        }
    });
}