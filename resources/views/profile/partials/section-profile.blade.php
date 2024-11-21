<div class="space-y-6">
    <!-- Profile Picture -->
    <div class="erah-box">
        <div class="max-w-xl">
            @include('profile.partials.profile-picture')
        </div>
    </div>

    <!-- Description -->
    <div class="erah-box mt-6">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-description-form', [$user])
        </div>
    </div>
</div>
