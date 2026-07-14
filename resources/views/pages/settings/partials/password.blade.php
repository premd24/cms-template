<form action="{{ route('pages.settings.password') }}" method="POST">
    @csrf
    <x-ui.section-card title="Update Password" subtitle="Ensure your account is using a long, random password to stay secure.">
        <div class="space-y-6 max-w-xl">
            <x-ui.input label="Current Password" name="current_password" type="password" icon="heroicon-o-lock-closed" required />
            <x-ui.input label="New Password" name="password" type="password" icon="heroicon-o-lock-closed" required />
            <x-ui.input label="Confirm Password" name="password_confirmation" type="password" icon="heroicon-o-lock-closed" required />
        </div>
        <div class="flex justify-end pt-4">
            <x-button type="submit" variant="primary" size="md">
                Update Password
            </x-button>
        </div>
    </x-ui.section-card>
</form>
