@extends('layouts.app')
@section('title')
Send News
@endsection
@section('content')
  <div class="container">

    <div class="row">
      <div class="col-sm-12">

      <a href="{!!url()->previous()!!}" class="btn btn-primary pull-left">Go back</a>
      <h4 style="color:black; " class="text-center">Send Message</h4>

      </div>
      <div class="col-sm-8 col-sm-offset-2">
        <form method="post"  action="{{route("news.store")}}">
          {{csrf_field()}}
          <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label for="subject" class=" control-label">Subject </label>

                  <input id="subject" type="text" class="form-control" name="subject" value="{{ $subject or old('subject') }}" required autofocus>

                  @if ($errors->has('subject'))
                      <span class="help-block">
                          <strong>{{ $errors->first('subject') }}</strong>
                      </span>
                  @endif

          </div>
          <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label for="name" class=" control-label">Message </label>

                 <textarea class="form-control" name="message" maxlength="255">

                 </textarea>

          </div>

         <button class="btn btn-primary pull-right" type="submit">Send</button>
        </form>

      </div>
    </div>
  </div>


@endsection
@section('script')
    <script src="//cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'message' );

    </script>

@endsection

