@extends('layout.app')
@section('title', 'Task Detail | TSP - Task Management System')
@section('pageTitle', 'Task Details')
@section('breadcrumTitle', 'Task Details')

@section('content')
    <style>
        .tribute-container {
            background: rgb(200, 200, 200);
            border-radius: 10px;
            font-weight: bold;
            padding: 20px;
            z-index: 99;

        }

        .highlight {
            padding: 5px;
            border-radius: 10px;
            margin-right: 10px;
            margin-top: 0px;
            margin-bottom: 0px;
            cursor: pointer;
        }
        .input-group-append{
            margin-left: 20px;
            border-radius: 10px
        }
        .input-group > .input-group-append > .btn, .input-group > .input-group-append > .input-group-text, .input-group > .input-group-prepend:not(:first-child) > .btn, .input-group > .input-group-prepend:not(:first-child) > .input-group-text, .input-group > .input-group-prepend:first-child > .btn:not(:first-child), .input-group > .input-group-prepend:first-child > .input-group-text:not(:first-child){
            border-radius: 10px
        }
    </style>
    <div class="row">

        <div class="col-lg-8">
            <div class="card card-body">

                <div class="bg-secondary rounded py-2 mt-2 px-4 text-white">
                    <h4 class=" fw-bold text-white"><u> Summary: </u></h4>
                    <p class="card-title">{{ $task->title }}</p>
                </div>

                <div class="bg-secondary rounded py-2 mt-2 px-4 text-white">
                    <h4 class=" fw-bold text-white"><u> Description: </u></h4>
                    <p class="card-title">{{ $task->description }}</p>
                </div>

                <hr>
                <div class="col-md-12 bg-secondary rounded py-2 mt-2 px-4 text-white">
                    <i class="fa fa-paperclip m-r-10 m-b-10"></i> Attachments
                </div>

                <div class="col-md-12 col-xs-12 rounded py-2 mt-1 px-4 text-white">
                    <a data-toggle="modal" data-target="#attachments_modal"
                        class=" px-4 btn btn-primary btn-sm rounded-pill waves-effect waves-light">
                        <div data-bs-toggle="modal" data-bs-target="#attachments_modal">
                            <span class="btn-label task-span-margin-left-minus "><i class="fa fa-plus"></i></span> <span
                                class="" style=" text-transform: capitalize;">New Attachments </span>
                        </div>
                    </a>
                </div>
                <hr>

                @foreach ($task->attachments as $attachment)
                    <h6> <a href="{{ asset('storage/tasks_file/' . $attachment->path) }}"
                            target="_blank">{{ $attachment->file_name }} <i class="fas fa-eye"></i> </a> <span
                            class="float-end"><a href="{{ asset('storage/tasks_file/' . $attachment->path) }}" download><i
                                    class="fas fa-download"></i></a></span></h6>
                @endforeach
                <hr>
                <div class="col-md-12 bg-secondary rounded py-2 mt-2 px-4 text-white">
                    <i class="fa fa-comment m-r-10 m-b-10"></i> Comments
                </div>
                <hr>
                @foreach ($task->comments as $comment)
                <div>
                    <h6>{{ $comment->user->name }} <span class="float-end">{{ $comment->formatted_created_at }}</span></h6>
                    <p>{{ $comment->comment }}</p>
            
                    @if ($comment->comment_images && $comment->comment_images->path)
                        <div>
                            <img style="object-fit: contain;border-radius:10px" height="100px" src="{{ asset('storage/comment_images/' . $comment->comment_images->path) }}" alt="{{ $comment->comment_images->path }}" class="img-fluid mt-2">
                        </div>
                    @endif
            
                    <hr style="border-top: dashed 1px;" />
                </div>
            @endforeach
            

                <form action="{{ route('comments.store') }}" method="post" enctype="multipart/form-data"
                    id="comment_form">
                    @csrf
                    <input type="hidden" name="task_id" id="task_id" value="{{ $task->id }}">
                    <input type="text" hidden name="userId" id="userId" value="">
                    <div class="input-group mb-3">
                        <textarea class="form-control" name="comment" placeholder="Write comment here" id="comment" required></textarea>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary" data-toggle="modal"
                                data-target="#comment_images">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                    </div>
                    <div id="tagged-users"></div> <!-- Hidden fields for tagged user IDs -->
                    <div class="float-right">
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                    </div>
                </form>




            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-body pb-5">
                <button type="button" class="btn btn-primary rounded-pill waves-effect waves-light mt-2 py-1 fw-bold">
                    <h6 class="text-white fw-bold mt-1">Tracking & Updation | <i class="fa fa-edit float-right mx-2 mt-1"
                            style="cursor:pointer;" data-toggle="modal" data-target="#details_modal"></i> <i
                            class="fas fa-eye float-right mx-1 mt-1" data-toggle="modal" data-target="#logs_modal"></i>
                    </h6>
                </button>

                <div class="d-flex justify-content-between mt-4">
                    <h5>Pro. Name:</h5>
                    <h5><span class="badge badge-soft-success float-end"> {{ $task->project->name ?? null }} </span></h5>
                </div>

                <div class="d-flex justify-content-between">
                    <h5>Task Status:</h5>
                    <h5><span
                            class="badge badge-soft-success float-end btn {{ config('constants.STATUS_LIST')[$task->status] == 'Assigned' ? 'btn-primary' : (config('constants.STATUS_LIST')[$task->status] == 'Closed' ? 'btn-success' : 'btn-secondary') }}  rounded-pill py-1 px-3">
                            {{ config('constants.STATUS_LIST')[$task->status] }}</span> </h5>
                </div>
                <div class="d-flex justify-content-between ">
                    <h5>Task Priority:</h5>
                    <h5><span
                            class="badge badge-soft-success float-end btn {{ config('constants.PRIORITY_LIST')[$task->priority] == 'Medium' ? 'btn-warning' : (config('constants.PRIORITY_LIST')[$task->priority] == 'High' ? 'btn-danger' : 'btn-secondary') }}  rounded-pill py-1 px-4">
                            {{ config('constants.PRIORITY_LIST')[$task->priority] }}</span></h5>
                </div>
                <div class="d-flex justify-content-between ">
                    <h5>Created At:</h5>
                    <h5><span class="badge badge-soft-success float-end rounded-pill py-1 ">
                            {{ $task->formatted_created_at }}</span> </h5>
                </div>
                <div class="d-flex justify-content-between ">
                    <h5>Task Start:</h5>
                    <h5><span class="badge badge-soft-success float-end rounded-pill py-1 ">
                            {{ $task->formatted_start_date }}</span> </h5>
                </div>
                <div class="d-flex justify-content-between ">
                    <h5>Task End:</h5>
                    <h5>

                        <span class="badge badge-soft-success float-end rounded-pill py-1 ">
                            {{ $task->end_date ? $task->formatted_end_date : '' }}</span>
                        @if (in_array(Auth::user()->scope, [1, 2, 3]))
                            <i class="fa fa-edit float-right mx-2 mt-1" style="cursor:pointer;" data-toggle="modal"
                                data-target="#deadline_modal"></i>
                        @endif
                    </h5>
                </div>
                <div class="d-flex justify-content-between ">
                    <h5>Task Closed:</h5>
                    <h5><span class="badge badge-soft-success float-end rounded-pill py-1 ">
                            {{ $task->closed_date ? $task->formatted_closed_date : '' }}</span> </h5>
                </div>
                <div class="d-flex justify-content-between ">
                    <h5>Assigned By:</h5>
                    <h5><span class="badge badge-soft-success float-end rounded-pill py-1 ">
                            {{ $task->creator->name }}</span> </h5>
                </div>
                <h5>Assign To: </h5>
                <span class="float-end px-5">
                    @foreach ($task->users as $ind => $user)
                        <b>{{ ++$ind }}. </b><span>{{ $user->name }}</span>
                        @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                </span>
                <hr>

                <div class="d-flex justify-content-between ">
                    <h5>Revisions:</h5>
                    <h5><span class="badge badge-soft-success float-end rounded-pill py-1 "> {{ $task->revisions }}</span>
                    </h5>
                </div>
                <div class="d-flex justify-content-between">
                    <h5>Time Tracking:</h5>
                    <h5><span class="badge badge-soft-success float-end btn btn-primary rounded-pill py-1 px-3"
                            data-toggle="modal" data-target="#tracking_modal"> <i class="fa fa-plus"></i></span> </h5>
                </div>

                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <h5>Total Time:</h5>
                    <h5>{{ $task->total_time }}</h5>
                </div>

                @foreach ($task->tracking as $tracking)
                    <div class="mb-2" style="border-bottom: 1px dotted #000">
                        <h6> {{ $tracking->summary }} </h6>
                        <div class="d-flex justify-content-between ">
                            <h6>{{ $tracking->user->name }}</h6>
                            <h6><span> {{ $tracking->formatted_date }}</span> <span
                                    class="ml-3">{{ $tracking->formatted_time }}</span> </h5>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    <!--  Modal for Logs -->
    <div class="modal fade bd-example-modal-lg" id="logs_modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="myLargeModalLabel">LOG History</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    @foreach ($task->logs as $log)
                        <p>Change status from {{ config('constants.STATUS_LIST')[$log->old_status] }} to
                            {{ config('constants.STATUS_LIST')[$log->status] }} BY {{ $log->user->name }} <span
                                class="float-end"> {{ $log->formatted_created_at }} </span></p>
                        <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->

    <!-- Modal for Attachments -->
    <div class="modal fade" id="attachments_modal" tabindex="-1" role="dialog" aria-labelledby="status_modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="status_modalLabel">Add Attachments</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('attachments.store') }}" method="post" enctype="multipart/form-data"
                                class="dropzone" id="file-dropzone">
                                @csrf
                                <input type="hidden" name="task_id" value="{{ $task->id }}">
                            </form>
                            <p>Max file size is 10mb.</p>
                            <div class="modal-footer">
                                <button type="button" class="btn  btn-secondary rounded py-1"
                                    data-dismiss="modal">Close</button>
                                <button id="upload-button" class="btn btn-primary rounded py-1">Upload</button>
                            </div>
                        </div> <!-- end card-body-->
                    </div>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
    <!-- Modal for Comment Images -->
    <div class="modal fade" id="comment_images" tabindex="-1" role="dialog" aria-labelledby="status_modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="status_modalLabel">Add Image and Comment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="comment_text">Comment</label>
                                <textarea class="form-control" name="comment" id="comment_text" rows="3" required>Type Here...</textarea>
                            </div>
                            <form action="{{ route('comments.store.images') }}" method="post"
                                enctype="multipart/form-data" class="dropzone" id="comment-file-dropzone">
                                @csrf
                                <input type="hidden" name="task_id" value="{{ $task->id }}">
                              
                                <div class="dropzone-previews" id="dropzonePreview"></div>
                            </form>
                            <p>Max file size is 10mb.</p>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary rounded py-1"
                                    data-dismiss="modal">Close</button>
                                <button id="upload-comment-image-button"
                                    class="btn btn-primary rounded py-1">Upload</button>
                            </div>
                        </div> <!-- end card-body-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->





    <!-- Modal for details Change -->
    <div class="modal fade" id="details_modal" tabindex="-1" role="dialog" aria-labelledby="status_modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="status_modalLabel">Update Task Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <form id="task_status_from" action="{{ route('tasks.update') }}" method="post"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->id }}">

                        <div class="row">

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group fill">
                                    <label for="department_id">Department Name</label>
                                    <select name="department_id" id="department_id" class="form-control">
                                        <option value="" selected>Please select department</option>
                                        @foreach ($departments as $deparment)
                                            <option value="{{ $deparment->id }}"
                                                {{ $deparment->id == $task->department_id ? 'selected' : '' }}>
                                                {{ $deparment->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('department_id'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('department_id') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group fill">
                                    <label for="project_id">Project Name</label>
                                    <select name="project_id" id="project_id" class="form-control">
                                        <option value="" selected>Please select project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}"
                                                {{ $project->id == $task->project_id ? 'selected' : '' }}>
                                                {{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('project_id'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('project_id') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group fill">
                                    <label for="priority">Priority</label>
                                    <select name="priority" id="priority" class="form-control">
                                        <option value="" selected>Select Task Priority</option>
                                        @foreach ($task_preority as $key => $val)
                                            <option value="{{ $key }}"
                                                {{ $task->priority == $key ? 'selected' : '' }}>{{ $val }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('priority'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('priority') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group fill">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        @foreach ($task_status as $key => $val)
                                            <option value="{{ $key }}"
                                                {{ $task->status == $key ? 'selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('status'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('status') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group fill">
                                    <label for="status">Assign To</label>
                                    <select name="assign_to[]" id="assign_to" class="form-control">
                                        <option value="" selected>Please select one from blow</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ isset($assignedUsers[$user->id]) && $assignedUsers[$user->id] == $user->name ? 'selected' : '' }}>
                                                {{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('assign_to'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('assign_to') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group fill">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date"
                                        value="{{ old('start_date', $task->start_date ?? '') }}" id="start_date">
                                </div>
                                @if ($errors->has('start_date'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('start_date') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="description">Task Attachment Url</label>
                                    <div class="custom-file">
                                        <label class="custom-file-label" for="file_url">Choose file...</label>
                                        <input type="text" class="form-control" name="file_url" class="form-control"
                                            id="file_url">
                                        <div class="invalid-feedback">Example invalid custom file feedback</div>
                                    </div>
                                </div>
                                @if ($errors->has('Task Title'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('Task Title') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group fill">
                                    <label for="Task Title">Task Title</label>
                                    <input type="text" class="form-control" name="title" id="title"
                                        placeholder="Enter Task Title" value="{{ old('title', $task->title ?? '') }}"
                                        required="">
                                </div>
                                @if ($errors->has('Task Title'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('Task Title') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-12">
                                <div class="form-group fill">
                                    <label for="Task Title">Task Title</label>
                                    <input type="text" class="form-control" name="title" id="title"
                                        placeholder="Enter Task Title" value="{{ old('title', $task->title ?? '') }}"
                                        required="">
                                </div>
                                @if ($errors->has('Task Title'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('Task Title') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">Task Description</label>
                                    <textarea class="form-control" name="description" placeholder="Write description here" id="description"
                                        required="" rows="3"> {{ old('description', $task->description ?? '') }}</textarea>
                                </div>
                                @if ($errors->has('description'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('description') }}
                                    </span>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-secondary rounded py-1"
                            data-dismiss="modal">Close</button>
                        <button type="submit" for="task_status_from" class="btn  btn-primary rounded py-1">Update
                            Details</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for time tracking -->
    <div class="modal fade" id="tracking_modal" tabindex="-1" role="dialog" aria-labelledby="tracking_modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tracking_modalLabel">Time Tracking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <form id="task_tracking_form" action="{{ route('tracking.store') }}" method="post">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->id }}">

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group fill">
                                    <label for="date">Date</label>
                                    <input type="date" class="form-control" name="date" id="date" required>
                                </div>
                                @if ($errors->has('date'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('date') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-12">
                                <div class="form-group fill">
                                    <label for="summary">Summary</label>
                                    <input type="text" class="form-control" name="summary" id="summary" required>
                                </div>
                                @if ($errors->has('summary'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('summary') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-12">
                                <div class="form-group fill">
                                    <label for="time">Time Spent (in minutes)</label>
                                    <input type="text" class="form-control" name="time" id="time" required>
                                    <span id="result">0 hour(s) and 0 minute(s)</span>
                                </div>
                                @if ($errors->has('time'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('time') }}
                                    </span>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-secondary rounded py-1"
                            data-dismiss="modal">Close</button>
                        <button type="submit" for="task_tracking_form"
                            class="btn  btn-primary rounded py-1">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Change End Date -->
    <div class="modal fade" id="deadline_modal" tabindex="-1" role="dialog" aria-labelledby="deadline_modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deadline_modalLabel">Edit End Date</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <form id="deadline_form" action="{{ route('tasks.update.deadline') }}" method="post">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task->id }}">

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="form-group fill">
                                    <label for="end_date">Date</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date"
                                        value="{{ old('end_date', $task->end_date ?? '') }}" required>
                                </div>
                                @if ($errors->has('end_date'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('end_date') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-12">
                                <div class="form-group fill">
                                    <label for="reason">Reason</label>
                                    <input type="text" class="form-control" name="reason" id="reason"
                                        @if ($task->end_date) required @endif>
                                </div>
                                @if ($errors->has('reason'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('reason') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn  btn-secondary rounded py-1"
                            data-dismiss="modal">Close</button>
                        <button type="submit" for="task_tracking_form"
                            class="btn  btn-primary rounded py-1">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <!-- Tribute.js JS -->
    <script src="https://cdn.jsdelivr.net/npm/tributejs@5.1.3/dist/tribute.js"></script>

    <script>
        var users = @json($users);

        document.addEventListener('DOMContentLoaded', function() {

            var tribute = new Tribute({
                values: [
                    @foreach ($users as $user)
                        {
                            key: "{{ $user->name }}",
                            value: "{{ $user->id }}"
                        },
                    @endforeach
                ],
                selectTemplate: function(item) {
                    var userIdInput = document.getElementById('userId');
                    var existingValue = userIdInput.value;
                    userIdInput.value = existingValue ? existingValue + ',' + item.original.value : item
                        .original.value;
                    return  item.original.key;
                }
            });

            tribute.attach(document.getElementById('comment'));

            document.getElementById('comment_form').addEventListener('submit', function(event) {
                var commentInput = document.getElementById('comment');
                var taggedUsersInput = document.getElementById('tagged-users');
                var mentionedUsers = tribute.collection[0].values;

                taggedUsersInput.innerHTML = '';
                mentionedUsers.forEach(function(user) {
                    var hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'tagged_users[]';
                    hiddenInput.value = user.value;
                    taggedUsersInput.appendChild(hiddenInput);
                });
            });
        });

        Dropzone.options.fileDropzone = {
            autoProcessQueue: false,
            maxFilesize: 10, // MB
            parallelUploads: 10,
            addRemoveLinks: true,
            dictRemoveFile: 'Remove',
            init: function() {
                var submitButton = document.querySelector("#upload-button");
                var myDropzone = this;

                submitButton.addEventListener("click", function() {
                    myDropzone.processQueue();
                });

                this.on("success", function(file, response) {
                    console.log(response);
                    // location.reload();
                });

                this.on("queuecomplete", function() {
                    console.log("All files have been uploaded successfully.");
                    setTimeout(function() {
                        location.reload();
                    }, 1000); // Delay of 1 second
                });

                this.on("removedfile", function(file) {
                    console.log('File removed:', file);
                    // Handle file removal if necessary
                });
            }
        };
        Dropzone.options.commentFileDropzone = {
            autoProcessQueue: false,
            maxFilesize: 10, // MB
            parallelUploads: 10,
            addRemoveLinks: true,
            dictRemoveFile: 'Remove',
            previewsContainer: "#dropzonePreview",
            init: function() {
                var submitButton = document.querySelector("#upload-comment-image-button");
                var myDropzone = this;

                submitButton.addEventListener("click", function() {
                    // Append comment to the form data
                    var comment = document.querySelector("#comment_text").value;
                    myDropzone.options.params = {
                        comment: comment
                    };

                    myDropzone.processQueue();
                });

                this.on("success", function(file, response) {
                    console.log(response);
                    // Handle the response if needed
                });

                this.on("queuecomplete", function() {
                    console.log("All files have been uploaded successfully.");
                    setTimeout(function() {
                        location.reload();
                    }, 1000); // Delay of 1 second
                });

                this.on("removedfile", function(file) {
                    console.log('File removed:', file);
                    // Handle file removal if necessary
                });
            }
        };


        $('#time').on('input', function() {
            var totalMinutes = $(this).val();

            var hours = Math.floor(totalMinutes / 60);
            var minutes = totalMinutes % 60;

            $('#result').text(hours + ' hour(s) and ' + minutes + ' minute(s)');
        });
    </script>
@endsection
