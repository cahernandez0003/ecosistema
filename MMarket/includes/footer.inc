	<footer class="nuevofoot">
		<div class="row">
			<div class="col">
				<p class="text-center"> 
					<small>
						&copy; Todos los derechos reservados a Mimoun Market SL <?php echo date('Y'); ?>
					</small>
				</p>
			</div>
		</div>
	</footer>
	<script src="<?php echo $url_js; ?>jquery-3.3.1.min.js"></script>
	<script src="<?php echo $url_js; ?>bootstrap.min.js"></script>	
	<script src="<?php echo $url_js; ?>sweetalert2.js"></script>
	<script>
		$(document).ready(function() {
			<?php if (isset($_SESSION['type'])): ?>
				<?php if ($_SESSION['type'] == 'success'): ?>
					swal('Felicitaciones!', '<?php echo $_SESSION['message']; ?>', 'success');
				<?php else: ?>
					swal('Lo Sentimos!', '<?php echo $_SESSION['message']; ?>', 'error');
				<?php endif ?>
			<?php endif ?>
			/* - - - - - - - - - - - - - - - - - - - - - - - - - - - */
			$('table').on('click', '.btn-delete', function(event) {
				event.preventDefault();
				$id = $(this).attr('data-id');
				swal({
				  title: 'Esta seguro ?',
				  text: "Realmente desea eliminar este Registro ?",
				  type: 'warning',
				  showCancelButton: false,
				  cancelButtonColor: '#d33'
				}).then((result) => {
				  if (result.value) {
					window.location.replace('delete.php?id='+$id);
				  }
				});
			});
			<?php 
				unset($_SESSION['type']);
				unset($_SESSION['message']);
			?>
			/* - - - - - - - - - - - - - - - - - - - - - - - - - - - */
			// $('input[type=file]').hide();
			// $('form').on('click', '.btn-foto', function(event) {
			// 	event.preventDefault();
			// 	$('input[type=file]').click();
			// });
			/* - - - - - - - - - - - - - - - - - - - - - - - - - - - */
			// $('table').on('click', 'img', function(event) {
			// 	event.preventDefault();
			// 	$ui = $(this).attr('data-img');
			// 	swal({
   //               	imageUrl: '../'+$ui
 		// 		});
			// });
			/* - - - - - - - - - - - - - - - - - - - - - - - - - - - */
			// $('select[name=materia_id]').hide();
			// $('form').on('change', '.slt-usuario', function(event) {
			// 	event.preventDefault();
			// 	$uid = $(this).val();
			// 	//alert($uid);
			// 	$.post('loadSubjects.php', {uid: $uid}, function(data) {
			// 		$('select[name=materia_id]').html(data).show();
			// 	});
			// });
		});
	</script>	
</body>
</html>