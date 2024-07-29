@extends('layout.app')
@section('title', 'Notifications List | TSP - Task Management System')
@section('pageTitle', 'Notifications List')
@section('breadcrumTitle', 'All Notifications')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="notifications-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Recieved</th>
                                <th>Go To view</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($notifications as $ind => $notification)
                            <tr>
                                <td> #{{ ++$ind }}</td>
                                <td>{{ $notification->title }}</td>
                                <td>
                                    @if($notification->message)
                                    @if(strlen(strip_tags($notification->message)) > 50)
                                    <span class="description-preview n-time text-muted">{!! Str::limit(strip_tags($notification->message ?? ''), 50) !!}</span>
                                    <span class="description-full n-time text-muted" style="display: none;">{!! $notification->message ?? '' !!}</span>
                                    <button class="btn btn-link read-more-btn ">Read More</button>
                                    @else
                                    <span class="description-full n-time text-muted">{!! $notification->message ?? '' !!}</span>
                                    @endif
                                    @else
                                    <span class="text-center n-time text-muted"></span>
                                    @endif
                                </td>
                                <td>{{ format_date_with_time($notification->created_at) }}</td>
                                <td class="d-flex align-items-center justify-content-center">
                                    <a href="{{ route('tasks.show', base64_encode($notification->task_id)) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

@endsection

@section('script')
<script>
    $('#notifications-datatable').DataTable({
        "ordering": false
    });
    $(document).ready(function() {
        $('.read-more-btn').click(function() {
            var $descriptionPreview = $(this).siblings('.description-preview');
            var $descriptionFull = $(this).siblings('.description-full');

            if ($descriptionPreview.is(':visible')) {
                $descriptionPreview.hide();
                $descriptionFull.show();
                $(this).removeClass('btn-primary').addClass('read-less-btn').text('Read Less');
            } else {
                $descriptionPreview.show();
                $descriptionFull.hide();
                $(this).removeClass('read-less-btn').addClass('btn-primary').text('Read More');
            }
        });
    });
</script>
@endsection