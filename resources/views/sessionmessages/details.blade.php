@if(Session::has('errors'))
<div id="errorMessage" class="alert alert-danger alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" arial-label="close">
        <span aria-hidden="true">&times;</span>
    </button>
    {{ Session::pull('errors') }}
</div>
@endif

@if(Session::has('message'))
<div id="successMessage" class="alert alert-info alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" arial-label="close">
        <span aria-hidden="true">&times;</span>
    </button>
    {{ Session::pull('message') }}
</div>
@endif