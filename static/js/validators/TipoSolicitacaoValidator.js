/**
 * 
 */

function TipoSolicitacaoValidator() {}

TipoSolicitacaoValidator.validate = function(form){
	form.bootstrapValidator({
		 live: "enabled",
		 feedbackIcons: {
		        valid: 'glyphicon glyphicon-ok',
		    invalid: 'glyphicon glyphicon-remove',
		    validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
			nome: {
				validators: {
					notEmpty: {
		            	message: 'Este campo deve ser preenchido'
		            }
	            }
			}
		}
	});
}