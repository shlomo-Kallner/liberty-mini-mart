

@section('login-modal')
{{-- Based on Bootstrapious/Universal-1-0 and Bootstrap v3.X docs.. --}}
<!-- *** LOGIN MODAL ***
    _________________________________________________________ -->

<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
                <h4 class="modal-title" id="Login">Sign In</h4>
            </div>
            <div class="modal-body">
                @include('lib.bootstrapious.modals.login_form')
            </div>
        </div>
    </div>
</div>
<!-- *** LOGIN MODAL END *** -->
@endsection
