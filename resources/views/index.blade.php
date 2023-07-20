@extends('layouts.main')
@section('content')
<!-- Basic Bootstrap Table -->
<div class="card">
    <h5 class="card-header">Table Basic</h5>
    <div id="success_message"></div>
    <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary col-2 me-4" data-bs-toggle="modal" data-bs-target="#addStudentModal">Add Data</button>
    </div>
    {{-- modal start --}}
        <div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content"> 
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Add Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <ul id="savefrom_errList"></ul>
                        <form action="" method="" enctype="multipart/form-data">
                    
                    <div class="mb-3">
                        <label for="formText" class="form-label">Student Name</label>
                        <input class="name form-control" type="text" id="formText" />
                    </div>
                    <div class="mb-3">
                        <label for="formEmail" class="form-label">Email</label>
                        <input class="email form-control" type="email" id="formText" />
                    </div>
                    <div class="mb-3">
                        <label for="formText" class="form-label">Phone</label>
                        <input class="phone form-control" type="text" id="formText" />
                    </div>
                    <div class="mb-3">
                        <label for="formText" class="form-label">Course</label>
                        <input class="course form-control" type="text" id="formText" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary add_student">Save changes</button>
                    </div>
                </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- modal end --}}
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Course</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<!--/ Basic Bootstrap Table -->
<script>
    $(document).ready(function () {
        fetchstudent();
        function fetchstudent(){
            $.ajax({
                type: "GET",
                url: "/fetch-students",
                dataType: "json",
                success: function (response) {
                    // console.log(response.students);
                    $('tbody').html("");
                    $.each(response.students, function (key, item) { 
                        $('tbody').append('<tr>\
                            <td>'+item.id+'</td>\
                            <td>'+item.name+'</td>\
                            <td>'+item.email+'</td>\
                            <td>'+item.phone+'</td>\
                            <td>'+item.course+'</td>\
                            <td>\
                                <div class="dropdown">\
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow btn-light" data-bs-toggle="dropdown">\
                                        <i class="bx bx-dots-vertical-rounded"></i>\
                                    </button>\
                                    <div class="dropdown-menu">\
                                        <a value="'+item.id+'" class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-2"></i>Edit</a>\
                                        <a value="'+item.id+'" class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-2"></i>Delete</a>\
                                    </div>\
                                </div>\
                            </td>\
                        </tr>');
                    });

                }
            });
        }

        $(document).on('click', '.add_student' ,function (e) {
            e.preventDefault();
            // console.log("hello");
            var data = {
                'name': $('.name').val(),
                'email': $('.email').val(),
                'phone': $('.phone').val(),
                'course': $('.course').val(),
            }
            // console.log(data);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "/students",
                data: data,
                dataType: "json",
                success: function (response) {
                    // console.log(response.errors);
                    if (response.status == 400) {
                        $('#savefrom_errList').html("");
                        $('#savefrom_errList').addClass("alert alert-danger");
                        $.each(response.errors, function (key, err_values) { 
                            $('#savefrom_errList').append('<li>'+err_values+'</li>')
                        });
                    }else{
                        $('#success_message').html("");
                        $('#success_message').addClass("alert alert-success");
                        $('#success_message').text(response.message);
                        $('#addStudentModal').modal('hide');
                        $('#addStudentModal').find('input').val("");
                        fetchstudent();
                    }
                }
            });
        });
    });
</script>
@endsection