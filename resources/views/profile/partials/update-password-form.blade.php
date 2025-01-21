<section>
    <header>
        <h2 class="update_info_title">
            {{ __('Update Password') }}
        </h2>

        <p class="update_info_subtitle">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>
    <div class="card table-card pb-5">
        <div class="card-body custom-form">
            <form method="post" action="{{ route('password.update') }}" class="row g-3 mt-0">
                @csrf
                @method('put')
                <div class="col-12">
                    <div>
                        <x-input-label for="current_password" :value="__('Current Password')" class="form-label custom-label"/>
                        <x-text-input id="current_password" name="current_password" type="password" class="form-control custom-input" autocomplete="current-password" />
                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 error-messages" />
                    </div>
            
                    <div>
                        <x-input-label for="password" :value="__('New Password')" class="form-label custom-label"/>
                        <x-text-input id="password" name="password" type="password" class="form-control custom-input" autocomplete="new-password" />
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 error-messages" />
                    </div>
            
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="form-label custom-label"/>
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="form-control custom-input" autocomplete="new-password" />
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 error-messages" />
                    </div>

                    <div class="mt-5">
                        <x-primary-button class="btn submit-button">{{ __('Update') }}</x-primary-button>
                
                            @if (session('status') === 'password-updated')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600"
                                >{{ __('Saved.') }}</p>
                            @endif
                    </div>
                </div>

                {{-- <div class="row mt-3">
                    <div class="col-9 text-end px-0">
                        <div class="flex items-center gap-4">
                            
                        </div>
                    </div>
                </div> --}}
            </form>
        </div>
    </div>




</section>