@extends('layouts.master')


@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header accepted-listings-content-header">
    <h1><i class="fa fa-bookmark"></i>
      <span>Прифатенa донациja</span>
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Примател</a></li>
      <li><a href="/{{Auth::user()->type()}}/accepted_listings"><i class="fa fa-bookmark"></i> Прифатени донации</a></li>
      <li><a href="/{{Auth::user()->type()}}/accepted_listings/{{$listing_offer->id}}"><i class="fa fa-bookmark"></i> {{$listing_offer->listing->product->name}}</a></li>
    </ol>
  </section>


<!-- Main content -->
<section class="content accepted-listings-content">

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif

  @if ($errors->any())
      <div class="alert alert-danger">
        Измените не се прифатени! Корегирајте ги грешките и обидете се повторно.
        <a href="javascript:document.getElementById('listingbox{{ old('lising_offer_id') }}').scrollIntoView();">
          <button type="button" class="btn btn-default">Иди до донацијата</button>
        </a>
      </div>
  @endif


    <div id="listingbox{{$listing_offer->id}}" name="listingbox{{$listing_offer->id}}"></div>
    <!-- Default box -->
    <div class="cso-accepted-listing-box box listing-box listing-box-{{$listing_offer->id}}">
      <div class="box-header with-border listing-box-header">

        <div class="listing-image">
          {{-- <img src="../img/avatar5.png" /> --}}
          @if ($listing_offer->listing->image_id)
            <img class="img-rounded" alt="{{$listing_offer->listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($listing_offer->listing->image_id)->filename)}}" />
          @elseif ($listing_offer->listing->product->food_type->image_id)
            <img class="img-rounded" alt="{{$listing_offer->listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($listing_offer->listing->product->food_type->image_id)->filename)}}" />
          @else
            <img class="img-rounded" alt="{{$listing_offer->listing->product->food_type->name}}" src="{{url('img/food_types/food-general.jpg')}}" />
          @endif

        </div>
        <div class="header-wrapper">
          <div id="listing-title-{{$listing_offer->id}}" class="listing-title col-xs-12 panel">
            <strong>
              {{$listing_offer->listing->product->name}}
            </strong>
          </div>
          <div class="header-elements-wrapper">
            <div class="col-md-3 col-sm-6 col-xs-12">
              <span class="col-xs-12">Истекува за:</span>

              <span class="col-xs-12" id="expires-in-{{$listing_offer->id}}"><strong>{{Carbon::parse($listing_offer->listing->date_expires)->diffForHumans()}}</strong></span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <span class="col-xs-12">Количина:</span>
              <span class="col-xs-12" id="quantity-offered-{{$listing_offer->id}}"><strong>{{$listing_offer->quantity}} {{$listing_offer->listing->quantity_type->description}}</strong></span>

            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <span class="col-xs-12">Локација:</span>
              <span class="col-xs-12" id="donor-location-{{$listing_offer->id}}"><strong>{{$listing_offer->listing->donor->location->name}}</strong></span>

            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <span class="col-xs-12">Донирано од:</span>
              <span class="col-xs-12" id="donor-info-{{$listing_offer->id}}"><strong>{{$listing_offer->listing->donor->first_name}} {{$listing_offer->listing->donor->last_name}} | {{$listing_offer->listing->donor->organization->name}}</strong></span>

            </div>
          </div>
        </div>

      </div>
      <div class="listing-box-body-wrapper">
        <div class="box-body">
          <div class="row">
            <div class="col-md-4 col-sm-6 listing-pick-up-time ">
              <span class="col-xs-12">Време за подигнување:</span>
              <span class="col-xs-12" id="pickup-time-{{$listing_offer->id}}"><strong>од {{Carbon::parse($listing_offer->listing->pickup_time_from)->format('H:i')}} до {{Carbon::parse($listing_offer->listing->pickup_time_to)->format('H:i')}} часот</strong></span>
            </div>
            <div class="col-md-4 col-sm-6 listing-food-type ">
              <span class="col-xs-12">Тип на храна:</span>
              <span class="col-xs-12" id="food-type-{{$listing_offer->id}}"><strong>{{$listing_offer->listing->product->food_type->name}}</strong></span>
            </div>
            <div class="col-md-4 col-sm-6 listing-volunteer ">
              <span class="col-xs-12">Волонтер:</span>
              <span class="col-xs-12" id="volunteer-{{$listing_offer->id}}"><strong>{{$listing_offer->volunteer->first_name}} {{$listing_offer->volunteer->last_name}}</strong></span>
              <div class="hidden" id="volunteer-id-{{$listing_offer->id}}">{{$listing_offer->volunteer->id}}</div>
              <button type="button" id="edit-volunteer-button-{{$listing_offer->id}}" name="edit-volunteer-button-{{$listing_offer->id}}"
                class="btn btn-success edit-volunteer-button" data-toggle="modal" data-target="#update-volunteer-popup">Промени волонтер</button>

            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-xs-12 listing-description">
              @if ($listing_offer->listing->description)
                <span class="col-xs-12">Опис:</span>
                <span class="col-xs-12" id="description-{{$listing_offer->id}}"><strong>{{$listing_offer->listing->description}}</strong></span>
              @endif
            </div>
          </div>
          <div class="row">
            {{-- <div class="col-xs-12 listing-description">
              @if ($listing_offer->listing->description)
                <span class="col-xs-12">Коментари:</span>
                <span class="col-xs-12" id="description-{{$listing_offer->id}}"><strong>{{$listing_offer->listing->description}}</strong></span>
              @endif
            </div> --}}
          </div>
        </div>
        <div class="box-footer text-center">
          <!-- Comments -->
          <div id="comments" class="comments-wrapper">
            <div class="new-comment-wrapper">
              <button type="button" data-toggle="collapse" data-target="#new-comment-box-wrapper" class="btn btn-basic">Внеси коментар ...</button>
              <div id="new-comment-box-wrapper" class="new-comment-box-wrapper collapse" collapsed>
                <form class="form-group new-comment-form" action="{{ route('cso.accepted_listings.single_accepted_listing', $listing_offer->id) }}" method="post">
                  {{csrf_field()}}
                  <input type="hidden" name="listing_offer_id" value="{{$listing_offer->id}}">
                  <textarea class="form-control" name="comment" rows="2" cols="50"></textarea>
                  <button id="submit-comment" type="submit" name="submit-comment" class="btn btn-primary pull-right">Внеси</button>
                </form>
              </div>
            </div>
            <div class="existing-comments-wrapper my-existing-comments-wrapper col-xs-12">
              @foreach ($comments as $comment)

                @if ($comment->sender_type == 'cso')
                <div class="row comment-row my-comment-row">
                  <div class="comment-image my-comment-image">
                    @if (Auth::user()->profile_image_id)
                      <img class="img-rounded" alt="{{Auth::user()->first_name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find(Auth::user()->profile_image_id)->filename)}}" />
                    @else
                      <img class="img-rounded" alt="{{Auth::user()->first_name}}" src="{{url('img/avatar5.png')}}" />
                    @endif
                  </div>
                  <div class="comment-bubble my-comment-bubble">
                    <div class="comment-header my-comment-header col-xs-12">
                      <span class="comment-name my-comment-name">{{Auth::user()->first_name}} {{Auth::user()->last_name}} (јас)</span>
                      <span class="comment-time my-comment-time">{{Carbon::parse($comment->updated_at)->diffForHumans()}}</span>
                      @if ($comment->created_at != $comment->updated_at)
                        <span class="comment-edited my-comment-edited">(изменет)</span>
                      @endif
                      <div id="comment-controls-{{$listing_offer->id}}" class="comment-controls">
                        <a href="#" id="edit-comment-button-{{$comment->id}}" class="edit-comment-button"
                                data-toggle="modal" data-target="#edit-comment-popup" ><i class="fa fa-pencil fa-1-5x"></i></a>
                        <a href="#" id="delete-comment-button-{{$comment->id}}" class="delete-comment-button"
                                data-toggle="modal" data-target="#delete-comment-popup" ><i class="fa fa-trash fa-1-5x"></i></a>
                      </div>
                    </div>
                    <hr class="comment-hr my-comment-hr">
                    <div id="comment-text-{{$comment->id}}" class="comment-text my-comment-text col-xs-12">
                      <span>{{$comment->text}}</span>
                    </div>
                  </div>
                </div>
                @endif

                @if ($comment->sender_type == 'donor')
                <div class="row comment-row other-comment-row">
                  <div class="comment-image other-comment-image">
                    @if ($listing_offer->listing->donor->profile_image_id)
                      <img class="img-rounded" alt="{{$listing_offer->listing->donor->first_name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($listing_offer->listing->donor->profile_image_id)->filename)}}" />
                    @else
                      <img class="img-rounded" alt="{{$listing_offer->listing->donor->first_name}}" src="{{url('img/avatar5.png')}}" />
                    @endif
                  </div>
                  <div class="comment-bubble other-comment-bubble">
                    <div class="comment-header other-comment-header col-xs-12">
                      <span class="comment-name other-comment-name">{{$listing_offer->listing->donor->first_name}} {{$listing_offer->listing->donor->last_name}}</span>
                      <span class="comment-time other-comment-time">{{Carbon::parse($comment->updated_at)->diffForHumans()}}</span>
                      @if ($comment->created_at != $comment->updated_at)
                        <span class="comment-edited other-comment-edited">(изменет)</span>
                      @endif
                    </div>
                    <hr class="comment-hr other-comment-hr">
                    <div class="comment-text other-comment-text col-xs-12">
                      <span>{{$comment->text}}</span>
                    </div>
                  </div>
                </div>
                @endif


              @endforeach
            </div>
          </div>

          <hr>
          @if (Carbon::parse($listing_offer->listing->date_expires)->addHours(config('constants.prevent_listing_delete_time')*(-1)) < Carbon::now())
            <button type="button" title="Прифатената донација не може да биде откажана бидејќи изминува наскоро!" id="delete-offer-button-{{$listing_offer->id}}" name="delete-offer-button-{{$listing_offer->id}}"
                      class="btn btn-danger delete-offer-button pull-right" data-toggle="modal" data-target="#delete-offer-popup" disabled>Избриши ја донацијата</button>
          @else
            <button type="button" title="Избриши ја донацијата" id="delete-offer-button-{{$listing_offer->id}}" name="delete-offer-button-{{$listing_offer->id}}"
                      class="btn btn-danger delete-offer-button pull-right" data-toggle="modal" data-target="#delete-offer-popup">Избриши ја донацијата</button>
          @endif
        </div>
      </div>
      <!-- /.box-footer-->
    </div>
    <!-- /.box -->



  <!-- Update Volunteer Modal  -->
  <div id="update-volunteer-popup" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <form id="update-volunteer-form" class="update-volunteer-form" action="{{ route('cso.accepted_listings') }}" method="post">
          {{ csrf_field() }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 id="popup-title" class="modal-title popup-title">Промени го волонтерот</h4>
          </div>
          <div id="update-volunteer-body" class="modal-body update-volunteer-body">
            <!-- Form content-->
            <h5 id="popup-info" class="popup-info row italic">
            </h5>

            <div id="popup-volunteer-wrapper" class="popup-volunteer-wrapper popup-element row">
              <div class="popup-volunteer-element form-group col-xs-12">
                <select id="popup-volunteer-select" class="popup-volunteer-select form-control" name="volunteer">
                  @foreach (Auth::user()->organization->volunteers as $volunteer)
                    @if ($volunteer->status == 'active')
                      <option value="{{$volunteer->id}}">{{$volunteer->first_name}} {{$volunteer->last_name}}</option>
                    @endif
                  @endforeach
                </select>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <i id="popup-loading" class="popup-loading"></i>
            <input type="submit" id="update-volunteer-popup-submit" name="update-volunteer-popup-submit" class="btn btn-success" value="Промени" />
            <button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
          </div>
          <input type="hidden" id="popup-hidden-listing-id" name="listing_offer_id" value="">
        </form>
      </div>
    </div>
  </div>

  <!-- Delete offer Modal  -->
  <div id="delete-offer-popup" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <form id="delete-offer-form" class="delete-offer-form" action="{{ route('cso.accepted_listings') }}" method="post">
          {{ csrf_field() }}
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 id="popup-title" class="modal-title popup-title">Избриши ја донацијата</h4>
          </div>
          <div id="delete-offer-body" class="modal-body delete-offer-body">
            <!-- Form content-->
            <h5 id="popup-info" class="popup-info row italic">
              Дали сте сигурни дека сакате да ја избришите прифатената донација?
            </h5>
          </div>
          <div class="modal-footer">
            <input type="submit" name="delete-offer-popup" class="btn btn-danger" value="Избриши" />
            <button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Delete comment Modal  -->
  <div id="delete-comment-popup" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <form id="delete-comment-form" class="delete-comment-form" action="{{ route('cso.accepted_listings.single_accepted_listing', $listing_offer->id) }}" method="post">
          {{ csrf_field() }}
          <input id="popup-hidden-delete-comment-id" type="hidden" name="comment_id" value="">
          <input id="popup-hidden-delete-listing-offer-id" type="hidden" name="listing_offer_id" value="">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 id="popup-title" class="modal-title popup-title">Избриши го коментарот</h4>
          </div>
          <div id="delete-comment-body" class="modal-body delete-comment-body">
            <!-- Form content-->
            <h5 id="popup-info" class="popup-info row italic">
              Дали сте сигурни дека сакате да го избришите коментарот?
            </h5>
          </div>
          <div class="modal-footer">
            <input type="submit" name="delete-comment" class="btn btn-danger" value="Избриши" />
            <button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit comment Modal  -->
  <div id="edit-comment-popup" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <form id="edit-comment-form" class="edit-comment-form" action="{{ route('cso.accepted_listings.single_accepted_listing', $listing_offer->id) }}" method="post">
          {{ csrf_field() }}
          <input id="popup-hidden-edit-comment-id" type="hidden" name="comment_id" value="">
          <input id="popup-hidden-edit-listing-offer-id" type="hidden" name="listing_offer_id" value="">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 id="popup-title" class="modal-title popup-title">Измени го коментарот</h4>
          </div>
          <div id="edit-comment-body form-group" class="modal-body edit-comment-body">
            <!-- Form content-->
            <textarea id="edit-comment-text" class="form-control" name="edit_comment_text" rows="4" cols="50"></textarea>
          </div>
          <div class="modal-footer">
            <input type="submit" name="edit-comment" class="btn btn-success" value="Измени" />
            <button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->

@endsection
