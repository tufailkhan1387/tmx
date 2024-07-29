@extends('layout.app')
@section('title', 'Project Detail | TSP - Task Management System')
@section('pageTitle', 'Project Detail')
@section('breadcrumTitle', 'Project Details')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card card-body">
            <div>
                <h3 class=" text-center bg-dark rounded-pill px-5 fw-bold text-white pb-1">{{ $project->name }}</h3>
            </div>

            <div class="bg-secondary rounded py-2 mt-2 px-4 text-white">
                <h4 class=" fw-bold text-white"><u> Overview: </u></h4>
                <p class="card-title">{{ $project->description }}</p>
            </div>

            <div class="bg-secondary rounded py-2 mt-2 px-4 text-white">
                <h4 class="fw-bold text-white"><u> Work Flow: </u></h4>
                <p class="card-title">{{ $project->plan }}</p>
            </div>

            <div class="bg-secondary rounded py-2 mt-2 px-4 text-white">
                <h4 class="fw-bold text-white "><u> Deadline: </u></h4>
                <p class="card-title">{{ format_date($project->deadline) }}</p>
            </div>

            <div class="bg-secondary rounded py-2 mt-2 px-4 text-white">
                <h4 class="fw-bold text-white "><u> Ref. Project: </u></h4>
                @foreach ($ref_url ?? [] as $ind => $url)
                <p class="">
                    <span> <b>{{++$ind }}. </b></span> <a href="{{$url}}" class="btn btn-link fw-bold text-" target="_blank"><u> {{ $url }}</u> </a>
                </p>
                @endforeach
            </div>

            <hr>
            <div class="col-md-12 bg-secondary rounded py-2 mt-2 px-4 text-white">
                <i class="fa fa-paperclip m-r-10 m-b-10"></i> Attachments
            </div>
            <div class="col-md-12 col-xs-12  rounded py-2 mt-2 px-4 text-white">
                <a data-toggle="modal" data-target="#attachments_modal" class="btn btn-primary btn-sm rounded-pill waves-effect waves-light">
                    <div data-bs-toggle="modal" data-bs-target="#attachments_modal">
                        <span class="btn-label task-span-margin-left-minus"><i class="fa fa-plus"></i></span> <span class="" style=" text-transform: capitalize;">New Attachments </span>
                    </div>
                </a>
            </div>
            <hr>
            @foreach ($project->attachments as $attachment)
            <h6> <a href="{{ asset('storage/projects_file/'.$attachment->path ) }}" target="_blank">{{ $attachment->file_name }} <i class="fas fa-eye"></i> </a> <span class="float-end"><a href="{{ asset('storage/tasks_file/'.$attachment->path ) }}" download> <i class="fas fa-download"></i></a></span></h6>
            @endforeach
            <hr>
            <div class="col-md-12 bg-secondary rounded py-2 mt-2 px-4 text-white">
                <i class="fa fa-comment m-r-10 m-b-10"></i> Comments
            </div>
            <hr>
            @foreach ($project->comments as $comment)
            <div>
                <h6>{{ $comment->user->name }} <span class="float-end">{{ $comment->formatted_created_at }}</span></h6>
                <p>{{ $comment->comment }}</p>
                <hr style="border-top: dashed 1px;" />
            </div>
            @endforeach

            <form action="{{ route('projects.comments.store') }}" method="post" id="comment_form">
                @csrf
                <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}">
                <div class="mb-3">
                    <textarea class="form-control" name="comment" placeholder="Write description here" id="comment" style="height: 100px" required></textarea>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary w-md">Submit</button>
                </div>
            </form>

        </div>
    </div>
</div>



<!-- Modal for Attachments -->
<div class="modal fade" id="attachments_modal" tabindex="-1" role="dialog" aria-labelledby="status_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="status_modalLabel">Add Attachments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('projects.attachments.store')}}" method="post" enctype="multipart/form-data" class="dropzone" id="file-dropzone">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                        </form>
                        <p>Maz file size is 2mb.</p>
                        <div class="modal-footer">
                            <button type="button" class="btn  btn-secondary rounded py-1" data-dismiss="modal">Close</button>
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
@endsection

@section('script')
<script>
    Dropzone.options.fileDropzone = {
        autoProcessQueue: false,
        maxFilesize: 2, // MB
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
</script>
@endsection