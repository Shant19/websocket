@extends('layouts.styles')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="main">
    <div class="container">
        <div class="signup-content">
            <form method="POST" id="signup-form" class="signup-form" action="{{url('/login')}}">
                <h2>Sign in </h2>
                <div class="form-group">
                    <input type="email" value="{{Request::old('email')}}" class="form-input" name="email" id="email" placeholder="Email"/>
                    {{csrf_field()}}
                </div>
                <div class="form-group">
                    <input type="text" class="form-input" name="password" id="password" placeholder="Password"/>
                    <span toggle="#password" class="zmdi zmdi-eye field-icon toggle-password"></span>
                </div>
                <div class="form-group">
                    <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                    <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree all statements in  <a href="#" class="term-service">Terms of service</a></label>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" id="submit" class="form-submit submit" value="Sign in"/>
                    <a href="{{url('register')}}" class="submit-link submit">Sign up</a>
                </div>
            </form>
        </div>
    </div>
</div>

@extends('layouts.scripts')