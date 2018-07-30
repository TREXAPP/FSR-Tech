@extends('layouts.home')
@section('content')



<div class="container free-volunteers-container custom-container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">Форма за апликација на волонтери</div>

        <div class="panel-body">
          <form class="form-horizontal" method="POST" action="{{ route('free_volunteers') }}" enctype="multipart/form-data">
            {{ csrf_field() }}

            {{-- <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
              <label for="type" class="col-md-4 control-label">@lang('register.type')</label>
              <div class="col-md-6">
                <select id="free_volunteers_type_select" class="form-control" name="type">
                  <option value="">@lang('register.choose')</option>
                  <option value="donor"{{ (old('type') == 'donor') ? ' selected' : ''}}>@lang('register.donor')</option>
                  <option value="cso"{{ (old('type') == 'cso') ? ' selected' : ''}}>@lang('register.cso')</option>
                </select> @if ($errors->has('type'))
                <span class="help-block">
                    <strong>{{ $errors->first('type') }}</strong>
                </span> @endif
              </div>
            </div> --}}

{{--
            <hr> --}}


            <!-- First Name -->
            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
              <label for="first_name" class="col-md-4 control-label">Име</label>
              <div class="col-md-6">
                <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required >
                @if ($errors->has('first_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('first_name') }}</strong>
                </span>
              @endif
              </div>
            </div>

            <!-- Last Name -->
            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
              <label for="last_name" class="col-md-4 control-label">Презиме</label>
              <div class="col-md-6">
                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required >
                @if ($errors->has('last_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('last_name') }}</strong>
                </span>
              @endif
              </div>
            </div>

            <!-- Phone -->
            <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
              <label for="phone" class="col-md-4 control-label">Телефон</label>
              <div class="col-md-6">
                <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required >
                @if ($errors->has('phone'))
                <span class="help-block">
                    <strong>{{ $errors->first('phone') }}</strong>
                </span>
              @endif
              </div>
            </div>

            <!-- Email -->
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-md-4 control-label">Емаил</label>
              <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required >
                @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
              @endif
              </div>
            </div>

            <!-- Address -->
            <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
              <label for="address" class="col-md-4 control-label">Адреса</label>
              <div class="col-md-6">
                <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" required >
                @if ($errors->has('address'))
                <span class="help-block">
                    <strong>{{ $errors->first('address') }}</strong>
                </span>
              @endif
              </div>
            </div>

            <!-- Locations -->
            <div class="form-group{{ $errors->has('locations') ? ' has-error' : '' }}">
              <label for="locations" class="col-md-4 control-label">Општини</label>
              <div class="col-md-6">
                <select id="locations" class="form-control selectpicker" data-actions-box="true"
                      name="locations[]" multiple title="Избери..." required>

                  @foreach ($locations as $location)
                    <option value="{{$location->id}}" {{(false) ? 'selected' : ''}}>{{$location->name}}</option>
                  @endforeach
                </select>
                @if ($errors->has('locations'))
                <span class="help-block">
                    <strong>{{ $errors->first('locations') }}</strong>
                </span>
              @endif
              </div>
            </div>


            <hr>

            <!-- Free volunteer type -->
            <div class="form-group{{ $errors->has('free_volunteer_type') ? ' has-error' : '' }}">
              <label for="free_volunteer_type" class="col-md-4 control-label">Како сакате да помогнете?</label>
              <div class="col-md-6">
                <select id="free_volunteer_type" class="form-control selectpicker" name="free_volunteer_type" title="Избери..." required>
                  {{-- <option value="">-- Избери --</option> --}}
                  <option value="build_relationships" {{(false) ? 'selected' : ''}}>Градење врски</option>
                  <option value="transport_food" {{(false) ? 'selected' : ''}}>Пренесување на храна</option>
                </select>
                @if ($errors->has('free_volunteer_type'))
                <span class="help-block">
                    <strong>{{ $errors->first('free_volunteer_type') }}</strong>
                </span>
              @endif
              </div>
            </div>

            <hr>

<div id="transport_food_section" class="hidden">
  <!-- Transport types -->
  <div class="form-group{{ $errors->has('transport_types') ? ' has-error' : '' }}">
    <label for="transport_types" class="col-md-4 control-label">Типови на транспорт</label>
    <div class="col-md-6">
      <select id="transport_types" class="form-control selectpicker" name="transport_types[]"
            title="Избери..." multiple >
        @foreach ($transport_types as $transport_type)
          <option value="{{$transport_type->id}}" data-subtext="{{$transport_type->quantity}}" {{(false) ? 'selected' : ''}}>{{$transport_type->name}}</option>
        @endforeach
      </select>
      @if ($errors->has('transport_types'))
      <span class="help-block">
          <strong>{{ $errors->first('transport_types') }}</strong>
      </span>
    @endif
    </div>
  </div>

  <!-- Organizations -->
  <div class="form-group{{ $errors->has('organizations') ? ' has-error' : '' }}">
    <label for="organizations" class="col-md-4 control-label">Дали преферирате да волонтирате за некои од организациите?</label>
    <div class="col-md-6">
      <select id="organizations" class="form-control selectpicker" data-actions-box="true"
            name="organizations[]" multiple title="Избери..." >

        @foreach ($organizations as $organization)
          <option value="{{$organization->id}}" {{(false) ? 'selected' : ''}}>{{$organization->name}}</option>
        @endforeach
      </select>
      @if ($errors->has('organizations'))
      <span class="help-block">
          <strong>{{ $errors->first('organizations') }}</strong>
      </span>
    @endif
    </div>
  </div>


{{-- <div class="container">
<div class="container">
  <div class="row"> --}}
    <div id="availability_table" class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">Одберете кога би биле достапни за волонтирање</div>
          <div class="panel-body">
            <table data-row-style="rowStyle" data-toggle="table" data-click-to-select="true">
              <thead>
                <th>
                  {{-- <input type="checkbox" /> --}}
                </th>
                @foreach ($timeframes_cols as $timeframes_col)
                  <th><span>од {{$timeframes_col->hours_from}} до {{$timeframes_col->hours_to}}</span></th>
                @endforeach
              </thead>
              <tbody>
                @foreach ($timeframes_rows as $timeframes_row)
                <tr>
                  <td>
                    {{$timeframes_row->day}}
                  </td>
                  @foreach (FSR\Timeframe::where('status', 'active')->orderby('hours_from', 'ASC')->where('day', $timeframes_row->day)->get() as $timeframe)
                    <td>
                      <input id="chk_availability_{{$timeframe->id}}" name="chk_availability_{{$timeframe->id}}" type="checkbox" />
                    </td>
                  @endforeach
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    {{-- </div>
    </div>
  </div> --}}
</div>





{{--
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
            </div> --}}
            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <label>
                  <input type="checkbox" required /> Ги прифаќам <a href="https://drive.google.com/file/d/1q4BI9Vxl0P2742mgPN8tTESJXDssDZlT/view" target="_blank">Правилата и прописите</a>
                </label>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                {{-- <button type="submit" class="btn btn-primary">
                    Аплицирај
                </button> --}}
                <input type="submit" value="Аплицирај" class="btn btn-primary" />
              </div>
            </div>
          </form>
          <div class="box-footer">

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
