/**
 * Validadores de modais de lotação
 */

function lotacao($form) {
	$($form).bootstrapValidator({
		fields: {
			nome: {
				validators: {
					notEmpty: {
						message: ' '
					}
				}
			},
			sigla: {
				validators: {
					notEmpty: {
						message: ' '
					}
				}
			}
		}
	});	
}