@extends('layouts.app') @section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">@lang('register.header')</div>

        <div class="panel-body">
          <form class="form-horizontal" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
              <label for="type" class="col-md-4 control-label">@lang('register.type')</label>
              <div class="col-md-6">
                <select id="register_type_select" class="form-control" name="type">
                  <option value="">@lang('register.choose')</option>
                  <option value="donor"{{ (old('type') == 'donor') ? ' selected' : ''}}>@lang('register.donor')</option>
                  <option value="cso"{{ (old('type') == 'cso') ? ' selected' : ''}}>@lang('register.cso')</option>
                </select> @if ($errors->has('type'))
                <span class="help-block">
                    <strong>{{ $errors->first('type') }}</strong>
                </span> @endif
              </div>
            </div>


            <hr>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-md-4 control-label">@lang('register.email_address')</label>

              <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required {{ (!old('type')) ? ' disabled' : '' }}>
                @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
              @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <label for="password" class="col-md-4 control-label">@lang('register.password')</label>

              <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" required {{ (!old('type')) ? ' disabled' : '' }}>
                @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label for="password-confirm" class="col-md-4 control-label">@lang('register.confirm_password')</label>

              <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required {{ (!old('type')) ? ' disabled' : '' }}>
              </div>
            </div>

            <hr>


            <div class="form-group{{ $errors->has('profile_image') ? ' has-error' : '' }}">
              <label for="profile_image" class="col-md-4 control-label">@lang('register.image')</label>

              <div class="col-md-6">
                <input id="profile_image" type="file" class="form-control" name="profile_image" value="{{ old('profile_image') }}" {{ (!old('type')) ? ' disabled' : '' }}>
                @if ($errors->has('profile_image'))
                <span class="help-block">
                    <strong>{{ $errors->first('profile_image') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <hr>
{{--
            <div id="register_donor_type_wrapper" class="form-group{{ $errors->has('donor_type') ? ' has-error' : '' }}{{ (old('type') == 'donor') ? '' : ' hidden' }}">
              <label for="donor_type" class="col-md-4 control-label">@lang('register.donor_type')</label>
              <div class="col-md-6">
                <select id="register_donor_type_select" class="form-control" name="donor_type"{{ (!old('type')) ? ' disabled' : '' }}>
                  <option value="">@lang('register.choose')</option>
                  @foreach ($donor_types as $donor_type)
                    <option value="{{$donor_type->id}}"{{ (old('donor_type') == $donor_type->id) ? ' selected' : ''}}>{{$donor_type->name}}</option>
                  @endforeach
                </select> @if ($errors->has('donor_type'))
                <span class="help-block">
                    <strong>{{ $errors->first('donor_type') }}</strong>
                </span> @endif
              </div>
            </div> --}}

            <div class="form-group{{ $errors->has('organization') ? ' has-error' : '' }}">
              <label for="organization" class="col-md-4 control-label">@lang('register.organization')</label>
              <div class="col-md-6">
                <select id="register_organization_select" class="form-control" name="organization"{{ (!old('type')) ? ' disabled' : '' }}>
                  <option value="">@lang('register.choose')</option>
                  @foreach ($organizations as $organization)
                    <option value="{{$organization->id}}"{{ (old('organization') == $organization->id) ? ' selected' : ''}}>{{$organization->name}}</option>
                  @endforeach
                </select> @if ($errors->has('organization'))
                <span class="help-block">
                    <strong>{{ $errors->first('organization') }}</strong>
                </span> @endif
              </div>
            </div>


            <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
              <label for="location" class="col-md-4 control-label">@lang('register.location')</label>
              <div class="col-md-6">
                <select id="register_location_select" class="form-control" name="location"{{ (!old('type')) ? ' disabled' : '' }}>
                  <option value="">@lang('register.choose')</option>
                  @foreach ($locations as $location)
                    <option value="{{$location->id}}"{{ (old('location') == $location->id) ? ' selected' : ''}}>{{$location->name}}</option>
                  @endforeach
                </select> @if ($errors->has('location'))
                <span class="help-block">
                    <strong>{{ $errors->first('location') }}</strong>
                </span> @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
              <label for="first_name" class="col-md-4 control-label">@lang('register.first_name')</label>

              <div class="col-md-6">
                <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" {{ (!old('type')) ? ' disabled' : '' }} required>
                @if ($errors->has('first_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('first_name') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
              <label for="last_name" class="col-md-4 control-label">@lang('register.last_name')</label>

              <div class="col-md-6">
                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" {{ (!old('type')) ? ' disabled' : '' }} required>
                @if ($errors->has('last_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('last_name') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
              <label for="address" class="col-md-4 control-label">@lang('register.address')</label>

              <div class="col-md-6">
                <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" {{ (!old('type')) ? ' disabled' : '' }} required>
                @if ($errors->has('address'))
                <span class="help-block">
                    <strong>{{ $errors->first('address') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
              <label for="phone" class="col-md-4 control-label">@lang('register.phone')</label>

              <div class="col-md-6">
                <input id="phone" type="tel" class="form-control" name="phone" value="{{ old('phone') }}" {{ (!old('type')) ? ' disabled' : '' }} required>
                @if ($errors->has('phone'))
                <span class="help-block">
                    <strong>{{ $errors->first('phone') }}</strong>
                </span>
              @endif
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary" {{ (!old('type')) ? ' disabled' : '' }}>
                    @lang('register.submit')
                </button>
              </div>
            </div>
          </form>
          <div class="box-footer">
            Со регистрирање на платформата се подразбира дека се согласувате со <a href="https://drive.google.com/open?id=1-zMDAQmv8LgFmcX7yU4ml9aIZD0JqrbedCBH9I6YxHY" target="_blank">Правилата и прописите</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
