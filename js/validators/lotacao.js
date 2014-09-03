/**
 * Validadores de modais de lotação
 */

function lotacao($form) {
	$($form).bootstrapValidator({
		fields: {
			nome: {
				validators: {
					notEmpty: {
						message: 'Este campo precisa ser preenchido'
					}
				}
			},
			sigla: {
				validators: {
					notEmpty: {
						message: 'Este campo precisa ser preenchido'
					}
				}
			}
		}
	});	
}