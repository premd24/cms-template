<form action="{{ route('pages.settings.profile') }}" method="POST">
    @csrf
    <x-ui.section-card title="Profile Information" subtitle="Update your account's profile name and email address.">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-ui.input label="Full Name" name="name" type="text" :value="$user->name" icon="heroicon-o-user" required />
            <x-ui.input label="Email Address" name="email" type="email" :value="$user->email" icon="heroicon-o-envelope" required />
        </div>
        <div class="flex justify-end pt-4">
            <x-button type="submit" variant="primary" size="md">
                Save Changes
            </x-button>
        </div>
    </x-ui.section-card>
</form>
