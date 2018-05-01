<?php
$useTimesIcon = true;
?>

@section('login-modal')
<!-- Based on Bootstrapious/Universal-1-0 and Bootstrap v3.X docs.. -->
<!-- *** LOGIN MODAL ***
    _________________________________________________________ -->

<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    @if($useTimesIcon)
                    <i class="fa fa-times" aria-hidden="true"></i>
                    @else
                    <i class="fa fa-window-close" aria-hidden="true"></i>
                    @endif
                </button>
                <h4 class="modal-title" id="Login">Customer Sign In</h4>
            </div>
            <div class="modal-body">
                <form action="{{ url('signin') }}" method="post" novalidate="novalidate">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-at" aria-hidden="true"></i>
                            </span>
                            <input type="email" class="form-control" id="email_modal" placeholder="email" name="email">
                        </div>

                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-key" aria-hidden="true"></i>
                            </span>
                            <input type="password" class="form-control" id="password_modal" placeholder="password" name="password">
                        </div>
                    </div>

                    <p class="text-center">
                        <button class="btn btn-template-main"><i class="fa fa-sign-in"></i> Sign In</button>
                    </p>

                </form>

                <p class="text-center text-muted">Not registered yet?</p>
                <p class="text-center text-muted"><a href="{{ url('signup') }}"><strong>Register now</strong></a>! It is easy and done in 1&nbsp;minute and gives you access to special discounts and much more!</p>

            </div>
        </div>
    </div>
</div>

<!-- *** LOGIN MODAL END *** -->
@endsection
