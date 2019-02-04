
@php
    use \App\Utilities\Functions\Functions;
    $reTok2 = Functions::getBladedString($reTok??'', '');
@endphp

<form action="{{ url('signin') }}" method="post" novalidate="novalidate">
    {{ csrf_field() }}
    @if (Functions::testVar($reTok2))
        <input type="hidden" name="reTok" value="{{ $reTok2 }}">
    @endif
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-at"></i>
            </span>
            <input type="email" class="form-control" id="email_modal" placeholder="email" name="email">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-key"></i>
            </span>
            <input type="password" class="form-control" id="password_modal" placeholder="password" name="password">
        </div>
    </div>
    <p class="text-center">
        <button class="btn btn-template-main"><i class="fa fa-sign-in"></i> Sign In</button>
    </p>
    <a href="javascript:;">Forgotten Password?</a>
    <div class="login-socio text-center">
        <p class="text-muted">or login using:</p>
        <ul class="social-icons">
            <li><a href="javascript:;" data-original-title="facebook" class="facebook" title="facebook"></a></li>
            <li><a href="javascript:;" data-original-title="Twitter" class="twitter" title="Twitter"></a></li>
            <li><a href="javascript:;" data-original-title="Google Plus" class="googleplus" title="Google Plus"></a></li>
            <li><a href="javascript:;" data-original-title="Linkedin" class="linkedin" title="LinkedIn"></a></li>
        </ul>
    </div>
</form>

<p class="text-center text-muted">Not registered yet?</p>
<p class="text-center text-muted"><a href="{{ url('signup') }}"><strong>Register now</strong></a>! It is easy, can be done in 1&nbsp;minute and gives you access to special discounts and much more!</p>
