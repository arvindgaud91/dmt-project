<?php use Acme\Auth\Auth; ?>
@extends('layouts.master')
@section('content')
<div class="head-weight">
	
</div>
@stop
@section('javascript')
<script>

    CrossStorageHub.init([
 {origin: /\.digitalindiapayments.com:800\d$/,            allow: ['get','set','del']},
{origin: /\.digitalindiapayments.com$/,            allow: ['get','set','del']},
 ]);

</script>
@stop
