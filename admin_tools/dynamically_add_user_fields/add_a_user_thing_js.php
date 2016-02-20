<script type="text/javascript">

				$(document).ready(function() {
					$("#submit").click(function() {

							//define variables..
							var label_text = $("#label").val();
							var type = $("#field_type").val();
							var options = $("#options").val();
	
							 $.ajax({
			                    url     : 'add_a_user_thing_submit.php', //the url you are sending datas to which will again send the result
			                    type    : 'GET', //type of request, GET or POST
			                    data    : { label_text:label_text,type: type, options: options}, //Data you are sending
			                    //success : function(data){$('#div_store').html(data)},
			                    success : function(data){alert(data)}, // On success, it will populate the 2nd select
			                    error   : function(){alert('An Error Has Occurred')} //error message

						});
						
					});
				});
</script>