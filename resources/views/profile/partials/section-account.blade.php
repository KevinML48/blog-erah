<div class="space-y-6">
    <!-- Profile Information -->
    <div class="erah-box">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <!-- Password -->
    <div class="erah-box mt-6">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <!-- Account Deletion -->
    <div class="erah-box mt-6">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
