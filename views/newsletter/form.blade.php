
<div class="two fields">
    <div class="twelve wide field{{ ($errors->has('title') || $errors->has('key')) ? ' error' : '' }}">
        <label for="title">{{ ___('Title') }}</label>
        <input type="text" id="title" name="title" placeholder="{{ ___('Title') }}" value="{{ old('title', $newsletter->title) }}">
    </div>
    <div class="four wide field{{ ($errors->has('newsletter_date') || $errors->has('key')) ? ' error' : '' }}">
        <label for="newsletter_date">{{ ___('Newsletter Date') }}</label>
        <input class="ui daterangepicker" type="text" id="newsletter_date" name="newsletter_date" placeholder="{{ ___('Newsletter Date') }}" value="{{ old('newsletter_date', $newsletter->newsletterDate->format('Y-m-d h:m A')) }}">
    </div>
</div>

{{-- <div class="field{{ $errors->has('content') ? ' error' : '' }}">
    <label for="content">{{ ___('Content') }}</label>
    <textarea id="content" name="content" class="editor" placeholder="{{ ___('Content') }}">{{ old('content', $newsletter->content) }}</textarea>
</div> --}}

<div class="ui horizontal divider header">
    <i class="file pdf outline icon"></i>
    {{ ___('PDF') }}
</div>

<p @if($pdf) style="display:none" @endif>
    <a class="item dz-pdf-clickable dropzone-pdf">
        <i class="file text icon"></i>
        {{ ___('Add PDF') }}
    </a>
</p>

<div class="dropzone-pdf-preview">
    @if($pdf)
        <div class="resource-details ui segment">
            <div class="ui very relaxed horizontal list">
                <a class="item" href="{{ route('newsletter.file.download', $pdf->id) }}">
                    <i class="pdf file outline icon"></i>
                    {{ $pdf->title }}
                </a>
                <a class="item pdf-delete" href="{{ route('newsletter.file.delete', $pdf->id) }}">
                    <i class="delete icon"></i>
                    {{ ___('Delete') }}
                </a>
            </div>
            <input type="hidden" name="pdf_ids[]" id="pdf_id" value="{{$pdf->id}}"/>
        </div>        
    @endif
</div>

<div class="ui horizontal divider header">
    <i class="file image outline icon"></i>
    {{ ___('Images') }}
</div>

<p>
    <a class="item dz-img-clickable">
        <i class="file text icon"></i>
        {{ ___('Add Images') }}
    </a>
</p>

<div clas="ui hidden divider"></div>

<div class="ui segment dropzone-img">
    <div class="ui five cards dropzone-img-preview">
        @forelse($images as $image)
            <div class="ui card">
                <div class="image">
                    <img src="{{route('newsletter.file.show', $image->id)}}">
                </div>
                <div class="ui button">
                    <a class="item img-delete" href="{{ route('newsletter.file.delete', $image->id) }}">
                        <i class="delete icon"></i>
                        {{ ___('Delete') }}
                    </a>
                </div>
                <input type="hidden" name="img_ids[]" value="{{$image->id}}">
            </div>
        @empty
        @endforelse
    </div>
</div>

<div class="ui hidden divider"></div>

<div class="dropzone-pdf-template" style="display: none">
    <div class="resource-details ui message">

        <div class="ui very relaxed horizontal list">
            <span class="item uploading"><strong>Uploading: </strong></span>
            <span class="item dz-error-message" data-dz-errormessage></span>
            <a class="item file-name" data-href="{{ route('newsletter.file.download', 1) }}">
                <i class="pdf file outline icon"></i>
                <span data-dz-name></span>
            </a>
            <a class="item pdf-delete" data-href="{{ route('newsletter.file.delete', 1) }}">
                <i class="delete icon"></i>
                {{ ___('Delete') }}
            </a>
            <a class="item delete-button" data-dz-remove>
                <i class="delete icon"></i>
                {{ ___('Remove') }}
            </a>
        </div>
        <input type="hidden" name="pdf_ids[]" class="dz-pdf-id" value="1"/>

        <div class="ui active green progress">
            <div class="bar" data-dz-uploadprogress>
                <div class="progress"></div>
            </div>
        </div>
    </div>
</div>

<div class="dropzone-img-template" style="display: none">
    <div class="ui card">
        <div class="image">
            <img data-dz-thumbnail />
        </div>
        <div class="content dz-error-message" data-dz-errormessage style="display: none"></div>
        <div class="ui button">
            <a class="item img-delete" data-href="{{ route('newsletter.file.delete', 1) }}">
                <i class="delete icon"></i>
                {{ ___('Delete') }}
            </a>
            <a class="item delete-button" data-dz-remove style="display:none">
                <i class="delete icon"></i>
                {{ ___('Remove') }}
            </a>
        </div>
        <input type="hidden" name="img_ids[]" class="dz-img-id" value="1">
    </div>
</div>

<div id="upload-dimmer" class="ui page dimmer">
    <div class="content">
        <div class="center">
            <h2 class="ui inverted icon header">
                <i class="cloud upload icon"></i>
                Drop to upload your file
                <div class="sub header">The file will start uploading automatically.</div>
            </h2>
        </div>
    </div>
</div>

@section('head')
<link rel="stylesheet" type="text/css" href="/css/daterangepicker.min.css">
{{-- <script type="text/javascript" src="/js/trix.js"></script> --}}
@endsection

@section('scripts')
<script type="text/javascript" src="/js/daterangepicker.min.js"></script>
<script>
    /**
     * Initialize the datepicker for the newsletter date
     */
    window.jQuery('.ui.daterangepicker').daterangepicker({
        singleDatePicker: true,
        format: 'YYYY-MM-DD h:mm A',
        timePicker: true,
        timePickerIncrement: 15,
        autoApply: true,
        showCustomRangeLabel: false,
        opens: "center",
    });

    /**
     * Initialize the newsletter image sorting
     */
    window.jQuery('.dropzone-img-preview').sortable({
        items: 'div.ui.card',
        cursor: 'move',
        containment: 'parent',
        scroll: false,
        scrollSensitivity: 40,
        forcePlaceholderSize: true,
        forceHelperSize: false,
        helper: 'clone',
        opacity: 0.65,
        placeholder: 'ui card',
        start: function( event, ui ) {
            ui.item.css( 'background-color', '#f6f6f6' );
        },
        stop: function( event, ui ) {
            ui.item.removeAttr( 'style' );
        },
        sort: function(event, ui) {  
            ui.helper.css({'top' : ui.position.top + $(window).scrollTop() + 'px'});
        }
    });

    /**
     * AJAX newsletter image deletion
     */
    window.jQuery('.dropzone-img-preview').on('click', 'a.img-delete', function(e) {
        e.preventDefault();
        window.user.confirm(
            {
                message: 'Are you sure you want to delete this image?<br/>This cannot be undone.',
                icon: 'warning circle icon',
            },
            function() {
                window.jQuery.post(e.target.href, { "_token": "{{ csrf_token() }}"}, function(response){
                    if (response && response.status == 'success') {
                        window.jQuery(e.target).closest('.card').remove();
                    }
                });
            },
            function() {
                //Handle denial (no action required in this case)
            }
        );        
    });

    /**
     * AJAX newsletter PDF deletion
     */
    window.jQuery('.dropzone-pdf-preview').on('click', 'a.pdf-delete', function(e) {
        e.preventDefault();
        window.user.confirm(
            {
                message: 'Are you sure you want to delete this file?<br/>This cannot be undone.',
                icon: 'warning circle icon',
            },
            function() {
                window.jQuery.post(e.target.href, { "_token": "{{ csrf_token() }}"}, function(response){
                    if (response && response.status == 'success') {
                        window.jQuery(e.target).closest('.resource-details').remove();
                        window.jQuery('.dz-pdf-clickable').parent().show();
                    }
                });
            },
            function() {
                //Handle denial (no action required in this case)
            }
        );        
    });
</script>

@include('newsletter::newsletter.dropzone', ['resource_id' => $newsletter->id])
@endsection
