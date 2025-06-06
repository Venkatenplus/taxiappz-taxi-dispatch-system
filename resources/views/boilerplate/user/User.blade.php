@extends('layouts.app')

@section('content')

    <div class="content">

        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">Admin Management</h5>
                <div class="header-elements">
                    <div class="list-icons">
                        @if(auth()->user()->can('new-user'))
                            <button type="button" id="add_new_btn" class="btn bg-purple btn-sm legitRipple"><i class="icon-plus3 mr-2"></i> {{ __('add-new') }}</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        

        <div class="card" id="tableDiv">
            
            <table class="table datatable-button-print-columns1" id="roletable">
                <thead>
                    <tr>
                        <th>{{ __('sl') }}</th>
                        <th>{{ __('name') }}</th>
                        <th>{{ __('email') }}</th>
                        <th>{{ __('role') }}</th>
                        <th>{{ __('status') }}</th>
                        <th>{{ __('action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user as $key => $users)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{!! $users->firstname !!} {!! $users->lastname !!}</td>
                            <td>{!! $users->email !!}</td> 
                            <td>{!! empty($users->roles[0]) ? '' : $users->roles[0]->display_name !!}</td> 
                            <td>@if($users->active == 1)
                                    <span class="badge badge-success">{{ __('active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('inactive') }}</span>
                                @endif
                            </td> 
                            <td>   

                                @if(auth()->user()->id== $users->id)
                                <a class="list-icons-item dropdown-toggle caret-0" style="visibility: hidden" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7" ></i></a>

                                    
                                    <div class="dropdown-menu dropdown-menu-right " x-placement="top-end" style="position: relative; will-change: transform; top: 0px; left: 0px; transform: translate3d(16px, 19px, 0px);">
                                    
                                    </div>
                                @else
                                <a class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7" ></i></a>

                                <div class="dropdown-menu dropdown-menu-right " x-placement="top-end" style="position: relative; will-change: transform; top: 0px; left: 0px; transform: translate3d(16px, 19px, 0px);">
                                    @if(auth()->user()->can('edit-user'))
                                        <a href="#" onclick="Javascript: return editAction(`{{ route('usersEdit',$users->slug) }}`)" data-popup="tooltip" title="" data-placement="bottom" data-original-title="Edit" class="dropdown-item"><i class="icon-pencil"></i> Edit </a>
                                        @endif
                                        @if(auth()->user()->can('delete-user'))
                                        <a href="#" onclick="Javascript: return deleteAction('$users->slug', `{{ route('usersDelete',$users->slug) }}`)" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                        @endif
                                        @if(auth()->user()->can('status-change-user'))
                                        <a href="#" onclick="Javascript: return activeStatus(`{{ route('usersActive',$users->slug) }}`)" class="dropdown-item"><i class="icon-checkmark-circle2"></i>Status</a>
                                        @endif
                                        @if(auth()->user()->can('user-change-password'))
                                        <a href="#" onclick="Javascript: return changePassword(`{{$users->slug}}`)" class="dropdown-item"><i class="icon-key"></i>Change Password</a>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Horizontal form modal -->
        <div id="roleModel" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title " id="modelHeading">{{ __('add-new') }}</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form id="roleForm" name="roleForm" class="form-horizontal">
                        @csrf

                        <div class="modal-body">
                            <div class="alert alert-danger" id="errorbox">
                                <button type="button" class="close"><span>×</span></button>
                                <span id="errorContent"></span>
                            </div>
                            <div class="form-group row required">
                                <label class="col-form-label col-sm-3"><b>{{ __('first-name') }}</b></label>
                                <div class="col-sm-9">
                                    <input type="text" placeholder="{{ __('first-name') }}" id="first_name" class="form-control" name="first_name">
                                    <input type="hidden" name="user_id" id="user_id">
                                </div>
                            </div>
                            <div class="form-group row required">
                                <label class="col-form-label col-sm-3">{{ __('last-name') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" placeholder="{{ __('last-name') }}" id="last_name" class="form-control" name="last_name">
                                </div>
                            </div>
                            <div class="form-group row required">
                                <label class="col-form-label col-sm-3">{{ __('email') }}</label>
                                <div class="col-sm-9">
                                    <input type="email" placeholder="{{ __('email') }}" id="email" class="form-control" name="email">
                                </div>
                            </div>
                            <div class="form-group row required">
                                <label class="col-form-label col-sm-3">{{ __('phone_number') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" placeholder="{{ __('phone_number') }}" id="phone_number" class="form-control" name="phone_number">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3">{{ __('emergency_number') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" placeholder="{{ __('emergency_number') }}" id="emergency_number" class="form-control" name="emergency_number">
                                </div>
                            </div>
                            <div class="form-group row password required">
                                <label class="col-form-label col-sm-3">{{ __('password') }}</label>
                                <div class="col-sm-9">
                                    <input type="password" placeholder="{{ __('password') }}" id="password" class="form-control" name="password">
                                </div>
                            </div>
                            <div class="form-group row password required">
                                <label class="col-form-label col-sm-3">{{ __('confirm-password') }}</label>
                                <div class="col-sm-9">
                                    <input type="password" placeholder="{{ __('confirm-password') }}" id="cpassword" class="form-control" name="cpassword">
                                </div>
                            </div>
                            <div class="form-group row required">
                                <label class="col-form-label col-sm-3">{{ __('role') }}</label>
                                <div class="col-sm-9">
                                    <select id="role" class="form-control" name="role">
                                        <option value="">Select Type</option>
                                        @foreach($roles as $key => $value)
                                            <option value="{!! $value->name !!}">{!! $value->display_name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3 ">{{ __('gender') }}</label>
                                <div class="col-sm-9">
                                    <select id="gender" class="form-control" name="gender">
                                        <option value="">Select Type</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3">{{ __('language') }}</label>
                                <div class="col-sm-9">
                                    <select id="language" class="form-control" name="language">
                                        <option value="">Select Type</option>
                                        @foreach($languages as $key => $value)
                                            <option value="{!! $value->code !!}">{!! $value->name !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3">{{ __('address') }}</label>
                                <div class="col-sm-9">
                                    <textarea placeholder="{{ __('address') }}" id="address" class="form-control" name="address"></textarea>
                                </div>
                            </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('close') }}</button>
                            <button type="submit" id="saveBtn" class="btn bg-primary">{{ __('save-changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
<!-- /horizontal form modal -->

    <!-- Horizontal form modal -->
    <div id="roleModel1" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title " id="modelHeading1">{{ __('add-new') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="roleForm1" name="roleForm" class="form-horizontal">
                    @csrf

                    <div class="modal-body">
                        <div class="alert alert-danger" id="errorbox1">
                            <button type="button" class="close"><span>×</span></button>
                            <span id="errorContent1"></span>
                        </div>
                        <div class="form-group row password">
                            <label class="col-form-label col-sm-3">{{ __('password') }}</label>
                            <div class="col-sm-9">
                                <input type="password" placeholder="{{ __('password') }}" id="password" class="form-control" name="password">
                                <input type="hidden" name="user_slug" id="user_slug">
                            </div>
                        </div>
                        <div class="form-group row password">
                            <label class="col-form-label col-sm-3">{{ __('confirm-password') }}</label>
                            <div class="col-sm-9">
                                <input type="password" placeholder="{{ __('confirm-password') }}" id="cpassword" class="form-control" name="cpassword">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('close') }}</button>
                        <button type="submit" id="saveBtn1" class="btn bg-primary">{{ __('save-changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<!-- /horizontal form modal -->

<script type="text/javascript">
    function editAction(actionUrl){
        $.ajax({
            url: actionUrl,
            type: "GET",
            dataType: 'json',
            success: function (data) {
               //console.log(data);
                $('#modelHeading').html("{{ __('edit-user') }}");
                $('#errorbox').hide();
                // $('#saveBtn').html("Edit Complaint");
                // console.log(data.user.roles[0].name);
                $('#saveBtn').val("edit_user");
                $('#roleModel').modal('show');
                $('#user_id').val(data.user.slug);
                $('#first_name').val(data.user.firstname);
                $('#last_name').val(data.user.lastname);
                $('#email').val(data.user.email);
                $('#phone_number').val(data.user.phone_number);
                $('#gender').val(data.user.gender);
                $('#language').val(data.user.language);
                $('#address').val(data.user.address);
                $('#emergency_number').val(data.user.emergency_number);
                $('#role').val(data.user.roles[0].name);
                $(".password").hide();
            },
            error: function (data) {
                console.log('Error:', data);
            }
         });
        return false;
    }
    function changePassword(actionUrl){
        $('#modelHeading1').html("{{ __('password-change') }}");
        $('#errorbox1').hide();
        $('#roleModel1').modal('show');
        $('#user_slug').val(actionUrl);
        return false;
    }
  $(function () {

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });


    $('#add_new_btn').click(function () {
        $('#complaint_id').val('');
        $('#roleForm').trigger("reset");
        $('#modelHeading').html("{{ __('create-new-user') }}");
        $('#roleModel').modal('show');
        $('#saveBtn').val("add_user");
        // $('#saveBtn').html("Save Complaint");
        $('#errorbox').hide();
        $(".password").show();
    });

    

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html("{{ __('sending') }}");
        var btnVal = $(this).val();
        $('#errorbox').hide();
       
        if(btnVal == 'edit_user'){
            $.ajax({
                data: $('#roleForm').serialize(),
                url: "{{ route('usersUpdate') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                        $('#roleForm').trigger("reset");
                        $('#roleModel').modal('hide');
                        swal({
                            title: "{{ __('data-updated') }}",
                            text: "{{ __('data-updated-successfully') }}",
                            icon: "success",
                            }).then((value) => {
                                // $("#reloadDiv").load("{{ route('user') }}");
                                location.reload();
                            });
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $('#errorbox').show();
                    var err = eval("(" + xhr.responseText + ")");
                    console.log(err);
                    $('#errorContent').html('');
                    $.each(err.errors, function(key, value) {
                        $('#errorContent').append('<strong><li>'+value+'</li></strong>');
                    });
                    $('#saveBtn').html("{{ __('save-changes') }}");
                }
            });
        }else{
            $.ajax({
                data: $('#roleForm').serialize(),
                url: "{{ route('usersSave') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                        $('#roleForm').trigger("reset");
                        $('#roleModel').modal('hide');
                        swal({
                            title: "{{ __('data-added') }}",
                            text: "{{ __('data-added-successfully') }}",
                            icon: "success",
                            }).then((value) => {
                            
                                // $("#reloadDiv").load("{{ route('user') }}");
                                location.reload();
                            });
                        
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $('#errorbox').show();
                        var err = eval("(" + xhr.responseText + ")");
                        $('#errorContent').html('');
                        $.each(err.errors, function(key, value) {
                            $('#errorContent').append('<strong><li>'+value+'</li></strong>');
                        });
                        $('#saveBtn').html("{{ __('save-changes') }}");
                    }
                });
        }
    });

$('#saveBtn1').click(function (e) {
    e.preventDefault();
    $(this).html("{{ __('sending') }}");
    $('#errorbox1').hide();
   
    $.ajax({
        data: $('#roleForm1').serialize(),
        url: "{{ route('usersPasswordUpdate') }}",
        type: "POST",
        dataType: 'json',
        success: function (data) {
                $('#roleForm1').trigger("reset");
                $('#roleModel1').modal('hide');
                swal({
                    title: "{{ __('data-updated') }}",
                    text: "{{ __('data-updated-successfully') }}",
                    icon: "success",
                }).then((value) => {
                    // $("#reloadDiv").load("{{ route('user') }}");
                    location.reload();
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $('#errorbox1').show();
                var err = eval("(" + xhr.responseText + ")");
                console.log(err);
                $('#errorContent1').html('');
                $.each(err.errors, function(key, value) {
                    $('#errorContent1').append('<strong><li>'+value+'</li></strong>');
                });
                $('#saveBtn1').html("{{ __('save-changes') }}");
            }
    });
    
});

    $(".close").click(function(){
        $('#errorbox').hide();
    })
    


  });

  function activeStatus(actionUrl){
    swal({
        title: "Are you sure?",
        text: "You will change status for this admin",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
            window.location.href = actionUrl;
            swal("Poof! Your admin status has been changed!", {
                icon: "success",
            });

          
        } else {
          swal("Your Data status is not chenged!");
        }
      });

    return false;
}
</script>

@endsection
