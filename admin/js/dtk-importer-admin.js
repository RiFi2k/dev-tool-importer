(function( $ ) {
	'use strict';
	// On DOM ready.
	$(function() {

		$('#dtk-importer-start').submit(function(e) {
			e.preventDefault();
			var str = $(this).serialize();
			var div = $('#dtk-import-reload');
			var error = '<span class="ajax-error">Sorry, you broke it</span>';
			var total = "<?php echo $total_chunks; ?>";
			var size = "<?php echo $total_chunks; ?>";
			function processChunk() {

				if (size <= 0) {
					return;
				}

				$.ajax({
					url: ajaxurl,
					method: 'POST',
					data: {
						action: 'dtk_import_chunk',
						total: total,
						size: size,
						data: str
					},
					beforeSend: function() {
						$('#dtk-import').addClass('dtk-loading');
					},
					error: function() {
						div.html(error);
					},
					success: function(data) {
						div.html(data);
					},
					complete: function() {
						$('#dtk-import').removeClass('dtk-loading');
						size--;
						processChunk();
					}
				});

			}
			processChunk();
		});

	});

})( jQuery );
