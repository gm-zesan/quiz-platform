<section>
    <header class="text-center">
        <h2 class="update_info_title">
            {{ __('Profile Information') }}
        </h2>

        <p class="update_info_subtitle">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <div class="card table-card">
        <div class="card-body custom-form">
            <form action="{{ route('profile.update') }}" method="POST" class="row g-3 mt-0" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="col-md-6 col-12">
                    <div>
                        <x-input-label for="name" :value="__('Name')" class="form-label custom-label"/>
                        <x-text-input id="name" name="name" type="text" class="form-control custom-input" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2 error-messages" :messages="$errors->get('name')" />
                    </div>
                    <div>
                        <x-input-label for="phone_no" :value="__('Phone Number')" class="form-label custom-label"/>
                        <x-text-input id="phone_no" name="phone_no" type="number" class="form-control custom-input" :value="old('phone_no', $user->phone_no)" autofocus autocomplete="phone_no" />
                        <x-input-error class="mt-2 error-messages" :messages="$errors->get('phone_no')" />
                    </div>
            
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="form-label custom-label"/>
                        <x-text-input id="email" name="email" type="email" class="form-control custom-input" :value="old('email', $user->email)" required autocomplete="username" />
                        <x-input-error class="mt-2 error-messages" :messages="$errors->get('email')" />
            
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div>
                                <p class="text-sm mt-2 text-gray-800">
                                    {{ __('Your email address is unverified.') }}
            
                                    <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        {{ __('Click here to re-send the verification email.') }}
                                    </button>
                                </p>
            
                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 font-medium text-sm text-green-600">
                                        {{ __('A new verification link has been sent to your email address.') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div style="margin-top: 25px">
                        <x-primary-button class="btn submit-button">{{ __('Update') }}</x-primary-button>
                        @if (session('status') === 'profile-updated')
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
                
                <div class="col-md-6 col-12">
                    <div class="card table-card">
                        <div class="custom-form card-body">
                            <div class="image-select-file shadow-sm">
                                <label class="form-label custom-label" for="cover_image">
                                    <input type="hidden" id="cover_image_data" class="form-control custom-input" name="cover_image_data">
                                    <input type="file" id="cover_image" class="form-file-input form-control custom-input d-none" onchange="imageUpload(this)" name="image">
                                    <div class="user-image">
                                        <img id="cover_imagePreview" src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('images/user.jpeg')}}" alt="" class="image-preview">
                                        <img id="cover_imagePreviewNo"
                                            src="{{ asset('images/user.jpeg') }}" alt=""
                                            class="image-preview d-none">
                                        
                                        <span class="formate-error cover_imageerror"></span>
                                        <div class="user-info">
                                            <p id="setEmail">{{ Auth::user()->email }}</p>
                                        </div>
                                    </div>
                                    <span class="upload-btn">Upload Iamge</span>
                                </label>
                            </div>
    
                            <div class="delete-btn mt-2 d-none remove-image" id="cover_imageDelete" onclick="removeImage('cover_image')">Remove image</div>
    
                            @if($errors->has('image'))
                                <div class="error_msg">
                                    {{ $errors->first('image') }}
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</section>