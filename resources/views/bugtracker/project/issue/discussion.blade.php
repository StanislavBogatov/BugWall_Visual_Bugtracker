@extends('layouts.project')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/bugtracker/issue-discussion.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/bugtracker/issue.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/bugtracker/minimized-board.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{asset('editor/css/marker-style.css')}}">
@endsection

@section('project-content')
{!! Breadcrumbs::render('discussion', $project, $issue) !!}
@can('close', $issue)
    @if(!$issue->closed)
        <form action="{{route('project.issue.close', compact('project', 'issue'))}}" method="POST">
            <button class="btn btn-success pull-right"><span class="glyphicon glyphicon-ok"></span> Close issue</button>
            <input type="hidden" name="_token" value="{{csrf_token()}}">
        </form>
    @endif
    @if($issue->closed)
        <form action="{{route('project.issue.open', compact('project', 'issue'))}}" method="POST">
            <button class="btn btn-warning pull-right"><span class="glyphicon glyphicon-repeat"></span> Re-open issue</button>
            <input type="hidden" name="_token" value="{{csrf_token()}}">
        </form>
    @endif
@endcan

<h2 class="heading">
    <span>
        <span>{{$issue->title}}.</span>
        <span class="text-muted small">#{{ $issue->id }}</span>
        @can('delete', $issue)
            <a class="delete-link small" href="{{ route('project.issue.delete', ['issue'=>$issue, 'project'=>$issue->project]) }}">
                <span class="glyphicon glyphicon-trash text-danger "></span>
            </a>
        @endcan
    </span>
</h2>
<div style="background:white; border: 1px solid lightgray">
    <div class="issue-creation-details">
        <div class="text-muted">
            @lang('projects.issue_created_when', ['when'=>$issue->created_at])
            <a href="{{ route('user', ['user_name'=>$issue->creator->name]) }}">
                <span>@</span>{{ $issue->creator->name }}
            </a>
            <br>
            <span class="small">
                @lang('projects.issue_type'):
                <span class="issue-badge {{ $issue->type->title }}-type">{{ $issue->type->title }}</span>
                @lang('projects.issue_priority'):
                <span class="issue-badge {{ $issue->priority->title }}-priority">{{ $issue->priority->title }}</span>
            </span>
            <br>
            @include('bugtracker.project.issue.partials.assignees-block')
        </div>
    </div>
    <div class="issue-body">
        <div>
            <p>{{$issue->description}}</p>
        </div>
        <div class="comment-points-list">
        @if( isset($issue->commentPoints) )
            <ul class="list-group small">
            @foreach($issue->commentPoints as $commentPoint)
                <li style="list-style: none">
                    <span class="text-muted small">
                        <div class="dropdown">
                          <button class="btn btn-default dropdown-toggle btn-xs" type="button" data-toggle="dropdown">
                               #<span>{{$commentPoint->id}}</span> <span>{{$commentPoint->text}}</span>
                          <span class="caret"></span></button>
                          <ul class="dropdown-menu" style="min-width: unset; padding: 0;">
                              <li>
                                  @include('bugtracker.board.minimized', ['current_board'=>$commentPoint->board, 'commentPoints'=>[$commentPoint]])
                              </li>
                          </ul>
                        </div>
                    </span>
                </li>
            @endforeach
            </ul>
        @endif
            @if($issue->closed)
                <div>
                    <span class="pull-right label label-warning">@lang('projects.issue_closed_by')
                        <a href="{{ route('user', ['user_name'=>$issue->closedby->name]) }}">
                            <span>@</span>{{ $issue->closedby->name }}
                        </a>
                    </span>
                    <div class="clearfix"></div>
                </div>
            @endif
        </div>
    </div>
</div>
<br>
<div class="col-md-12">
    <div class="issue-discussion-container">
        @include('bugtracker.project.issue.partials.messages-block')
    </div>
    <div style="background-color:#F7F7F7; border: 1px solid lightgray; padding:10px;">
        @include('bugtracker.project.issue.partials.create-message-form')
    </div>
</div>



@endsection