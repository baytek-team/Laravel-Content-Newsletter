<script>
	Dropzone.autoDiscover = false;
	$('.dz-pdf-preview').hide();

	/**
	 * Newsletter PDF
	 */
	jQuery('.dropzone-pdf').dropzone({ //'#dropzone-uploader'
		previewsContainer: $('.dropzone-pdf-preview')[0],
		previewTemplate: $('.dropzone-pdf-template').html(),
		clickable: '.dz-pdf-clickable',
		url: '{{ route('newsletter.file.store', $resource_id) }}',
		paramName: 'file', // The name that will be used to transfer the file
	    maxFilesize: 25, // MB
	    parallelUploads: 1, //limits number of files processed to reduce stress on server
	    addRemoveLinks: false,
	    acceptedFiles: '.pdf',
	    accept: function(file, done) {
	    	$('.dz-pdf-clickable').parent().hide();
	        // TODO: Image upload validation
	        done();
	    },
	    // drop: function() {
	    // 	jQuery('#upload-dimmer').dimmer('hide')
	    // },
	    // dragover: function() {
	    // 	jQuery('#upload-dimmer').dimmer('show')
	    // },
	    // dragend: function() {
	    // 	jQuery('#upload-dimmer').dimmer('hide')
	    // },
	    // dragleave: function() {
	    // 	jQuery('#upload-dimmer').dimmer('hide')
	    // },
	    init: function() {
	        this.on('success', function(file, response) {
	            // On successful upload do whatever :-)
	        });
	    },
	    sending: function(file, xhr, formData) {
            // Pass token. You can use the same method to pass any other values as well such as a id to associate the image with for example.
            formData.append('_token', $('input[name=\'_token\']').val() ); // Laravel expect the token post value to be named _token by default
        },
        // addedfile: function(file) {
        // 	// file.previewElement =
        // 	console.log(this.options.previewTemplate)
        // 	console.log(Dropzone.createElement(this.options.previewTemplate));
        // 	$(this.options.previewsContainer).append(file.previewElement);
        //   // file.previewElement.addEventListener("click", function() {
        //   // 	console.log(file);
        //   //   // myDropzone.removeFile(file);
        //   // });
        // },
		// thumbnail: function(file, dataUrl) {
		// 	// Display the image in your file.previewElement
		// },
		// uploadprogress: function(file, progress, bytesSent) {
		// 	// Display the progress
		// },
		success: function (file, response) {
			var $preview = $(file.previewElement);
			var downloadLink = $preview.find('.file-name')[0].dataset.href.replace(/\/1/g, '/' + response.id);
			var deleteLink = $preview.find('.pdf-delete')[0].dataset.href.replace(/\/1/g, '/' + response.id);
			$preview.find('.delete-button').remove();
			$preview.find('.progress').hide();
			$preview.find('.completed').hide();
			$preview.find('.uploading').remove();
			$preview.find('.dz-error-message').remove();
			$preview.addClass('positive');
			$preview.find('.file-name').attr('href', downloadLink);
			$preview.find('.dz-pdf-id').attr('value', response.id);
			$preview.find('.pdf-delete').attr('href', deleteLink);
			// $preview.find('.pdf-delete .delete-text').text('Delete');
		},
		error: function (file, response) {
			var $preview = $(file.previewElement);
			$preview.find('.pdf-delete').remove();
			$preview.addClass('negative');
			$preview.find('.uploading').remove();
			$preview.find('.progress').hide();

			if(typeof response != 'string' && typeof response.error.exception != 'undefined') {
				response = response.error.exception;
			}

			$preview.find('.dz-error-message').html('<strong>Error Uploading: </strong>');
		},
		removedfile: function(file) {
			$(file.previewElement).remove();
			$('.dz-pdf-clickable').parent().show();
		}
	});
</script>