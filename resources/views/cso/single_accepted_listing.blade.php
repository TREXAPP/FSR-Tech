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
      <li><a href="/{{Auth::user()->type()}}/accepted_listings/{{$listing_offer->id}}"><i class="fa fa-bookmark"></i> {{$listing_offer->hub_listing->product->name}}</a></li>
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
    <div class="{{($selected_filter == 'active') ? 'cso-accepted-listing-box' : 'cso-past-listing-box'}}
              box listing-box listing-box-{{$listing_offer->id}}">
      <div class="box-header with-border listing-box-header">

        <div class="listing-image">
          @if ($listing_offer->hub_listing->image_id)
            <img class="img-rounded" alt="{{$listing_offer->hub_listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($listing_offer->hub_listing->image_id)->filename)}}" />
          @elseif ($listing_offer->hub_listing->product->food_type->image_id)
            <img class="img-rounded" alt="{{$listing_offer->hub_listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($listing_offer->hub_listing->product->food_type->image_id)->filename)}}" />
          @else
            <img class="img-rounded" alt="{{$listing_offer->hub_listing->product->food_type->name}}" src="{{url('img/food_types/food-general.jpg')}}" />
          @endif

        </div>
        <div class="header-wrapper">
          <div id="listing-title-{{$listing_offer->id}}" class="listing-title col-xs-12 panel">
            <strong>{{$listing_offer->hub_listing->product->food_type->name}} | {{$listing_offer->hub_listing->product->name}}</strong>
          </div>
          <div class="header-elements-wrapper">
            <div class="col-md-4 col-sm-6 col-xs-12">
              <span class="col-xs-12">Достапна на платформата уште:</span>

              <span class="col-xs-12" id="expires-in-{{$listing_offer->id}}"><strong>{{Carbon::parse($listing_offer->hub_listing->date_expires)->diffForHumans()}}</strong></span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <span class="col-xs-12">Количина:</span>
              <span class="col-xs-12" id="quantity-offered-{{$listing_offer->id}}"><strong>{{$listing_offer->quantity}} {{$listing_offer->hub_listing->quantity_type->description}}</strong></span>

            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
              <span class="col-xs-12">Донирано од:</span>
              <span class="col-xs-12" id="hub-info-{{$listing_offer->id}}"><strong>{{$listing_offer->hub_listing->hub->first_name}} {{$listing_offer->hub_listing->hub->last_name}} | {{$listing_offer->hub_listing->hub->organization->name}}</strong></span>

            </div>
          </div>
        </div>

      </div>
      <div class="listing-box-body-wrapper">
        <div class="box-body">
          <div class="col-md-6">
            <div class="listing-info-box-inside listing-pick-up-time ">
              <span class="col-xs-12">Време за подигнување:</span>
              <span class="col-xs-12" id="pickup-time-{{$listing_offer->id}}"><strong>од {{Carbon::parse($listing_offer->hub_listing->pickup_time_from)->format('H:i')}} до {{Carbon::parse($listing_offer->hub_listing->pickup_time_to)->format('H:i')}} часот</strong></span>
            </div>
            <div class="listing-info-box-inside listing-expires-in ">
              <span class="col-xs-12">Рок на траење на храната:</span>
              <span class="col-xs-12" id="expires-in-{{$listing_offer->id}}"><strong>{{Carbon::parse($listing_offer->hub_listing->sell_by_date)->format('d.m.Y')}}</strong></span>
            </div>
            <?php
            $portion_size = 0;
            $beneficiaries_no = 0;
              foreach ($listing_offer->hub_listing->product->quantity_types as $quantity_type) {
                  //dump($quantity_type);
                  if ($quantity_type->pivot->quantity_type_id == $listing_offer->hub_listing->quantity_type->id) {
                      $portion_size = $quantity_type->pivot->portion_size;
                  }
              }
              if ($portion_size) {
                  $beneficiaries_no = intval($listing_offer->quantity / $portion_size);
              } else {
                  $beneficiaries_no = 0;
              }

            ?>
            <div class="listing-info-box-inside listing-beneficiaries-no ">
              <span class="col-xs-12"><b>За {{$beneficiaries_no}} лица*</b></span>
              <span class="col-xs-12"><small>*препорачана вредност</small></span>
              {{-- <span class="col-xs-12" id="food-type-{{$listing_offer->id}}"><strong>{{$listing_offer->hub_listing->product->food_type->name}}</strong></span> --}}
            </div>
            <div class="listing-info-box-inside listing-description">
              @if ($listing_offer->hub_listing->description)
                <span class="col-xs-12">Опис:</span>
                <span class="col-xs-12" id="description-{{$listing_offer->id}}"><strong>{{$listing_offer->hub_listing->description}}</strong></span>
              @endif
            </div>
          </div>

          @if ($listing_offer->delivered_by_hub)
            <div class="col-md-6 listing-info-box-inside ">
              <div class="text-bold">Избравте донацијата да ви биде доставена од хабот</div>
              <div class="alert alert-danger" style="padding: 5px; margin: 5px;">
                <i class="fa fa-warning"></i>
                <span>Хабот има право да побара соодветен надоместок за достава!</span>
              </div>
            </div>
          @else
            <div class="col-md-6 listing-info-box-inside listing-volunteer ">
                <span class="col-xs-12">Доставувач:</span>
              <div class="row">

                <div class="hidden" id="volunteer-id-{{$listing_offer->id}}">
                  {{$listing_offer->volunteer->id}}
                </div>

                <div id="volunteer-image-wrapper-{{$listing_offer->id}}" class="volunteer-image-wrapper two-col-layout-image-wrapper col-md-4">
                    <img id="volunteer-info-image-{{$listing_offer->id}}" class="img-rounded" alt="{{$listing_offer->volunteer->first_name}}" src="{{Methods::get_volunteer_image_url($listing_offer->volunteer)}}" />
                </div>

                <div id="volunteer-info-wrapper-{{$listing_offer->id}}" class="volunteer-info-wrapper-accepted two-col-layout-info-wrapper col-md-8" >

                  <!-- First Name -->
                  <div id="volunteer-info-first-name-{{$listing_offer->id}}" class="volunteer-info-first-name row">
                    <div id="volunteer-info-first-name-label-{{$listing_offer->id}}" class="volunteer-info-label volunteer-info-first-name-label col-md-4">
                      <span>Име:</span>
                    </div>
                    <div id="volunteer-info-first-name-value-{{$listing_offer->id}}" class="volunteer-info-value volunteer-info-first-name-value col-md-8">
                      <span>{{$listing_offer->volunteer->first_name}}</span>
                    </div>
                  </div>

                  <!-- Last Name -->
                  <div id="volunteer-info-last-name-{{$listing_offer->id}}" class="volunteer-info-last-name row">
                    <div id="volunteer-info-last-name-label-{{$listing_offer->id}}" class="volunteer-info-label volunteer-info-last-name-label col-md-4">
                      <span>Презиме:</span>
                    </div>
                    <div id="volunteer-info-last-name-value-{{$listing_offer->id}}" class="volunteer-info-value volunteer-info-last-name-value col-md-8">
                      <span>{{$listing_offer->volunteer->last_name}}</span>
                    </div>
                  </div>

                  <!-- Email -->
                  <div id="volunteer-info-email-{{$listing_offer->id}}" class="volunteer-info-email row">
                    <div id="volunteer-info-email-label-{{$listing_offer->id}}" class="volunteer-info-label volunteer-info-email-label col-md-4">
                      <span>Емаил:</span>
                    </div>
                    <div id="volunteer-info-email-value-{{$listing_offer->id}}" class="volunteer-info-value volunteer-info-email-value col-md-8">
                      <span>{{$listing_offer->volunteer->email}}</span>
                    </div>
                  </div>

                  <!-- Phone -->
                  <div id="volunteer-info-phone-{{$listing_offer->id}}" class="volunteer-info-phone row">
                    <div id="volunteer-info-phone-label-{{$listing_offer->id}}" class="volunteer-info-label volunteer-info-phone-label col-md-4">
                      <span>Телефон:</span>
                    </div>
                    <div id="volunteer-info-phone-value-{{$listing_offer->id}}" class="volunteer-info-value volunteer-info-phone-value col-md-8">
                      <span>{{$listing_offer->volunteer->phone}}</span>
                    </div>
                  </div>


                </div>

              </div>
              <!-- Change volunteer button -->
              <div class="volunteer-info-change-button row">
                <div class="col-xs-12">
                  @if($selected_filter == 'active')
                  <button type="button" id="edit-volunteer-button-{{$listing_offer->id}}" name="edit-volunteer-button-{{$listing_offer->id}}"
                    class="btn btn-success edit-volunteer-button" data-toggle="modal" data-target="#update-volunteer-popup">Промени доставувач</button>
                  @endif
                </div>
              </div>
            </div>
          @endif

        </div>
        <div class="box-footer text-center">


          <!-- Comments -->
          <div id='comments-{{$listing_offer->id}}' class="comments-wrapper">

            <div class="existing-comments-wrapper my-existing-comments-wrapper">


              @foreach ($comments->where('listing_offer_id', $listing_offer->id) as $comment)


                  @if ($comment->sender_type == 'cso')
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
                          @if($selected_filter == 'active')
                          <div id="comment-controls-{{$listing_offer->id}}" class="comment-controls">
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
                              <img class="img-rounded" alt="{{$listing_offer->hub_listing->hub->first_name}}" src="{{Methods::get_user_image_url($listing_offer->hub_listing->hub)}}" />
                            @elseif ($comment->sender_type == 'admin')
                              <img class="img-rounded" alt="{{FSR\Admin::find($comment->user_id)->first_name}}" src="{{Methods::get_user_image_url(FSR\Admin::find($comment->user_id))}}" />
                            @endif
                          </div>
                          <div class="comment-bubble other-comment-bubble">
                            <div class="comment-header other-comment-header col-xs-12">
                              @if ($comment->sender_type == 'hub')
                                <span class="comment-name other-comment-name">{{$listing_offer->hub_listing->hub->first_name}} {{$listing_offer->hub_listing->hub->last_name}}</span>
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
                @if($selected_filter == 'active')
                  <div class="new-comment-wrapper">
                    <div id="new-comment-box-wrapper-{{$listing_offer->id}}" class="new-comment-box-wrapper collapse" collapsed>
                      <form class="form-group new-comment-form" action="{{ route('cso.accepted_listings') }}" method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="listing_offer_id" value="{{$listing_offer->id}}">
                        <textarea class="form-control" name="comment" rows="2" cols="50"></textarea>
                        <button id="submit-comment" type="submit" name="submit-comment" class="btn btn-primary pull-right">Внеси</button>
                      </form>
                    </div>
                    <button type="button" data-toggle="collapse" data-target="#new-comment-box-wrapper-{{$listing_offer->id}}" class="btn btn-basic">Внеси коментар ...</button>
                  </div>
                @endif


          </div>

          <hr>
          @if($selected_filter == 'active')
            @if (Carbon::parse($listing_offer->hub_listing->date_expires)->addHours(config('constants.prevent_listing_delete_time')*(-1)) < Carbon::now())
              <button type="button" title="Прифатената донација не може да биде откажана бидејќи изминува наскоро!" id="delete-offer-button-{{$listing_offer->id}}" name="delete-offer-button-{{$listing_offer->id}}"
                        class="btn btn-danger delete-offer-button pull-right" data-toggle="modal" data-target="#delete-offer-popup" disabled>Избриши ја донацијата</button>
            @else
              <button type="button" title="Избриши ја донацијата" id="delete-offer-button-{{$listing_offer->id}}" name="delete-offer-button-{{$listing_offer->id}}"
                        class="btn btn-danger delete-offer-button pull-right" data-toggle="modal" data-target="#delete-offer-popup">Избриши ја донацијата</button>
            @endif
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
            <h4 id="popup-title" class="modal-title popup-title">Промени го доставувачот</h4>
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
