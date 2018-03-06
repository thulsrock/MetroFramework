	</main>	
	<?php $this->buttonToTop(); ?>
			
	<script type="text/javascript">
	// When the user scrolls down from the top of the document
		window.onscroll = function() {
			scrollFunction()
		};

		function scrollFunction() {
		    if (document.body.scrollTop > 211 || document.documentElement.scrollTop > 211 ) {
		        document.getElementById("buttonToTop").style.display = "block";
		    } else {
		        document.getElementById("buttonToTop").style.display = "none";
		    }
		}

		// When the user clicks on the button, scroll to the top of the document
		function topFunction() {
		    document.body.scrollTop = 0; // For Safari
		    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
		} 

		$(function() {
			$( ".control .btn:not(:last)" )
			.removeClass('btn-add').addClass('btn-remove')
			.removeClass('btn-success').addClass('btn-danger')
			.html('<i class="material-icons">&#xE15B;</i>');

			$(document).on('click', '.btn-add', function(e) {
				e.preventDefault();

				var controlForm = $(this).parents('form:first .control'),
				currentEntry = $(this).parents('.entry:first'),
				newEntry = $(currentEntry.clone()).appendTo(controlForm);

				newEntry.find('input').val('');
				controlForm.find('.entry:not(:last) .btn-add')
				.removeClass('btn-add').addClass('btn-remove')
				.removeClass('btn-success').addClass('btn-danger')
				.html('<i class="material-icons">&#xE15B;</i>');
				}).on('click', '.btn-remove', function(e) {
					$(this).parents('.entry:first').remove();
					e.preventDefault();
					return false;
				});
		});

		function printThis() {
		     window.print();
		}

		$(document)
	    .on('focus.autoExpand', 'textarea.autoExpand', function(){
	        var savedValue = this.value;
	        this.value = '';
	        this.baseScrollHeight = this.scrollHeight;
	        this.value = savedValue;
	    })
	    .on('input.autoExpand', 'textarea.autoExpand', function(){
	        var minRows = this.getAttribute('data-min-rows')|0, rows;
	        this.rows = minRows;
	        rows = Math.ceil((this.scrollHeight - this.baseScrollHeight) / 16);
	        this.rows = minRows + rows;
	    });

		$(function () {
		    $("#multiSelect").css("height", parseInt($("#multiSelect option").length) * 31);
		});
		
	</script>
	</body>
</html>