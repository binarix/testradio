@extends('_layouts.master')
@section('content')

@if(Session::has('message'))
    <p>{{ Session::get('message') }}</p>
@endif

<p>
Input::old('station') = {{ Input::old('station') }}
</p>
{{ Form::open(['route' => 'radios.store', 'method' => 'POST', 'name' => 'radiosCreateForm']) }}
<p>
{{ Form::label('alpha', 'alpha: ') }}
{{ Form::radio('station', 'alpha', '', ['id' => 'alpha']) }}
</p>
<p>
{{ Form::label('beta', 'beta: ') }}
{{ Form::radio('station', 'beta', '', ['id' => 'beta']) }}
</p>
<p>
{{ Form::label('charlie', 'charlie: ') }}
{{ Form::radio('station', 'charlie', '', ['id' => 'charlie']) }}
</p>
{{ Form::submit('Submit', ['name' => 'submit']) }}

{{ Form::close() }}

<h3>Steps to replicate the issue</h3>
<ol>
    <li>Click on the link above to navigate to radios/create. View::make('radios.create'); called.</li>
    <ul>
        <li>Input::old('station') = initially empty <strong>EXPECTED</strong></li>
        <li>No message <strong>EXPECTED</strong></li>
    </ul>
    <li>Select <strong>alpha</strong> radio button. Hit submit button. Redirect::route('radios.create')->withInput(); called.</li>
    <ul>
        <li>Input::old('station') = alpha <strong>EXPECTED</strong></li>
        <li>Message is Input::get('station') from store method = alpha <strong>EXPECTED</strong></li>
    </ul>
    <li>Select <strong>beta</strong> radio button. Hit submit button. Redirect::route('radios.create')->withInput(); called.</li>
    <ul>
        <li>Input::old('station') = alpha <strong>UNEXPECTED</strong> Value should be beta not alpha. Why is this value not updated to beta?</li>
        <li>Message is Input::get('station') from store method = alpha <strong>UNEXPECTED</strong> Value should be beta not alpha.</li>
    </ul>
    <li>Select <strong>charlie</strong> radio button. Hit submit button. Redirect::route('radios.create')->withInput(); called.</li>
    <ul>
        <li>Input::old('station') = alpha <strong>UNEXPECTED</strong> Value should be charlie not alpha. Value still not updated to charlie</li>
        <li>Message is Input::get('station') from store method = alpha <strong>UNEXPECTED</strong> Value should be charlie not alpha.</li>
    </ul>
    <li>Hit refresh on the browser</li>
    <ul>
        <li>Input::old('station') = empty once again <strong>EXPECTED</strong></li>
        <li>No message <strong>EXPECTED</strong></li>
    </ul>
    <li>Select <strong>charlie</strong> radio button. Hit submit button. Redirect::route('radios.create')->withInput(); called.</li>
    <ul>
        <li>Input::old('station') = charlie <strong>EXPECTED</strong> Refreshing seems to clear the stored session old value. Now it updates to charlie</li>
        <li>Message is Input::get('station') from store method = charlie <strong>EXPECTED</strong></li>
    </ul>
    <li>Select <strong>alpha</strong> radio button. Hit submit button. Redirect::route('radios.create')->withInput(); called.</li>
    <ul>
        <li>Input::old('station') = charlie <strong>UNEXPECTED</strong> Broken again, value should be alpha not charlie</li>
        <li>Message is Input::get('station') from store method = charlie <strong>UNEXPECTED</strong> Value should be alpha not charlie</li>
    </ul>
</ol>

<p>
I tried to var_dump($input) inside withInput method under Illuminate\Http\RedirectResponse.php and the value is the correct value everytime.
</p>

<p>
As can be seen, this issue affects subsequent post submissions after the initial Redirect::route()->withInput(). The value received by the store method of the controller from the post submission of the form is also incorrect. My use case was redirecting after failed validation in my actual app but is not important for this sample as the issues lies with the Redirect::route()->withInput().
</p>

<p>
I suspect that the current implementation of $this->session->flashInput($input); is detecting that there already exists a old station key and is not updating its value properly.
</p>

@stop