
@if(session('alert'))
    <div class="alert alert-success alert-dismissible mb-2">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon"></i>{{session('title')}}</h4>
        {{session('alert')}}
    </div>
@endif


@if(session('alert-error'))
    <div class="alert alert-danger alert-dismissible mb-2">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon"></i> {{session('title')}}</h4>
        {{session('alert-error')}}
    </div>
@endif
