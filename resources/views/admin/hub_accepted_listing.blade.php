@extends('layouts.admin_master')


@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header accepted-listings-content-header">
    <h1><i class="fa fa-bookmark"></i>
      <span>Прифатенa донациja</span>
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Админ</a></li>
      <li><a href="#"><i class="fa fa-bookmark"></i> Прифатени донации</a></li>
      <li><a href="/{{Auth::user()->type()}}/listing_offer/{{$hub_listing_offer->id}}"><i class="fa fa-bookmark"></i> {{$hub_listing_offer->listing->product->name}}</a></li>
    </ol>
  </section>


<!-- Main content -->
<section class="content accepted-listings-content">

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif

    <!-- Default box -->
    <div class="admin-accepted-listing-box box listing-box listing-box-{{$hub_listing_offer->id}}">
      <div class="box-header with-border listing-box-header
                  {{($selected_filter == 'active') ? '' : 'listing-box-header-past'}}">

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
            {{-- <div class="col-md-3 col-sm-6 col-xs-12">
              <span class="col-xs-12">Достапна на платформата уште:</span>

              <span class="col-xs-12" id="expires-in-{{$hub_listing_offer->id}}"><strong>{{Carbon::parse($hub_listing_offer->listing->date_expires)->diffForHumans()}}</strong></span>
            </div> --}}
            <div class="col-md-6">
              <div class="col-md-6">
                <span class="col-xs-12">Прифатена количина:</span>
                <span class="col-xs-12" id="quantity-offered-{{$hub_listing_offer->id}}"><strong>{{$hub_listing_offer->quantity}} (од {{$hub_listing_offer->listing->quantity}}) {{$hub_listing_offer->listing->quantity_type->description}}</strong></span>
              </div>
              <div class="col-md-6">
                @if ($selected_filter == 'active')
                  <button class="btn btn-success" name="edit_accepted_quantity"
                    data-toggle="modal" data-target="#edit-quantity-popup">Измени</button>
                @endif
              </div>
            </div>

            <div class="col-md-6">
              <button class="btn btn-primary" name="admin_listing_details_popup"
                    data-toggle="modal" data-target="#admin-listing-details-popup">Детали за донацијата</button>
            </div>
            {{-- <div class="col-md-3 col-sm-6 col-xs-12">
              <span class="col-xs-12">Донирано од:</span>
              <span class="col-xs-12" id="donor-info-{{$hub_listing_offer->id}}"><strong>{{$hub_listing_offer->listing->donor->first_name}} {{$hub_listing_offer->listing->donor->last_name}} | {{$hub_listing_offer->listing->donor->organization->name}}</strong></span>

            </div> --}}
          </div>
        </div>

      </div>
      <div class="listing-box-body-wrapper">
        <div class="box-body">

          <div class="col-md-6 listing-info-box-inside listing-hub ">
              <span class="col-xs-12">Хаб:</span>
            <div class="row">

              <div class="hidden" id="hub-id-{{$hub_listing_offer->id}}">
                {{$hub_listing_offer->hub->id}}
              </div>

              <div id="hub-image-wrapper-{{$hub_listing_offer->id}}" class="hub-image-wrapper two-col-layout-image-wrapper col-md-4">
                  <img id="hub-info-image-{{$hub_listing_offer->id}}" class="img-rounded" alt="{{$hub_listing_offer->hub->first_name}}"
                        src="{{Methods::get_hub_image_url($hub_listing_offer->hub)}}" />
              </div>

              <div id="hub-info-wrapper-{{$hub_listing_offer->id}}" class="hub-info-wrapper-accepted two-col-layout-info-wrapper col-md-8" >

                <!-- First Name -->
                <div id="hub-info-first-name-{{$hub_listing_offer->id}}" class="hub-info-first-name row">
                  <div id="hub-info-first-name-label-{{$hub_listing_offer->id}}" class="hub-info-label hub-info-first-name-label col-md-4">
                    <span>Име:</span>
                  </div>
                  <div id="hub-info-first-name-value-{{$hub_listing_offer->id}}" class="hub-info-value hub-info-first-name-value col-md-8">
                    <span>{{$hub_listing_offer->hub->first_name}}</span>
                  </div>
                </div>

                <!-- Last Name -->
                <div id="hub-info-last-name-{{$hub_listing_offer->id}}" class="hub-info-last-name row">
                  <div id="hub-info-last-name-label-{{$hub_listing_offer->id}}" class="hub-info-label hub-info-last-name-label col-md-4">
                    <span>Презиме:</span>
                  </div>
                  <div id="hub-info-last-name-value-{{$hub_listing_offer->id}}" class="hub-info-value hub-info-last-name-value col-md-8">
                    <span>{{$hub_listing_offer->hub->last_name}}</span>
                  </div>
                </div>

                <!-- Email -->
                <div id="hub-info-email-{{$hub_listing_offer->id}}" class="hub-info-email row">
                  <div id="hub-info-email-label-{{$hub_listing_offer->id}}" class="hub-info-label hub-info-email-label col-md-4">
                    <span>Емаил:</span>
                  </div>
                  <div id="hub-info-email-value-{{$hub_listing_offer->id}}" class="hub-info-value hub-info-email-value col-md-8">
                    <span>{{$hub_listing_offer->hub->email}}</span>
                  </div>
                </div>

                <!-- Phone -->
                <div id="hub-info-phone-{{$hub_listing_offer->id}}" class="hub-info-phone row">
                  <div id="hub-info-phone-label-{{$hub_listing_offer->id}}" class="hub-info-label hub-info-phone-label col-md-4">
                    <span>Телефон:</span>
                  </div>
                  <div id="hub-info-phone-value-{{$hub_listing_offer->id}}" class="hub-info-value hub-info-phone-value col-md-8">
                    <span>{{$hub_listing_offer->hub->phone}}</span>
                  </div>
                </div>


              </div>

            </div>
          </div>

        </div>
        <div class="box-footer text-center">


          <!-- Comments -->
          <div id='comments-{{$hub_listing_offer->id}}' class="comments-wrapper">

            <div class="existing-comments-wrapper my-existing-comments-wrapper">


              @foreach ($comments->where('hub_listing_offer_id', $hub_listing_offer->id) as $comment)


                  @if ($comment->sender_type == 'admin')
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
                            <div id="admin-comment-controls-{{$hub_listing_offer->id}}" class="admin-comment-controls">
                              <a href="#" id="admin-edit-comment-button-hub-{{$comment->id}}" class="admin-edit-comment-button-hub"
                                data-toggle="modal" data-target="#edit-comment-popup" ><i class="fa fa-pencil fa-1-5x"></i></a>
                                <a href="#" id="admin-delete-comment-button-hub-{{$comment->id}}" class="admin-delete-comment-button-hub"
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

                      @if ($comment->sender_type == 'donor' || $comment->sender_type == 'hub')
                        <div class="row comment-row other-comment-row">
                          <div class="comment-image other-comment-image">
                            @if ($comment->sender_type == 'donor')
                              <img class="img-rounded" alt="{{$hub_listing_offer->listing->donor->first_name}}" src="{{Methods::get_user_image_url($hub_listing_offer->listing->donor)}}" />
                            @elseif ($comment->sender_type == 'hub')
                              <img class="img-rounded" alt="{{$hub_listing_offer->hub->first_name}}" src="{{Methods::get_user_image_url($hub_listing_offer->hub)}}" />
                            @endif
                          </div>
                          <div class="comment-bubble other-comment-bubble">
                            <div class="comment-header other-comment-header col-xs-12">
                              @if ($comment->sender_type == 'donor')
                                <span class="comment-name other-comment-name">(Донатор) {{$hub_listing_offer->listing->donor->first_name}} {{$hub_listing_offer->listing->donor->last_name}}</span>
                              @elseif ($comment->sender_type == 'hub')
                                <span class="comment-name other-comment-name">(Хаб) {{$hub_listing_offer->hub->first_name}} {{$hub_listing_offer->hub->last_name}}</span>
                              @endif
                              <span class="comment-time other-comment-time">{{Carbon::parse($comment->updated_at)->diffForHumans()}}</span>
                              @if ($comment->created_at != $comment->updated_at)
                                <span class="comment-edited other-comment-edited">(изменет)</span>
                              @endif
                              @if($selected_filter == 'active')
                                <div id="admin-comment-controls-{{$hub_listing_offer->id}}" class="admin-comment-controls">
                                  <a href="#" id="admin-edit-comment-button-hub-{{$comment->id}}" class="admin-edit-comment-button-hub"
                                    data-toggle="modal" data-target="#edit-comment-popup" ><i class="fa fa-pencil fa-1-5x"></i></a>
                                    <a href="#" id="admin-delete-comment-button-hub-{{$comment->id}}" class="admin-delete-comment-button-hub"
                                      data-toggle="modal" data-target="#delete-comment-popup" ><i class="fa fa-trash fa-1-5x"></i></a>
                                    </div>
                              @endif
                            </div>
                            <hr class="comment-hr other-comment-hr">
                            <div id="comment-text-{{$comment->id}}" class="comment-text other-comment-text col-xs-12">
                              <span>{{$comment->text}}</span>
                            </div>
                          </div>
                        </div>
                      @endif


                  @endforeach

                </div>
                @if($selected_filter == 'active')
                  <div class="new-comment-wrapper">
                    <div id="new-comment-box-wrapper-{{$hub_listing_offer->id}}" class="new-comment-box-wrapper collapse" collapsed>
                      <form class="form-group new-comment-form" action="{{ route('admin.hub_listing_offer', $hub_listing_offer->id) }}" method="post">
                        {{csrf_field()}}
                        <input id="post-type" type="hidden" name="post-type" value="new_comment">
                        <input type="hidden" name="hub_listing_offer_id" value="{{$hub_listing_offer->id}}">
                        <textarea class="form-control" name="comment" rows="2" cols="50"></textarea>
                        <button id="submit-comment" type="submit" name="submit-comment" class="btn btn-primary pull-right">Внеси</button>
                      </form>
                    </div>
                    <button type="button" data-toggle="collapse" data-target="#new-comment-box-wrapper-{{$hub_listing_offer->id}}" class="btn btn-basic">Внеси коментар ...</button>
                  </div>
                @endif
          </div>

          <hr>
          @if($selected_filter == 'active')
            <button type="button" title="Избриши ја прифатената донација" id="delete-offer-button-{{$hub_listing_offer->id}}" name="delete-offer-button-{{$hub_listing_offer->id}}"
                      class="btn btn-danger delete-offer-button pull-right" data-toggle="modal" data-target="#admin-delete-offer-popup">Избриши ја прифатената донација</button>
          @endif
        </div>
      </div>
      <!-- /.box-footer-->
    </div>
    <!-- /.box -->


  <!-- Delete offer Modal  -->
  <div id="admin-delete-offer-popup" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <form id="delete-offer-form" class="delete-offer-form" action="{{ route('admin.hub_listing_offer', $hub_listing_offer->id) }}" method="post">
          {{ csrf_field() }}
          <input id="post-type" type="hidden" name="post-type" value="delete_listing_offer">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 id="popup-title" class="modal-title popup-title">Избриши ја прифатената донација</h4>
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
        <form id="delete-comment-form" class="delete-comment-form" action="{{ route('admin.hub_listing_offer', $hub_listing_offer->id) }}" method="post">
          {{ csrf_field() }}
          <input id="post-type" type="hidden" name="post-type" value="delete_comment">
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
        <form id="edit-comment-form" class="edit-comment-form" action="{{ route('admin.hub_listing_offer', $hub_listing_offer->id) }}" method="post">
          {{ csrf_field() }}
          <input id="post-type" type="hidden" name="post-type" value="edit_comment">
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

  <!-- Edit accepted quantity Modal  -->
  <div id="edit-quantity-popup" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <form id="edit-quantity-form" class="edit-quantity-form" action="{{ route('admin.hub_listing_offer', $hub_listing_offer->id) }}" method="post">
          {{ csrf_field() }}
          <input id="post-type" type="hidden" name="post-type" value="edit_quantity">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 id="popup-title" class="modal-title popup-title">Измени ја количината</h4>
          </div>
          <div id="edit-quantity-body form-group" class="modal-body edit-quantity-body">
            <!-- Form content-->
            <input type="number" id="edit-quantity" name="edit_quantity" class="form-control"
                  min="0.0001" max="{{$max_quantity}}" step="0.0001"
                  value="{{$hub_listing_offer->quantity}}" >
          </div>
          <div class="modal-footer">
            <input type="submit" name="edit-quantity" class="btn btn-success" value="Измени" />
            <button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <!-- Details Modal -->
  <div id="admin-listing-details-popup" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 id="details-popup-title" class="modal-title popup-title details-popup-title">Детали за донацијата</h4>
          </div>
          <div id="listing-confirm-body" class="modal-body listing-confirm-body">
            <!-- Form content-->
            <h5 id="popup-info" class="popup-info row italic">
            </h5>
            <div id="details-popup-quantity" class="details-popup-quantity popup-element row">
              <div class="details-popup-quantity-label col-xs-6">
                <span class="pull-right popup-element-label">Целосна количина:</span>
              </div>
              <div id="details-popup-quantity-value" class="details-popup-quantity-value popup-element-value col-xs-6">{{$hub_listing_offer->listing->quantity}} {{$hub_listing_offer->listing->quantity_type->description}}</div>
            </div>

            <div id="details-popup-pickup-time" class="details-popup-pickup-time popup-element row">
              <div class="details-popup-pickup-time-label col-xs-6">
                <span class="pull-right popup-element-label">Време за подигнување:</span>
              </div>
              <div id="details-popup-pickup-time-value" class="details-popup-pickup-time-value popup-element-value col-xs-6">од {{Carbon::parse($hub_listing_offer->listing->pickup_time_from)->format('H:i')}} до {{Carbon::parse($hub_listing_offer->listing->pickup_time_to)->format('H:i')}} часот</div>
            </div>

            <div id="details-popup-listed" class="details-popup-listed popup-element row">
              <div class="details-popup-listed-label col-xs-6">
                @if ($selected_filter == 'active')
                  <span class="pull-right popup-element-label">Важи од:</span>
                @else
                  <span class="pull-right popup-element-label">Важеше од:</span>
                @endif
              </div>
              @if ($selected_filter == 'active')
                <div id="details-popup-listed-value" class="details-popup-listed-value popup-element-value col-xs-6">{{Carbon::parse($hub_listing_offer->listing->date_listed)->diffForHumans()}}</div>
              @else
                <div id="details-popup-listed-value" class="details-popup-listed-value popup-element-value col-xs-6">{{Carbon::parse($hub_listing_offer->listing->date_listed)->format('d-m-Y')}}</div>
              @endif
            </div>

            <div id="details-popup-sell-by-date" class="details-popup-sell-by-date popup-element row">
              <div class="details-popup-sell-by-date-label col-xs-6">
                <span class="pull-right popup-element-label">Употребливо до:</span>
              </div>
              @if ($selected_filter == 'active')
                <div id="details-popup-sell-by-date-value" class="details-popup-sell-by-date-value popup-element-value col-xs-6">{{Carbon::parse($hub_listing_offer->listing->sell_by_date)->diffForHumans()}}</div>
              @else
                <div id="details-popup-sell-by-date-value" class="details-popup-sell-by-date-value popup-element-value col-xs-6">{{Carbon::parse($hub_listing_offer->listing->sell_by_date)->format('d-m-Y')}}</div>
              @endif
            </div>

            <div id="details-popup-expires-in" class="details-popup-expires-in popup-element row">
              <div class="details-popup-expires-in-label col-xs-6">
                @if ($selected_filter == 'active')
                  <span class="pull-right popup-element-label">Достапна на платформата уште:</span>
                @else
                  <span class="pull-right popup-element-label">Истече на:</span>
                @endif
              </div>
              @if ($selected_filter == 'active')
                <div id="details-popup-expires-in-value" class="details-popup-expires-in-value popup-element-value col-xs-6">{{Carbon::parse($hub_listing_offer->listing->date_expires)->diffForHumans()}}</div>
              @else
                <div id="details-popup-expires-in-value" class="details-popup-expires-in-value popup-element-value col-xs-6">{{Carbon::parse($hub_listing_offer->listing->date_expires)->format('d-m-Y')}}</div>
              @endif
            </div>

            <div id="details-popup-food-type" class="details-popup-food-type popup-element row">
              <div class="details-popup-food-type-label col-xs-6">
                <span class="pull-right popup-element-label">Тип на храна:</span>
              </div>
              <div id="details-popup-food-type-value" class="details-popup-food-type-value popup-element-value col-xs-6">{{$hub_listing_offer->listing->product->food_type->name}}</div>
            </div>

            <div id="details-popup-description" class="details-popup-description popup-element row">
              <div class="details-popup-description-label col-xs-6">
                <span class="pull-right popup-element-label">Опис:</span>
              </div>
              <div id="details-popup-description-value" class="details-popup-description-value popup-element-value col-xs-6">{{$hub_listing_offer->listing->description}}</div>
            </div>
            <h5>
            </h5>
          <div class="modal-footer">
            {{-- <input type="submit" name="submit-listing-popup" class="btn btn-primary" value="Прифати" /> --}}
          @if($selected_filter == 'active')
            <a href="{{route('admin.edit_donor_listing', $hub_listing_offer->listing->id)}}"
              id="admin-listing-edit-{{$hub_listing_offer->listing->id}}"
              name="button"
              class="btn btn-success admin-listing-button admin-listing-edit-{{$hub_listing_offer->listing->id}}">Измени</a>
          @endif
            <button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->

@endsection
