@extends("install.layout")

@section("step_description")
    Create the administrator account. This user will have full access to the platform
    and will be able to manage projects, agents, and other users.
@endsection

@section("content")
<h2 class="text-xl font-bold text-navy mb-2">Create admin user</h2>
<p class="text-sm text-warm mb-6">
    This will be the first user of the platform — with full administrator privileges.
    You can add more users later from the dashboard.
</p>

<form action="/install/user" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium text-navy mb-1">Full name</label>
        <input name="name" value="{{ old("name") }}"
               class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition"
               placeholder="e.g. Jane Smith" required>
        <p class="text-xs text-cool mt-1">How you'll be identified in the platform.</p>
    </div>
    <div>
        <label class="block text-sm font-medium text-navy mb-1">Email address</label>
        <input name="email" type="email" value="{{ old("email") }}"
               class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition"
               placeholder="you@example.com" required>
        <p class="text-xs text-cool mt-1">Used to sign in. Won't be shared.</p>
    </div>
    <div>
        <label class="block text-sm font-medium text-navy mb-1">Password</label>
        <input name="password" type="password"
               class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition"
               placeholder="At least 8 characters" required minlength="8">
        <p class="text-xs text-cool mt-1">Minimum 8 characters. Use a strong password.</p>
    </div>
    <div>
        <label class="block text-sm font-medium text-navy mb-1">Confirm password</label>
        <input name="password_confirmation" type="password"
               class="w-full border border-cool rounded-lg px-3 py-2.5 text-sm text-navy placeholder:text-cool focus:outline-none focus:ring-2 focus:ring-teal focus:border-teal transition"
               placeholder="Type the same password" required minlength="8">
    </div>
    <button type="submit"
            class="w-full bg-teal text-white rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-teal-dark transition shadow-sm">
        Create user and finish installation
    </button>
</form>
@endsection
