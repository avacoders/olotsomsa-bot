@extends('layouts.site')
@section('style')
    <style>



    </style>

@endsection

@section('content')

    <div class="p-5" style="height: 100%">

        <div class="px-lg-5">
            <div class="header header-sm" style="margin-top: 100px">@lang('site.cabinet')</div>

            <input type="text" class="input" id="authname" value="">
            <input type="text" class="input" id="phone-mask2" value="+998901234567">

            <button type="button" class="edit">@lang('site.edit')</button>
        </div>
    </div>




@endsection

