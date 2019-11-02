@extends('layouts.master')


@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header accepted-listings-content-header">
    <h1><i class="fa fa-bookmark"></i>
      <span>Прифатенa донациja</span>
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Донатор</a></li>
      <li><a href="/{{Auth::user()->type()}}/my_active_listings"> Мои донации</a></li>
      <li><a href="/{{Auth::user()->type()}}/accepted_listings/{{$hub_listing_offer->id}}"><i class="fa fa-bookmark"></i> Прифатенa донациja</a></li>
    </ol>
  </section>


<!-- Main content -->
<section class="content accepted-listings-content">

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif


      <div id="listingbox{{$hub_listing_offer->id}}" name="listingbox{{$hub_listing_offer->id}}"></div>
      <!-- Default box -->
      <div class=" {{($selected_filter == 'active') ? 'donor-my-accepted-listings-box' : 'donor-my-past-listings-box'}}
            box listing-box listing-box-{{$hub_listing_offer->id}} collaped-box">
        <div class="box-header with-border listing-box-header">
            <div class="listing-image">
              @if ($hub_listing_offer->listing->image_id)
                <img class="img-rounded" alt="{{$hub_listing_offer->listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($hub_listing_offer->listing->image_id)->filename)}}" />
              @elseif ($hub_listing_offer->listing->product->food_type->image_id)
                <img class="img-rounded" alt="{{$hub_listing_offer->listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($hub_listing_offer->listing->product->food_type->image_id)->filename)}}" />
              @else
                <img class="img-rounded" alt="{{$hub_listing_offer->listing->product->food_type->name}}" src="{{url('img/food_types/food-general.jpg')}}" />
              @endif

            </div>
            <div class="header-wrapper">
              <div id="listing-title-{{$hub_listing_offer->id}}" class="listing-title col-xs-12 panel">
                <strong>{{$hub_listing_offer->listing->product->food_type->name}} | {{$hub_listing_offer->listing->product->name}}</strong>
              </div>
              <div class="header-elements-wrapper">
                <div class="col-md-4 col-sm-4 col-xs-12 donor-accepted-header-element">
                  <span class="col-xs-12">Прифатена Количина:</span>
                  <span class="col-xs-12" id="quantity-offered-{{$hub_listing_offer->id}}"><strong>{{$hub_listing_offer->quantity}} {{$hub_listing_offer->listing->quantity_type->description}}</strong></span>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 donor-accepted-header-element">
                  <span class="col-xs-12">Време за подигнување:</span>
                  <span class="col-xs-12" id="pickup-time-{{$hub_listing_offer->id}}"><strong>од {{Carbon::parse($hub_listing_offer->listing->pickup_time_from)->format('H:i')}} до {{Carbon::parse($hub_listing_offer->listing->pickup_time_to)->format('H:i')}} часот</strong></span>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 donor-accepted-header-element">
                  <span class="col-xs-12">Достапна на платформата уште:</span>
                  <span class="col-xs-12" id="expires-in-{{$hub_listing_offer->id}}"><strong>{{Carbon::parse($hub_listing_offer->listing->date_expires)->diffForHumans()}}</strong></span>
                </div>
              </div>
            </div>
        </div>
        <div class="listing-box-body-wrapper">
          <div class="box-body">
              <div class="panel col-xs-12 text-center">
                Хаб
              </div>
              <div class="col-md-4 col-sm-12 volunteer-image-wrapper ">
                @if ($hub_listing_offer->hub->image_id)
                  <img class="img-rounded" alt="{{$hub_listing_offer->hub->first_name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($hub_listing_offer->volunteer->image_id)->filename)}}" />
                @else
                  <img class="img-rounded" alt="{{$hub_listing_offer->hub->first_name}}" src="{{Methods::get_hub_image_url($hub_listing_offer->hub)}}" />
                @endif
              </div>
              <hr>
              <div class="col-md-8 col-sm-12 hub-wrapper">
                <div class="row">
                  <div class="col-sm-6">
                    <span class="col-xs-12">Име и презиме:</span>
                    <span class="col-xs-12" id="hub-name-{{$hub_listing_offer->id}}"><strong>{{$hub_listing_offer->hub->first_name}} {{$hub_listing_offer->hub->last_name}}</strong></span>
                  </div>
                  <div class="col-sm-6">
                    <span class="col-xs-12">Организација:</span>
                    <span class="col-xs-12" id="hub-organization-{{$hub_listing_offer->id}}"><strong>{{$hub_listing_offer->hub->organization->name}}</strong></span>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-sm-6">
                    <span class="col-xs-12">Телефон за контакт:</span>
                    <span class="col-xs-12" id="hub-phone-{{$hub_listing_offer->id}}"><strong>{{$hub_listing_offer->hub->phone}}</strong></span>
                  </div>
                  <div class="col-sm-6">
                    <span class="col-xs-12">Емаил:</span>
                    <span class="col-xs-12" id="hub-email-{{$hub_listing_offer->id}}"><strong>{{$hub_listing_offer->hub->email}}</strong></span>
                  </div>
                </div>
              </div>

          </div>
          <div class="box-footer text-center">

            <!-- Comments -->
            <div id="comments" class="comments-wrapper">

              <div class="existing-comments-wrapper my-existing-comments-wrapper">
                @foreach ($comments as $comment)

                  @if ($comment->sender_type == 'donor')
                  <div class="row comment-row my-comment-row">
                    <div class="comment-image my-comment-image">
                        <img class="img-rounded" alt="{{Auth::user()->first_name}}" src="{{Methods::get_user_image_url(Auth::user())}}" />
                    </div>
                    <div class="comment-bubble my-comment-bubble">
                      <div class="comment-header my-comment-header col-xs-12">
                        <span class="comment-name my-comment-name">{{Auth::user()->first_name}} {{Auth::user()->last_name}} (јас)</span>
                        <span class="comment-time my-comment-time">{{Carbon::parse($comment->updated_at)->diffForHumans()}}</span>
                        @if ($comment->created_at != $comment->updated_at)
                          <span class="comment-edited my-comment-edited">(изменет)</span>
                        @endif
                        @if ($selected_filter == 'active')
                          <div id="comment-controls-{{$hub_listing_offer->id}}" class="comment-controls">
                            <a href="#" id="edit-comment-button-{{$comment->id}}" class="edit-comment-button"
                              data-toggle="modal" data-target="#edit-comment-popup" ><i class="fa fa-pencil fa-1-5x"></i></a>
                            <a href="#" id="delete-comment-button-{{$comment->id}}" class="delete-comment-button"
                              data-toggle="modal" data-target="#delete-comment-popup" ><i class="fa fa-trash fa-1-5x"></i></a>
                          </div>
                        @endif
                      </div>
                      <hr class="comment-hr my-comment-hr">
                      <div id="comment-text-{{$comment->id}}" class="comment-text my-comment-text col-xs-12">
                        <span>{{$comment->text}}</span>
                      </div>
                    </div>
                  </div>
                  @endif

                  @if ($comment->sender_type == 'hub' || $comment->sender_type == 'admin')
                  <div class="row comment-row other-comment-row">
                    <div class="comment-image other-comment-image">
                      @if ($comment->sender_type == 'hub')
                        <img class="img-rounded" alt="{{$hub_listing_offer->hub->first_name}}" src="{{Methods::get_user_image_url($hub_listing_offer->hub)}}" />
                      @elseif ($comment->sender_type == 'admin')
                        <img class="img-rounded" alt="{{FSR\Admin::find($comment->user_id)->first_name}}" src="{{Methods::get_user_image_url(FSR\Admin::find($comment->user_id))}}" />
                      @endif
                    </div>
                    <div class="comment-bubble other-comment-bubble">
                      <div class="comment-header other-comment-header col-xs-12">
                        @if ($comment->sender_type == 'hub')
                          <span class="comment-name other-comment-name">{{$hub_listing_offer->hub->first_name}} {{$hub_listing_offer->hub->last_name}}</span>
                        @elseif ($comment->sender_type == 'admin')
                          <span class="comment-name other-comment-name">{{FSR\Admin::find($comment->user_id)->first_name}} {{FSR\Admin::find($comment->user_id)->last_name}}</span>
                        @endif
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
              @if ($selected_filter == 'active')
                <div class="new-comment-wrapper">
                  <div id="new-comment-box-wrapper" class="new-comment-box-wrapper collapse" collapsed>
                    <form class="form-group new-comment-form" action="{{ route('donor.single_hub_listing_offer', $hub_listing_offer->id) }}" method="post">
                      {{csrf_field()}}
                      <input type="hidden" name="hub_listing_offer_id" value="{{$hub_listing_offer->id}}">
                      <textarea class="form-control" name="comment" rows="2" cols="50"></textarea>
                      <button id="submit-comment" type="submit" name="submit-comment" class="btn btn-primary pull-right">Внеси</button>
                    </form>
                  </div>
                  <button type="button" data-toggle="collapse" data-target="#new-comment-box-wrapper" class="btn btn-basic">Внеси коментар ...</button>
                </div>
              @endif

            </div>



          </div>
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->



    <!-- Delete comment Modal  -->
    <div id="delete-comment-popup" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <form id="delete-comment-form" class="delete-comment-form" action="{{  route('donor.single_hub_listing_offer', $hub_listing_offer->id) }}" method="post">
            {{ csrf_field() }}
            <input id="popup-hidden-delete-comment-id" type="hidden" name="comment_id" value="">
            <input id="popup-hidden-delete-listing-offer-id" type="hidden" name="hub_listing_offer_id" value="">
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
          <form id="edit-comment-form" class="edit-comment-form" action="{{  route('donor.single_hub_listing_offer', $hub_listing_offer->id) }}" method="post">
            {{ csrf_field() }}
            <input id="popup-hidden-edit-comment-id" type="hidden" name="comment_id" value="">
            <input id="popup-hidden-edit-listing-offer-id" type="hidden" name="hub_listing_offer_id" value="">
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
