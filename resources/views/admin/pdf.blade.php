@extends('layouts.app')

@section('title', 'PDF文件管理')

@section('content')
<div>
    <h1>PDF文件管理</h1>
    <a href="/documents/test.pdf/preview">预览</a>
    <a href="/documents/test.pdf/download">下载</a>
</div>
@endsection